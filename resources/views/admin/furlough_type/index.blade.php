<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.furlough_type_management.manage_furlough_type') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>

    
    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.furlough_type_management.manage_furlough_type') => '',
        ]"
    />

    <x-custom.stat-box :id="'furlough-type-management-filter'" :custom-col="'col-lg-12'">
        <x-slot:boxTitle>
            {{ __('general.filter.title') }}
        </x-slot:boxTitle>

        @include('admin.furlough_type.filters.index')
    </x-custom.stat-box>

    <div class="align-items-center justify-content-between mb-3">
        <x-slot:boxTitle>
            {{ __('general.menu.furlough_type_management.manage_furlough_type') }}
        </x-slot:boxTitle>
        <div></div>

        <div>
            @can(Acl::PERMISSION_FURLOUGH_TYPE_ADD)
                <x-buttons.button-link
                    :label="__('general.menu.furlough_type_management.create_furlough_type')"
                    :url="route('admin.furlough-type.create')"
                />
            @endcan
        </div>
    </div>

    <x-custom.stat-box
        :boxId="'furlough-types-box'"
        :custom-col="'col-lg-12'"
        :box_of_datatable="true"
    >
        <x-table.datatable 
            :id="'sFurloughTypeTable'"
            :title="__('Furlough Type List')"
        >
            <x-slot:tableHeader>
                <tr>
                    <th style="width:5%">ID</th>
                    <th>{{ __('general.common.name') }}</th>
                    <th>{{ __('general.common.status') }}</th>
                    <th>{{ __('general.common.action') }}</th>
                </tr>
            </x-slot:tableHeader>
            <x-slot:customScript>
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('admin.furlough-type.index') }}",
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
                            let urlEdit = `{{ route('admin.furlough-type.edit', ':id') }}`.replace(':id', data);
                            let urlDestroy = `{{ route('admin.furlough-type.destroy', ':id') }}`.replace(':id', data);

                            return `
                                <ul class="table-controls d-flex justify-content-center">
                                    <x-table.actions.edit-action
                                        :permission="Acl::PERMISSION_FURLOUGH_TYPE_EDIT"
                                        :url="'${urlEdit}'"
                                    />
                                    <x-table.actions.delete-action
                                        :permission="Acl::PERMISSION_FURLOUGH_TYPE_DELETE"
                                        :url="'${urlDestroy}'"
                                        :datatableId="'sFurloughTypeTable'"
                                    />
                                </ul>`;
                        }
                    }
                ]
            </x-slot:customScript>
        </x-table.datatable>
    </x-custom.stat-box>

    
</x-base-layout>
