<div class="mb-3">
    @if($label)
        <label for="{{ $id ?? $name }}" class="form-label">{{ $label }}</label>
    @endif

    <textarea
        id="{{ $id ?? $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        {{ $attributes->merge(['class' => 'form-control custom-ckeditor']) }}>{{ old($name, $value) }}</textarea>
</div>
