<div class="col-lg-12 p-4">
    <div id="filterBody" class="row align-items-center">
        <div class="col-md-6">
            <x-form.form-select
                :id="'sStatus'"
                :label="__('general.common.status')"
                :data-values="App\Enum\ActiveStatus::options(true)"
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
            $('#sFurloughTypeTable').DataTable().ajax.reload();
        });
        $('#remove-filter-btn').on('click', function () {
            $('#filterBody').find('.js-enhanced-select').each(function () {
                $(this).val($(this).is('[multiple]') ? [] : '').trigger('change');
            });
            $('#sFurloughTypeTable').DataTable().ajax.reload();
        });
    </script>
@endpush
