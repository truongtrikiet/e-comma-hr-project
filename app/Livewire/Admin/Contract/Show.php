<?php

namespace App\Livewire\Admin\Contract;

use Livewire\Component;
use App\Repositories\AppendixContract\AppendixContractRepositoryInterface;

class Show extends Component
{
    public $contract;

    public $route;

    public $routeCreateAppendixContract;

    public $routeEditAppendixContract;

    public $model;

    public $appendixContracts;

    public $appendixContractId;

    protected $appendixContractRepository;

    /**
     * Initialize necessary components for the component.
     *
     * @param AppendixContractRepositoryInterface $appendixContractRepository
     */
    public function boot(
        AppendixContractRepositoryInterface $appendixContractRepository,
    ) {
        $this->appendixContractRepository = $appendixContractRepository;
    }

    /**
     * Assign the list of appendix contracts to the property when the component is mounted.
     */
    public function mount()
    {
        $this->appendixContracts = $this->contract->appendixContracts;
    }

    /**
     * Create a route to the page for creating a new appendix contract.
     *
     * @return \Illuminate\Routing\RedirectResponse
     */
    public function createAppendixContract()
    {
        return to_route($this->routeCreateAppendixContract, ['contract' => $this->contract->id]);
    }

    /**
     * Create a route to the page listing all contracts.
     *
     * @return \Illuminate\Routing\RedirectResponse
     */
    public function store()
    {
        return to_route($this->route, $this->model);
    }

    /**
     * Create a route to the page for editing an appendix contract.
     *
     * @param int $id ID of the appendix contract to be edited
     * @return \Illuminate\Routing\RedirectResponse
     */
    public function editAppendixContract($id)
    {
        $appendixContract = $this->appendixContractRepository->find($id);

        return to_route($this->routeEditAppendixContract, ['contract' => $this->contract->id, 'appendixContract' => $appendixContract]);
    }

    /**
     * Confirm the request to delete an appendix contract.
     *
     * @param int $id ID of the appendix contract to be deleted
     */
    public function confirmDeleteAppendixContract($id)
    {
        $this->appendixContractId = $id;
    }

    /**
     * Perform the deletion of an appendix contract and update the list of appendix contracts.
     */
    public function destroyAppendixContract()
    {
        $appendixContract = $this->appendixContractRepository->find($this->appendixContractId);

        $this->appendixContractRepository->destroy($appendixContract);

        $this->appendixContracts = $this->appendixContracts->filter(function ($item) {
            return $item->id !== $this->appendixContractId;
        });

        $this->reset('appendixContractId');
        $this->dispatch('hide-delete-modal');
    }

    /**
     * Render the view for the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.admin.contract.show');
    }
}
