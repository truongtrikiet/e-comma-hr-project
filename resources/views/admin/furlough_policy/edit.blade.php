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
            __('general.menu.furlough_policy_management.edit_furlough_policy') => '',
        ]"
    />

    <x-form.form-layout
        :form-id="'general-settings'"
        :form-url="route('admin.furlough-policies.update', $furloughPolicy->id)"
        :form-method="'PUT'"
        :card-title="__('general.menu.furlough_policy_management.edit_furlough_policy')"
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
                                    :selected="$furloughPolicy?->school_id"
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
                                :selected="$furloughPolicy?->employee_type_id"
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
                                :selected="$furloughPolicy?->furlough_type_id"
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
                                            :selected="$furloughPolicy?->furlough_policy_template_id"
                                        />
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.form-input 
                                            :id="'accrual_per_month'"
                                            :label="__('general.common.accrual_per_month')"
                                            :name="'accrual_per_month'"
                                            :type="'number'"
                                            :isRequired="false"
                                            :value="old('accrual_per_month', $furloughPolicy?->accrual_per_month ?? 0)"
                                        />
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.form-input 
                                            :id="'max_days'"
                                            :label="__('general.common.max_days')"
                                            :name="'max_days'"
                                            :type="'number'"
                                            :isRequired="false"
                                            :value="old('max_days', $furloughPolicy?->max_days ?? 0)"
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
                                :selected="old('is_paid', $furloughPolicy?->is_paid->value)"
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
                                :selected="old('carry_forward', (int) $furloughPolicy->carry_forward)"
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
                                            :selected="old('reset_type', $furloughPolicy?->reset_type->value)"
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
                                            :selected="old('reset_month', $furloughPolicy?->reset_month?->value)"
                                        />
                                    </div>
                                </div>
                            </div>

                            <x-form.form-select
                                :id="'sStatusSelect'"
                                :label="__('general.common.status')"
                                :data-values="$statuses"
                                :name="'status'"
                                :select-value-attribute="'value'"
                                :select-value-label="'label'"
                                :placeholder="__('general.common.status')"
                                :selected="old('status', $furloughPolicy?->status->value)"
                            />
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
            document.addEventListener('DOMContentLoaded', function () {
                const fieldResetType = document.getElementById('sResetTypeSelect');
                const fieldResetMonth = document.getElementById('reset_month');

                function updateResetMonthVisibility() {
                    if (!fieldResetType || !fieldResetMonth) return;
                    const monthlyValue = '1'; 
                    const wrap = fieldResetMonth.closest('.col-md-6') || fieldResetMonth.parentElement;

                    if (String(fieldResetType.value) === monthlyValue) {
                        if (wrap) wrap.style.display = '';
                        if (fieldResetMonth && fieldResetMonth.tomselect) {
                            try { fieldResetMonth.tomselect.enable(); } catch (e) {}
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

                const furloughTemplates = @json($furloughPolicyTemplates);
                
                const templateSelect = document.getElementById('sFurloughPolicyTemplateSelect');
                const fieldAccrual = document.getElementById('accrual_per_month');
                const fieldMaxDays = document.getElementById('max_days');
                const fieldIsPaid = document.getElementById('sIsPaidSelect');
                const fieldCarryForward = document.getElementById('sCarryForwardSelect');

                const manualFields = [fieldAccrual, fieldMaxDays, fieldIsPaid, fieldCarryForward];
                let isAutoFilling = false;

                function updateSelectUI(element, value) {
                    if (element.tomselect) {
                        element.tomselect.setValue(value, true);
                    } else {
                        element.value = value;
                    }
                }

                // Case 1
                if (templateSelect) {
                    templateSelect.addEventListener('change', function() {
                        if (isAutoFilling) return;

                        const selectedId = this.value;
                        if (selectedId) {
                            const template = furloughTemplates.find(t => t.id == selectedId);
                            if (template) {
                                isAutoFilling = true;
                                
                                fieldAccrual.value = template.accrual_per_month;
                                fieldMaxDays.value = template.max_days;
                                
                                updateSelectUI(fieldIsPaid, template.is_paid);
                                updateSelectUI(fieldCarryForward, template.carry_forward);

                                manualFields.forEach(f => f.dispatchEvent(new Event('change')));
                                
                                isAutoFilling = false;
                            }
                        }
                    });
                }

                // Case 2 and case 3
                manualFields.forEach(field => {
                    const events = ['input', 'change'];
                    events.forEach(evt => {
                        field.addEventListener(evt, function() {
                            if (isAutoFilling) return;

                            const currentTemplateId = templateSelect.value;
                            if (!currentTemplateId) return;

                            const template = furloughTemplates.find(t => t.id == currentTemplateId);
                            if (template) {
                                const isDifferent = 
                                    fieldAccrual.value != template.accrual_per_month ||
                                    fieldMaxDays.value != template.max_days ||
                                    fieldIsPaid.value != template.is_paid ||
                                    fieldCarryForward.value != template.carry_forward;

                                if (isDifferent) {
                                    isAutoFilling = true;
                                    updateSelectUI(templateSelect, "");
                                    isAutoFilling = false;
                                }
                            }
                        });
                    });
                });
            });
        </script>
        
    </x-slot:footerFiles>
</x-base-layout>
