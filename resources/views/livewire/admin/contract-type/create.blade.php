<div>
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
        <div class="form-group mb-4">
            <div class="row d-flex align-items-end" wire:ignore>
                <div class="col-md-11">
                    <div class="form-group ">
                        <x-form.form-select-multiple
                            :id="'selectedAttributes'"
                            :label="__('general.common.contract_attribute')"
                            :data-values="$contractAttributes"
                            :select-value-attribute="'id'"
                            :select-value-label="'name'"
                            :name="'selectedAttributes'"
                            :selected="$selectedAttributes"
                            :placeholder="__('general.common.contract_attribute')"
                            :use-select2="false"
                            wire:model="selectedAttributes"
                        />
                    </div>
                </div>
                <div class="col-md-1 mb-3">
                    <button
                        class="btn btn-primary"
                        type="button"
                        onclick="openContractAttributeModal()"
                    >
                        <i data-feather="plus">+</i>
                    </button>
                </div>
            </div>
        </div>

        <div id="group-attributes">
        @foreach ($selectedAttributes as $item)
            <div class="row d-flex align-items-end" wire:key='{{ $item['id'] }}'>
                <div class="form-group mb-4 col-md-3">
                    <x-form.form-input
                        :id="'attribute_key_'.$item['id']"
                        :name="'attribute_key_'.$item['id']"
                        :label="__('general.common.contract_attribute_key')"
                        :value="$item['key']"
                        readonly
                    />
                </div>

                <div class="form-group mb-4 col-md-5">
                    <x-form.form-input
                        :id="'attribute_name_'.$item['id']"
                        :name="'attribute_name_'.$item['id']"
                        :label="__('general.common.contract_attribute_name')"
                        :value="$item['name']"
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
                            <i data-feather="plus">X</i>
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
                <livewire:admin.contract-type.partials.editor />
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

        <button class="btn btn-primary" wire:click.prevent='store'>
            {{ __('general.common.complete') }}
        </button>

        @script
        <script>
        (function () {
            let tomSelectAttributes = null;
            let clipboardInstances = new Map();
            let observer = null;

            function initTomSelect() {
                if (tomSelectAttributes) return;

                tomSelectAttributes = new TomSelect('#selectedAttributes', {
                    placeholder: '{{ __('general.common.contract_attribute') }}',
                    render: {
                        no_results() {
                            return '<div class="no-results">{{ __('general.common.no_results_found') }}</div>';
                        }
                    },
                });

                const debouncedChange = debounce(() => {
                    $wire.updateSelected(tomSelectAttributes.getValue());
                }, 300);

                tomSelectAttributes.on('change', debouncedChange);
            }

            function initClipboard(container = document) {
                container.querySelectorAll('.kt_clipboard').forEach(input => {
                    const button = input.nextElementSibling;
                    const key = input.id;

                    if (clipboardInstances.has(key)) return;

                    const clipboard = new ClipboardJS(button, {
                        text: () => input.value
                    });

                    clipboard.on('success', () => toggleCopiedIcon(button));
                    clipboardInstances.set(key, clipboard);
                });
            }

            function toggleCopiedIcon(button) {
                const original = button.innerHTML;
                const successIcon = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-check">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                `;

                if (button.dataset.copied) return;

                button.innerHTML = successIcon;
                button.dataset.copied = true;

                setTimeout(() => {
                    button.innerHTML = original;
                    delete button.dataset.copied;
                }, 2000);
            }

            function initObserver() {
                const target = document.getElementById('group-attributes');
                if (!target || observer) return;

                observer = new MutationObserver(debounce(mutations => {
                    mutations.forEach(m => {
                        if (m.addedNodes.length) {
                            initClipboard(target);
                        }
                    });
                }, 100));

                observer.observe(target, {
                    childList: true,
                    subtree: false
                });
            }

            function initModalHook() {
                $('#contractAttributeModal').on('hidden.bs.modal', function () {
                    const id = $('#contract-attribute-id').val();
                    const key = $('#contract-attribute-key').val();

                    if (id && key && tomSelectAttributes) {
                        tomSelectAttributes.addOption({ value: id, text: key });
                    }
                });

                var livewireGlobal = window.Livewire || window.livewire || null;
                if (livewireGlobal && typeof livewireGlobal.on === 'function') {
                    livewireGlobal.on('contractAttributeCreated', function(id, key) {
                        if (id && key && tomSelectAttributes) {
                            tomSelectAttributes.addOption({ value: id, text: key });
                            tomSelectAttributes.addItem(id);
                        }

                        const modalEl = document.getElementById('contractAttributeModal');
                        if (modalEl) {
                            if (window.bootstrap && bootstrap.Modal) {
                                try {
                                    var m = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                                    m.hide();
                                } catch (e) {
                                    console.warn('bootstrap hide failed', e);
                                }
                            } else if (window.$ && window.$.fn && window.$.fn.modal) {
                                $('#contractAttributeModal').modal('hide');
                            }
                        }

                        try {
                            Snackbar.show({
                                text: document.querySelector('#contractAttributeModal #notification-success')?.value || '{{ __('success.contract_attribute.store') }}',
                                textColor: '#ddf5f0',
                                backgroundColor: '#00ab55',
                                actionText: '{{ __('general.common.dismiss') }}',
                                actionTextColor: '#3b3f5c'
                            });
                        } catch (e) {
                            console.warn('Snackbar show failed', e);
                        }
                    });
                }
            }

            function debounce(fn, delay = 300) {
                let t;
                return (...args) => {
                    clearTimeout(t);
                    t = setTimeout(() => fn.apply(this, args), delay);
                };
            }

            document.addEventListener('livewire:load', function () {
                initTomSelect();
                initClipboard();
                initObserver();
                initModalHook();
            });

            window.openContractAttributeModal = function () {
                const modalEl = document.getElementById('contractAttributeModal');
                if (!modalEl) return;

                if (window.bootstrap?.Modal) {
                    new bootstrap.Modal(modalEl).show();
                } else if (window.$?.fn?.modal) {
                    $('#contractAttributeModal').modal('show');
                }
            };
        })();
        </script>
        @endscript
    </form>

    <livewire:admin.contract-type.partials.create-contract-attribute-modal />
</div>


