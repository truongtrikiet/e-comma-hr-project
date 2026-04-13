<div>
    <div class="modal fade" id="contractAttributeModal" tabindex="-1" role="dialog" aria-labelledby="contractAttributeModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contractAttributeModalLabel" style="color: #3b3f5c;">
                        {{ __('general.menu.contract_attribute_management.create_contract_attribute') }}
                    </h5>
                    <a href="#" role="button" class="btn btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close" onclick="event.preventDefault();">X</a>
                </div>

                <form>
                    <div class="modal-body pb-2">
                        <div id="container-success">
                            <input type="hidden" id="notification-success" value="{{ __('success.contract_attribute.store') }}">
                            <input wire:ignore type="hidden" id="contract-attribute-id">
                            <input wire:ignore type="hidden" id="contract-attribute-key">
                        </div>

                        <div class="form-group mb-4">
                            <label for="key">{{ __('general.common.key') }} <strong class="text-danger">*</strong>
                            </label>
                            <input
                                id="key"
                                class="form-control @error('key') is-invalid @enderror"
                                placeholder="{{ __('general.common.key') }}"
                                spellcheck="false"
                                wire:model='key'
                            >
                            @error('key')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="attribute_name">{{ __('general.common.name') }} <strong class="text-danger">*</strong>
                            </label>
                            <input
                                class="form-control @error('name') is-invalid @enderror"
                                id="attribute_name"
                                placeholder="{{ __('general.common.name') }}"
                                spellcheck="false"
                                wire:model='name'
                            >
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal" data-dismiss="modal">
                            {{ __('general.common.cancel') }}
                        </button>

                        <button type="button" class="btn btn-primary" wire:click.prevent="store">
                            {{ __('general.common.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @push('footerFiles')
            <script>
                $('#contractAttributeModal').on('show.bs.modal', function (e) {
                    $('#contractAttributeModal .modal-body .alert').remove();
                    $('#contractAttributeModal #key').val('');
                    $('#contractAttributeModal #attribute_name').val('');

                    let invalidFeedbacks = document.querySelectorAll('#contractAttributeModal .invalid-feedback');
                    invalidFeedbacks.forEach(item => {
                        item.remove();
                    });

                    let formControls = document.querySelectorAll('#contractAttributeModal .form-control');
                    formControls.forEach(item => {
                        item.classList.remove('is-invalid');
                    });
                });

                // document.addEventListener('livewire:init', () => {
                //     Livewire.on('contract-attribute-created', data => {

                //         $('#contract-attribute-id').val(data.id);
                //         $('#contract-attribute-key').val(data.key);

                //         const modalEl = document.getElementById('contractAttributeModal');
                //         if (window.bootstrap?.Modal) {
                //             bootstrap.Modal.getInstance(modalEl)?.hide();
                //         } else if (window.$?.fn?.modal) {
                //             $('#contractAttributeModal').modal('hide');
                //         }

                //         if (window.tomSelectAttributes) {
                //             tomSelectAttributes.addOption({
                //                 value: data.id,
                //                 text: data.key
                //             });
                //             tomSelectAttributes.addItem(data.id);
                //         }
                //     });
                // });
            </script>
        @endpush
    </div>
</div>
