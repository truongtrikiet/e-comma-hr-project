<?php

namespace App\Http\Controllers\Admin;

use App\Acl\Acl;
use App\Models\Contract;
use App\Repositories\Setting\SettingRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\ContractService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contract\StoreContractRequest;
use App\Http\Requests\Contract\UpdateContractRequest;
use App\Http\Resources\Contract\ContractResource;
use App\Models\ContractAttributeValue;
use App\Models\Contracts\SettingKey;
use App\Repositories\Contract\ContractRepositoryInterface;
use App\Repositories\ContractAttributeValue\ContractAttributeValueRepositoryInterface;
use App\Repositories\ContractType\ContractTypeRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;

class ContractController extends Controller
{
    public function __construct(
        protected ContractRepositoryInterface $contractRepository,
        protected ContractService $contractService,
        protected SettingRepositoryInterface $settingRepository,
        protected ContractTypeRepositoryInterface $contractTypeRepository,
        protected UserRepositoryInterface $userRepository,
        protected ContractAttributeValueRepositoryInterface $contractAttributeValueRepository,
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_CONTRACT_LIST)->only('index');
        $this->middleware('permission:' . Acl::PERMISSION_CONTRACT_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . Acl::PERMISSION_CONTRACT_EDIT)->only(['edit', 'update']);
        $this->middleware('permission:' . Acl::PERMISSION_CONTRACT_DELETE)->only('destroy');
        $this->middleware('permission:' . Acl::PERMISSION_CONTRACT_DETAIL_PDF)->only('showDetail');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $contract = $this->contractRepository->serverPaginationFilteringForAdmin($request->all());

            return ContractResource::collection($contract);
        }

        return view('admin.contract.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contractTypes = $this->contractTypeRepository->all();
        $contractTypes->load([
            'contractAttributes',
            'contractAttributes.contractTypes',
            'school',
        ]);

        $first = $contractTypes->first();
        if ($first) {
            $schoolId = $first->school_id ?? ($first->school->id ?? null);
        } else {
            $schoolId = null;
        }

        $schoolId = $schoolId ?? auth()->user()->school_id ?? null;

        $users = $this->userRepository->getUsersBySchoolId((int) ($schoolId ?? 0));

        return view('admin.contract.create', compact('contractTypes', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContractRequest $request)
    {
        $this->contractService->create($request->validated()) ? 
            session()->flash(NOTIFICATION_SUCCESS, __('success.contract.create')) 
            : session()->flash(NOTIFICATION_ERROR, __('error.contract.create'));

        return to_route('admin.contract.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        return view('admin.contract.show', compact('contract'));
    }

    /**
     * Display the specified resource.
     */
    public function showDetailPdf(Request $request, Contract $contract)
    {
        $appendixContracts = $this->contractRepository->getAppendixContractInArrayForContract(
            $contract,
            json_decode($request->appendixContracts, true) ?: []
        );

        if (! $contract->contractType) {
            return response()->json(['message' => 'Contract type not found.'], Response::HTTP_NOT_FOUND);
        }

        $contractTypeContent = $this->contractService->generateContractContent($contract);
        $contractHeader  = $this->settingRepository->findByKey(SettingKey::CONTRACT_HEADER['key']);
        $contractWatermark  = $this->settingRepository->findByKey(SettingKey::CONTRACT_WATERMARK['key']);

        $pdf = PDF::loadView(
            'admin.contract.show-detail',
            compact(
                'contract',
                'contractTypeContent',
                'appendixContracts',
                'contractHeader',
                'contractWatermark',
            )
        );

        return $pdf->stream('contract.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract)
    {
        $contract->load([
            'contractTypeAttributes.contractAttribute',
            'contractable',
            'contractType',
            'appendixContracts',
            'school',
        ]);

        $contractTypes = $this->contractTypeRepository->all();
        $contractTypes->load([
            'contractAttributes',
            'contractAttributes.contractTypes',
            'school',
        ]);

        $schoolId = $contract->school_id ?? auth()->user()->school_id ?? null;

        $users = $this->userRepository->getUsersBySchoolId((int) $schoolId);

        $contractAttributeValues = $this->contractAttributeValueRepository->getValuesByContractId($contract->id);

        $contractTypeAttributesMap = [];
        foreach ($contractTypes as $contractType) {
            $contractTypeAttributesMap[$contractType->id] = $contractType->contractTypeAttributes->map(fn ($contractTypeAttribute) => [
                'id' => $contractTypeAttribute->id,
                'name' => $contractTypeAttribute->contractAttribute->name,
                'key' => $contractTypeAttribute->contractAttribute->key,
            ])->toArray();
        }

        return view('admin.contract.edit', compact(
            'contract',
            'contractTypes',
            'users',
            'contractTypeAttributesMap',
            'contractAttributeValues'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContractRequest $request, Contract $contract)
    {
        $this->contractService->update($contract, $request->validated()) ? 
            session()->flash(NOTIFICATION_SUCCESS, __('success.contract.update')) 
            : session()->flash(NOTIFICATION_ERROR, __('error.contract.update'));

        return to_route('admin.contract.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        if ($this->contractRepository->destroy($contract))
            return response()->json([
                'message' => __('success.delete'),
            ], Response::HTTP_OK);
        return response()->json([
            'message' => __('error.delete'),
        ], Response::HTTP_BAD_REQUEST);
    }
}