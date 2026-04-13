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

<div>
    <form>
        <div class="row d-flex align-items-end" wire:ignore>
            <div class="form-group mb-4 col-md-11">
                <label for="selectedAttributes">{{ __('general.common.contract_attribute') }}</label>
                <select
                    id="selectedAttributes"
                    class="form-control form-control-lg"
                    multiple
                    wire:model="selectedAttributes"
                >
                    @foreach($contractAttributes as $item)
                        <option wire:key='{{ $item->id }}'
                            value="{{ $item->id }}"
                            selected
                        >
                            {{ $item->key }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 mb-4">
                <button
                    class="btn btn-primary"
                    style="width: 100%;height: 45px;"
                    data-bs-toggle="modal"
                    data-bs-target="#contractAttributeModal"
                    type="button"
                >
                    <i data-feather="plus"></i>
                </button>
            </div>
        </div>

        <div id="group-attributes">
        @foreach ($selectedAttributes as $item)
            <div class="row d-flex align-items-end" wire:key='{{ $item['id'] }}'>
                <div class="form-group mb-4 col-md-3">
                    <label>{{ __('general.common.contract_attribute_key') }}</label>
                    <input
                        class="form-control flatpickr-input"
                        style="cursor: no-drop"
                        value="{{ $item['key'] }}"
                        readonly
                    />
                </div>

                <div class="form-group mb-4 col-md-5">
                    <label>{{ __('general.common.contract_attribute_name') }}</label>
                    <input
                        class="form-control flatpickr-input"
                        style="cursor: no-drop"
                        value="{{ $item['name'] }}"
                        readonly
                    />
                </div>

                <div class="form-group mb-4 col-md-4">
                    <div class="input-group">
                        <input
                            id="kt_clipboard_{{ $item['id'] }}"
                            class="form-control flatpickr-input kt_clipboard"
                            style="cursor: no-drop"
                            value="{!! '&#123;&#123; $'.$item['key'].' &#125;&#125;' !!}"
                            readonly
                        />

                        <button class="btn btn-light-primary" data-clipboard-target="#kt_clipboard_{{ $item['id'] }}" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
        </div>

        <div class="form-group mb-4">
            <label for="name">{{ __('general.common.name') }} <strong class="text-danger">*</strong></label>
            <input
                id="name"
                class="form-control @error('name') is-invalid @enderror"
                placeholder="{{ __('general.common.name') }}"
                wire:model='name'
            />
            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="form-group mb-4">
            <label for="editor">{{ __('general.common.content') }} <strong class="text-danger">*</strong></label>
            <div wire:ignore>
                <livewire:admin.contract-type.partials.editor :initial-data="$content"/>
            </div>
            <input
                type="hidden"
                id="attribute-type-content"
                class="is-invalid"
                wire:model='content'
            />
            @error('content')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <button class="btn btn-primary" wire:click.prevent='update'>
            {{ __('general.common.complete') }}
        </button>

        @script
        <script>
            $(document).ready(function () {
                let tomSelectAttributes = new TomSelect("#selectedAttributes", {
                    placeholder: '{{ __('general.common.contract_attribute') }}',
                    render: {
                        no_results: function(data, escape) {
                            return '<div class="no-results">{{ __('general.common.no_results_found') }}</div>';
                        }
                    },
                });

                if(JSON.parse($('#contract_attributes').val())){
                    let values = JSON.parse($('#contract_attributes').val());
                    values.forEach(item => {
                        tomSelectAttributes.addItem(item.id);
                    });
                }

                tomSelectAttributes.on('change', function () {
                    processChange();
                });

                function debounce(func, timeout = 300){
                    let timer;
                    return (...args) => {
                        clearTimeout(timer);
                        timer = setTimeout(() => { func.apply(this, args); }, timeout);
                    };
                }

                const processChange = debounce(() => $wire.updateSelected(tomSelectAttributes.getValue()));

                const observer = new MutationObserver(function(mutationsList, observer) {
                    mutationsList.forEach(function(mutation) {
                        if (mutation.type === 'childList' && mutation.target === document.querySelector('#group-attributes')) {
                            handleClipboard();
                        }
                    });
                });

                function handleClipboard() {
                    let clipboards = document.querySelectorAll('.kt_clipboard');

                    clipboards.forEach(item => {
                        const target = item;
                        const button = target.nextElementSibling;

                        var clipboard = new ClipboardJS(button, {
                            target: target,
                            text: function() {
                                return target.value;
                            }
                        });

                        clipboard.on('success', function(e) {
                            const currentLabel = button.innerHTML;
                            if(button.innerHTML === '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg>'){
                                return;
                            }

                            button.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg>';

                            setTimeout(function(){
                                button.innerHTML = currentLabel;
                            }, 2000)
                        });
                    });
                }

                handleClipboard();

                observer.observe(document.querySelector('#group-attributes'), { childList: true });

                function reloadTomselectAttribute(){
                    let id = $('#contractAttributeModal #contract-attribute-id').val();
                    let key = $('#contractAttributeModal #contract-attribute-key').val();
                    if(id && key){
                        tomSelectAttributes.addOption({value: id, text: key});
                    }
                }

                $('#contractAttributeModal').on('hidden.bs.modal', function (e) {
                    reloadTomselectAttribute();
                });
            });
        </script>
        @endscript
    </form>

    <livewire:admin.contract-type.partials.create-contract-attribute-modal/>

    <input type="hidden" id="contract_attributes" value="{{ json_encode($selectedAttributes) }}">
</div>
