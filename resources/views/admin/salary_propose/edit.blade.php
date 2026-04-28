<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.salary_propose_management.edit_salary_propose') }}
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
            __('general.menu.salary_propose_management.title') => route('admin.salary-propose.index'),
            __('general.menu.salary_propose_management.edit_salary_propose') => '',
        ]"
    />

    <x-form.form-layout
        :form-id="'general-settings'"
        :form-url="route('admin.salary-propose.update', $salaryPropose->id)"
        :form-method="'PUT'"
        :card-title="__('general.menu.salary_propose_management.edit_salary_propose')"
        :custom-col="'col-lg-12'"
    >
        <div class="row">
            <div class="col-md-6">

                @if(auth()->user() && auth()->user()->roles()->whereIn('name', [\App\Acl\Acl::ROLE_ADMIN, \App\Acl\Acl::ROLE_HR])->exists())
                <x-form.form-select
                    :id="'sUser'"
                    :label="__('general.common.user')"
                    :data-values="$users"
                    :select-value-attribute="'id'"
                    :select-value-label="'name'"
                    :name="'user_id'"
                    :multiple="false"
                    :placeholder="__('general.common.user')"
                    :isRequired="true"
                    :values="$salaryPropose->user_id"
                />
                @else
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                @endif

                <x-form.form-input
                    :id="'proposed_gross_amount_formatted'"
                    :label="__('general.common.gross_amount')"
                    :name="'proposed_gross_amount_formatted'"
                    :placeholder="__('general.common.gross_amount')"
                    :isRequired="true"
                    :type="'text'"
                    :value="old('proposed_gross_amount_formatted', isset($salaryPropose->proposed_gross_amount) ? number_format($salaryPropose->proposed_gross_amount, 0, '.', ',') : '')"
                />
                <input type="hidden" name="proposed_gross_amount" id="proposed_gross_amount" value="{{ old('proposed_gross_amount', $salaryPropose->proposed_gross_amount ?? '') }}">

                <x-form.form-textarea
                    :id="'reason'"
                    :label="__('general.common.reason')"
                    :name="'reasons'"
                    :placeholder="__('general.common.reason')"
                    :isRequired="false"
                    :value="$salaryPropose->reason ?? ''"
                />

                <x-form.form-input
                    :id="'effective_date'"
                    :label="__('general.common.effective_date')"
                    :name="'effective_date'"
                    :placeholder="__('general.common.effective_date')"
                    :isRequired="true"
                    :type="'date'"
                    :value="optional($salaryPropose->effective_date)->format('Y-m-d')"
                />

                <x-form.form-input
                    :id="'ends_at'"
                    :label="__('general.common.ends_at')"
                    :name="'ends_at'"
                    :placeholder="__('general.common.ends_at')"
                    :isRequired="true"
                    :type="'date'"
                    :value="optional($salaryPropose->ends_at)->format('Y-m-d')"
                />
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
            (function () {
                function formatNumberWithCommas(value, decimals) {
                    if (value === '' || value === null || value === undefined) return '';
                    const num = Number(value);
                    if (Number.isNaN(num)) return '';
                    if (typeof decimals === 'number') {
                        const fixed = num.toFixed(decimals);
                        const parts = fixed.split('.');
                        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                        return parts.join('.');
                    }
                    const parts = value.toString().split('.');
                    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    return parts.join('.');
                }

                function normalizeInputValue(value) {
                    if (value === null || value === undefined) return '';
                    return value.toString().replace(/,/g, '').replace(/[^0-9.]/g, '');
                }

                function roundTo(value, decimals) {
                    const num = Number(value) || 0;
                    return Number(num.toFixed(decimals)).toString();
                }

                function setFormattedById(id, hiddenId, value, displayDecimals) {
                    const el = document.getElementById(id);
                    const hidden = document.getElementById(hiddenId);
                    if (!el || !hidden) return;
                    hidden.value = value === '' || value === null ? '' : value;
                    el.value = value === '' || value === null ? '' : formatNumberWithCommas(value, displayDecimals);
                }

                function computeAndUpdate() {
                    const grossRaw = normalizeInputValue(document.getElementById('proposed_gross_amount').value) || '0';
                    const percentRaw = normalizeInputValue(document.getElementById('proposed_tax_percent').value) || '0';
                    const gross = Number(grossRaw) || 0;
                    const percent = Number(percentRaw) || 0;

                    const tax = (gross * percent) / 100;
                    const taxRounded = roundTo(tax, 4);
                    const net = gross - Number(taxRounded);
                    const netRounded = roundTo(net, 4);

                    setFormattedById('proposed_tax_amount_formatted', 'proposed_tax_amount', taxRounded, 0);
                    setFormattedById('proposed_net_amount_formatted', 'proposed_net_amount', netRounded, 0);
                }

                function wireFormattedInput(idFormatted, idHidden, hiddenDecimals, options = {}) {
                    const elFormatted = document.getElementById(idFormatted);
                    const elHidden = document.getElementById(idHidden);
                    if (!elFormatted || !elHidden) return;

                    const displayDecimals = options.displayDecimals !== undefined ? options.displayDecimals : hiddenDecimals;

                    if (elHidden.value) {
                        elFormatted.value = formatNumberWithCommas(elHidden.value, displayDecimals);
                    }

                    if (options.readOnly) {
                        elFormatted.readOnly = true;
                        return;
                    }

                    elFormatted.addEventListener('input', function () {
                        const raw = normalizeInputValue(this.value);
                        elHidden.value = raw;
                        if (idHidden === 'proposed_tax_percent') {
                            this.value = formatNumberWithCommas(raw, undefined);
                        } else {
                            this.value = formatNumberWithCommas(raw, displayDecimals);
                        }
                        computeAndUpdate();
                    });

                    elFormatted.addEventListener('blur', function () {
                        const raw = normalizeInputValue(this.value);
                        if (hiddenDecimals !== undefined) {
                            elHidden.value = roundTo(raw || 0, hiddenDecimals);
                            if (idHidden === 'proposed_tax_percent') {
                                const truncated = Math.trunc(Number(elHidden.value));
                                this.value = formatNumberWithCommas(truncated, 0);
                            } else {
                                this.value = formatNumberWithCommas(elHidden.value, displayDecimals);
                            }
                        } else {
                            elHidden.value = raw;
                            this.value = formatNumberWithCommas(raw, displayDecimals);
                        }
                        computeAndUpdate();
                    });
                }

                document.addEventListener('DOMContentLoaded', function () {
                    wireFormattedInput('proposed_gross_amount_formatted', 'proposed_gross_amount', 4, { displayDecimals: 0 });
                    wireFormattedInput('proposed_tax_percent_formatted', 'proposed_tax_percent', 2, { displayDecimals: 0 });
                    wireFormattedInput('proposed_tax_amount_formatted', 'proposed_tax_amount', 4, { readOnly: true, displayDecimals: 0 });
                    wireFormattedInput('proposed_net_amount_formatted', 'proposed_net_amount', 4, { readOnly: true, displayDecimals: 0 });

                    computeAndUpdate();
                });
            })();
        </script>
    </x-slot:footerFiles>
</x-base-layout>
