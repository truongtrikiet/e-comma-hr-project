<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.user_management.user') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>

    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.user_management.user') => '',
        ]"
    />

    <x-custom.stat-box
        :boxId="'users-box'"
        :custom-col="'col-lg-12'"
        :box_of_datatable="true"
    >
        <div class="d-flex align-items-center justify-content-between mb-3">
            <x-slot:boxTitle>
                {{ __('general.menu.user_management.user') }}
            </x-slot:boxTitle>
            <div></div>

            <div>
                @can(Acl::PERMISSION_USER_ADD)
                    <x-buttons.button-link
                        :label="__('general.menu.user_management.create_user')"
                        :url="route('admin.user.create')"
                    />
                @endcan
            </div>
        </div>
    
        <x-table.datatable 
            :id="'sUserTable'"
            :title="__('User List')"
        >
            <x-slot:tableHeader>
                <tr>
                    <th style="width:4%">ID</th>
                    <th style="width:18%">{{ __('general.common.name') }}</th>
                    <th style="width:18%">{{ __('general.common.email') }}</th>
                    <th style="width:20%">{{ __('general.common.login_at') }}</th>
                    <th style="width:20%">{{ __('general.common.created_at') }}</th>
                    <th style="width:10%">{{ __('general.common.status') }}</th>
                    <th style="width:10%">{{ __('general.common.action') }}</th>
                </tr>
            </x-slot:tableHeader>
            <x-slot:customScript>
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('admin.user.index') }}",
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
                        "orderable": false
                    },
                    {
                        "data": "email",
                        "orderable": false
                    },
                    {
                        "data": "login_at",
                        "orderable": false,
                        "class": "text-center",
                    },
                    {
                        "data": "created_at",
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
                            let urlShow = `{{ route('admin.user.show', ':id') }}`.replace(':id', data);
                            let urlEdit = `{{ route('admin.user.edit', ':id') }}`.replace(':id', data);
                            let urlDestroy = `{{ route('admin.user.destroy', ':id') }}`.replace(':id', data);

                            return `
                                <ul class="table-controls d-flex justify-content-center">
                                    <x-table.actions.show-action
                                        :permission="Acl::PERMISSION_USER_VIEW"
                                        :url="'${urlShow}'"
                                        :dataTableId="'sUserTable'"
                                    />
                                    <x-table.actions.edit-action
                                        :permission="Acl::PERMISSION_USER_EDIT"
                                        :url="'${urlEdit}'"
                                    />
                                    <x-table.actions.delete-action
                                        :permission="Acl::PERMISSION_USER_DELETE"
                                        :url="'${urlDestroy}'"
                                        :datatableId="'sUserTable'"
                                    />
                                </ul>`;
                        }
                    }
                ]
            </x-slot:customScript>
        </x-table.datatable>
    </x-custom.stat-box>

    
</x-base-layout>
