<div class="col-lg-12 p-4">
    <div id="filterBody" class="row align-items-center">
        @if (session('school_name') === config('subdomain.system_main'))
            <div class="col-md-6">
                <x-form.form-select
                    :id="'sSchoolSelect'"
                    :label="__('general.common.school')"
                    :data-values="$schools"
                    :select-value-attribute="'id'"
                    :select-value-label="'name'"
                    :name="'school_id'"
                    :multiple="false"
                    :placeholder="__('general.common.school')"
                    :isRequired="false"
                    :is-filter="true"
                />
            </div>
        @else
            <input type="hidden" name="school_id" value="{{ session('school_id') }}">
        @endif

        <div class="col-md-6">
            <x-form.form-select
                :id="'sStatus'"
                :label="__('general.common.status')"
                :data-values="App\Enum\SettingStatus::options(true)"
                :select-value-attribute="'value'"
                :select-value-label="'label'"
                :name="'status'"
                :multiple="false"
                :placeholder="__('general.common.status')"
                :is-filter="true"
            />
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
            $('#sSubjectTable').DataTable().ajax.reload();
        });
        $('#remove-filter-btn').on('click', function () {
            $('#filterBody').find('.js-enhanced-select').each(function () {
                $(this).val($(this).is('[multiple]') ? [] : '').trigger('change');
            });
            $('#sSubjectTable').DataTable().ajax.reload();
        });
    </script>
@endpush
