<div class="mb-3">
    @if($label)
        <label for="{{ $id ?? $name }}" class="form-label">{{ $label }}</label>
    @endif

    <textarea
        id="{{ $id ?? $name }}"
        name="{{ $name }}"
        class="form-control summernote"
        data-height="{{ $height }}"
        placeholder="{{ $placeholder ?? '' }}"
    >{{ old($name, $value) }}</textarea>
</div>

@push('footerScripts')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
@endpush
