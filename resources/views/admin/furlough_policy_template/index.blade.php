<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.furlough_policy_template_management.manage_furlough_policy_template') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>

    
    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.furlough_policy_template_management.manage_furlough_policy_template') => '',
        ]"
    />

    <div class="align-items-center justify-content-between mb-3">
        <x-slot:boxTitle>
            {{ __('general.menu.furlough_policy_template_management.manage_furlough_policy_template') }}
        </x-slot:boxTitle>
        <div></div>

        <div>
            @can(Acl::PERMISSION_FURLOUGH_POLICY_TEMPLATE_ADD)
                <x-buttons.button-link
                    :label="__('general.menu.furlough_policy_template_management.create_furlough_policy_template')"
                    :url="route('admin.furlough-policy-template.create')"
                />
            @endcan
        </div>
    </div>

    <x-custom.stat-box
        :boxId="'furlough-policy-template-box'"
        :custom-col="'col-lg-12'"
        :box_of_datatable="true"
    >
        <x-table.datatable 
            :id="'sFurloughPolicyTemplateTable'"
            :title="__('Furlough Policy Template List')"
        >
            <x-slot:tableHeader>
                <tr>
                    <th style="width:5%">ID</th>
                    <th>{{ __('general.common.name') }}</th>
                    <th>{{ __('general.common.description') }}</th>
                    <th>{{ __('general.common.paid') }}</th>
                    <th>{{ __('general.common.carry_forward') }}</th>
                    <th>{{ __('general.common.status') }}</th>
                    <th>{{ __('general.common.action') }}</th>
                </tr>
            </x-slot:tableHeader>
            <x-slot:customScript>
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('admin.furlough-policy-template.index') }}",
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
                        "data": "name",
                        "class": "text-center",
                        "orderable": false
                    },
                    {
                        "data": "description",
                        "class": "text-center",
                        "orderable": false
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
                            return `<span class="badge badge-${full.carry_forward_badge}">${full.carry_forward_name}</span>`;
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
                            let urlEdit = `{{ route('admin.furlough-policy-template.edit', ':id') }}`.replace(':id', data);
                            let urlDestroy = `{{ route('admin.furlough-policy-template.destroy', ':id') }}`.replace(':id', data);

                            return `
                                <ul class="table-controls d-flex justify-content-center">
                                    <x-table.actions.edit-action
                                        :permission="Acl::PERMISSION_FURLOUGH_POLICY_TEMPLATE_EDIT"
                                        :url="'${urlEdit}'"
                                    />
                                    <x-table.actions.delete-action
                                        :permission="Acl::PERMISSION_FURLOUGH_POLICY_TEMPLATE_DELETE"
                                        :url="'${urlDestroy}'"
                                        :datatableId="'sFurloughPolicyTemplateTable'"
                                    />
                                </ul>`;
                        }
                    }
                ]
            </x-slot:customScript>
        </x-table.datatable>
    </x-custom.stat-box>

    
</x-base-layout>
