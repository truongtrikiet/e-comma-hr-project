<x-staff.base-layout :scrollspy="false">
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
                    "url": "{{ route('staff.contract.index') }}",
                        "data": function(d) {
                            let searchParams = new URLSearchParams(window.location.search);
                            drawDT = d.draw;
                            d.limit = d.length;
                            d.page = d.start / d.length + 1;
                            d.school_id = $('#sSchoolSelect').val() || searchParams.get('school_id');
                            d.status = $('#sContractStatus').val() || searchParams.get('status');
                            d.signed_at = $('#sSignedAt').val() || searchParams.get('signed_at');
                            d.expired_at = $('#sExpiredAt').val() || searchParams.get('expired_at');
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
                        "orderable": false,
                        "class": "text-center",
                    },
                    {
                        "data": "contractable_name",
                        "orderable": false,
                        "class": "text-center",
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
                            <!-- let urlShow = `{{ route('staff.contract.show', ':id') }}`.replace(':id', data); -->
                            let urlEdit = `{{ route('staff.contract.edit', ':id') }}`.replace(':id', (full.code ?? data));
                            let urlDestroy = `{{ route('staff.contract.destroy', ':id') }}`.replace(':id', (full.code ?? data));
                            let urlPdf = `{{ route('staff.contract.contract-detail', ':id') }}`.replace(':id', (full.code ?? data));

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

    
</x-staff.base-layout>
