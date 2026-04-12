<?php

namespace App\Services;

use App\Enum\ContractStatus;
use App\Models\Contract;
use App\Models\ContractAttributeValue;
use App\Models\User;
use App\Repositories\ContractAttributeValue\ContractAttributeValueRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContractService
{
    /**
     * Summary of __construct
     *
     * @param ContractAttributeValueRepositoryInterface $contractAttributeValueRepository
     */
    public function __construct(
        protected ContractAttributeValueRepositoryInterface $contractAttributeValueRepository
    ) {
        //
    }

    /**
     * Override create method
     */
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            $user = User::query()
                ->select('id', 'school_id')
                ->findOrFail($data['user_id']);

            $contract = Contract::create([
                'code'              => null,
                'user_id'           => $user->id,
                'school_id'         => $user->school_id,
                'contractable_id'   => $user->id,
                'contractable_type' => User::class,
                'contract_type_id'  => $data['contract_type_id'],
                'status'            => ContractStatus::UNDER_ACCEPTANCE->value,
                'signed_at'         => $data['signed_at'] ?? null,
                'expired_at'        => $data['expired_at'] ?? null,
            ]);

            $contract->forceFill([
                'code' => generateCode(Contract::PREFIX_CODE, $contract->id),
            ])->save();

            $pivotIds = DB::table('contract_type_attributes')
                ->where('contract_type_id', $data['contract_type_id'])
                ->pluck('id')
                ->all();

            $attributes = $this->normalizeAttributeValues($data['attributes'] ?? []);

            foreach ($attributes as $pivotId => $value) {

                if (!in_array($pivotId, $pivotIds, true)) {
                    Log::warning("Contract {$contract->id}: invalid attribute pivot {$pivotId}");
                    continue;
                }

                if ($value === null || $value === '') {
                    continue;
                }

                ContractAttributeValue::create([
                    'contract_id'                => $contract->id,
                    'contract_type_attribute_id' => $pivotId,
                    'value'                      => (string) $value,
                ]);
            }

            if (!empty($data['appendix_ids'])) {
                $contract->appendixContracts()->sync($data['appendix_ids']);
            }

            return $contract;
        });
    }

    /**
     * Override update method
     */
    public function update($model, array $data)
    {
        return DB::transaction(function () use ($model, $data) {

            $model->update([
                'contract_type_id' => $data['contract_type_id'],
                'status' => $data['status'] ?? $model->status,
                'signed_at' => $data['signed_at'] ?? $model->signed_at,
                'expired_at' => $data['expired_at'] ?? $model->expired_at,
            ]);

            $pivotIds = DB::table('contract_type_attributes')
                ->where('contract_type_id', $data['contract_type_id'])
                ->pluck('id')
                ->all();

            $attributes = $this->normalizeAttributeValues($data['attributes'] ?? []);

            foreach ($attributes as $pivotId => $value) {
                if (!in_array($pivotId, $pivotIds, true)) {
                    Log::warning("Contract {$model->id}: invalid attribute pivot {$pivotId}");
                    continue;
                }

                if ($value === null || $value === '') {
                    continue;
                }

                ContractAttributeValue::updateOrCreate(
                    [
                        'contract_id' => $model->id,
                        'contract_type_attribute_id' => $pivotId,
                    ],
                    [
                        'value' => (string) $value,
                    ]
                );
            }

            if (isset($data['appendix_ids'])) {
                $model->appendixContracts()->sync($data['appendix_ids']);
            }

            return $model;
        });
    }

    private function normalizeAttributeValues(array $rawAttributes): array
    {
        $normalized = [];

        foreach ($rawAttributes as $key => $value) {
            if (is_numeric($key)) {
                $normalized[(int) $key] = is_array($value)
                    ? ($value['value'] ?? json_encode($value))
                    : $value;
                continue;
            }

            if (is_array($value)) {
                $pivotId = $value['contract_type_attribute_id']
                    ?? $value['pivot_id']
                    ?? null;

                if ($pivotId) {
                    $normalized[(int) $pivotId] = $value['value'] ?? '';
                }
            }
        }

        return $normalized;
    }

    /**
     * Generate the contract content with placeholders replaced by actual values.
     *
     * @param Contract $contract The contract instance
     * @return string The contract content with replaced placeholders
     */
    public function generateContractContent(Contract $contract)
    {
        $contract->load('contractTypeAttributes.contractAttribute', 'contractType');

        $contractTypeContent = $contract->contractType->content ?? '';

        $contractTypeContent = $this->replaceImageUrlsWithAbsolutePaths($contractTypeContent, $contract);

        foreach ($contract->contractTypeAttributes as $attributeValue) {
            $placeholder = '{{ $' . $attributeValue->contractAttribute->key . ' }}';

            $contractAttributeValue = $this->contractAttributeValueRepository->advancedGetFirst([
                'conditions' => [
                    'where' => [
                        'contract_id' => $contract->id,
                        'contract_type_attribute_id' => $attributeValue->id,
                    ],
                ],
            ]);

            $replacement = '';
            if ($contractAttributeValue && isset($contractAttributeValue->value)) {
                $replacement = $contractAttributeValue->value;
            }

            $contractTypeContent = str_replace($placeholder, $replacement, $contractTypeContent);
        }

        $contractTypeContent = str_replace('!important', '', $contractTypeContent);

        return $contractTypeContent;
    }

    protected function replaceImageUrlsWithAbsolutePaths(string $contractTypeContent, Contract $contract)
    {
        $imageUrls = extractImageUrls($contractTypeContent);

        if (empty($imageUrls)) {
            return $contractTypeContent;
        }

        foreach ($imageUrls as $url) {
            $parsedUrl = parse_url($url);

            // Try to extract file_url from query string
            $relativePath = null;
            if (!empty($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], $qs);
                $fileQuery = $qs['file_url'] ?? null;
                if ($fileQuery) {
                    // normalize
                    $relativePath = preg_replace('#^public/media/#', '', $fileQuery);
                }
            }

            // Fallback: if URL already points to storage/media path
            if (!$relativePath && !empty($parsedUrl['path'])) {
                if (str_contains($parsedUrl['path'], '/storage/media/')) {
                    $relativePath = ltrim(str_replace('/storage/media/', '', $parsedUrl['path']), '/');
                }
            }

            if (!$relativePath) {
                Log::warning('Unable to resolve image url for PDF replacement', ['url' => $url]);
                continue;
            }

            $absolutePath = public_path('storage/media/' . $relativePath);
            if (file_exists($absolutePath)) {
                $contractTypeContent = str_replace($url, $absolutePath, $contractTypeContent);
            } else {
                Log::warning('PDF image not found', ['path' => $absolutePath, 'url' => $url]);
            }
        }

        return $contractTypeContent;
    }

    public function getFileAbsolutePath(string $fileUrl): string
    {
        $parsedUrl = parse_url($fileUrl);

        $decodedPath = urldecode($parsedUrl['query']);

        $relativePath = ltrim($decodedPath, 'file_url=public/media/');  

        $absolutePath = public_path('storage/media/' . $relativePath);
    
        if (file_exists($absolutePath)) {
            return $absolutePath;
        }

        abort(404, 'Tệp không tồn tại.');
    }
}
