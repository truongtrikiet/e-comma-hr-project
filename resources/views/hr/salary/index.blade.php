<x-hr.base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{ __('general.menu.salary_management.title') }}
    </x-slot:pageTitle>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <!--  BEGIN CUSTOM STYLE FILE  -->
        <link rel="stylesheet" type="text/css" href="{{asset('plugins/sweetalerts2/sweetalerts2.css')}}">

        @vite([
            'resources/scss/light/plugins/sweetalerts2/custom-sweetalert.scss',
            'resources/scss/dark/plugins/sweetalerts2/custom-sweetalert.scss',
        ])
        <!--  END CUSTOM STYLE FILE  -->
    </x-slot:headerFiles>
    <!-- END GLOBAL MANDATORY STYLES -->

    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.salary_management.title') => '',
            __('general.menu.salary_management.manage_salary') => '',
        ]"
    />

    <div class="align-items-center justify-content-between mb-3">
        <x-slot:boxTitle>
            {{ __('general.menu.salary_management.manage_salary') }}
        </x-slot:boxTitle>
        <div></div>

        <div>
            @can(Acl::PERMISSION_SALARY_ADD)
                <x-buttons.button-link
                    :label="__('general.menu.salary_management.create_salary')"
                    :url="route('hr.salary.create')"
                />
            @endcan
        </div>
    </div>

    <x-custom.stat-box
        :id="'salary-box'"
        :custom-col="'col-lg-12'"
        :box_of_datatable="true"
    >
        <x-slot:boxTitle>
            {{ __('general.menu.salary_management.manage_salary') }}
        </x-slot:boxTitle>

        <x-table.datatable
            :id="'sSalaryTable'"
        >
            <x-slot:tableHeader>
                <tr>
                    <th style="width:5%">ID</th>
                    <th style="width:15%">{{ __('general.common.user_name') }}</th>
                    <th style="width:15%">{{ __('general.common.email') }}</th>
                    <th style="width:15%">{{ __('general.common.gross_amount') }}</th>
                    <th style="width:15%">{{ __('general.common.approved_at') }}</th>
                    <th style="width:15%">{{ __('general.common.effective_date') }}</th>
                    <th style="width:15%">{{ __('general.common.status') }}</th>
                    <th style="width:10%">{{ __('general.common.action') }}</th>
                </tr>
            </x-slot:tableHeader>
            <x-slot:customScript>
                "processing": true,
                "serverSide": true,
                "ordering": false,
                "ajax": {
                "url": "{{ route('hr.salary.index') }}",
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
                        "orderable": false,
                        "class": "text-center",
                    },
                    {
                        "data": "email",
                        "orderable": false,
                        "class": "text-center",
                    },
                    {
                        "data": "gross_amount",
                        "orderable": false,
                        "class": "text-center",
                    },
                    {
                        "data": "approved_at",
                        "orderable": false,
                        "class": "text-center",
                    },
                    {
                        "data": "effective_date",
                        "orderable": false,
                        "class": "text-center",
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
                            let urlEdit = `{{ route('hr.salary.edit', ':id') }}`.replace(':id', data);
                            let urlShow = `{{ route('hr.salary.show', ':id') }}`.replace(':id', data);

                            return `
                                <ul class="table-controls d-flex justify-content-center">
                                    @can(Acl::PERMISSION_SALARY_EDIT)
                                    <li>
                                        <a
                                            href="javascript:void(0);"
                                            title="{{ __('general.common.show') }}" data-bs-original-title="{{ __('general.common.choose') }}"
                                            onclick="openModalObjectHistory('${urlShow}')"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye p-1 br-6 mb-1">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>
                                    </li>
                                    @endcan
                                    <x-table.actions.edit-action
                                        :permission="Acl::PERMISSION_SALARY_EDIT"
                                        :url="'${urlEdit}'"
                                    />
                                </ul>`;
                            }
                        }
                    ]
            </x-slot:customScript>
        </x-table.datatable>

        <x-template.salary-history
            :label="__('general.menu.template_management.salary_history')"
        />

    </x-custom.stat-box>

    <!--  BEGIN CUSTOM SCRIPTS FILE  -->
    <x-slot:footerFiles>
        
    
    </x-slot:footerFiles>
    <!--  END CUSTOM SCRIPTS FILE  -->
</x-hr.base-layout>
