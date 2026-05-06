<?php

namespace App\Http\Controllers\Admin;

use App\Acl\Acl;
use App\Models\ContractType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContractType\StoreContractTypeRequest;
use App\Http\Requests\ContractType\UpdateContractTypeRequest;
use App\Http\Resources\Contract\ContractTypeResource;
use App\Repositories\ContractAttribute\ContractAttributeRepositoryInterface;
use App\Repositories\ContractType\ContractTypeRepositoryInterface;
use App\Repositories\School\SchoolRepositoryInterface;
use App\Services\ContractTypeService;

class ContractTypeController extends Controller
{
    public function __construct(
        protected ContractTypeRepositoryInterface $contractTypeRepository,
        protected ContractAttributeRepositoryInterface $contractAttributeRepository,
        protected ContractTypeService $contractTypeService,
        protected SchoolRepositoryInterface $schoolRepository,
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_CONTRACT_TYPE_LIST)->only('index');
        $this->middleware('permission:' . Acl::PERMISSION_CONTRACT_TYPE_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . Acl::PERMISSION_CONTRACT_TYPE_EDIT)->only(['edit', 'update']);
        $this->middleware('permission:' . Acl::PERMISSION_CONTRACT_TYPE_DELETE)->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $contractTypes = $this->contractTypeRepository->serverPaginationFilteringForAdmin($request->all());
            
            return ContractTypeResource::collection($contractTypes);
        }

        return view('admin.contract_type.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contractAttributes = $this->contractAttributeRepository->all();
        $schools = $this->schoolRepository->getSchoolActive();

        return view('admin.contract_type.create', compact('contractAttributes', 'schools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContractTypeRequest $request)
    {
        $this->contractTypeService->create($request->validated()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.contract-type.store'))
            : session()->flash(NOTIFICATION_ERROR, __('error.contract-type.store'));

        return to_route('admin.contract_type.index');
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
    public function edit(ContractType $contractType)
    {
        $contractAttributes = $this->contractAttributeRepository->all();
        $schools = $this->schoolRepository->getSchoolActive();

        return view('admin.contract_type.edit', compact('contractType', 'contractAttributes', 'schools'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContractTypeRequest $request, ContractType $contractType)
    {
        $this->contractTypeService->update($contractType, $request->validated()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.contract-type.update'))
            : session()->flash(NOTIFICATION_ERROR, __('error.contract-type.update'));

        return to_route('admin.contract_type.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContractType $contractType)
    {
        if ($contractType->contracts->count()) {
            return response()->json([
                'message' => __('error.contract_type.contracts_count'),
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($this->contractTypeRepository->destroy($contractType))
            return response()->json([
                'message' => __('success.delete'),
            ], Response::HTTP_OK);

        return response()->json([
            'message' => __('error.delete'),
        ], Response::HTTP_BAD_REQUEST);
    }
}
