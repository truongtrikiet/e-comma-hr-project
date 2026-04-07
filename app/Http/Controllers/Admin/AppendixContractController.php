<?php

namespace App\Http\Controllers\Admin;

use App\Acl\Acl;
use App\Models\Contract;
use Illuminate\Http\Request;
use App\Models\AppendixContract;
use App\Http\Controllers\Controller;
use App\Services\AppendixContractService;
use App\Repositories\Contract\ContractRepositoryInterface;
use App\Http\Requests\AppendixContract\StoreAppendixContractRequest;
use App\Http\Requests\AppendixContract\UpdateAppendixContractRequest;
use App\Repositories\AppendixContract\AppendixContractRepositoryInterface;

class AppendixContractController extends Controller
{
    public function __construct(
        protected AppendixContractRepositoryInterface $appendixContractRepository,
        protected ContractRepositoryInterface $contractRepository,
        protected AppendixContractService $appendixContractService,
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_APPENDIX_CONTRACT_LIST)->only('index');
        $this->middleware('permission:' . Acl::PERMISSION_APPENDIX_CONTRACT_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . Acl::PERMISSION_APPENDIX_CONTRACT_EDIT)->only(['edit', 'update']);
        $this->middleware('permission:' . Acl::PERMISSION_APPENDIX_CONTRACT_DELETE)->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Contract $contract)
    {
        return view('admin.appendix_contract.create', compact('contract'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppendixContractRequest $request, Contract $contract)
    {
        $data = $request->validated();
        $data['contract_id'] = $contract->id;
        $this->appendixContractService->create($data) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.appendix_contract.store'))
            : session()->flash(NOTIFICATION_ERROR, __('error.appendix_contract.store'));

        return to_route('admin.contract.show', $contract);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract, AppendixContract $appendixContract)
    {
        return view('admin.appendix_contract.edit', compact('contract', 'appendixContract'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Contract $contract, UpdateAppendixContractRequest $request, AppendixContract $appendixContract)
    {
        $this->appendixContractRepository->update($appendixContract, $request->validated()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.appendix_contract.update'))
            : session()->flash(NOTIFICATION_ERROR, __('error.appendix_contract.update'));

        return to_route('admin.contract.show', $contract);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AppendixContract $appendixContract)
    {
        //
    }
}