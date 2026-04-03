<div class="basic-form">
    <div class="form-group col-md-12">
        @if($label)
            <label for="{{ $id }}">{{ $label }}@if($isRequired) <strong class="text-danger">*</strong> @endif</label>
        @endif

        @php
            $displayValue = $value;
            if (($type ?? 'text') === 'time') {
                try {
                    if ($value instanceof \DateTimeInterface) {
                        $displayValue = $value->format('H:i');
                    } elseif (is_string($value) && $value !== '') {
                        $displayValue = \Carbon\Carbon::parse($value)->format('H:i');
                    } else {
                        $displayValue = '';
                    }
                } catch (\Throwable $e) {
                    $displayValue = '';
                }
            }
        @endphp

        <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}" class="form-control @error($oldName ?: $name) is-invalid @enderror input-default"
               placeholder="{{ $placeholder }}" value="{{ old($oldName ?: $name, $displayValue) }}" {{ $attributes }}>

        @error($oldName ?: $name)
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
