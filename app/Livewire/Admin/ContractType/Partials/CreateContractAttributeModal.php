<?php

namespace App\Livewire\Admin\ContractType\Partials;

use App\Models\ContractAttribute;
use App\Repositories\ContractAttribute\ContractAttributeRepositoryInterface;
use App\Rules\LowercaseUnderscore;
use Livewire\Component;

class CreateContractAttributeModal extends Component
{
    protected $contractAttributeRepository;

    public $key;

    public $name;

    public function boot(
        ContractAttributeRepositoryInterface $contractAttributeRepository
    ) {
        $this->contractAttributeRepository = $contractAttributeRepository;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'key' => [
                'required',
                'max:255',
                'unique:contract_attributes',
                new LowercaseUnderscore,
            ],
            'name' => 'required|max:255',
        ];
    }

    /**
     * Store contract attribute.
     */
    // public function store()
    // {
    //     $validated = $this->validate();

    //     $contractAttribute = $this->contractAttributeRepository->create($validated);

    //     $this->reset();
    //     return $contractAttribute;
    // }

    public function store()
    {
        $this->validate();

        $attribute = ContractAttribute::create([
            'key' => $this->key,
            'name' => $this->name,
        ]);

        $this->dispatch('contract-attribute-created', [
            'id' => $attribute->id,
            'key' => $attribute->key,
        ]);

        $this->reset(['key', 'name']);
    }

    public function render()
    {
        return view('livewire.admin.contract-type.partials.create-contract-attribute-modal');
    }
}
