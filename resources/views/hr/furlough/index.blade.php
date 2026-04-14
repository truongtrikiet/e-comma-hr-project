<x-hr.base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.furlough_management.manage_furlough') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>

    
    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.furlough_management.manage_furlough') => '',
        ]"
    />

    <div class="align-items-center justify-content-between mb-3">
        <x-slot:boxTitle>
            {{ __('general.menu.furlough_management.manage_furlough') }}
        </x-slot:boxTitle>
        <div></div>

        <div>
            @can(Acl::PERMISSION_FURLOUGH_ADD)
                <x-buttons.button-link
                    :label="__('general.menu.furlough_management.create_furlough')"
                    :url="route('hr.furlough.create')"
                />
            @endcan
        </div>
    </div>

    <x-custom.stat-box
        :boxId="'furlough-management-box'"
        :custom-col="'col-lg-12'"
        :box_of_datatable="true"
    >
        <x-table.datatable 
            :id="'sFurloughTable'"
            :title="__('Furlough List')"
        >
            <x-slot:tableHeader>
                <tr>
                    <th style="width:5%">ID</th>
                    <th style="width:30%">{{ __('general.common.name') }}</th>
                    <th style="width:15%">{{ __('general.common.furlough_type_name') }}</th>
                    <th style="width:15%">{{ __('general.common.furlough_duration_type') }}</th>
                    <th style="width:15%">{{ __('general.common.furlough_half_day_session') }}</th>
                    <th style="width:15%">{{ __('general.common.start_time') }}</th>
                    <th style="width:5%">{{ __('general.common.status') }}</th>
                    <th style="width:10%">{{ __('general.common.action') }}</th>
                </tr>
            </x-slot:tableHeader>
            <x-slot:customScript>
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('hr.furlough.index') }}",
                        "data": function(d) {
                            let searchParams = new URLSearchParams(window.location.search);
                            drawDT = d.draw;
                            d.limit = d.length;
                            d.page = d.start / d.length + 1;
                            d.user_id = $('#sUserSelect').val() || searchParams.get('user_id');
                            d.school_id = $('#sSchoolSelect').val() || searchParams.get('school_id');
                            d.furlough_type_id = $('#sFurloughTypeSelect').val() || searchParams.get('furlough_type_id');
                            d.duration_type = $('#sDurationTypeSelect').val() || searchParams.get('duration_type');
                            d.half_day_session = $('#sHalfDaySessionSelect').val() || searchParams.get('half_day_session');
                            d.furlough_status = $('#sFurloughStatus').val() || searchParams.get('furlough_status');
                            d.start_time = $('#sStartTime').val() || searchParams.get('start_time');
                            d.end_time = $('#sEndTime').val() || searchParams.get('end_time');
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
                        "data": "user_name",
                        "class": "text-center",
                        "orderable": false,
                        "render": function(data, type, full) {
                            const name = full.user_name ?? '-';
                            const mail = full.email ?? '-';
                            const school = full.school ?? '-';
                            return `<strong>{{ __('general.common.name') }}:</strong> ${name}<br/><strong>{{ __('general.common.email') }}:</strong> ${mail}<br/><strong>{{ __('general.common.school') }}:</strong> ${school}`;
                        }
                    },
                    {
                        "data": "furlough_type_name",
                        "class": "text-center",
                        "orderable": false,
                        "render": function(data, type, full) {
                            return `<span class="badge badge-light">${full.furlough_type_name}</span>`;
                        }
                    },
                    {
                        "data": "duration_type",
                        "class": "text-center",
                        "orderable": false,
                        "render": function(data, type, full) {
                            return `<span class="badge badge-light">${full.duration_type_name}</span>`;
                        }
                    },
                    {
                        "data": "half_day_session",
                        "class": "text-center",
                        "orderable": false,
                        "render": function(data, type, full) {
                            return `<span class="badge badge-light">${full.half_day_session_name ?? "-"}</span>`;
                        }
                    },
                    {
                        "data": "start_time",
                        "class": "text-center",
                        "orderable": true,
                    },
                    {
                        "data": "furlough_status",
                        "orderable": false,
                        "className": "text-center",
                        "render": function(data, type, full) {
                            return `<span class="badge badge-${full.furlough_status_badge_name}">${full.furlough_status_name}</span>`;
                        }
                    },
                    {
                        "data": "id",
                        "class": "text-center no-content",
                        "orderable": false,
                        "render": function (data, type, full) {
                            let urlShow = `{{ route('hr.furlough.show', ':id') }}`.replace(':id', data);
                            let urlEdit = `{{ route('hr.furlough.edit', ':id') }}`.replace(':id', data);
                            let urlDestroy = `{{ route('hr.furlough.destroy', ':id') }}`.replace(':id', data);

                            return `
                                <ul class="table-controls d-flex justify-content-center">
                                    <x-table.actions.show-action
                                        :permission="Acl::PERMISSION_FURLOUGH_SHOW"
                                        :url="'${urlShow}'"
                                        :dataTableId="'sFurloughTable'"
                                    />
                                    <x-table.actions.edit-action
                                        :permission="Acl::PERMISSION_FURLOUGH_EDIT"
                                        :url="'${urlEdit}'"
                                    />
                                    <x-table.actions.delete-action
                                        :permission="Acl::PERMISSION_FURLOUGH_DELETE"
                                        :url="'${urlDestroy}'"
                                        :datatableId="'sFurloughTable'"
                                    />
                                </ul>`;
                        }
                    }
                ]
            </x-slot:customScript>
        </x-table.datatable>
    </x-custom.stat-box>

    
</x-hr.base-layout>
