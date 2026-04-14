<x-hr.base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.contract_management.contract') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>

    
    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.contract_management.contract') => '',
        ]"
    />

    <div class="align-items-center justify-content-between mb-3">
        <x-slot:boxTitle>
            {{ __('general.menu.contract_management.contract') }}
        </x-slot:boxTitle>
        <div></div>

        <div>
            @can(Acl::PERMISSION_CONTRACT_ADD)
                <x-buttons.button-link
                    :label="__('general.menu.contract_management.create_contract')"
                    :url="route('hr.contract.create')"
                />
            @endcan
        </div>
    </div>

    <x-custom.stat-box
        :boxId="'contracts-box'"
        :custom-col="'col-lg-12'"
        :box_of_datatable="true"
    >
        <x-table.datatable 
            :id="'sContractTable'"
            :title="__('Contract List')"
        >
            <x-slot:tableHeader>
                <tr>
                    <th style="width:5%">ID</th>
                    <th>{{ __('general.common.contract_code') }}</th>
                    <th>{{ __('general.common.contract_type') }}</th>
                    <th>{{ __('general.common.object') }}</th>
                    <th>{{ __('general.common.status') }}</th>
                    <th>{{ __('general.common.signed_at') }}</th>
                    <th>{{ __('general.common.expired_at') }}</th>
                    <th>{{ __('general.common.action') }}</th>
                </tr>
            </x-slot:tableHeader>
            <x-slot:customScript>
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('hr.contract.index') }}",
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
                        "data": "code",
                        "class": "text-center",
                        "render": function (data, type, full) {
                            return `<span class="badge badge-light">${data}</span>`;
                        },
                    },
                    {
                        "data": "contract_type_name",
                        "class": "text-center",
                        "orderable": false,
                    },
                    {
                        "data": "contractable_name",
                        "class": "text-center",
                        "orderable": false,
                    },
                    {
                        "data": "status_name",
                        "class": "text-center",
                        "render": function (data, type, full) {
                            return `<span class="badge badge-${full.status_badge}">${data}</span>`;
                        },
                    },
                    {
                        "data": "signed_at",
                        "class": "text-center",
                        "orderable": false,
                    },
                    {
                        "data":"expired_at",
                        "orderable": false,
                        "class": "text-center",
                    },
                    {
                        "data": "id",
                        "class": "text-center no-content",
                        "orderable": false,
                        "render": function (data, type, full) {
                            <!-- let urlShow = `{{ route('admin.contract.show', ':id') }}`.replace(':id', data); -->
                            let urlEdit = `{{ route('hr.contract.edit', ':id') }}`.replace(':id', (full.code ?? data));
                            let urlDestroy = `{{ route('hr.contract.destroy', ':id') }}`.replace(':id', (full.code ?? data));
                            let urlPdf = `{{ route('hr.contract.contract-detail', ':id') }}`.replace(':id', (full.code ?? data));

                            return `
                                <ul class="table-controls d-flex justify-content-center">
                                    <x-table.actions.download-pdf
                                        :permission="Acl::PERMISSION_CONTRACT_DETAIL_PDF"
                                        :url="'${urlPdf}'"
                                    />
                                    <x-table.actions.edit-action
                                        :permission="Acl::PERMISSION_CONTRACT_EDIT"
                                        :url="'${urlEdit}'"
                                    />
                                    <x-table.actions.delete-action
                                        :permission="Acl::PERMISSION_CONTRACT_DELETE"
                                        :url="'${urlDestroy}'"
                                        :datatableId="'sContractTable'"
                                    />
                                </ul>`;
                        }
                    }
                ]
            </x-slot:customScript>
        </x-table.datatable>
    </x-custom.stat-box>

    
</x-hr.base-layout>
