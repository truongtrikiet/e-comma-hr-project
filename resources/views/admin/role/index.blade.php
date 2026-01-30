<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.role_management.role') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>

    
    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.role_management.role') => '',
        ]"
    />

    <x-custom.stat-box
        :boxId="'roles-box'"
        :custom-col="'col-lg-12'"
        :box_of_datatable="true"
    >
        <x-table.datatable 
            :id="'sRoleTable'"
            :title="__('Role List')"
        >
            <x-slot:tableHeader>
                <tr>
                    <th style="width: 10%">ID</th>
                    <th style="width: 20%">{{ __('general.common.role') }}</th>
                    <th class="text-center style="width: 60%">{{ __('general.common.have_permissions') }}</th>
                    <th class="text-center" style="width: 10%">{{ __('general.common.action') }}</th>
                </tr>
            </x-slot:tableHeader>
            <x-slot:tableBody>
                @foreach ($roles as $role)
                    <tr>
                        <td>
                            {{ $loop->index + 1 }}
                        </td>
                        <td>
                            {{ $role->name }}
                        </td>
                        <td style="display: flex; flex-flow: wrap" class="text-center">
                            @foreach ($role->permissions->take(10) as $permission)
                                <span class="badge badge-info mb-1 me-1">
                                    {{ $permission->name }}
                                </span>
                            @endforeach
                        </td>
                        <td class="text-center">
                            <ul class="table-controls d-flex">
                                <x-table.actions.edit-action
                                    :permission="Acl::PERMISSION_ROLE_EDIT"
                                    :url="route('admin.role.edit', $role)"/>
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </x-slot:tableBody>

        </x-table.datatable>
    </x-custom.stat-box>

    
</x-base-layout>
