<div class="mb-3">
    @if(!empty($label))
        <label for="{{ $id ?? $name }}" class="form-label">
            {{ $label }}
        </label>
    @endif

    <textarea
        id="{{ $id ?? $name }}"
        name="{{ $name }}"
        rows="{{ $rows ?? 4 }}"
        {{ $attributes->merge(['class' => 'form-control js-ckeditor']) }}
    >{{ old($name, $value ?? '') }}</textarea>
</div>
