@push('headerFiles')
    <link href="{{ asset('assets/css/lib/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/lib/themify-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/lib/helper.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    <style>
    .header { 
        margin-left: 0 !important; 
        z-index: 1 !important; 
    };
    .body {
        color: #868e96;
    };
    </style>
@endpush
<style>
    #{{ $id ?? 'datatable' }} td,
    #{{ $id ?? 'datatable' }} th,
    #{{ $id ?? 'datatable' }} .badge {
        color: #1b1b1b !important;
    }
</style>

<div class="table-responsive">
    <table id="{{ $id ?? 'datatable' }}" class="{{ $tableClass ?? 'display' }}" style="{{ $tableStyle ?? 'width: 100%;' }}">
        <thead>
            {{ $tableHeader }}
        </thead>
        @if(isset($tableBody))
            <tbody>
                {{ $tableBody }}
            </tbody>
        @endif
    </table>
</div>

@push('footerFiles')
    <script src="{{ asset('assets/js/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/jquery.nanoscroller.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/data-table/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/data-table/datatables.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (!window.jQuery || !jQuery().DataTable) {
                console.warn('jQuery or DataTables not loaded yet.');
                return;
            }

            let drawDT = 0;

            const baseOptions = {
                dom: "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'>>>" +
                    "<'table-responsive'tr>" +
                    "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
                oLanguage: {
                    oPaginate: {
                        sPrevious: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                        sNext: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                    },
                    sEmptyTable: "{{ __('Chưa có dữ liệu') }}",
                    sInfo: "{{ __('general.common.showing_page', ['page' => '_PAGE_', 'pages' => '_PAGES_']) }}",
                    sInfoEmpty: "{{ __('general.common.showing_page', ['page' => '_PAGE_', 'pages' => '_PAGES_']) }}",
                    sSearch: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                    sSearchPlaceholder: "{{ __('Tìm kiếm') }}...",
                    sLengthMenu: "{{ __('Số lượng') }} :  _MENU_",
                    sInfoFiltered: "({{ __('Lọc từ tổng số') }} _MAX_ {{ 'bản ghi' }})",
                    sZeroRecords: "{{ __('Không có bản ghi nào trùng khớp') }}",
                    sProcessing: "{{ __('Đang xử lý') }}...",
                },
                stripeClasses: [],
                lengthMenu: {!! json_encode($menuLength) !!},
                pageLength: {{ $pageLength }},
                autoWidth: false,
            };

            let customOptions = {};
            @if(isset($customScript) && trim($customScript) !== '')
                try {
                    customOptions = { {!! $customScript !!} };
                } catch (e) {
                    console.error('Invalid customScript for DataTable:', e);
                    customOptions = {};
                }
            @endif

            if (customOptions.ajax) {
                customOptions.ajax.dataType = customOptions.ajax.dataType || 'json';
            }

            const finalOptions = $.extend(true, {}, baseOptions, customOptions);

            console.log('init datatable {{ $id }}', finalOptions);

            const c1 = $('#{{ $id }}').DataTable(finalOptions);

            try {
                setTimeout(function() { c1.columns.adjust(); }, 0);
            } catch (e) {
                console.warn('columns.adjust() failed', e);
            }

            if (typeof multiCheck === 'function') {
                try { multiCheck(c1); } catch (e) { console.warn('multiCheck error', e); }
            }

            $(document).on('keyup', '.search-bar .search-form-control', function() {
                c1.search(this.value).draw();
            });

            $('.search-bar .search-close').on('click', function(e) {
                c1.search('').draw();
            });
        });
    </script>
@endpush
