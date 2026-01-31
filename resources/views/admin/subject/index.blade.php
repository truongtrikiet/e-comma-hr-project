<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.subject_management.subject') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>

    
    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.subject_management.subject') => '',
        ]"
    />

    <x-custom.stat-box :id="'subject-management-filter'" :custom-col="'col-lg-12'">
        <x-slot:boxTitle>
            {{ __('Bộ lọc') }}
        </x-slot:boxTitle>

        @include('admin.subject.filters.index')
    </x-custom.stat-box>

    <div class="align-items-center justify-content-between mb-3">
        <x-slot:boxTitle>
            {{ __('general.menu.subject_management.subject') }}
        </x-slot:boxTitle>
        <div></div>

        <div>
            @can(Acl::PERMISSION_SUBJECT_ADD)
                <x-buttons.button-link
                    :label="__('general.menu.subject_management.create_subject')"
                    :url="route('admin.subject.create')"
                />
            @endcan
        </div>
    </div>

    <x-custom.stat-box
        :boxId="'subjects-box'"
        :custom-col="'col-lg-12'"
        :box_of_datatable="true"
    >
        <x-table.datatable 
            :id="'sSubjectTable'"
            :title="__('Subject List')"
        >
            <x-slot:tableHeader>
                <tr>
                    <th style="width:5%">ID</th>
                    <th>{{ __('general.common.name') }}</th>
                    <th>{{ __('general.common.school') }}</th>
                    <th>{{ __('general.common.status') }}</th>
                    <th>{{ __('general.common.action') }}</th>
                </tr>
            </x-slot:tableHeader>
            <x-slot:customScript>
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('admin.subject.index') }}",
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
                        "data": "school",
                        "orderable": false,
                        "class": "text-center",
                        "render": function(data, type, full) {
                            return `<span class="badge badge-light">${data?.name ?? 'N/A'}</span>`;
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
                            <!-- let urlShow = `{{ route('admin.subject.show', ':id') }}`.replace(':id', data); -->
                            let urlEdit = `{{ route('admin.subject.edit', ':id') }}`.replace(':id', data);
                            let urlDestroy = `{{ route('admin.subject.destroy', ':id') }}`.replace(':id', data);

                            return `
                                <ul class="table-controls d-flex justify-content-center">
                                    <x-table.actions.edit-action
                                        :permission="Acl::PERMISSION_SUBJECT_EDIT"
                                        :url="'${urlEdit}'"
                                    />
                                    <x-table.actions.delete-action
                                        :permission="Acl::PERMISSION_SUBJECT_DELETE"
                                        :url="'${urlDestroy}'"
                                        :datatableId="'sSubjectTable'"
                                    />
                                </ul>`;
                        }
                    }
                ]
            </x-slot:customScript>
        </x-table.datatable>
    </x-custom.stat-box>

    
</x-base-layout>
