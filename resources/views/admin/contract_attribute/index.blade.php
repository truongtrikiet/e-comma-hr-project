<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.contract_attribute_management.contract_attribute') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>

    
    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.contract_attribute_management.contract_attribute') => '',
        ]"
    />

    <div class="align-items-center justify-content-between mb-3">
        <x-slot:boxTitle>
            {{ __('general.menu.contract_attribute_management.contract_attribute') }}
        </x-slot:boxTitle>
        <div></div>

        <div>
            @can(Acl::PERMISSION_CONTRACT_ATTRIBUTE_ADD)
                <x-buttons.button-link
                    :label="__('general.menu.contract_attribute_management.create_contract_attribute')"
                    :url="route('admin.contract_attribute.create')"
                />
            @endcan
        </div>
    </div>

    <x-custom.stat-box
        :boxId="'contract-attributes-box'"
        :custom-col="'col-lg-12'"
        :box_of_datatable="true"
    >
        <x-table.datatable 
            :id="'sContractAttributeTable'"
            :title="__('Contract Attribute List')"
        >
            <x-slot:tableHeader>
                <tr>
                    <th style="width:5%">ID</th>
                    <th>{{ __('general.common.key') }}</th>
                    <th>{{ __('general.common.name') }}</th>
                    <th>{{ __('general.common.contract_types_count') }}</th>
                    <th>{{ __('general.common.action') }}</th>
                </tr>
            </x-slot:tableHeader>
            <x-slot:customScript>
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('admin.contract_attribute.index') }}",
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
                        "data": "key",
                        "class": "text-center",
                        "orderable": false
                    },
                    {
                        "data": "name",
                        "class": "text-center",
                        "orderable": false,
                    },
                    {
                        "data": "contract_types_count",
                        "class": "text-center",
                    },
                    {
                        "data": "id",
                        "class": "text-center no-content",
                        "orderable": false,
                        "render": function (data, type, full) {
                            let urlEdit = `{{ route('admin.contract_attribute.edit', ':id') }}`.replace(':id', data);
                            let urlDestroy = `{{ route('admin.contract_attribute.destroy', ':id') }}`.replace(':id', data);

                            return `
                                <ul class="table-controls d-flex justify-content-center">
                                    <x-table.actions.edit-action
                                        :permission="Acl::PERMISSION_CONTRACT_ATTRIBUTE_EDIT"
                                        :url="'${urlEdit}'"
                                    />
                                    <x-table.actions.delete-action
                                        :permission="Acl::PERMISSION_CONTRACT_ATTRIBUTE_DELETE"
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

    
</x-base-layout>
