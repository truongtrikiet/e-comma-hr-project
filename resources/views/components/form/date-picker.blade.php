<div class="form-group col-md-6">
    @if(!empty($label))
        <label for="{{ $id ?? $name }}">{{ $label }}@if($isRequired) <strong class="text-danger">*</strong> @endif</label>
    @endif
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        class="form-control datepicker-default {{ $inputRounded ? 'input-rounded' : '' }} {{ $attributes->get('class') }} @error($oldName ?: $name) is-invalid @enderror"
        placeholder="{{ $placeholder }}"
        value="{{ old($oldName ?: $name, $value) }}"
        {{ $attributes->except('class') }}
        autocomplete="off"
    >
    @error($oldName ?: $name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

@push('scripts')
    <script src="{{ asset('vendor/pickadate/picker.js') }}"></script>
    <script src="{{ asset('vendor/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('vendor/pickadate/picker.time.js') }}"></script>
    <script>
        (function () {
            function initPickadate() {
                if (typeof $ === 'undefined' || !$.fn.pickadate) return;

                $('.datepicker-default').each(function () {
                    var $el = $(this);
                    if ($el.data('pickadate-initialized')) return;
                    try {
                        $el.pickadate({
                            format: 'yyyy-mm-dd',
                            selectMonths: true,
                            selectYears: true,
                            timePicker: true
                        });
                        $el.data('pickadate-initialized', true);
                    } catch (e) {
                        console.warn('pickadate init failed', e);
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', initPickadate);
            document.addEventListener('initPickadate', initPickadate);
        })();
    </script>
@endpush
