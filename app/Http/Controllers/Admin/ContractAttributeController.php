<?php

namespace App\Http\Controllers\Admin;

use App\Acl\Acl;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContractAttribute\StoreContractAttributeRequest;
use App\Http\Requests\ContractAttribute\UpdateContractAttributeRequest;
use App\Http\Resources\Contract\ContractAttributeResource;
use App\Models\ContractAttribute;
use App\Repositories\ContractAttribute\ContractAttributeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ContractAttributeController extends Controller
{
    public function __construct(
        protected ContractAttributeRepositoryInterface $contractAttributeRepository,
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_CONTRACT_ATTRIBUTE_LIST)->only('index');
        $this->middleware('permission:' . Acl::PERMISSION_CONTRACT_ATTRIBUTE_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . Acl::PERMISSION_CONTRACT_ATTRIBUTE_EDIT)->only(['edit', 'update']);
        $this->middleware('permission:' . Acl::PERMISSION_CONTRACT_ATTRIBUTE_DELETE)->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $contractAttributes = $this->contractAttributeRepository->serverPaginationFilteringForAdmin($request->all());

            return ContractAttributeResource::collection($contractAttributes);
        }
        
        return view('admin.contract_attribute.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.contract_attribute.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContractAttributeRequest $request)
    {
        $this->contractAttributeRepository->create($request->validated()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.contract_attribute.store'))
            : session()->flash(NOTIFICATION_ERROR, __('error.contract_attribute.store'));

        return to_route('admin.contract_attribute.index');
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
    public function edit(ContractAttribute $contractAttribute)
    {
        return view('admin.contract_attribute.edit', compact('contractAttribute'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContractAttributeRequest $request, ContractAttribute $contractAttribute)
    {
        $this->contractAttributeRepository->update($contractAttribute, $request->validated()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.contract_attribute.update'))
            : session()->flash(NOTIFICATION_ERROR, __('error.contract_attribute.update'));

        return to_route('admin.contract_attribute.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContractAttribute $contractAttribute)
    {
        if ($contractAttribute->contractTypes->count()) {
            return response()->json([
                'message' => __('error.contract_attribute.contract_types_count'),
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($this->contractAttributeRepository->destroy($contractAttribute)) {
            return response()->json([
                'message' => __('success.contract_attribute.delete'),
            ], Response::HTTP_OK);
        }

        return response()->json([
            'message' => __('error.contract_attribute.delete'),
        ], Response::HTTP_BAD_REQUEST);
    }
}
