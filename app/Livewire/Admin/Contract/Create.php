<?php

namespace App\Livewire\Admin\Contract;

use App\Enum\ContractStatus;
use App\Models\User;
use Livewire\Component;
use App\Models\Customer;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Contract\ContractRepositoryInterface;
use App\Repositories\ContractType\ContractTypeRepositoryInterface;
use Illuminate\Validation\Rule;

class Create extends Component
{
    protected $userRepository;

    protected $contractTypeRepository;

    protected $contractRepository;

    public $contractStatuses;

    public $objectTypes;

    public $objects;

    public $contractTypes;

    public $selectedContractTypeContent;

    public $selectedContractAttributes;

    public $selectedContractType;

    public $objectType;

    public $object;

    public $contractType;

    public $status;

    public $signedAt;

    public $expiredAt;

    public $contractAttributes;

    public function boot(
        UserRepositoryInterface $userRepository,
        ContractTypeRepositoryInterface $contractTypeRepository,
        ContractRepositoryInterface $contractRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->contractTypeRepository = $contractTypeRepository;
        $this->contractRepository = $contractRepository;
    }

    public function mount()
    {
        $this->objectTypes = [
            [
                'class' => get_class(new User()),
                'name' => __('general.common.user'),
            ],
            [
                'class' => get_class(new Customer()),
                'name' => __('general.common.customer'),
            ],
        ];
        $this->objects = [];
        $this->contractTypes = $this->contractTypeRepository->all();
        $this->selectedContractAttributes = [];
        $this->contractStatuses = ContractStatus::options(true);
    }

    /**
     * Handle select contract type.
     *
     * @return void
     */
    public function handleChangeContractType()
    {
        $this->selectedContractType = $this->contractTypeRepository->find($this->contractType);
        if ($this->selectedContractType) {
            $this->selectedContractTypeContent = $this->selectedContractType->content;
            $this->selectedContractAttributes = $this->selectedContractType->contractAttributes;
        } else {
            $this->selectedContractTypeContent = '';
            $this->selectedContractAttributes = [];
        }

        $this->contractAttributes = [];
        foreach ($this->selectedContractAttributes as $attribute) {
            $this->contractAttributes[$attribute['id']] = [
                'id' => $attribute['id'],
                'name' => $attribute['name'],
                'value' => '',
            ];
        }
    }

    /**
     * Handle select object type.
     *
     */
    public function handleChangeObjectType()
    {
        $this->object = null;
        if (!class_exists($this->objectType)) {
            $this->objects = [];
            $this->objectType = null;
            return;
        }

        if (method_exists($this->objectType, 'scopeActive')) {
            $this->objects = $this->objectType::active()->get();
        } else {
            $this->objects = $this->objectType::all();
        }

        return $this->objects;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'objectType' => 'required',
            'object' => 'required',
            'contractType' => 'required|exists:contract_types,id',
            'status' => ['required', Rule::enum(ContractStatus::class)],
            'signedAt' => 'required',
            'expiredAt' => 'required',
            'contractAttributes.*.value' => 'required',
        ];
    }

    /**
     * Store a contract.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $this->validate();

        $data['object'] = [
            'id' => $this->object,
            'type' => $this->objectType,
        ];

        $data['dataContract'] = [
            'contract_type_id' => $this->contractType,
            'status' => $this->status,
            'signed_at' => $this->signedAt,
            'expired_at' => $this->expiredAt,
        ];

        $data['contractTypeAttributes'] = $this->selectedContractType->contractTypeAttributes;

        $data['contractAttributes'] = $this->contractAttributes;

        $this->contractRepository->create($data) ?
            request()->session()->flash(NOTIFICATION_SUCCESS, __('success.contract.store'))
            :
            request()->session()->flash(NOTIFICATION_ERROR, __('error.contract.store'));

        $this->reset();

        return to_route('admin.contract.index');
    }

    public function render()
    {
        return view('livewire.admin.contract.create');
    }
}
