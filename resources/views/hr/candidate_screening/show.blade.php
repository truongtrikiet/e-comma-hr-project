<x-hr.base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.candidate_screening_management.candidate_detail') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>
        <link href="{{ asset('vendor/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
        <!-- Clockpicker -->
        <link href="{{ asset('vendor/clockpicker/css/bootstrap-clockpicker.min.css') }}" rel="stylesheet">
        <!-- asColorpicker -->
        <link href="{{ asset('vendor/jquery-asColorPicker/css/asColorPicker.min.css') }}" rel="stylesheet">
        <!-- Material color picker -->
        <link href="{{ asset('vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
        <!-- Pick date -->
        <link rel="stylesheet" href="{{ asset('vendor/pickadate/themes/default.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/pickadate/themes/default.date.css') }}">
        <!-- Custom Stylesheet -->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.default.min.css">
        <link rel="stylesheet" type="text/css" href="{{asset('plugins/filepond/filepond.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('plugins/filepond/FilePondPluginImagePreview.min.css')}}">

    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.candidate_screening_management.manage_candidate_screening') => route('hr.candidate-screening.index'),
            __('general.menu.candidate_screening_management.candidate_detail') => '',
        ]"
    />

    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('Candidate Information') }}</h5>
                    </div>
                    <div class="card-body">
                        @php $candidateInfo = $candidateScreening; $detail = $detail ?? []; @endphp
                        <dl class="row mb-0">
                            <dt class="col-sm-4">{{ __('general.common.candidate') }}</dt>
                            <dd class="col-sm-8">{{ $candidateInfo->candidate_name ?? '-' }}</dd>

                            <dt class="col-sm-4">{{ __('general.common.email') }}</dt>
                            <dd class="col-sm-8">{{ $candidateInfo->candidate_email ?? '-' }}</dd>

                            <dt class="col-sm-4">{{ __('general.common.phone') }}</dt>
                            <dd class="col-sm-8">{{ $candidateInfo->candidate_phone_number ?? '-' }}</dd>

                            <dt class="col-sm-4">{{ __('general.common.position_type') }}</dt>
                            <dd class="col-sm-8">{{ $detail['position_type_name'] ?? '-' }}</dd>

                            <dt class="col-sm-4">{{ __('general.common.status') }}</dt>
                            <dd class="col-sm-8">{{ $detail['status_name'] ?? '-' }}</dd>

                            <!-- <dt class="col-sm-4">{{ __('general.common.created_at') }}</dt>
                            <dd class="col-sm-8">{{ optional($candidateInfo->created_at)->toDateTimeString() ?? '-' }}</dd> -->
                        </dl>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('AI Result') }}</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $ai = $detail['ai_result_json'] ?? ($candidateInfo->ai_result_json ?? []);
                            $recommended = $detail['recommended_roles'] ?? ($candidateInfo->recommended_roles ?? ($ai['recommended_roles'] ?? []));

                            $summaryBullets = [];

                            if (!empty($ai['highlights']) && is_array($ai['highlights'])) {
                                $summaryBullets = array_values($ai['highlights']);
                            } elseif (!empty($ai['summary']) && is_string($ai['summary'])) {
                                $s = trim($ai['summary']);
                                $sents = preg_split('/(?<=[.!?])\s+/', $s, -1, PREG_SPLIT_NO_EMPTY);
                                if (!empty($sents)) {
                                    $summaryBullets = array_slice($sents, 0, 5);
                                } else {
                                    $summaryBullets = [$s];
                                }
                            } elseif (!empty($ai['top_findings']) && is_array($ai['top_findings'])) {
                                $summaryBullets = array_values($ai['top_findings']);
                            } elseif (!empty($recommended) && is_array($recommended)) {
                                foreach ($recommended as $r) {
                                    $label = $r['role'] ?? $r['title'] ?? null;
                                    if ($label) {
                                        $s = $label;
                                        if (!empty($r['confidence'])) $s .= ' — ' . $r['confidence'];
                                        $summaryBullets[] = $s;
                                    }
                                }
                            } elseif (!empty($ai['skills']) && is_array($ai['skills'])) {
                                $summaryBullets[] = __('Skills') . ': ' . implode(', ', array_slice($ai['skills'], 0, 10));
                            }

                            $summaryBullets = array_map(fn($b) => is_scalar($b) ? (string)$b : json_encode($b), $summaryBullets);
                        @endphp

                        <h6>{{ __('AI Summary') }}</h6>
                        @if(!empty($summaryBullets))
                            <ul>
                                @foreach($summaryBullets as $b)
                                    <li>{{ $b }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">{{ __('No AI summary available') }}</p>
                        @endif

                        <hr />

                        <h6>{{ __('Recommended Roles') }}</h6>
                        @if(!empty($recommended) && is_array($recommended))
                            <ul>
                                @foreach($recommended as $role)
                                    <li>
                                        <strong>{{ $role['role'] ?? ($role['title'] ?? '—') }}</strong>
                                        @if(!empty($role['confidence']))
                                            — <em>{{ $role['confidence'] }}</em>
                                        @endif
                                        @if(!empty($role['evidence']) && is_array($role['evidence']))
                                            <ul class="mt-1">
                                                @foreach($role['evidence'] as $ev)
                                                    <li class="small text-muted">{{ $ev }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">{{ __('No recommended roles') }}</p>
                        @endif

                        <hr />

                        <h6>{{ __('Raw AI Result (formatted)') }}</h6>
                        @if(empty($ai))
                            <p class="text-muted">{{ __('No AI result available') }}</p>
                        @else
                            @php
                                function renderValue($v) {
                                    if (is_null($v)) return '<span class="text-muted">null</span>';
                                    if (is_scalar($v)) return e((string)$v);
                                    if (is_array($v)) {
                                        $out = '<ul>';
                                        foreach ($v as $k => $item) {
                                            $out .= '<li>' . (is_string($k) ? '<strong>'.e($k).':</strong> ' : '') . renderValue($item) . '</li>';
                                        }
                                        $out .= '</ul>';
                                        return $out;
                                    }
                                    return e(json_encode($v));
                                }
                            @endphp

                            <div>{!! renderValue($ai) !!}</div>
                        @endif

                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-header"><strong>{{ __('Metadata') }}</strong></div>
                    <div class="card-body">
                        <p class="mb-1"><strong>{{ __('AI Profile') }}:</strong> {{ $candidateInfo->aiProfile?->name ?? '-' }}</p>
                        <p class="mb-1"><strong>{{ __('Created') }}:</strong> {{ $candidateInfo->created_at }}</p>
                        <p class="mb-1"><strong>{{ __('Updated') }}:</strong> {{ $candidateInfo->updated_at }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

</x-hr.base-layout>
