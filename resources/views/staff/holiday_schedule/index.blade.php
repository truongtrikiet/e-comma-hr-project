<x-staff.base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.holiday_schedule_management.manage_holiday_schedule') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>
    
    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.holiday_schedule_management.manage_holiday_schedule') => '',
        ]"
    />

    <div class="align-items-center justify-content-between mb-3">
        <x-slot:boxTitle>
            {{ __('general.menu.holiday_schedule_management.manage_holiday_schedule') }}
        </x-slot:boxTitle>
        <div></div>

        <div class="modal fade" id="holidayModal" tabindex="-1">
            <div class="modal-dialog">
                <form id="holidayForm">
                    <input type="hidden" name="holiday_id" id="holiday_id" />
                    <input type="hidden" name="_method" id="_method" value="POST" />
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('general.menu.holiday_schedule_management.create_holiday_schedule') }}</h5>
                            <button type="button" id="holidayModalCloseBtn" class="btn btn-close" data-bs-dismiss="modal">X</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group col-md-12">
                                <label>{{ __('general.common.name') }}</label>
                                <input type="text" id="name" name="name" class="form-control input-rounded" readonly>
                            </div>
                            <div class="form-group col-md-12">
                                <label>{{ __('general.common.start_date') }}</label>
                                <input type="date" id="start_date" name="start_date" class="form-control input-rounded" readonly>
                            </div>
                            <div class="form-group col-md-12">
                                <label>{{ __('general.common.end_date') }}</label>
                                <input type="date" name="end_date" class="form-control input-rounded" readonly>
                            </div>
                            <div class="form-group col-md-12" id="totalDaysGroup" style="display: none;">
                                <label>{{ __('general.common.total_days') }}</label>
                                <input type="text" id="total_days" name="total_days" class="form-control input-rounded" readonly>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="deleteHolidayBtn" class="btn btn-danger" style="display:none">{{ __('general.common.delete') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('general.common.complete') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div>
            @include('staff.holiday_schedule.partials.calendar')
        </div>
        
    </div>

    <x-slot:footerFiles>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const holidayForm = document.getElementById('holidayForm');
            const holidayModalEl = document.getElementById('holidayModal');
            const holidayIdInput = document.getElementById('holiday_id');
            const methodInput = document.getElementById('_method');
            const deleteBtn = document.getElementById('deleteHolidayBtn');
            const locales = @json(config('app.locales'));
            const appLocale = "{{ app()->getLocale() }}";

            const getModal = () => {
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal && typeof bootstrap.Modal.getOrCreateInstance === 'function') {
                    return bootstrap.Modal.getOrCreateInstance(holidayModalEl);
                } else if (window.jQuery && typeof jQuery(holidayModalEl).modal === 'function') {
                    return {
                        show() { jQuery(holidayModalEl).modal('show'); },
                        hide() { jQuery(holidayModalEl).modal('hide'); }
                    };
                } else {
                    return {
                        show() { holidayModalEl.style.display = 'block'; holidayModalEl.classList.add('show'); },
                        hide() { holidayModalEl.style.display = 'none'; holidayModalEl.classList.remove('show'); }
                    };
                }
            };

            const modalCloseBtn = document.getElementById('holidayModalCloseBtn');
            if (modalCloseBtn) {
                modalCloseBtn.addEventListener('click', function(ev) {
                    try {
                        getModal().hide();
                    } catch (e) {
                        holidayModalEl.style.display = 'none';
                        holidayModalEl.classList.remove('show');
                    }
                });
            }

            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.querySelector('input[name="end_date"]');
            const totalDaysInput = document.getElementById('total_days');
            const today = new Date().toISOString().slice(0,10);
            if (startDateInput) {
                startDateInput.setAttribute('min', today);
                startDateInput.addEventListener('change', function() {
                    const s = startDateInput.value;
                    if (!s) return;
                    if (endDateInput) {
                        endDateInput.setAttribute('min', s);
                        if (endDateInput.value && endDateInput.value < s) {
                            endDateInput.value = s;
                        }
                    }

                    computeTotalDays();
                });
            }

            if (endDateInput) {
                endDateInput.addEventListener('change', function() {
                    try {
                        const s = startDateInput && startDateInput.value ? startDateInput.value : '';
                        if (s && endDateInput.value && endDateInput.value < s) {
                            endDateInput.value = s;
                        }
                    } catch (err) {
                        console.error('endDate change clamp error', err);
                    }
                    computeTotalDays();
                });

                endDateInput.addEventListener('input', function() {
                    try {
                        const s = startDateInput && startDateInput.value ? startDateInput.value : '';
                        if (s && endDateInput.value && endDateInput.value < s) {
                            endDateInput.value = s;
                        }
                    } catch (err) {
                        console.error('endDate input clamp error', err);
                    }
                    computeTotalDays();
                });
            }

            if (endDateInput) {
                const currentMin = endDateInput.getAttribute('min');
                if (!currentMin || currentMin < today) {
                    endDateInput.setAttribute('min', today);
                }
            }

            function computeTotalDays() {
                try {
                    const td = document.getElementById('total_days');
                    if (!td) return;
                    const s = startDateInput && startDateInput.value ? startDateInput.value : '';
                    const e = endDateInput && endDateInput.value ? endDateInput.value : s;
                    if (!s) { td.value = ''; return; }
                    const sd = new Date(s + 'T00:00:00');
                    const ed = new Date(e + 'T00:00:00');
                    if (isNaN(sd.getTime()) || isNaN(ed.getTime())) { td.value = ''; return; }
                    const diff = Math.floor((ed - sd) / (24 * 60 * 60 * 1000));
                    const days = diff >= 0 ? (diff + 1) : '';
                    td.value = days;
                } catch (err) {
                    console.error('computeTotalDays error', err);
                }
            }

            function openCreateModal(dateStr) {
                holidayIdInput.value = '';
                methodInput.value = 'POST';
                deleteBtn.style.display = 'none';
                holidayForm.querySelector('.modal-title').textContent = "{{ __('general.menu.holiday_schedule_management.create_holiday_schedule') }}";

                locales.forEach(l => {
                    const el = holidayForm.querySelector(`[name="name[${l}]"]`);
                    if (el) el.value = '';
                });

                if (startDateInput) startDateInput.value = dateStr || '';
                if (endDateInput) { endDateInput.value = dateStr || ''; if (dateStr) endDateInput.setAttribute('min', dateStr); }
                const totalDaysGroup = document.getElementById('totalDaysGroup');
                const totalDaysInput = document.getElementById('total_days');
                if (totalDaysGroup) totalDaysGroup.style.display = 'none';
                if (totalDaysInput) totalDaysInput.value = '';
                getModal().show();
            }

            function openEditModal(holiday) {
                holidayIdInput.value = holiday.id;
                methodInput.value = 'PUT';
                deleteBtn.style.display = '';
                holidayForm.querySelector('.modal-title').textContent = "{{ __('general.menu.holiday_schedule_management.edit_holiday_schedule') }}";

                locales.forEach(l => {
                    const el = holidayForm.querySelector(`[name="name[${l}]"]`);
                    if (el) el.value = (holiday.names && holiday.names[l]) ? holiday.names[l] : '';
                });

                if (startDateInput) startDateInput.value = holiday.start_date || '';
                if (endDateInput) endDateInput.value = holiday.end_date || holiday.start_date || '';

                if (startDateInput && startDateInput.value && endDateInput) {
                    endDateInput.setAttribute('min', startDateInput.value);
                    if (endDateInput.value && endDateInput.value < startDateInput.value) {
                        endDateInput.value = startDateInput.value;
                    }
                }

                const totalDaysGroup = document.getElementById('totalDaysGroup');
                const totalDaysInput = document.getElementById('total_days');
                if (totalDaysGroup) totalDaysGroup.style.display = '';
                if (totalDaysInput) totalDaysInput.value = (holiday.total_days !== undefined ? holiday.total_days : '');

                getModal().show();
            }

            document.addEventListener('holiday:event-click', function(e){ openViewModal(e.detail.extendedProps.holiday); });

            try {
                const submitBtn = holidayForm.querySelector('button[type="submit"]');
                if (submitBtn) submitBtn.style.display = 'none';
                if (deleteBtn) deleteBtn.style.display = 'none';
                const inputs = holidayForm.querySelectorAll('input, textarea, select, button');
                inputs.forEach(i => {
                    if (i.type !== 'button' && i.type !== 'submit') {
                        i.setAttribute('readonly', 'readonly');
                        i.setAttribute('disabled', 'disabled');
                    }
                });
            } catch (err) {
                console.error('staff view disable inputs error', err);
            }

            function openViewModal(holiday) {
                try {
                    holidayIdInput.value = holiday.id || '';
                    methodInput.value = '';
                    holidayForm.querySelector('.modal-title').textContent = "{{ __('general.menu.holiday_schedule_management.view_holiday_schedule') }}";

                    try {
                        const nameInput = document.getElementById('name');
                        if (nameInput) {
                            const localized = holiday.names && (holiday.names[appLocale] !== undefined) ? holiday.names[appLocale] : (holiday.name || '');
                            nameInput.value = localized;
                        }
                    } catch (err) {
                        console.error('set localized name error', err);
                    }

                    if (startDateInput) startDateInput.value = holiday.start_date || '';
                    if (endDateInput) endDateInput.value = holiday.end_date || holiday.start_date || '';

                    const totalDaysGroup = document.getElementById('totalDaysGroup');
                    const totalDaysInput = document.getElementById('total_days');
                    if (totalDaysGroup) totalDaysGroup.style.display = '';
                    if (totalDaysInput) totalDaysInput.value = (holiday.total_days !== undefined ? holiday.total_days : '');

                    getModal().show();
                } catch (err) {
                    console.error('openViewModal error', err);
                }
            }
        });
        </script>
        
    </x-slot:footerFiles>
    
</x-staff.base-layout>
