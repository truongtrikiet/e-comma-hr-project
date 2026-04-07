<div class="modal fade" id="contractStatusModal" tabindex="-1" role="dialog" aria-labelledby="contractStatusModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contractStatusModalLabel" style="color: #3b3f5c;">
                    {{ __('general.menu.contract_management.edit_contract') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <form>
                <div class="modal-body pb-2">
                    <div class="form-group mb-4">
                        <label for="status">{{ __('general.common.status') }} <strong class="text-danger">*</strong>
                        </label>
                        <select
                            id="status"
                            class="form-select w-100 @error('status') is-invalid @enderror"
                            placeholder="{{ __('general.common.status') }}"
                            wire:model='status'
                        >
                            <option value="">{{ __('general.common.choose') }}</option>
                            @foreach ($contractStatuses as $item)
                                <option value="{{ $item['value'] }}">{{ $item['label'] }}</option>
                            @endforeach
                        </select>
                        @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">
                        {{ __('general.common.cancel') }}
                    </button>

                    <button
                        type="button"
                        class="btn btn-primary btn-submit-modal"
                        wire:click='update'
                    >
                        {{ __('general.common.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('footerFiles')
    <script>
        $('#contractStatusModal').on('show.bs.modal', function (e) {
            $('#contractStatusModal .modal-body .alert').remove();
            $('#contractStatusModal #status').val($('#contract-status').val());

            let invalidFeedbacks = document.querySelectorAll('#contractStatusModal .invalid-feedback');
            invalidFeedbacks.forEach(item => {
                item.remove();
            });

            let formSelects = document.querySelectorAll('#contractStatusModal .form-select');
            formSelects.forEach(item => {
                item.classList.remove('is-invalid');
            });
        });
    </script>
@endpush


