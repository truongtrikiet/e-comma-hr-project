<div class="basic-form">
    <div class="form-group col-md-12">
        @if($label)
            <label for="{{ $id }}">{{ $label }}@if($isRequired) <strong class="text-danger">*</strong> @endif</label>
        @endif

        <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}" class="form-control @error($oldName ?: $name) is-invalid @enderror input-rounded"
               placeholder="{{ $placeholder }}" value="{{ old($oldName ?: $name, $value) }}" {{ $attributes }}>

        @error($oldName ?: $name)
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
