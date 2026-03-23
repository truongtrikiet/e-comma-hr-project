<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.furlough_policy_management.furlough_policy') }}
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
            __('general.menu.furlough_policy_management.manage_furlough_policy') => route('admin.furlough-policies.index'),
            __('general.menu.furlough_policy_management.create_furlough_policy') => '',
        ]"
    />

    <x-form.form-layout
        :form-id="'general-settings'"
        :form-url="route('admin.furlough-policies.store')"
        :card-title="__('general.menu.furlough_policy_management.create_furlough_policy')"
        :custom-col="'col-lg-12'"
    >
        <div class="row">
            <div class="col-lg-8">
                <div class="mb-3">
                    <h5 class="mb-2">{{ __('general.common.information') }}</h5>
                    <div class="row">
                        <div class="col-md-12">
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
                                />
                            @else
                                <input type="hidden" name="school_id" value="{{ session('school_id') }}">
                            @endif

                            <x-form.form-select
                                :id="'sEmployeeTypeSelect'"
                                :label="__('general.menu.employee_type_management.title')"
                                :data-values="$employeeTypes"
                                :select-value-attribute="'id'"
                                :select-value-label="'name'"
                                :name="'employee_type_id'"
                                :multiple="false"
                                :placeholder="__('general.menu.employee_type_management.title')"
                                :isRequired="true"
                            />

                            <x-form.form-select
                                :id="'sFurloughTypeSelect'"
                                :label="__('general.menu.furlough_type_management.title')"
                                :data-values="$furloughTypes"
                                :select-value-attribute="'id'"
                                :select-value-label="'name'"
                                :name="'furlough_type_id'"
                                :multiple="false"
                                :placeholder="__('general.menu.furlough_type_management.title')"
                                :isRequired="true"
                            />

                            <div class="mb-3">
                                <h5 class="mt-5 mb-2">{{ __('general.common.information') }}</h5>
                                <div class="row">
                                    <div class="col-md-12">
                                        <x-form.form-select
                                            :id="'sFurloughPolicyTemplateSelect'"
                                            :label="__('general.menu.furlough_policy_template_management.title')"
                                            :data-values="$furloughPolicyTemplates"
                                            :select-value-attribute="'id'"
                                            :select-value-label="'name'"
                                            :name="'furlough_policy_template_id'"
                                            :multiple="false"
                                            :placeholder="__('general.menu.furlough_policy_template_management.title')"
                                            :isRequired="false"
                                        />
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.form-input 
                                            :id="'accrual_per_month'"
                                            :label="__('general.common.accrual_per_month')"
                                            :name="'accrual_per_month'"
                                            :type="'number'"
                                            :value="0"
                                            :isRequired="false"
                                        />
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.form-input 
                                            :id="'max_days'"
                                            :label="__('general.common.max_days')"
                                            :name="'max_days'"
                                            :type="'number'"
                                            :value="0"
                                            :isRequired="false"
                                        />
                                    </div>
                                </div>
                            </div>

                            <x-form.form-select
                                :id="'sIsPaidSelect'"
                                :label="__('general.common.paid')"
                                :data-values="$isPaid"
                                :name="'is_paid'"
                                :select-value-attribute="'value'"
                                :select-value-label="'label'"
                                :placeholder="__('general.common.paid')"
                                :isRequired="true"
                            />

                            <x-form.form-select
                                :id="'sCarryForwardSelect'"
                                :label="__('general.common.carry_forward')"
                                :data-values="[['value' => 1, 'label' => __('general.common.yes')], ['value' => 0, 'label' => __('general.common.no')]]"
                                :name="'carry_forward'"
                                :select-value-attribute="'value'"
                                :select-value-label="'label'"
                                :placeholder="__('general.common.carry_forward')"
                                :isRequired="false"
                            />

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <x-form.form-select
                                            :id="'sResetTypeSelect'"
                                            :label="__('general.common.reset_type')"
                                            :data-values="$resetTypes"
                                            :name="'reset_type'"
                                            :select-value-attribute="'value'"
                                            :select-value-label="'label'"
                                            :placeholder="__('general.common.reset_type')"
                                            :isRequired="true"
                                        />
                                    </div>
                                    <div class="col-md-6">
                                        <x-form.form-select
                                            :id="'reset_month'"
                                            :label="__('general.common.reset_month')"
                                            :data-values="$months"
                                            :name="'reset_month'"
                                            :select-value-attribute="'value'"
                                            :select-value-label="'label'"
                                            :placeholder="__('general.common.reset_month')"
                                        />
                                    </div>
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
            document.addEventListener("DOMContentLoaded", function() {
                const furloughTemplates = @json($furloughPolicyTemplates);
                
                const templateSelect = document.getElementById('sFurloughPolicyTemplateSelect');
                const fieldAccrual = document.getElementById('accrual_per_month');
                const fieldMaxDays = document.getElementById('max_days');
                const fieldIsPaid = document.getElementById('sIsPaidSelect');
                const fieldCarryForward = document.getElementById('sCarryForwardSelect');
                const fieldResetType = document.getElementById('sResetTypeSelect');
                const fieldResetMonth = document.getElementById('reset_month');

                const manualFields = [fieldAccrual, fieldMaxDays, fieldIsPaid, fieldCarryForward];

                let isAutoFilling = false;

                // Case 1
                templateSelect.addEventListener('change', function() {
                    if (isAutoFilling) return;

                    const selectedId = this.value;
                    if (selectedId) {
                        const template = furloughTemplates.find(t => t.id == selectedId);
                        if (template) {
                            isAutoFilling = true;
                            
                            fieldAccrual.value = template.accrual_per_month;
                            fieldMaxDays.value = template.max_days;
                            
                            fieldIsPaid.value = template.is_paid;
                            fieldCarryForward.value = template.carry_forward ? '1' : '0';

                            manualFields.forEach(field => field.dispatchEvent(new Event('change')));
                            
                            isAutoFilling = false;
                        }
                    }
                });

                manualFields.forEach(field => {
                    field.addEventListener('input', checkManualOverride);
                    field.addEventListener('change', checkManualOverride);
                });

                // Case 2 và Case 3
                function checkManualOverride() {
                    if (isAutoFilling) return;

                    const currentTemplateId = templateSelect.value;
                    if (!currentTemplateId) return;

                    const template = furloughTemplates.find(t => t.id == currentTemplateId);
                    if (template) {
                        const isDifferent = 
                            fieldAccrual.value != template.accrual_per_month ||
                            fieldMaxDays.value != template.max_days ||
                            fieldIsPaid.value != String(template.is_paid) ||
                            fieldCarryForward.value != (template.carry_forward ? '1' : '0');

                        if (isDifferent) {
                            isAutoFilling = true; 
                            templateSelect.value = "";
                            templateSelect.dispatchEvent(new Event('change'));
                            isAutoFilling = false;
                        }
                    }
                }

                function updateResetMonthVisibility() {
                    if (!fieldResetType || !fieldResetMonth) return;

                    const monthlyValue = '1';
                    const wrap = fieldResetMonth.closest('.col-md-6') || fieldResetMonth.parentElement;

                    if (String(fieldResetType.value) === monthlyValue) {
                        if (wrap) wrap.style.display = '';
                        if (fieldResetMonth && fieldResetMonth.tomselect) {
                            fieldResetMonth.tomselect.enable();
                        }
                        fieldResetMonth.removeAttribute('disabled');
                        fieldResetMonth.setAttribute('required', 'required');
                    } else {
                        if (wrap) wrap.style.display = 'none';
                        fieldResetMonth.removeAttribute('required');
                        if (fieldResetMonth && fieldResetMonth.tomselect) {
                            fieldResetMonth.tomselect.setValue('', true); fieldResetMonth.tomselect.disable();
                        }
                        fieldResetMonth.value = '';
                        fieldResetMonth.setAttribute('disabled', 'disabled');
                        fieldResetMonth.dispatchEvent(new Event('change'));
                    }
                }

                updateResetMonthVisibility();
                if (fieldResetType) fieldResetType.addEventListener('change', updateResetMonthVisibility);
            });
        </script>
        
    </x-slot:footerFiles>

</x-base-layout>
