<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.role_management.role') }}
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
            __('general.menu.role_management.role') => route('admin.role.index'),
            __('general.menu.role_management.edit_role') => '',
        ]"
    />

    <x-form.form-layout
        :form-id="'general-settings'"
        :form-url="route('admin.role.update', $role->id)"
        :form-method="'PUT'"
        :card-title="__('general.menu.role_management.edit_role')"
        :custom-col="'col-lg-12'"
    >
        <div class="row">
            <input type="hidden" name="id" value="{{$role->id}}">

            <div class="col-6 mb-3">
                <div class="input-group">
                    <input id="sRoleSearch" type="text" class="form-control" placeholder="Tìm quyền (nhấn Enter)" aria-label="Search permissions">
                </div>

                <div class="form-check form-check-primary d-block new-control mt-2 mb-3">
                    <input class="form-check-input chk-parent"
                        type="checkbox"
                        id="form-check-default"
                        onclick="toggleAllCheckboxes(this)"
                        >
                    <label class="form-check-label" for="form-check-default">{{ __('general.common.select_all') }}</label>
                </div>
            </div>

            <x-form.form-multiple-checkbox
                :data-source="$permissions"
                :id="'sPermissionsCheckbox'"
                :name="'permissions'"
                :value-attribute="'name'"
                :label="__('general.common.have_permissions')"
                :value="$role->permissions->pluck('name')->toArray()"
            />

            <div id="permissionsEmpty" class="col-12 mt-2 mb-4 text-muted" style="display:none">
                <span>{{ __('general.common.no_permissions_found')}}</span>
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
            $(document).ready(function() {
                window.filterPermissions = function(term) {
                    term = (term || '').toString().trim().toLowerCase();

                    $('.form-check-label').each(function () {
                        $(this).html($(this).text());
                    });

                    if (!term) {
                        $('.form-check').show();
                        return;
                    }

                    const regex = new RegExp('(' + term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
                    let found = false;

                    $('.form-check').each(function() {
                        const $label = $(this).find('.form-check-label');
                        const text = $label.text().toLowerCase();

                        if (text.includes(term)) {
                            $label.html($label.text().replace(regex, '<span class="bg-warning">$1</span>'));
                            $(this).show();
                            found = true;
                        } else {
                            $(this).hide();
                        }
                    });

                    if (!found) {
                        $('#permissionsEmpty').show();
                    } else {
                        $('#permissionsEmpty').hide();
                    }
                };

                $('#sRoleSearch').on('input', function () {
                    filterPermissions($(this).val());
                });

                $('#sRoleSearch').on('keypress', function (e) {
                    if (e.which === 13) {
                        e.preventDefault();
                        filterPermissions($(this).val());
                    }
                });

                $('#clearRoleSearch').on('click', function () {
                    $('#sRoleSearch').val('');
                    filterPermissions('');
                });
            });
            
            function toggleAllCheckboxes(source) {
                $('input[name="permissions[]"]').prop('checked', $(source).is(':checked'));
            }
        </script>
        
    </x-slot:footerFiles>
</x-base-layout>
