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
            class="filepond-reusable {{ $multiple ? 'file-upload-multiple' : '' }}"
            id="{{ $id }}"
            name="{{ $name }}"
            data-value="{{ $value ?? '' }}"
            data-preview-id="{{ $previewId ?? '' }}"
            {{ $multiple ? 'multiple' : '' }}
            {{ $attributes }}
        >

        @error($multiple ? $name . '*' : $name)
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
</div>

@pushOnce('scripts')
<script>
    if (typeof FilePond !== 'undefined') {
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginImageExifOrientation,
            FilePondPluginFileValidateSize,
            FilePondPluginImageTransform,
            FilePondPluginFileEncode,
            FilePondPluginFileValidateType
        );
    }

    document.addEventListener('DOMContentLoaded', () => {
        let userInteracted = false;
        document.addEventListener('click', () => { userInteracted = true; });

        document.querySelectorAll('.filepond-reusable').forEach(inputEl => {
            const existingUrl = inputEl.getAttribute('data-value');
            const previewImgId = inputEl.getAttribute('data-preview-id');
            const isMultiple = inputEl.hasAttribute('multiple');
            
            let preloadedFiles = [];
            if (existingUrl) {
                const corsSafeUrl = existingUrl.replace('/storage/', '/cors-image/');

                preloadedFiles = [{
                    source: corsSafeUrl,
                    options: { type: 'local' }
                }];
            }

            const pond = FilePond.create(inputEl, {
                allowMultiple: isMultiple,
                acceptedFileTypes: ['image/*'],
                fileValidateTypeLabelExpectedTypes: 'phải là hình ảnh',
                labelFileTypeNotAllowed: 'sai định dạng',
                maxFileSize: '5MB',
                labelMaxFileSizeExceeded: 'Tệp quá lớn',
                labelMaxFileSize: 'Kích thước ảnh tối đa 5MB',
                stylePanelLayout: 'compact',
                labelIdle: 'Kéo & thả hoặc <span class="filepond--label-action">chọn từ thiết bị</span>',
                files: preloadedFiles,
                server: {
                    process: '/laravel-filepond/process',
                    revert: '/laravel-filepond/revert',
                    restore: '/laravel-filepond/restore/',
                    load: (source, load, error, progress, abort, headers) => {
                        if (source.startsWith('http')) {
                            const request = new XMLHttpRequest();
                            request.open('GET', source, true);
                            request.responseType = 'blob';

                            request.onload = function() {
                                if (request.status >= 200 && request.status < 300) {
                                    load(request.response);
                                } else {
                                    console.error('Lỗi HTTP:', request.status);
                                    error('Lỗi tải ảnh');
                                }
                            };

                            request.onerror = function() {
                                console.error('Lỗi mạng hoặc CORS');
                                error('Lỗi kết nối');
                            };

                            request.withCredentials = false; 
                            request.send();

                            return {
                                abort: () => {
                                    request.abort();
                                    abort();
                                }
                            };
                        } else {
                            const request = new XMLHttpRequest();
                            request.open('GET', '/laravel-filepond/load/' + source, true);
                            request.responseType = 'blob';
                            request.onload = () => load(request.response);
                            request.send();
                        }
                    }
                }
            });

            pond.on('addfile', (error, fileItem) => {
                if (error) return;

                if (fileItem.origin === 1 && previewImgId) {
                    const previewImg = document.getElementById(previewImgId);
                    if (previewImg && fileItem.file) {
                        if (previewImg.src.startsWith('blob:')) {
                            URL.revokeObjectURL(previewImg.src);
                        }
                        previewImg.src = URL.createObjectURL(fileItem.file);
                    }
                }

                if (userInteracted && fileItem.origin === 1 && !fileItem.file.type.startsWith('image/')) {
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi định dạng file!',
                            text: 'Chỉ chấp nhận file hình ảnh.',
                            confirmButtonText: 'Đã hiểu'
                        });
                    }
                    setTimeout(() => pond.removeFile(fileItem.id), 100);
                }
            });

            pond.on('removefile', () => {
                if (previewImgId) {
                    const previewImg = document.getElementById(previewImgId);
                    if (previewImg) {
                        if (previewImg.src.startsWith('blob:')) {
                            URL.revokeObjectURL(previewImg.src);
                        }

                        previewImg.src = "{{ asset('images/default-avatar.png') }}"; 
                    }
                }
            });

            pond.on('error', (err, file, status) => {
                if (userInteracted && status === 'file-type-not-allowed' && window.Swal) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Định dạng file không hợp lệ!',
                        text: 'Vui lòng chọn file hình ảnh.',
                        confirmButtonText: 'Đã hiểu'
                    });
                }
            });
        });
    });
</script>
@endpushOnce
