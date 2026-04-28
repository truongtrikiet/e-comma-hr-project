<x-hr.base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.meeting_schedule_management.manage_meeting_schedule') }}
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
            __('general.menu.meeting_schedule_management.manage_meeting_schedule') => route('hr.meeting-schedule.index'),
            __('general.menu.meeting_schedule_management.create_meeting_schedule') => '',
        ]"
    />

    <x-form.form-layout
        :form-id="'general-settings'"
        :form-url="route('hr.meeting-schedule.store')"
        :card-title="__('general.menu.meeting_schedule_management.create_meeting_schedule')"
        :custom-col="'col-lg-12'"
    >
        <div class="row">
            <div class="col-lg-8">
                <div class="mb-3">
                    <h5 class="mb-2">{{ __('general.common.information') }}</h5>
                    <div class="row">
                        <div class="col-md-12">

                            <input type="hidden" name="school_id" value="{{ auth()->user()->school_id }}">
                            <x-form.form-input
                                :label="__('general.common.title')"
                                :name="'title'"
                                :placeholder="__('general.common.title')"
                                :is-required="true"
                            />

                            <x-form.form-textarea
                                :id="'description'"
                                :name="'description'"
                                :label="__('general.common.description')"
                                :placeholder="__('general.common.description')"
                                :rows="4"
                                :isRequired="false"
                            />

                            <x-form.form-select
                                :id="'sMeetingTargetTypeSelect'"
                                :label="__('general.common.meeting_target_type')"
                                :data-values="$meetingTargetTypes"
                                :select-value-attribute="'value'"
                                :select-value-label="'label'"
                                :name="'targets[0][target_type]'"
                                :multiple="false"
                                :placeholder="__('general.common.meeting_target_type')"
                                :isRequired="true"
                            />

                            <div id="sMeetingTargetWrapper" class="mb-3 d-none">
                                <x-form.form-select-multiple
                                    :id="'sMeetingTargetSelect'"
                                    :label="__('general.common.meeting_targets')"
                                    :data-values="[]"
                                    :select-value-attribute="'value'"
                                    :select-value-label="'label'"
                                    :name="'targets[0][target_ids]'"
                                    :multiple="true"
                                    :placeholder="__('general.common.select_meeting_targets')"
                                    :isRequired="false"
                                />

                                <input type="hidden" id="sMeetingTargetHiddenSchool" name="targets[0][target_ids][]" value="{{ auth()->user()->school_id }}" disabled>
                            </div>

                            <x-form.form-input
                                :id="'sStartTime'"
                                :label="__('general.common.start_time')"
                                :name="'start_time'"
                                type="datetime-local"
                                :placeholder="__('general.common.start_time')"
                                :isRequired="true"
                                :is-filter="false"
                            />

                            <x-form.form-input
                                :id="'sEndTime'"
                                :label="__('general.common.end_time')"
                                :name="'end_time'"
                                type="datetime-local"
                                :placeholder="__('general.common.end_time')"
                                :isRequired="true"
                                :is-filter="false"
                            />
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="mb-3">
                    <x-buttons.submit :label="__('general.common.complete')"/>
                </div>
            </div>
        </div>
    </x-form.form-layout>

    <x-slot:footerFiles>
        <script>
            window.MEETING_USERS = @json(
                $users->map(fn($user) => [
                    'id' => $user->id,
                    'name' => $user->name
                ])
            );

            window.MEETING_DEPARTMENTS = @json(
                $departments->map(fn($department) => [
                    'id' => $department->id,
                    'name' => $department->name
                ])
            );
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const typeSelect = document.getElementById('sMeetingTargetTypeSelect');
                const targetWrapper = document.getElementById('sMeetingTargetWrapper');
                const targetSelect = document.getElementById('sMeetingTargetSelect');
                const hiddenSchoolInput = document.getElementById('sMeetingTargetHiddenSchool');

                if (!typeSelect || !targetSelect || !targetWrapper) return;

                const schoolId = document.querySelector('[name="school_id"]').value;

                function resetAll() {
                    while (targetSelect.firstChild) targetSelect.removeChild(targetSelect.firstChild);
                    targetWrapper.classList.add('d-none');
                    hiddenSchoolInput.disabled = true;
                    hiddenSchoolInput.value = '';
                }

                function renderOptions(data, isMultiple) {
                    if (!targetSelect) return;
                    targetSelect.multiple = !!isMultiple;

                    data.forEach(item => {
                        const opt = document.createElement('option');
                        opt.value = item.id;
                        opt.text = item.name;
                        targetSelect.appendChild(opt);
                    });

                    targetWrapper.classList.remove('d-none');
                }

                function handleTargetType(type) {
                    resetAll();

                    if (String(type) === '3') {
                        hiddenSchoolInput.value = schoolId;
                        hiddenSchoolInput.disabled = false;
                        return;
                    }

                    if (String(type) === '1') {
                        renderOptions(window.MEETING_USERS || [], false);
                        return;
                    }

                    if (String(type) === '2') {
                        renderOptions(window.MEETING_DEPARTMENTS || [], false);
                        return;
                    }
                }

                typeSelect.addEventListener('change', function () {
                    handleTargetType(this.value);
                });

                if (typeSelect.value) {
                    handleTargetType(typeSelect.value);
                }
            });
        </script>
        
    </x-slot:footerFiles>
</x-hr.base-layout>
