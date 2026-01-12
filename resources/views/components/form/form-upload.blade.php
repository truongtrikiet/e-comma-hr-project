<div class="form-group mb-4">
    <div class=" {{ $multiple ? 'multiple-file-upload' : 'file-upload' }}">
        @if($label)
        <label for="{{ $id }}">{{ $label }}@if($isRequired) <strong class="text-danger">*</strong> @endif</label>
        @endif
        <input type="file" class="filepond @if($multiple) file-upload-multiple @endif"
               id="{{ $id }}"
               name="{{ $name }}{{ $multiple ? '[]' : '' }}"
                {{ $multiple ? 'multiple' : 0 }}
                {{ $attributes }}>
        @error($multiple ? $name . '*' : $name)
        <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
</div>
