<div class="form-group col-md-12">
    @if(!empty($label))
        <label for="{{ $id ?? $name }}">
            {{ $label }}
            @if($isRequired) <strong class="text-danger">*</strong> @endif
        </label>
    @endif

    <input
        type="text"
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        class="form-control datepicker-default
            {{ $inputRounded ? 'input-rounded' : '' }}
            {{ $attributes->get('class') }}
            @error($oldName ?: $name) is-invalid @enderror"
        placeholder="{{ $placeholder }}"
        value="{{ old($oldName ?: $name, $value) }}"
        autocomplete="off"
        {{ $isRequired ? 'required' : '' }}
        {{ $attributes->except('class') }}
    >

    @error($oldName ?: $name)
        <span class="invalid-feedback">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

@push('scripts')
<script src="{{ asset('vendor/pickadate/picker.js') }}"></script>
<script src="{{ asset('vendor/pickadate/picker.date.js') }}"></script>
<script>
(function () {
    function initPickadate() {
        if (typeof $ === 'undefined' || !$.fn.pickadate) return;

        $('.datepicker-default').each(function () {
            const $input = $(this);

            if ($input.data('pickadate-initialized')) return;

            const picker = $input.pickadate({
                format: 'dd-mm-yyyy',
                formatSubmit: 'yyyy-mm-dd',
                selectMonths: true,
                selectYears: 100,
                disable: false,

                onSet: function (context) {
                    if (context.select) {
                        const date = this.get('select', 'dd-mm-yyyy');
                        $input.val(date);
                    }
                },

                onClose: function () {
                    $input.trigger('change');
                }
            }).pickadate('picker');

            if ($input.val()) {
                picker.set('select', $input.val(), { format: 'yyyy-mm-dd' });
            }

            $input.data('pickadate-initialized', true);
        });
    }

    document.addEventListener('DOMContentLoaded', initPickadate);
    document.addEventListener('initPickadate', initPickadate);
})();
</script>
@endpush
