<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Acl\Acl;

class CandidateScreeningController extends Controller
{
    public function __construct(
        //
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_CANDIDATE_SCREENING_LIST)->only('index');
        $this->middleware('permission:' . Acl::PERMISSION_CANDIDATE_SCREENING_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . Acl::PERMISSION_CANDIDATE_SCREENING_EDIT)->only(['edit', 'update']);
        $this->middleware('permission:' . Acl::PERMISSION_CANDIDATE_SCREENING_DELETE)->only('destroy'); 
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
