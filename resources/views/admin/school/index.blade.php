<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.school_management.school') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>

    
    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.school_management.school') => '',
        ]"
    />

    <x-custom.stat-box :id="'campaign-schedule-management-filter'" :custom-col="'col-lg-12'">
        <x-slot:boxTitle>
            {{ __('Bộ lọc') }}
        </x-slot:boxTitle>

        @include('admin.school.filters.index')
    </x-custom.stat-box>

    <div class="align-items-center justify-content-between mb-3">
        <x-slot:boxTitle>
            {{ __('general.menu.school_management.school') }}
        </x-slot:boxTitle>
        <div></div>

        <div>
            @can(Acl::PERMISSION_SCHOOL_ADD)
                <x-buttons.button-link
                    :label="__('general.menu.school_management.create_school')"
                    :url="route('admin.school.create')"
                />
            @endcan
        </div>
    </div>

    <x-custom.stat-box
        :boxId="'schools-box'"
        :custom-col="'col-lg-12'"
        :box_of_datatable="true"
    >
        <x-table.datatable 
            :id="'sSchoolTable'"
            :title="__('School List')"
        >
            <x-slot:tableHeader>
                <tr>
                    <th style="width:5%">ID</th>
                    <th>{{ __('general.common.name') }}</th>
                    <th>{{ __('general.common.type') }}</th>
                    <th>{{ __('general.common.status') }}</th>
                    <th>{{ __('general.common.action') }}</th>
                </tr>
            </x-slot:tableHeader>
            <x-slot:customScript>
                "processing": true,
                "serverSide": true,
                "ordering": false,
                "ajax": {
                    "url": "{{ route('admin.school.index') }}",
                        "data": function(d) {
                            let searchParams = new URLSearchParams(window.location.search);
                            drawDT = d.draw;
                            d.limit = d.length;
                            d.page = d.start / d.length + 1;
                            d.ssl_status = $('#sSslStatus').val() || searchParams.get('ssl_status');
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
                        "data": "sub_domain",
                        "class": "text-center",
                        "orderable": false,
                        "render": function(data, type, full) {
                            return `<span class="badge badge-info">${full.sub_domain}</span>`;
                        }
                    },
                    {
                        "data": "ssl_status",
                        "orderable": false,
                        "class": "text-center",
                        "render": function(data, type, full) {
                            return `<span class="badge badge-${full.ssl_status_badge_name}">${full.ssl_status_name}</span>`;
                        }
                    },
                    {
                        "data": "id",
                        "class": "text-center no-content",
                        "orderable": false,
                        "render": function (data, type, full) {
                            <!-- let urlShow = `{{ route('admin.school.show', ':id') }}`.replace(':id', data); -->
                            let urlEdit = `{{ route('admin.school.edit', ':id') }}`.replace(':id', data);
                            let urlDestroy = `{{ route('admin.school.destroy', ':id') }}`.replace(':id', data);

                            return `
                                <ul class="table-controls d-flex justify-content-center">
                                    <x-table.actions.edit-action
                                        :permission="Acl::PERMISSION_SCHOOL_EDIT"
                                        :url="'${urlEdit}'"
                                    />
                                    <x-table.actions.delete-action
                                        :permission="Acl::PERMISSION_SCHOOL_DELETE"
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
