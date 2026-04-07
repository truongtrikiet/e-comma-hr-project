<?php

namespace App\Livewire\Admin\ContractType;

use App\Repositories\ContractAttribute\ContractAttributeRepositoryInterface;
use App\Repositories\ContractType\ContractTypeRepositoryInterface;
use Livewire\Component;

class Create extends Component
{
    protected $contractTypeRepository;

    protected $contractAttributeRepository;

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

    public function mount()
    {
        $this->contractAttributes = $this->contractAttributeRepository->all();
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
                'unique:contract_types',
            ],
            'content' => 'required',
        ];
    }

    /**
     * Create a contract type.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $validated = $this->validate();

        $data['validated'] = $validated;
        $data['contract_attributes'] = $this->selectedAttributes;

        $this->contractTypeRepository->create($data) ?
            request()->session()->flash(NOTIFICATION_SUCCESS, __('success.contract_type.store'))
            :
            request()->session()->flash(NOTIFICATION_ERROR, __('error.contract_type.store'));

        return to_route('admin.contract_type.index');
    }

    public function render()
    {
        return view('livewire.admin.contract-type.create');
    }
}
