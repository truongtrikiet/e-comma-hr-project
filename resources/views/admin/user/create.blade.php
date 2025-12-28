<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.user_management.user') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>
        <!-- Datepicker CSS -->
        <link href="{{ asset('plugins/vendor/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('plugins/vendor/clockpicker/css/bootstrap-clockpicker.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('plugins/vendor/jquery-asColorPicker/css/asColorPicker.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('plugins/vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet" type="text/css" />

        <!-- Pick date -->
        <link rel="stylesheet" href="{{ asset('plugins/vendor/pickadate/themes/default.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/vendor/pickadate/themes/default.date.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/vendor/pickadate/themes/default.time.css') }}">

    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.user_management.user') => route('admin.user.index'),
            __('general.menu.user_management.create_user') => '',
        ]"
    />

    <x-custom.stat-box
        :boxId="'users-box'"
        :custom-col="'col-lg-12'"
    >
        <x-form.form-layout
            :form-id="'general-settings'"
            :form-url="route('admin.user.store')"
            :form-method="'POST'"
            :card-title="__('general.menu.user_management.create_user')"
        >
            <x-form.form-input
                :id="'sLastName'"
                :label="__('general.common.last_name')"
                :name="'last_name'"
                :placeholder="__('general.common.last_name')"
                :isRequired="true"
            />

            <x-form.form-input
                :id="'sFirstName'"
                :label="__('general.common.first_name')"
                :name="'first_name'"
                :placeholder="__('general.common.first_name')"
                :isRequired="true"
            />

            <x-form.form-input
                :id="'sEmail'"
                :label="__('general.common.email')"
                :name="'email'"
                :placeholder="__('general.common.email')"
                :type="'email'"
                :isRequired="true"
            />

            <x-form.date-picker 
                :id="'sDateOfBirth'"
                :name="'birth'"
                :label="__('general.common.birth')"
                :placeholder="__('general.common.birth')"
                :isRequired="true"
                :type="'text'"
            />

            <!-- <div class="form-group row"> -->
            <div class="col-sm-10">
                <x-buttons.submit
                    :label="__('general.common.confirm')"
                />
            </div>
            <!-- </div> -->
            
        </x-form.form-layout>

    </x-custom.stat-box>

</x-base-layout>