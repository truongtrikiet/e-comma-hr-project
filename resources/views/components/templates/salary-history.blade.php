<!-- @push('headerFiles')
    //
@endpush -->

<div class="modal fade" id="objectHistoryModal" tabindex="-1" role="dialog" aria-labelledby="objectHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="objectHistoryModalLabel">{{__('general.menu.salary_management.salary_history')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <svg> ... </svg>
                </button>
            </div>
            <div class="modal-body overflow-auto" style="max-height: 600px;">
                <div class="mt-container-fluid mx-auto">
                    <div class="timeline-line">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('footerFiles')
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script> -->

    <script>
        const openModalObjectHistory = (urlShow) => {
            $.ajax({
                url: urlShow,
                success: function(salaryHistories) {
                    salaryHistories.sort((a, b) => new Date(b.sent_at) - new Date(a.sent_at));
                    const html = salaryHistories.length
                        ? salaryHistories.map(item => `
                            <div class="item-timeline">
                                <p class="t-time" style="min-width: 110px;">${moment(item.sent_at).format('DD/MM/YYYY')}</p>
                                <div class="t-dot t-dot-primary"></div>
                                <div class="t-text w-100">
                                    <p class="mb-1 fs-6 font-weight-bold">
                                        ${item.editor ? `${item.editor.name} (${item.editor.email}) đã cập nhật bản ghi` : 'Người chỉnh sửa không xác định'}
                                    </p>
                                    <p class="mb-1 text-muted">
                                        ${item.new_value.titles
                                            ? `• Vị trí đã được cập nhật từ <span class="text-primary">${item.old_value.titles ? item.old_value.titles : 'null'}</span>
                                            thành <span class="text-primary">${item.new_value.titles}</span>` : ''}
                                    </p>
                                    <p class="mb-1 text-muted">
                                        ${item.new_value.amount
                                            ? `• Mức lương đã được cập nhật từ <span class="text-primary">${formatPrice(item.old_value.amount)}</span>
                                            thành <span class="text-primary">${formatPrice(item.new_value.amount)}</span>` : ''}
                                    </p>
                                    <p class="mb-1 text-muted">
                                        ${item.new_value.effective_date
                                            ? `• Ngày áp dụng đã được cập nhật từ <span class="text-primary">${moment(item.old_value.effective_date).format('DD/MM/YYYY')}</span>
                                            thành <span class="text-primary">${moment(item.new_value.effective_date).format('DD/MM/YYYY')}</span>` : ''}
                                    </p>
                                    <p class="mb-1 text-muted">
                                        ${item.new_value.approved_at
                                            ? `• Ngày duyệt đã được cập nhật từ <span class="text-primary">${moment(item.old_value.approved_at).format('DD/MM/YYYY')}</span>
                                            thành <span class="text-primary">${moment(item.new_value.approved_at).format('DD/MM/YYYY')}</span>` : ''}
                                    </p>
                                </div>
                            </div>
                        `).join('')
                        : '<p class="fs-5 font-weight-bold text-center">{{__('general.menu.salary_management.empty_salary_history')}}</p>';

                    $('#objectHistoryModal .timeline-line').html(html);
                    $('#objectHistoryModal').modal('show');
                },
            });
        }
        const formatPrice = (value) => {
            return new Intl.NumberFormat('vi-VN', { style: 'decimal' }).format(parseFloat(value));
        };
    </script>
@endpush
