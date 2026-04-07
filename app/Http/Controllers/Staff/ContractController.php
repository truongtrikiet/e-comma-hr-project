<?php

namespace App\Http\Controllers\Staff;

use App\Acl\Acl;
use App\Models\Contract;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\ContractService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Contract\ContractResource;
use App\Models\Contracts\SettingKey;
use App\Repositories\Contract\ContractRepositoryInterface;
use App\Repositories\Setting\SettingRepositoryInterface;

class ContractController extends Controller
{
    public function __construct(
        protected ContractRepositoryInterface $contractRepository,
        protected ContractService $contractService,
        protected SettingRepositoryInterface $settingRepository,
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_CONTRACT_LIST)->only('index');
        $this->middleware('permission:' . Acl::PERMISSION_CONTRACT_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . Acl::PERMISSION_CONTRACT_EDIT)->only(['edit', 'update']);
        $this->middleware('permission:' . Acl::PERMISSION_CONTRACT_DELETE)->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $contract = $this->contractRepository->serverPaginationFilteringForStaff($request->all());
            return ContractResource::collection($contract);
        }
        return view('staff.contract.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        return view('staff.contract.show', compact('contract'));
    }

    /**
     * Display the specified resource.
     */
    public function showDetail(Request $request, Contract $contract)
    {
        $appendixContracts = $this->contractRepository->getAppendixContractInArrayForContract($contract, json_decode($request->appendixContracts));
        $contractTypeContent = $this->contractService->generateContractContent($contract);
        $contractHeader  = $this->settingRepository->findByKey(SettingKey::CONTRACT_HEADER['key']);
        $contractWatermark  = $this->settingRepository->findByKey(SettingKey::CONTRACT_WATERMARK['key']);

        $pdf = PDF::loadView(
            'staff.contract.show-detail',
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        //
    }
}