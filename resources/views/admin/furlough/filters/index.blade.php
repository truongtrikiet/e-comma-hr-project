<div class="col-lg-12 p-4">
    <div id="filterBody" class="row align-items-center">
        <div class="col-md-4">
            <x-form.form-select
                :id="'sFurloughTypeSelect'"
                :label="__('general.common.furlough_type_name')"
                :data-values="$furloughTypes"
                :select-value-attribute="'id'"
                :select-value-label="'name'"
                :name="'furlough_type_id'"
                :multiple="false"
                :placeholder="__('general.common.furlough_type_name')"
                :is-filter="true"
            />
        </div>
        <div class="col-md-4">
            <x-form.form-select
                :id="'sDurationTypeSelect'"
                :label="__('general.common.furlough_duration_type')"
                :data-values="App\Enum\DurationType::options(true)"
                :select-value-attribute="'value'"
                :select-value-label="'label'"
                :name="'duration_type'"
                :multiple="false"
                :placeholder="__('general.common.furlough_duration_type')"
                :is-filter="true"
            />
        </div>
        <div class="col-md-4">
            <x-form.form-select
                :id="'sHalfDaySessionSelect'"
                :label="__('general.common.furlough_half_day_session')"
                :data-values="App\Enum\HalfDaySession::options(true)"
                :select-value-attribute="'value'"
                :select-value-label="'label'"
                :name="'half_day_session'"
                :multiple="false"
                :placeholder="__('general.common.furlough_half_day_session')"
                :is-filter="true"
            />
        </div>
        <div class="col-md-4">
            <x-form.form-select
                :id="'sFurloughStatus'"
                :label="__('general.common.status')"
                :data-values="App\Enum\FurloughStatus::options(true)"
                :select-value-attribute="'value'"
                :select-value-label="'label'"
                :name="'furlough_status'"
                :multiple="false"
                :placeholder="__('general.common.status')"
                :is-filter="true"
            />
        </div>
        <div class="col-md-4">
            <x-form.form-input
                :id="'sStartTime'"
                :label="__('general.common.start_time')"
                :name="'start_time'"
                type="date"
                :placeholder="__('general.common.start_time')"
                :isRequired="false"
                :is-filter="true"
            />
        </div>

        <div class="col-md-4">
            <x-form.form-input
                :id="'sEndTime'"
                :label="__('general.common.end_time')"
                :name="'end_time'"
                type="date"
                :placeholder="__('general.common.end_time')"
                :isRequired="false"
                :is-filter="true"
            />
        </div>

        <div class="col-md-4">
            @if (session('school_name') === config('subdomain.system_main'))
            <x-form.form-select
                :id="'sSchoolSelect'"
                :label="__('general.common.school')"
                :data-values="$schools"
                :select-value-attribute="'id'"
                :select-value-label="'name'"
                :name="'school_id'"
                :multiple="false"
                :placeholder="__('general.common.school')"
                :is-filter="true"
            />
            @endif
        </div>
    </div>
    <hr>
    <div class="filter-header d-flex justify-content-end align-items-center">
        <button type="button" class="btn btn-primary mx-1" id="filter-btn">{{ __('general.filter.sort') }}</button>
        <button type="button" class="btn btn-default" id="remove-filter-btn">{{ __('general.filter.remove_filter') }}</button>
    </div>
</div>

@push('footerFiles')
    <script>
        $('#filter-btn').on('click', function () {
            $('#sFurloughTable').DataTable().ajax.reload();
        });
        $('#remove-filter-btn').on('click', function () {
            $('#filterBody').find('.js-enhanced-select').each(function () {
                $(this).val($(this).is('[multiple]') ? [] : '').trigger('change');
                $('#sStartTime').val(null).trigger('change');
                $('#sEndTime').val(null).trigger('change');
            });
            $('#sFurloughTable').DataTable().ajax.reload();
        });
    </script>
@endpush
