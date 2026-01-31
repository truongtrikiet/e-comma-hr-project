<div class="col-lg-12 p-4">
    <div id="filterBody" class="row align-items-center">
        <div class="col-md-4">
            <x-form.form-select
                :id="'sSslStatus'"
                :label="__('general.common.status')"
                :data-values="App\Enum\SslStatus::options(true)"
                :select-value-attribute="'value'"
                :select-value-label="'label'"
                :name="'ssl_status'"
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
            $('#sSchoolTable').DataTable().ajax.reload();
        });
        $('#remove-filter-btn').on('click', function () {
            $('#filterBody').find('.js-enhanced-select').each(function () {
                $(this).val($(this).is('[multiple]') ? [] : '').trigger('change');
            });
            $('#sSchoolTable').DataTable().ajax.reload();
        });
    </script>
@endpush
