<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Repositories\AIProfile\AIProfileRepositoryInterface;
use Illuminate\Http\Request;
use App\Acl\Acl;
use App\Enum\ActiveStatus;
use App\Enum\AIProviderEnum;
use App\Http\Requests\AIProfile\StoreAIProfileRequest;
use App\Http\Requests\AIProfile\UpdateAIProfileRequest;
use App\Http\Resources\AI\AIProfileResource;
use App\Models\AIProfile;
use App\Repositories\School\SchoolRepositoryInterface;
use App\Services\AIProfileService;
use Illuminate\Support\Facades\Log;

class AIProfileController extends Controller
{
    public function __construct(
        protected AIProfileRepositoryInterface $aIProfileRepository,
        protected SchoolRepositoryInterface $schoolRepository,
        protected AIProfileService $aIProfileService,
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_AI_PROFILE_LIST)->only('index');
        $this->middleware('permission:' . Acl::PERMISSION_AI_PROFILE_ADD)->only(['create', 'store']);
        $this->middleware('permission:' . Acl::PERMISSION_AI_PROFILE_EDIT)->only(['edit', 'update']);
        $this->middleware('permission:' . Acl::PERMISSION_AI_PROFILE_DELETE)->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $aIProfiles = $this->aIProfileRepository->serverPaginationFiltering($request->all());

            return AIProfileResource::collection($aIProfiles);
        }

        return view('hr.ai_profile.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = $this->schoolRepository->getSchoolActive();
        $provides = AIProviderEnum::options();
        $statuses = ActiveStatus::options();

        return view('hr.ai_profile.create', compact(
            'schools',
            'provides',
            'statuses'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAIProfileRequest $request)
    {
        $this->aIProfileService->create($request->validated()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.ai-profile.create'))
            : session()->flash(NOTIFICATION_ERROR, __('error.ai-profile.create'));

        return to_route('hr.ai_profile.index');
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
    public function edit(AIProfile $ai_profile)
    {
        $schools = $this->schoolRepository->getSchoolActive();
        $provides = AIProviderEnum::options();
        $statuses = ActiveStatus::options();

        return view ('hr.ai_profile.edit', compact(
            'ai_profile',
            'schools', 
            'provides',
            'statuses'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAIProfileRequest $request, AIProfile $ai_profile)
    {
        $this->aIProfileService->update($ai_profile, $request->validated()) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.ai-profile.update'))
            : session()->flash(NOTIFICATION_ERROR, __('error.ai-profile.update'));

        return to_route('hr.ai_profile.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AIProfile $ai_profile)
    {
        $this->aIProfileRepository->destroy($ai_profile) ?
            session()->flash(NOTIFICATION_SUCCESS, __('success.ai-profile.delete'))
            : session()->flash(NOTIFICATION_ERROR, __('error.ai-profile.delete'));

        return to_route('hr.ai_profile.index');
    }

    /**
     * Call test API connection for a profile.
     */
    public function testApi(AIProfile $ai_profile)
    {
        try {
            $result = $this->aIProfileService->testApiConnection($ai_profile);

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            Log::error('AIProfile testApi failed: ' . $e->getMessage(), ['id' => $ai_profile->id ?? null]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
