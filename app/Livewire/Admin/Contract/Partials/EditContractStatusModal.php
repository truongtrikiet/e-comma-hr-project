<?php

namespace App\Livewire\Admin\Contract\Partials;

use App\Enum\ContractStatus;
use App\Repositories\Contract\ContractRepositoryInterface;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditContractStatusModal extends Component
{
    protected $contractRepository;

    public $contract;

    public $route;

    public $contractStatuses;

    public $status;

    public function boot(
        ContractRepositoryInterface $contractRepository
    ) {
        $this->contractRepository = $contractRepository;
    }

    /**
     * Summary of mount
     *
     * @param mixed $post
     * @return void
     */
    public function mount($contract, $route)
    {
        $this->contract = $contract;
        $this->route = $route;
        $this->status = $contract->status;
        $this->contractStatuses = ContractStatus::options();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => ['required', Rule::enum(ContractStatus::class)],
        ];
    }

    /**
     * Update status a contract
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update()
    {
        $validated = $this->validate();

        $this->contractRepository->update($this->contract, $validated) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.contract.update_status'))
            : session()->flash(NOTIFICATION_ERROR, __('error.contract.update_status'));

        return to_route($this->route, $this->contract);
    }

    public function render()
    {
        return view('livewire.admin.contract.partials.edit-contract-status-modal');
    }
}
