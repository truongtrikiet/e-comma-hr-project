<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.department_management.department') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>
        <link href="{{ asset('vendor/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
        <!-- Clockpicker -->
        <link href="{{ asset('vendor/clockpicker/css/bootstrap-clockpicker.min.css') }}" rel="stylesheet">
        <!-- asColorpicker -->
        <link href="{{ asset('vendor/jquery-asColorPicker/css/asColorPicker.min.css') }}" rel="stylesheet">
        <!-- Material color picker -->
        <link href="{{ asset('vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
        <!-- Pick date -->
        <link rel="stylesheet" href="{{ asset('vendor/pickadate/themes/default.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/pickadate/themes/default.date.css') }}">
        <!-- Custom Stylesheet -->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.default.min.css">
        <link rel="stylesheet" type="text/css" href="{{asset('plugins/filepond/filepond.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('plugins/filepond/FilePondPluginImagePreview.min.css')}}">

    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.department_management.department') => route('admin.department.index'),
            __('general.menu.department_management.edit_department') => '',
        ]"
    />

    <x-form.form-layout
        :form-id="'general-settings'"
        :form-url="route('admin.department.update', $department->id)"
        :form-method="'PUT'"
        :card-title="__('general.menu.department_management.edit_department')"
        :custom-col="'col-lg-12'"
    >
        <div class="row">
            <div class="col-lg-8">
                <div class="mb-3">
                    <h5 class="mb-2">{{ __('general.common.information') }}</h5>
                    <div class="row">
                        <div class="col-md-12">
                            <x-form.form-input
                                :id="'name'"
                                :name="'name'"
                                :label="__('general.common.name') "
                                :placeholder="__('general.common.name') "
                                :isRequired="true"
                                :value="$department->name"
                            />

                            <x-form.form-select
                                :id="'sParentDepartmentSelect'"
                                :label="__('general.common.parent_department')"
                                :data-values="$departments"
                                :name="'parent_id'"
                                :select-value-attribute="'id'"
                                :select-value-label="'name'"
                                :placeholder="__('general.common.parent_department')"
                                :selected="old('parent_id', $department->parent_id)"
                            />

                            @if (session('school_name') === config('subdomain.system_main'))
                                <x-form.form-select
                                    :id="'sSchoolSelect'"
                                    :label="__('general.common.school')"
                                    :data-values="$schools"
                                    :select-value-attribute="'id'"
                                    :select-value-label="'name'"
                                    :name="'school_id'"
                                    :multiple="false"
                                    :placeholder="__('general.common.school')"
                                    :isRequired="false"
                                    :selected="old('school_id', $department?->school_id)"
                                />
                            @else
                                <input type="hidden" name="school_id" value="{{ session('school_id') }}">
                            @endif

                            <x-form.form-textarea
                                :id="'description'"
                                :name="'description'"
                                :label="__('general.common.description')"
                                :placeholder="__('general.common.description')"
                                :rows="4"
                                :value="$department->description"
                            />

                            <x-form.form-select
                                :id="'sTypeDepartmentSelect'"
                                :label="__('general.common.type')"
                                :data-values="$types"
                                :name="'type'"
                                :select-value-attribute="'value'"
                                :select-value-label="'label'"
                                :placeholder="__('general.common.type')"
                                :isRequired="true"
                                :selected="old('type', $department->type->value)"
                            />

                            <x-form.form-select
                                :id="'sStatusSelect'"
                                :label="__('general.common.status')"
                                :data-values="$statuses"
                                :name="'status'"
                                :select-value-attribute="'value'"
                                :select-value-label="'label'"
                                :placeholder="__('general.common.status')"
                                :isRequired="true"
                                :selected="old('status', $department->status->value)"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ __('general.common.users') }}</h5>
                                <div>
                                    <button type="button" id="btn-add-user-row" class="btn btn-sm btn-primary">+</button>
                                    <button type="button" id="btn-remove-user-rows" class="btn btn-sm btn-danger">-</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="users-empty" class="text-muted" @if($department->users?->count()) style="display:none" @endif>
                                    {{ __('general.common.no_users_assigned') ?? 'No users assigned.' }}
                                </div>

                                <div class="table-responsive" id="users-table-wrapper" @if(!$department->users->count()) style="display:none" @endif>
                                    <input type="hidden" name="head_user_id" id="head_user_id" value="{{ old('head_user_id', $department->head_user_id ?? '') }}">
                                    <table class="table table-sm" id="users-assignment-table">
                                        <thead>
                                            <tr>
                                                <th style="width:30px"><input type="checkbox" id="select-all-rows"/></th>
                                                <th style="width:48px"></th>
                                                <th>{{ __('general.common.name') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $initialUserIds = old('user_ids') ?? $department->users->pluck('id')->toArray();
                                                if (!empty($department->head_user_id)) {
                                                    $head = $department->head_user_id;
                                                    $initialUserIds = array_values(array_filter($initialUserIds, fn($i) => $i != $head));
                                                    array_unshift($initialUserIds, $head);
                                                }
                                            @endphp
                                            @foreach($initialUserIds as $oldUserId)
                                                <tr>
                                                    <td><input type="checkbox" class="row-select"/></td>
                                                    <td class="text-center align-middle">
                                                        <button type="button" class="btn-make-head btn btn-sm btn-outline-secondary" title="Make head">↑</button>
                                                    </td>
                                                    <td>
                                                        <select name="user_ids[]" class="form-control form-control-sm">
                                                            <option value="">-- Select user --</option>
                                                            @foreach($users as $user)
                                                                <option value="{{ $user->id }}" @if($user->id == $oldUserId) selected @endif>{{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="mb-3">
                    <x-buttons.submit :label="__('general.common.complete')"/>
                </div>
            </div>
        </div>
    </x-form.form-layout>

    <x-slot:footerFiles>
        <script>
            (function(){
                const users = @json($users->map(fn($u)=>['id'=>$u->id,'name'=>$u->name]));

                const addBtn = document.getElementById('btn-add-user-row');
                const removeBtn = document.getElementById('btn-remove-user-rows');
                const tableWrapper = document.getElementById('users-table-wrapper');
                const tbody = document.querySelector('#users-assignment-table tbody');
                const emptyEl = document.getElementById('users-empty');
                const selectAll = document.getElementById('select-all-rows');
                const headInput = document.getElementById('head_user_id');

                function getSelectedIds(){
                    return Array.from(tbody.querySelectorAll('select[name="user_ids[]"]')).map(s => s.value).filter(v => v);
                }

                function rebuildOptions(){
                    const selected = getSelectedIds();
                    tbody.querySelectorAll('select[name="user_ids[]"]').forEach(select => {
                        const current = select.value;
                        select.innerHTML = '';
                        const placeholder = document.createElement('option');
                        placeholder.value = '';
                        placeholder.textContent = '-- Select user --';
                        select.appendChild(placeholder);

                        users.forEach(u => {
                            if (selected.includes(String(u.id)) && String(u.id) !== String(current)) {
                                return;
                            }
                            const opt = document.createElement('option');
                            opt.value = u.id;
                            opt.text = u.name;
                            if (String(u.id) === String(current)) opt.selected = true;
                            select.appendChild(opt);
                        });
                    });
                }

                function updateHeadInput(){
                    const firstSelect = tbody.querySelector('select[name="user_ids[]"]');
                    const val = firstSelect ? firstSelect.value : '';
                    headInput.value = val || '';
                    tbody.querySelectorAll('tr').forEach((tr, idx) => {
                        tr.querySelectorAll('.badge-head').forEach(b => b.remove());
                        if (idx === 0 && val) {
                            const td = tr.querySelector('td:nth-child(3)');
                            if (td) {
                                const badge = document.createElement('span');
                                badge.className = 'badge bg-dark ms-6 mt-2 badge-head';
                                badge.textContent = 'HEAD';
                                td.appendChild(badge);
                            }
                        }
                    });
                }

                function renderRow(selectedId) {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td><input type="checkbox" class="row-select"/></td>
                        <td class="text-center align-middle">
                            <button type="button" class="btn-make-head btn btn-sm btn-outline-secondary" title="Make head">↑</button>
                        </td>
                        <td>
                            <select name="user_ids[]" class="form-control form-control-sm">
                                <option value="">-- Select user --</option>
                            </select>
                        </td>
                    `;

                    tr.querySelector('select[name="user_ids[]"]').addEventListener('change', function(){
                        rebuildOptions();
                        updateHeadInput();
                    });

                    tr.querySelector('.btn-make-head').addEventListener('click', function(){
                        tbody.insertBefore(tr, tbody.firstChild);
                        rebuildOptions();
                        updateHeadInput();
                    });

                    return tr;
                }

                function showTableIfNeeded(){
                    const hasRows = tbody.querySelectorAll('tr').length > 0;
                    tableWrapper.style.display = hasRows ? '' : 'none';
                    emptyEl.style.display = hasRows ? 'none' : '';
                    if (hasRows) rebuildOptions();
                    updateHeadInput();
                }

                addBtn.addEventListener('click', function(){
                    const row = renderRow();
                    tbody.appendChild(row);
                    showTableIfNeeded();
                });

                removeBtn.addEventListener('click', function(){
                    const checked = tbody.querySelectorAll('input.row-select:checked');
                    if (checked.length === 0) return;
                    checked.forEach(cb => cb.closest('tr').remove());
                    selectAll.checked = false;
                    showTableIfNeeded();
                });

                selectAll.addEventListener('change', function(){
                    const checked = this.checked;
                    tbody.querySelectorAll('input.row-select').forEach(cb => cb.checked = checked);
                });

                tbody.querySelectorAll('.btn-make-head').forEach(btn => {
                    btn.addEventListener('click', function(){
                        const tr = btn.closest('tr');
                        tbody.insertBefore(tr, tbody.firstChild);
                        rebuildOptions();
                        updateHeadInput();
                    });
                });

                tbody.querySelectorAll('select[name="user_ids[]"]').forEach(s => s.addEventListener('change', function(){ rebuildOptions(); updateHeadInput(); }));

                showTableIfNeeded();
            })();
        </script>
    </x-slot:footerFiles>
</x-base-layout>
