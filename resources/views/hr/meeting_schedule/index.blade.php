<x-hr.base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.meeting_schedule_management.title') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>
    
    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.meeting_schedule_management.title') => '',
        ]"
    />

    <div class="align-items-center justify-content-between mb-3">
        <x-slot:boxTitle>
            {{ __('general.menu.meeting_schedule_management.title') }}
        </x-slot:boxTitle>
        <div></div>

        <div>
            @can(Acl::PERMISSION_MEETING_SCHEDULE_ADD)
                <x-buttons.button-link
                    :label="__('general.menu.meeting_schedule_management.create_meeting_schedule')"
                    :url="route('hr.meeting-schedule.create')"
                />
            @endcan
        </div>
    </div>

    <x-custom.stat-box
        :boxId="'meeting-schedules-box'"
        :custom-col="'col-lg-12'"
        :box_of_datatable="true"
    >
        <x-table.datatable 
            :id="'sMeetingScheduleTable'"
            :title="__('Meeting Schedule List')"
        >
            <x-slot:tableHeader>
                <tr>
                    <th style="width:5%">ID</th>
                    <th>{{ __('general.common.title') }}</th>
                    <th>{{ __('general.common.meeting_target_type') }}</th>
                    <th>{{ __('general.common.start_time') }}</th>
                    <th>{{ __('general.common.status') }}</th>
                    <th>{{ __('general.common.action') }}</th>
                </tr>
            </x-slot:tableHeader>
            <x-slot:customScript>
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('hr.meeting-schedule.index') }}",
                        "data": function(d) {
                            let searchParams = new URLSearchParams(window.location.search);
                            drawDT = d.draw;
                            d.limit = d.length;
                            d.page = d.start / d.length + 1;
                            d.school_id = $('#sSchoolSelect').val() || searchParams.get('school_id');
                            d.status = $('#sStatus').val() || searchParams.get('status');
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
                        "data": "title",
                        "class": "text-center",
                        "orderable": false
                    },
                    {
                        "data": "target_type_name",
                        "orderable": false,
                        "class": "text-center",
                        "render": function(data, type, full) {
                            return data || full.target_type;
                        }
                    },
                    {
                        "data": "start_time",
                        "orderable": false,
                        "class": "text-center",
                    },
                    {
                        "data": "status",
                        "orderable": false,
                        "className": "text-center",
                        "render": function(data, type, full) {
                            return `<span class="badge badge-${full.status_badge}">${full.status_name}</span>`;
                        }
                    },
                    {
                        "data": "id",
                        "class": "text-center no-content",
                        "orderable": false,
                        "render": function (data, type, full) {
                            let urlShow = `{{ route('hr.meeting-schedule.show', ':id') }}`.replace(':id', data);
                            let urlEdit = `{{ route('hr.meeting-schedule.edit', ':id') }}`.replace(':id', data);
                            let urlDestroy = `{{ route('hr.meeting-schedule.destroy', ':id') }}`.replace(':id', data);

                            return `
                                <ul class="table-controls d-flex justify-content-center">
                                    <x-table.actions.show-action
                                        :permission="Acl::PERMISSION_MEETING_SCHEDULE_VIEW"
                                        :url="'${urlShow}'"
                                    />
                                    <x-table.actions.edit-action
                                        :permission="Acl::PERMISSION_MEETING_SCHEDULE_EDIT"
                                        :url="'${urlEdit}'"
                                    />
                                    <x-table.actions.delete-action
                                        :permission="Acl::PERMISSION_MEETING_SCHEDULE_DELETE"
                                        :url="'${urlDestroy}'"
                                        :datatableId="'sMeetingScheduleTable'"
                                    />
                                </ul>`;
                        }
                    }
                ]
            </x-slot:customScript>
        </x-table.datatable>
    </x-custom.stat-box>

    
</x-hr.base-layout>
