<div class="form-group mb-4">
    <div class="{{ $multiple ? 'multiple-file-upload' : 'file-upload' }}">

        @if($label)
            <label for="{{ $id }}">
                {{ $label }}
                @if($isRequired) <strong class="text-danger">*</strong> @endif
            </label>
        @endif

        <input
            type="file"
            class="filepond {{ $multiple ? 'file-upload-multiple' : '' }}"
            id="{{ $id }}"
            data-upload-name="{{ $name }}"
            {{ $multiple ? 'multiple' : '' }}
            {{ $attributes }}
        >

        <input
            type="hidden"
            name="{{ $name }}"
            id="{{ $id }}_base64"
        >

        @error($multiple ? $name . '*' : $name)
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    if (typeof FilePond === 'undefined') return;

    document.querySelectorAll('.filepond').forEach(function (input) {

        const hiddenInput = document.getElementById(input.id + '_base64');
        if (!hiddenInput) return;

        const pond = FilePond.create(input, {
            allowMultiple: false,
            instantUpload: false,
            allowProcess: false,

            onaddfile: (error, fileItem) => {
                if (error) return;

                const file = fileItem.file;
                const reader = new FileReader();

                reader.onload = function (e) {
                    hiddenInput.value = JSON.stringify({
                        name: file.name,
                        type: file.type,
                        data: e.target.result
                    });
                };

                reader.readAsDataURL(file);
            },

            onremovefile: () => {
                hiddenInput.value = '';
            }
        });
    });
});
</script>
@endpush
