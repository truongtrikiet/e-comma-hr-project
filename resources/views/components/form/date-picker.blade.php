<div class="form-group col-md-6">
    @if(!empty($label))
        <label for="{{ $id ?? $name }}">{{ $label }}@if($isRequired) <strong class="text-danger">*</strong> @endif</label>
    @endif
    <input
        type="{{ $type ?? 'text' }}"
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        class="datepicker-default form-control @error($oldName ?: $name) is-invalid @enderror input-rounded"
        placeholder="{{ $placeholder }}"
        value="{{ old($oldName ?: $name, $value) }}"
        {{ $attributes }}
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
<!-- Pickadate init (targets .datepicker-default) -->
<script src="{{ asset('js/plugins-init/pickadate-init.js') }}"></script>
<!-- <script>
    $(function(){
        console.log('datepicker');
        $('#{{ $id ?? $name }}').pickadate({
            format: 'yyyy-mm-dd',
            selectMonths: true,
            selectYears: true
        });
    });
</script> -->
@endpush
