<x-hr.base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.furlough_policy_management.manage_furlough_policy') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>

    
    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.furlough_policy_management.manage_furlough_policy') => '',
        ]"
    />

    <div class="align-items-center justify-content-between mb-3">
        <x-slot:boxTitle>
            {{ __('general.menu.furlough_policy_management.manage_furlough_policy') }}
        </x-slot:boxTitle>
        <div></div>

        <div>
            @can(Acl::PERMISSION_FURLOUGH_POLICY_ADD)
                <x-buttons.button-link
                    :label="__('general.menu.furlough_policy_management.create_furlough_policy')"
                    :url="route('hr.furlough-policies.create')"
                />
            @endcan
        </div>
    </div>

    <x-custom.stat-box
        :boxId="'furlough-policy-box'"
        :custom-col="'col-lg-12'"
        :box_of_datatable="true"
    >
        <x-table.datatable 
            :id="'sFurloughPolicyTable'"
            :title="__('Furlough Policy List')"
        >
            <x-slot:tableHeader>
                <tr>
                    <th style="width:5%">ID</th>
                    <th>{{ __('general.common.school') }}</th>
                    <th>{{ __('general.common.furlough_type_name') }}</th>
                    <th>{{ __('general.common.employee_type') }}</th>
                    <th>{{ __('general.common.paid') }}</th>
                    <th>{{ __('general.common.carry_forward') }}</th>
                    <th>{{ __('general.common.reset_type') }}</th>
                    <th>{{ __('general.common.reset_month') }}</th>
                    <th>{{ __('general.common.status') }}</th>
                    <th>{{ __('general.common.action') }}</th>
                </tr>
            </x-slot:tableHeader>
            <x-slot:customScript>
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('hr.furlough-policies.index') }}",
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
                        "data": "school",
                        "orderable": false,
                        "class": "text-center",
                        "render": function(data, type, full) {
                            return `<span class="badge badge-light">${data?.name ?? 'N/A'}</span>`;
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
                        "data": "employee_type_name",
                        "class": "text-center",
                        "orderable": false,
                        "render": function(data, type, full) {
                            return `<span class="badge badge-light">${full.employee_type_name}</span>`;
                        }
                    },
                    {
                        "data": "is_paid",
                        "orderable": false,
                        "className": "text-center",
                        "render": function(data, type, full) {
                            return `<span class="badge badge-${full.is_paid_badge}">${full.is_paid_name}</span>`;
                        }
                    },
                    {
                        "data": "carry_forward",
                        "orderable": false,
                        "className": "text-center",
                        "render": function(data, type, full) {
                            return `<span class="badge badge-light">${full.carry_forward_name}</span>`;
                        }
                    },
                    {
                        "data": "reset_type",
                        "orderable": false,
                        "className": "text-center",
                        "render": function(data, type, full) {
                            return `<span class="badge badge-light">${full.reset_type_name}</span>`;
                        }
                    },
                    {
                        "data": "reset_month",
                        "orderable": false,
                        "className": "text-center",
                        "render": function(data, type, full) {
                            return `<span class="badge badge-light">${full.reset_month_name ?? 'N/A'}</span>`;
                        }
                    },
                    {
                        "data": "status",
                        "orderable": false,
                        "className": "text-center",
                        "render": function(data, type, full) {
                            return `<span class="badge badge-${full.badge_name}">${full.status_name}</span>`;
                        }
                    },
                    {
                        "data": "id",
                        "class": "text-center no-content",
                        "orderable": false,
                        "render": function (data, type, full) {
                            let urlEdit = `{{ route('hr.furlough-policies.edit', ':id') }}`.replace(':id', data);
                            let urlDestroy = `{{ route('hr.furlough-policies.destroy', ':id') }}`.replace(':id', data);

                            return `
                                <ul class="table-controls d-flex justify-content-center">
                                    <x-table.actions.edit-action
                                        :permission="Acl::PERMISSION_FURLOUGH_POLICY_EDIT"
                                        :url="'${urlEdit}'"
                                    />
                                    <x-table.actions.delete-action
                                        :permission="Acl::PERMISSION_FURLOUGH_POLICY_DELETE"
                                        :url="'${urlDestroy}'"
                                        :datatableId="'sFurloughPolicyTable'"
                                    />
                                </ul>`;
                        }
                    }
                ]
            </x-slot:customScript>
        </x-table.datatable>
    </x-custom.stat-box>

    
</x-hr.base-layout>
