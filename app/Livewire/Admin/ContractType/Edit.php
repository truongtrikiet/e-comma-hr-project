<?php

namespace App\Livewire\Admin\ContractType;

use App\Repositories\ContractAttribute\ContractAttributeRepositoryInterface;
use App\Repositories\ContractType\ContractTypeRepositoryInterface;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Edit extends Component
{
    protected $contractTypeRepository;

    protected $contractAttributeRepository;

    public $contractType;

    public $contractAttributes;

    public $selectedAttributes = [];

    public $name;

    public $content;

    public function boot(
        ContractTypeRepositoryInterface $contractTypeRepository,
        ContractAttributeRepositoryInterface $contractAttributeRepository
    ) {
        $this->contractTypeRepository = $contractTypeRepository;
        $this->contractAttributeRepository = $contractAttributeRepository;
    }

    public function mount($contractType)
    {
        $this->contractType = $contractType;
        $this->name = $contractType->name;
        $this->content = $contractType->content;
        $this->contractAttributes = $this->contractAttributeRepository->all();
        $this->selectedAttributes = $contractType->contractAttributes;
    }

    /**
     * Update selected for contract attribute.
     *
     * @param mixed $values
     * @return void
     */
    public function updateSelected($values)
    {
        $this->selectedAttributes = $this->contractAttributeRepository->getDataInArray('id', $values);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'max:255',
                Rule::unique('contract_types', 'name')->ignore($this->contractType->id),
            ],
            'content' => 'required',
        ];
    }

    /**
     * Update a contract type.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update()
    {
        $validated = $this->validate();

        $data['validated'] = $validated;
        $data['contract_attributes'] = $this->selectedAttributes;

        $this->contractTypeRepository->update($this->contractType, $data) ?
            request()->session()->flash(NOTIFICATION_SUCCESS, __('success.contract_type.update'))
            :
            request()->session()->flash(NOTIFICATION_ERROR, __('error.contract_type.update'));

        return to_route('admin.contract_type.index');
    }

    public function render()
    {
        return view('livewire.admin.contract-type.edit');
    }
}
