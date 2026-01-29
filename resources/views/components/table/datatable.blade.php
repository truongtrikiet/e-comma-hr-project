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

            $(document).on('keyup', '.search_bar .search-form-control', function() {
                c1.search(this.value).draw();
            });

            $('.search-bar .search-close').on('click', function(e) {
                c1.search('').draw();
            });

            // handle destroy
            (function () {
                const meta = document.querySelector('meta[name="csrf-token"]');
                const CSRF_TOKEN = meta ? meta.content : @json(csrf_token());

                if ($ && $.ajaxSetup) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                }

                function normalizeSelector(sel) {
                    if (!sel) return null;
                    if (typeof sel !== 'string') return sel;
                    return (sel.startsWith('#') || sel.startsWith('.')) ? sel : ('#' + sel);
                }

                function showSuccess(message) {
                    if (window.Snackbar && typeof Snackbar.show === 'function') {
                        Snackbar.show({ text: message, textColor: '#ddf5f0', backgroundColor: '#00ab55', actionText: '{{ __('Bỏ qua') }}', actionTextColor: '#3b3f5c' });
                    } else {
                        alert(message);
                    }
                }

                function showError(message) {
                    if (window.Snackbar && typeof Snackbar.show === 'function') {
                        Snackbar.show({ text: message, textColor: '#fbeced', backgroundColor: '#e7515a', actionText: '{{ __('Bỏ qua') }}', actionTextColor: '#3b3f5c' });
                    } else {
                        alert(message);
                    }
                }

                function performDelete(dataTableId, url) {
                    const selector = normalizeSelector(dataTableId);
                    $.post(url, { _method: 'DELETE' })
                        .done(function (response) {
                            showSuccess(response.message || '{{ __('success.delete') }}');
                            try {
                                if (selector && $.fn.DataTable) {
                                    $(selector).DataTable().ajax.reload(null, false);
                                } else {
                                    location.reload();
                                }
                            } catch (err) {
                                console.error('Error reloading datatable:', err);
                                location.reload();
                            }
                        })
                        .fail(function (jqXHR) {
                            console.error('Delete request failed', { status: jqXHR.status, responseText: jqXHR.responseText });
                            showError('{{ __('Xóa lựa chọn thất bại.') }}');
                        });
                }

                $(document).on('click', 'a.delete', function (e) {
                    e.preventDefault();
                    const url = $(this).data('url');
                    const dataTableId = $(this).data('datatable-id');

                    const confirmTitle = "{{ __('general.popup_message.confirm_delete') }}";
                    const confirmText = "{{ __('Bạn sẽ không thể hoàn lại thao tác này!') }}";

                    if (window.Swal && typeof Swal.fire === 'function') {
                        Swal.fire({ title: confirmTitle, text: confirmText, icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: "{{ __('Xác nhận') }}", cancelButtonText: "{{ __('Hủy bỏ') }}", reverseButtons: true })
                            .then(function (result) { if (result.isConfirmed) performDelete(dataTableId, url); })
                            .catch(function (err) { console.error('Swal error:', err); if (confirm(confirmTitle)) performDelete(dataTableId, url); });
                    } else {
                        if (confirm(confirmTitle)) performDelete(dataTableId, url);
                    }
                });
            })();
        });
    </script>
@endpush
