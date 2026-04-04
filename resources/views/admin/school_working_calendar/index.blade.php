<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.school_working_calendar_management.school_working_calendar') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>

    
    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.school_working_calendar_management.school_working_calendar') => '',
        ]"
    />

    <x-custom.stat-box :id="'school-working-calendar-management-filter'" :custom-col="'col-lg-12'">
        <x-slot:boxTitle>
            {{ __('general.filter.title') }}
        </x-slot:boxTitle>

        @include('admin.school_working_calendar.filters.index')
    </x-custom.stat-box>

    <div class="align-items-center justify-content-between mb-3">
        <x-slot:boxTitle>
            {{ __('general.menu.school_working_calendar_management.school_working_calendar') }}
        </x-slot:boxTitle>
        <div></div>

        <div>
            @can(Acl::PERMISSION_SCHOOL_WORKING_CALENDAR_ADD)
                <x-buttons.button-link
                    :label="__('general.menu.school_working_calendar_management.create_school_working_calendar')"
                    :url="route('admin.school-working-calendar.create')"
                />
            @endcan
        </div>
    </div>

    <x-custom.stat-box
        :boxId="'school-working-calendars-box'"
        :custom-col="'col-lg-12'"
        :box_of_datatable="true"
    >
        <x-table.datatable 
            :id="'sSchoolWorkingCalendarTable'"
            :title="__('general.menu.school_working_calendar_management.school_working_calendar')"
        >
            <x-slot:tableHeader>
                <tr>
                    <th style="width:5%">ID</th>
                    <th>{{ __('general.common.school') }}</th>
                    <th>{{ __('general.common.working_days_count') }}</th>
                    <th>{{ __('general.common.time') }}</th>
                    <th>{{ __('general.common.status') }}</th>
                    <th>{{ __('general.common.action') }}</th>
                </tr>
            </x-slot:tableHeader>
            <x-slot:customScript>
                "processing": true,
                "serverSide": true,
                "ordering": false,
                "ajax": {
                    "url": "{{ route('admin.school-working-calendar.index') }}",
                        "data": function(d) {
                            let searchParams = new URLSearchParams(window.location.search);
                            drawDT = d.draw;
                            d.limit = d.length;
                            d.page = d.start / d.length + 1;
                            const statusVal = $('#sStatus').val() || searchParams.get('status');
                            d.status = statusVal;
                            d.is_active = statusVal;
                            d.school_id = $('#sSchoolSelect').val() || searchParams.get('school_id');
                        },
                        "dataSrc": function(res) {
                            res.draw = drawDT;
                            res.recordsTotal = res.meta.total;
                            res.recordsFiltered = res.meta.total;
                            return res.data;
                        }
                    },
                "columns": [
                    {
                        "data": "id",
                        "class": "text-center",
                        "orderable": true
                    },
                    { 
                        "data": "school",
                        "class": "text-center",
                        "orderable": false
                    },
                    { 
                        "data": "working_days_count",
                        "class": "text-center",
                        "orderable": false,
                        "render": function(data, type, full) {
                            return `<span class="badge badge-light">${full.working_days_count}</span>`;
                        }
                    },
                    {
                        "data": "time",
                        "class": "text-center",
                        "orderable": false,
                        "render": function(data, type, full) {
                            return `${full.working_hours_start} - ${full.working_hours_end}`;
                        }
                    },
                    {
                        "data": "status",
                        "orderable": false,
                        "class": "text-center",
                        "render": function(data, type, full) {
                            return `<span class="badge badge-${full.badge_name}">${full.status_name}</span>`;
                        }
                    },
                    {
                        "data": "id",
                        "class": "text-center no-content",
                        "orderable": false,
                        "render": function (data, type, full) {
                            <!-- let urlShow = `{{ route('admin.school.show', ':id') }}`.replace(':id', data); -->
                            let urlEdit = `{{ route('admin.school-working-calendar.edit', ':id') }}`.replace(':id', data);
                            let urlDestroy = `{{ route('admin.school-working-calendar.destroy', ':id') }}`.replace(':id', data);

                            return `
                                <ul class="table-controls d-flex justify-content-center">
                                    <x-table.actions.edit-action
                                        :permission="Acl::PERMISSION_SCHOOL_WORKING_CALENDAR_EDIT"
                                        :url="'${urlEdit}'"
                                    />
                                    <x-table.actions.delete-action
                                        :permission="Acl::PERMISSION_SCHOOL_WORKING_CALENDAR_DELETE"
                                        :url="'${urlDestroy}'"
                                        :datatableId="'sSchoolWorkingCalendarTable'"
                                    />
                                </ul>`;
                        }
                    }
                ]
            </x-slot:customScript>
        </x-table.datatable>
    </x-custom.stat-box>

    
</x-base-layout>
