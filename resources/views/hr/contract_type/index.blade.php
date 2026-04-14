<x-hr.base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.contract_type_management.contract_type') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>

    
    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.contract_type_management.contract_type') => '',
        ]"
    />

    <div class="align-items-center justify-content-between mb-3">
        <x-slot:boxTitle>
            {{ __('general.menu.contract_type_management.contract_type') }}
        </x-slot:boxTitle>
        <div></div>

        <div>
            @can(Acl::PERMISSION_CONTRACT_TYPE_ADD)
                <x-buttons.button-link
                    :label="__('general.menu.contract_type_management.create_contract_type')"
                    :url="route('hr.contract_type.create')"
                />
            @endcan
        </div>
    </div>

    <x-custom.stat-box
        :boxId="'contract-types-box'"
        :custom-col="'col-lg-12'"
        :box_of_datatable="true"
    >
        <x-table.datatable 
            :id="'sContractTypeTable'"
            :title="__('Contract Type List')"
        >
            <x-slot:tableHeader>
                <tr>
                    <th style="width:5%">ID</th>
                    <th>{{ __('general.common.name') }}</th>
                    <th>{{ __('general.common.school') }}</th>
                    <th>{{ __('general.common.contract_attribute') }}</th>
                    <th>{{ __('general.common.contracts_count') }}</th>
                    <th>{{ __('general.common.action') }}</th>
                </tr>
            </x-slot:tableHeader>
            <x-slot:customScript>
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('hr.contract_type.index') }}",
                        "data": function(d) {
                            let searchParams = new URLSearchParams(window.location.search);
                            drawDT = d.draw;
                            d.limit = d.length;
                            d.page = d.start / d.length + 1;
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
                        "orderable": false,
                    },
                    {
                        "data": "school",
                        "class": "text-center",
                        "orderable": false,
                        "render": function (data, type, full) {
                            return data ? `<span class="badge badge-light">${data}</span>` : '';
                        }
                    },
                    {
                        "data": "contract_attributes",
                        "class": "text-center",
                        "render": function (data, type, full) {
                            if (!Array.isArray(data) || data.length === 0) {
                                return `<span class="text-muted">N/A</span>`;
                            }

                            let contractAttributeBadges = ``;
                            let count = 0;

                            for (let i = 0; i < data.length; i++) {
                                contractAttributeBadges += `
                                    <span class="badge badge-light me-2 mt-1 mb-1">
                                        ${data[i].key}
                                    </span>
                                `;

                                if(++count == 5){
                                    break;
                                }
                            }
                            return contractAttributeBadges;
                        }
                    },
                    {
                        "data": "contracts_count",
                        "class": "text-center",
                    },
                    {
                        "data": "id",
                        "class": "text-center no-content",
                        "orderable": false,
                        "render": function (data, type, full) {
                            let urlEdit = `{{ route('hr.contract_type.edit', ':id') }}`.replace(':id', data);
                            let urlDestroy = `{{ route('hr.contract_type.destroy', ':id') }}`.replace(':id', data);

                            return `
                                <ul class="table-controls d-flex justify-content-center">
                                    <x-table.actions.edit-action
                                        :permission="Acl::PERMISSION_CONTRACT_TYPE_EDIT"
                                        :url="'${urlEdit}'"
                                    />
                                    <x-table.actions.delete-action
                                        :permission="Acl::PERMISSION_CONTRACT_TYPE_DELETE"
                                        :url="'${urlDestroy}'"
                                        :datatableId="'sDepartmentTable'"
                                    />
                                </ul>`;
                        }
                    }
                ]
            </x-slot:customScript>
        </x-table.datatable>
    </x-custom.stat-box>

    
</x-hr.base-layout>
