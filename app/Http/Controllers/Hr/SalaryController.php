<?php

namespace App\Http\Controllers\Hr;

use App\Acl\Acl;
use App\Models\Salary;
use Illuminate\Http\Request;
use App\Services\SalaryService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Salary\StoreSalaryRequest;
use App\Http\Resources\Salary\SalaryResource;
use App\Http\Requests\Salary\UpdateSalaryRequest;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Salary\SalaryRepositoryInterface;
use Illuminate\Support\Facades\Log;

class SalaryController extends Controller
{
    public function __construct(
        protected SalaryRepositoryInterface $salaryRepository,
        protected UserRepositoryInterface $userRepository,
        protected SalaryService $salaryService,
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_SALARY_LIST)->only('index');
        $this->middleware('permission:' . Acl::PERMISSION_SALARY_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . Acl::PERMISSION_SALARY_EDIT)->only(['edit', 'update']);
        $this->middleware('permission:' . Acl::PERMISSION_SALARY_DELETE)->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $salary = $this->salaryRepository->serverPaginationFilteringForAdmin($request->all());
            
            return SalaryResource::collection($salary);
        }
        return view('hr.salary.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = $this->userRepository->all();

        return view('hr.salary.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSalaryRequest $request)
    {
        $this->salaryRepository->create($request->validated()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.salary.create'))
            : session()->flash(NOTIFICATION_ERROR, __('error.salary.create'));

        return to_route('hr.salary.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Salary $salary)
    {
        $audits = $this->salaryService->getSalaryAudits($salary);

        return response()->json($audits);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Salary $salary)
    {
        $users = $this->userRepository->all();

        return view('hr.salary.edit', compact('users', 'salary'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalaryRequest $request, Salary $salary)
    {
        $this->salaryRepository->update($salary, $request->validated()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.salary.update'))
            : session()->flash(NOTIFICATION_ERROR, __('error.salary.update'));

        return to_route('hr.salary.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
