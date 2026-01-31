<?php

namespace App\Http\Controllers\Admin;

use App\Acl\Acl;
use App\Enum\SettingStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subject\StoreSubjectRequest;
use App\Http\Requests\Subject\UpdateSubjectRequest;
use App\Http\Resources\Subject\SubjectResource;
use App\Models\Subject;
use App\Repositories\School\SchoolRepositoryInterface;
use App\Repositories\Subject\SubjectRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubjectController extends Controller
{
    public function __construct(
        protected SubjectRepositoryInterface $subjectRepository,
        protected SchoolRepositoryInterface $schoolRepository,
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_SUBJECT_LIST)->only('index');
        $this->middleware('permission:' . Acl::PERMISSION_SUBJECT_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . Acl::PERMISSION_SUBJECT_EDIT)->only(['edit', 'update']);
        $this->middleware('permission:' . Acl::PERMISSION_SUBJECT_DELETE)->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $subjects = $this->subjectRepository->serverPaginationFiltering($request->all());

            return SubjectResource::collection($subjects);
        }

        $schools = $this->schoolRepository->getSchoolActive();

        return view('admin.subject.index', compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = $this->schoolRepository->getSchoolActive();
        $statuses = SettingStatus::options();

        return view('admin.subject.create', compact('schools', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubjectRequest $request)
    {
        $this->subjectRepository->create($request->validated()) ? 
            session()->flash('success', __('success.subject.store')) 
            : session()->flash('error', __('error.subject.store'));

        return to_route('admin.subject.index');
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
    public function edit(Subject $subject)
    {
        $schools = $this->schoolRepository->getSchoolActive();
        $statuses = SettingStatus::options();

        return view('admin.subject.edit', compact(
            'subject', 
            'schools', 
            'statuses'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        $this->subjectRepository->update($subject, $request->validated()) ? 
            session()->flash('success', __('success.subject.update')) 
            : session()->flash('error', __('error.subject.update'));

        return to_route('admin.subject.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        if ($this->subjectRepository->destroy($subject))
            return response()->json([
                'message' => __('success.delete'),
            ], Response::HTTP_OK);
        return response()->json([
            'message' => __('error.delete'),
        ], Response::HTTP_BAD_REQUEST);
    }
}
