@push('headerFiles')
    <style>
        .ts-wrapper.form-control-lg .ts-control {
            font-size: 15px;
        }

        .ts-dropdown {
            font-size: 15px;
        }
    </style>
@endpush

<form>
    <div class="row">
        <div class="form-group mb-4 col-md-6">
            <label for="objectType">{{ __('general.common.object_type') }} <strong class="text-danger">*</strong></label>
            <select
                id="objectType"
                class="form-select @error('objectType') is-invalid @enderror"
                wire:model='objectType'
                wire:change='handleChangeObjectType'
            >
                <option>{{ __('general.common.choose') }}</option>
                @foreach ($objectTypes as $item)
                <option wire:key='{{ $item['class'] }}' value="{{ $item['class'] }}">{{ $item['name'] }}</option>
                @endforeach
            </select>
            @error('objectType')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="form-group mb-4 col-md-6">
            <label for="object">
                {{ __('general.common.object') }}
                <strong class="text-danger">*</strong>
            </label>
            <div wire:ignore>
                <select
                    class="form-control form-control-lg"
                    id="object"
                    wire:model='object'
                >
                    <option value="">{{ __('general.common.choose') }}</option>
                </select>
            </div>
            @error('object')
            <input type="hidden" class="is-invalid">
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>

    <div class="form-group mb-4">
        <label for="contractType">
            {{ __('general.common.contract_type') }}
            <strong class="text-danger">*</strong>
        </label>
        <div wire:ignore>
            <select
                id="contractType"
                class="form-control form-control-lg"
                wire:model='contractType'
                wire:change='handleChangeContractType'
            >
                <option value="">{{ __('general.common.choose') }}</option>
                @foreach($contractTypes as $item)
                    <option
                        wire:key='{{ $item->id }}'
                        value="{{ $item->id }}"
                    >
                        {{ $item->name }}
                    </option>
                @endforeach
            </select>
        </div>
        @foreach($contractTypes as $item)
            <input type="hidden" id="contractTypeContent{{ $item->id }}" value="{{ $item->content }}">
        @endforeach
        @error('contractType')
            <input type="hidden" class="is-invalid">
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group mb-4">
        <label for="status">
            {{ __('general.common.status') }}
            <strong class="text-danger">*</strong>
        </label>
        <select
            id="status"
            class="form-select @error('status') is-invalid @enderror"
            wire:model='status'
        >
            <option value="">{{ __('general.common.choose') }}</option>
            @foreach($contractStatuses as $item)
                <option
                    wire:key='{{ $item->value }}'
                    value="{{ $item->value }}"
                >
                    {{ $item->label }}
                </option>
            @endforeach
        </select>
        @error('status')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <input
        type="hidden"
        id="selectedContractTypeContent"
        value="{{ $selectedContractTypeContent }}"
    >

    <div class="form-group mb-4">
        <label for="editor">{{ __('general.common.content') }}</label>
        <div wire:ignore>
            <livewire:admin.contract.partials.editor />
        </div>
    </div>

    <div id="group-attributes">
        @foreach ($selectedContractAttributes as $item)
            <div class="row d-flex align-items-end" wire:key='{{ $item['id'] }}'>
                <div class="form-group col-md-3">
                    <label>{{ __('general.common.field') }}</label>
                </div>

                <div class="form-group col-md-9">
                    <label for="{{ $item->id }}" style="overflow-wrap: anywhere;">
                        {{ $item->name }} <strong class="text-danger">*</strong>
                    </label>
                </div>
            </div>
            <div class="row d-flex align-items-start" wire:key='{{ $item['id'] }}'>
                <div class="form-group mb-4 col-md-3">
                    <input
                        class="form-control flatpickr-input"
                        style="cursor: no-drop"
                        value="{!! '&#123;&#123; $'.$item['key'].' &#125;&#125;' !!}"
                        readonly
                    />
                </div>

                <div class="form-group mb-4 col-md-9">
                    <input
                        id="{{ $item->id }}"
                        class="form-control @error('contractAttributes.'.$item['id'].'.value') is-invalid @enderror"
                        placeholder="{{ $item->name }}"
                        wire:key='{{ $item->id }}'
                        wire:model="contractAttributes.{{ $item['id'] }}.value"
                    />
                    @error('contractAttributes.'.$item['id'].'.value')
                        <span class="invalid-feedback" style="overflow-wrap: anywhere;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="form-group mb-4 col-md-6">
            <label for="signed_at">
                {{ __('general.common.signed_at') }}
                <strong class="text-danger">*</strong>
            </label>
            <input type="text"
                class="form-control date-input @error('signedAt') is-invalid @enderror"
                id="signed_at"
                placeholder="{{ __('general.common.signed_at') }}"
                wire:model='signedAt'
            >
            @error('signedAt')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group mb-4 col-md-6">
            <label for="expired_at">
                {{ __('general.common.expired_at') }}
                <strong class="text-danger">*</strong>
            </label>
            <input type="text"
                class="form-control date-input @error('expiredAt') is-invalid @enderror"
                id="expired_at"
                placeholder="{{ __('general.common.expired_at') }}"
                wire:model='expiredAt'
            >
            @error('expiredAt')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <button class="btn btn-primary" wire:click.prevent='store'>
        {{ __('general.common.complete') }}
    </button>

    <div class="modal fade" wire:ignore.self id="deleteItemModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{__('general.selected_item.delete')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{__('general.selected_item.delete_ask_confirm')}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">{{__('general.common.cancel')}}</button>
                    <button type="button" class="btn btn-primary" wire:click="destroyAppendixContract"> {{__('general.common.confirm')}}</button>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        $(document).ready(function () {
            const signedAt = flatpickr("#signed_at", {
                dateFormat: "Y-m-d",
                locale: "vn",
                onChange: function(selectedDates, dateStr, instance) {
                    let startDate = selectedDates[0];

                    expiredAt.set('minDate', startDate);

                    let endDate = expiredAt.selectedDates[0];
                    if (endDate && endDate < startDate) {
                        expiredAt.clear();
                    }
                }
            });

            const expiredAt = flatpickr("#expired_at", {
                dateFormat: "Y-m-d",
                locale: "vn",
                onChange: function(selectedDates, dateStr, instance) {
                    let endDate = selectedDates[0];

                    signedAt.set('maxDate', endDate);

                    let startDate = signedAt.selectedDates[0];
                    if (startDate && startDate > endDate) {
                        signedAt.clear();
                    }
                }
            });

            let objectSelect = new TomSelect("#object", {
                render: {
                    no_results: function(data, escape) {
                        return '<div class="no-results">{{ __('general.common.no_results_found') }}</div>';
                    }
                },
            });

            let contractTypeSelect = new TomSelect("#contractType", {
                render: {
                    no_results: function(data, escape) {
                        return '<div class="no-results">{{ __('general.common.no_results_found') }}</div>';
                    }
                },
            });

            $('#objectType').on('change', function () {
                objectSelect.clear();
                objectSelect.clearOptions();
                $wire.handleChangeObjectType()
                .then(objects => {
                    if(objects) {
                        objects.forEach(object => objectSelect.addOption({ value: object.id, text: object.name }));
                    }
                })
            });

            contractTypeSelect.on('change', function () {
                $wire.handleChangeContractType();
            });
        });
    </script>
    @endscript
</form>
