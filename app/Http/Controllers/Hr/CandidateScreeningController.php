<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Acl\Acl;
use App\Enum\CandidateScreeningStatus;
use App\Enum\PositionTypeEnum;
use App\Http\Requests\CandidateScreening\ScanCandidateResumeRequest;
use App\Http\Resources\AI\CandidateScreeningResource;
use App\Http\Resources\CandidateScreening\CandidateScreeningDetailResource;
use App\Models\CandidateScreening;
use App\Repositories\CandidateScreening\CandidateScreeningRepositoryInterface;
use App\Models\AIProfile;
use App\Repositories\AIProfile\AIProfileRepositoryInterface;
use App\Services\CandidateScreeningService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CandidateScreeningController extends Controller
{
    public function __construct(
        protected CandidateScreeningRepositoryInterface $candidateScreeningRepository,
        protected AIProfileRepositoryInterface $aIProfileRepository,
        protected CandidateScreeningService $candidateScreeningService,
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_CANDIDATE_SCREENING_LIST)->only('index');
        $this->middleware('permission:' . Acl::PERMISSION_CANDIDATE_SCREENING_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . Acl::PERMISSION_CANDIDATE_SCREENING_EDIT)->only(['edit', 'update']);
        $this->middleware('permission:' . Acl::PERMISSION_CANDIDATE_SCREENING_DELETE)->only(['destroy', 'deleteAllByStatus']); 
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $result = $this->candidateScreeningRepository
                ->serverPaginationFiltering($request->all());

            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $result['total'],
                'recordsFiltered' => $result['filtered'],
                'data' => CandidateScreeningResource::collection($result['data']),
            ]);
        }

        $positionTypes = PositionTypeEnum::options();
        $user = auth()->user();
        $aiProfiles = $this->aIProfileRepository->getAIProfileBySchool($user->school_id);
        $statuses = CandidateScreeningStatus::options();

        return view(
            'hr.candidate_screening.index', compact('positionTypes', 'aiProfiles', 'statuses')
        );
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
    public function show(CandidateScreening $candidateScreening)
    {
        $resource = new CandidateScreeningDetailResource($candidateScreening);

        if (request()->wantsJson()) {
            return $resource;
        }

        $detail = $resource->toArray(request());

        return view('hr.candidate_screening.show', compact('candidateScreening', 'detail'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CandidateScreening $candidateScreening)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CandidateScreening $candidateScreening)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CandidateScreening $candidateScreening)
    {
        $this->candidateScreeningRepository->destroy($candidateScreening) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.candidate_screening.delete'))
            : session()->flash(NOTIFICATION_ERROR, __('error.candidate_screening.delete'));

        return to_route('hr.candidate_screening.index');
    }

    /**
     * Scan/upload resumes and create candidate screening entries.
     */
    public function scan(ScanCandidateResumeRequest $request)
    {
        $user = auth()->user();
        $schoolId = session('school_id') ?? ($user->school_id ?? null);

        $files = $request->file('files') ?? [];
        if ($files && !is_array($files)) {
            $files = [$files];
        }

        Log::info('Starting candidate screening scan', [
            'school_id' => $schoolId,
            'ai_profile_id' => $request->ai_profile_id,
            'position_type' => $request->position_type,
            'file_count' => count($files),
        ]);

        if (empty($schoolId)) {
            return response()->json(['success' => false, 'message' => 'Missing school id'], 422);
        }

        $result = $this->candidateScreeningService->scanAndAnalyze(
            schoolId: (int) $schoolId,
            aiProfileId: (int) $request->ai_profile_id,
            positionType: $request->position_type,
            files: $files
        );

        $response = [
            'success' => true,
            'created' => $result['created'] ?? 0,
            'passed'  => $result['passed'] ?? 0,
            'failed'  => $result['failed'] ?? 0,
        ];

        if (!empty($result['errors'])) {
            $response['errors'] = $result['errors'];
            $first = $result['errors'][0];
            $response['message'] = sprintf('File %s failed: %s', $first['file'] ?? 'unknown', $first['message'] ?? '');
        }

        return response()->json($response);
    }

    /**
     * Delete all candidate screening records by status.
     */
    public function deleteAllByStatus(Request $request)
    {
        $status = $request->input('status');

        $allowed = CandidateScreeningStatus::values();
        $statusInt = is_numeric($status) ? (int) $status : null;

        if ($statusInt === null || ! in_array($statusInt, $allowed, true)) {
            return response()->json(['success' => false, 'message' => 'Invalid status'], 422);
        }

        $deletedCount = $this->candidateScreeningService->deleteAllByStatus($statusInt);

        return response()->json([
            'success' => true,
            'deleted_count' => $deletedCount,
        ]);
    }

    /**
     * Send candidate screening result to email.
     */
    public function sendResultEmail(Request $request, CandidateScreening $candidateScreening)
    {
        $data = $request->validate([
            'interview_time' => 'required|string',
            'interview_location' => 'required|string',
            'interview_note' => 'nullable|string',
        ]);

        $this->candidateScreeningService->sendResultEmail($candidateScreening, $data);

        return response()->json(['success' => true, 'message' => 'Email sent successfully']);
    }
}
