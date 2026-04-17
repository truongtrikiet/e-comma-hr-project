<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.ai_profile_management.manage_ai_profile') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>

    
    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.ai_profile_management.manage_ai_profile') => '',
        ]"
    />

    <div class="align-items-center justify-content-between mb-3">
        <x-slot:boxTitle>
            {{ __('general.menu.ai_profile_management.manage_ai_profile') }}
        </x-slot:boxTitle>
        <div></div>

        <div>
            @can(Acl::PERMISSION_AI_PROFILE_ADD)
                <x-buttons.button-link
                    :label="__('general.menu.ai_profile_management.create_ai_profile')"
                    :url="route('admin.ai_profile.create')"
                />
            @endcan
        </div>
    </div>

    <x-custom.stat-box
        :boxId="'ai-profiles-box'"
        :custom-col="'col-lg-12'"
        :box_of_datatable="true"
    >
        <x-table.datatable 
            :id="'sAiProfileTable'"
            :title="__('AI Profile List')"
        >
            <x-slot:tableHeader>
                <tr>
                    <th style="width:5%">ID</th>
                    <th>{{ __('general.common.school') }}</th>
                    <th>{{ __('general.common.name') }}</th>
                    <th>{{ __('general.common.provider') }}</th>
                    <th>{{ __('general.common.model') }}</th>
                    <th>{{ __('general.common.status') }}</th>
                    <th>{{ __('general.common.action') }}</th>
                </tr>
            </x-slot:tableHeader>
            <x-slot:customScript>
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('admin.ai_profile.index') }}",
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
                        "data": "school",
                        "orderable": false,
                        "class": "text-center",
                        "render": function(data, type, full) {
                            return `<span class="badge badge-light">${data?.name ?? 'N/A'}</span>`;
                        }
                    },
                    { 
                        "data": "name",
                        "class": "text-center",
                        "orderable": false
                    },
                    {
                        "data": "provider",
                        "class": "text-center",
                        "orderable": false,
                        "render": function(data, type, full) {
                            return `<span class="badge badge-light">${full.provider}</span>`;
                        }
                    },
                    {
                        "data": "model",
                        "class": "text-center",
                        "orderable": false,
                        "render": function(data, type, full) {
                            return `<span class="badge badge-light">${full.model}</span>`;
                        }
                    },
                    {
                        "data": "status",
                        "orderable": false,
                        "class": "text-center",
                        "render": function(data, type, full) {
                            return `<span class="badge badge-${full.badge_name}">${full.status_name}</span>`;
                        }
                    },
                    {
                        "data": "id",
                        "class": "text-center no-content",
                        "orderable": false,
                        "render": function (data, type, full) {
                            <!-- let urlShow = `{{ route('admin.ai_profile.show', ':id') }}`.replace(':id', data); -->
                            let urlEdit = `{{ route('admin.ai_profile.edit', ':id') }}`.replace(':id', data);
                            let urlDestroy = `{{ route('admin.ai_profile.destroy', ':id') }}`.replace(':id', data);
                            let profileData = encodeURIComponent(JSON.stringify(full));

                            return `
                                <ul class="table-controls d-flex justify-content-center">
                                    <li>
                                        <a href="#" class="bs-tooltip show-detail" data-profile="${profileData}" title="{{ __('general.common.show_detail') }}" data-bs-toggle="tooltip" data-bs-placement="top">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-eye p-1 br-6 mb-1">
                                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>
                                    </li>
                                    <x-table.actions.edit-action
                                        :permission="Acl::PERMISSION_AI_PROFILE_EDIT"
                                        :url="'${urlEdit}'"
                                    />
                                    <x-table.actions.delete-action
                                        :permission="Acl::PERMISSION_AI_PROFILE_DELETE"
                                        :url="'${urlDestroy}'"
                                        :datatableId="'sAiProfileTable'"
                                    />
                                </ul>`;
                        }
                    }
                ]
            </x-slot:customScript>
        </x-table.datatable>
    </x-custom.stat-box>

    <div class="modal fade" id="aiProfileDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('general.common.show_detail') }}</h5>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <div id="ai-profile-detail-body">
                        <dl class="row">
                            <dt class="col-sm-3">{{ __('general.common.name') }}</dt>
                            <dd class="col-sm-9" id="ai-detail-name"></dd>

                            <dt class="col-sm-3">{{ __('general.common.school') }}</dt>
                            <dd class="col-sm-9" id="ai-detail-school"></dd>

                            <dt class="col-sm-3">{{ __('general.common.provider') }}</dt>
                            <dd class="col-sm-9" id="ai-detail-provider"></dd>

                            <dt class="col-sm-3">{{ __('general.common.model') }}</dt>
                            <dd class="col-sm-9" id="ai-detail-model"></dd>

                            <dt class="col-sm-3">{{ __('general.common.endpoint') }}</dt>
                            <dd class="col-sm-9" id="ai-detail-endpoint"></dd>

                            <dt class="col-sm-3">{{ __('general.common.api_key') }}</dt>
                            <dd class="col-sm-9" id="ai-detail-api-key"></dd>
                        </dl>
                        <div id="ai-api-response" class="mt-3" style="white-space:pre-wrap;display:none;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal" id="ai-cancel-btn">{{__('general.common.cancel')}}</button>
                    <button type="button" id="ai-call-api-btn" class="btn btn-primary">{{ __('general.common.call_api') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('click', function(e) {
            const target = e.target.closest('.show-detail');
            if (!target) return;
            e.preventDefault();

            const raw = target.getAttribute('data-profile');
            if (!raw) return;
            const profile = JSON.parse(decodeURIComponent(raw));

            document.getElementById('ai-detail-name').textContent = profile.name ?? '';
            document.getElementById('ai-detail-school').textContent = profile.school?.name ?? '';
            document.getElementById('ai-detail-provider').textContent = profile.provider ?? '';
            document.getElementById('ai-detail-model').textContent = profile.model ?? '';
            document.getElementById('ai-detail-endpoint').textContent = profile.endpoint ?? '';
            document.getElementById('ai-detail-api-key').textContent = profile.api_key_encrypted ? '••••••••' : 'N/A';
            document.getElementById('ai-api-response').style.display = 'none';

            const callBtn = document.getElementById('ai-call-api-btn');
            callBtn.setAttribute('data-id', profile.id);

                const modalElement = document.getElementById('aiProfileDetailModal');
                if (!window._aiProfileModalInstance) {
                    window._aiProfileModalInstance = new bootstrap.Modal(modalElement);

                    modalElement.addEventListener('click', function(ev) {
                        if (ev.target.closest('[data-bs-dismiss]')) {
                            if (window._aiProfileModalInstance) window._aiProfileModalInstance.hide();
                        }
                    });
                }

                window._aiProfileModalInstance.show();
        });

        document.getElementById('ai-call-api-btn').addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            if (!id) return;

            const url = `{{ route('admin.ai_profile.test_api', ':id') }}`.replace(':id', id);
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            this.disabled = true;
            this.textContent = '{{ __('general.common.calling') ?? "Calling..." }}';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            }).then(r => r.json())
            .then(res => {
                const out = document.getElementById('ai-api-response');
                out.style.display = 'block';
                out.textContent = JSON.stringify(res, null, 2);
                try { if (window._aiProfileModalInstance) window._aiProfileModalInstance.show(); } catch(e) {}
            }).catch(err => {
                const out = document.getElementById('ai-api-response');
                out.style.display = 'block';
                out.textContent = err?.message || String(err);
            }).finally(() => {
                this.disabled = false;
                this.textContent = '{{ __('general.common.call_api') }}';
            });
        });
    </script>

</x-base-layout>
