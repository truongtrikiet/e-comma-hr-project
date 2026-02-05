<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.user_management.user') }}
    </x-slot:pageTitle>
    <x-slot:headerFiles>
        <link href="{{ asset('vendor/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
        <!-- Clockpicker -->
        <link href="{{ asset('vendor/clockpicker/css/bootstrap-clockpicker.min.css') }}" rel="stylesheet">
        <!-- asColorpicker -->
        <link href="{{ asset('vendor/jquery-asColorPicker/css/asColorPicker.min.css') }}" rel="stylesheet">
        <!-- Material color picker -->
        <link href="{{ asset('vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
        <!-- Pick date -->
        <link rel="stylesheet" href="{{ asset('vendor/pickadate/themes/default.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/pickadate/themes/default.date.css') }}">
        <!-- Custom Stylesheet -->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.default.min.css">
        <link rel="stylesheet" type="text/css" href="{{asset('plugins/filepond/filepond.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('plugins/filepond/FilePondPluginImagePreview.min.css')}}">

    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.user_management.user') => route('admin.user.index'),
            __('general.menu.user_management.create_user') => '',
        ]"
    />

    <x-form.form-layout
        :form-id="'general-settings'"
        :form-url="route('admin.user.store')"
        :card-title="__('general.menu.user_management.create_user')"
        :custom-col="'col-lg-12'"
    >
        <div class="row">
            <div class="col-lg-8">
                <div class="mb-3">
                    <h5 class="mb-2">{{ __('general.common.information') }}</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <x-form.form-input
                                :id="'last_name'"
                                :name="'last_name'"
                                :label="__('general.common.last_name') "
                                :placeholder="__('general.common.last_name') "
                                :isRequired="true"
                            />
                        </div>
                        <div class="col-md-6">
                            <x-form.form-input
                                :id="'first_name'"
                                :name="'first_name'"
                                :label="__('general.common.first_name')"
                                :placeholder="__('general.common.first_name')"
                                :isRequired="true"
                            />
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-6">
                            <x-form.form-input
                                :id="'email'"
                                :name="'email'"
                                type="email"
                                :label="__('general.common.email')"
                                :placeholder="__('general.common.email')"
                                :isRequired="true"
                            />
                        </div>
                        <div class="col-md-6">
                            <x-form.form-input
                                :id="'phone_number'"
                                :name="'phone_number'"
                                type="text"
                                :label="__('general.common.phone_number')"
                                :placeholder="__('general.common.phone_number')"
                                :isRequired="true"
                            />
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <h5 class="mb-2">{{ __('general.common.work_information') }}</h5>
                    <div class="row">
                        @if (session('school_name') === config('subdomain.system_main'))
                            <div class="col-md-6">
                                <x-form.form-select
                                    :id="'sSchoolSelect'"
                                    :label="__('general.common.school')"
                                    :data-values="$schools"
                                    :select-value-attribute="'id'"
                                    :select-value-label="'name'"
                                    :name="'school_id'"
                                    :multiple="false"
                                    :placeholder="__('general.common.school')"
                                    :isRequired="false"
                                />
                            </div>
                        @else
                            <input type="hidden" name="school_id" value="{{ session('school_id') }}">
                        @endif

                        <div class="col-md-6">
                            <x-form.form-select
                                :id="'sSubjectSelect'"
                                :label="__('general.common.subject')"
                                :data-values="$subjects"
                                :select-value-attribute="'id'"
                                :select-value-label="'name'"
                                :name="'subject_id'"
                                :multiple="false"
                                :placeholder="__('general.common.subject')"
                                :isRequired="false"
                            />
                        </div>

                        <div class="col-md-6">
                            <x-form.form-select
                                :id="'sRoleSelect'"
                                :label="__('general.common.role')"
                                :data-values="$roles"
                                :select-value-attribute="'id'"
                                :select-value-label="'name'"
                                :name="'roles'"
                                :multiple="false"
                                :placeholder="__('general.common.role')"
                                :isRequired="true"
                            />
                        </div>
                        <div class="col-md-6">
                            <x-form.form-select
                                :id="'sStatusSelect'"
                                :label="__('general.common.status')"
                                :data-values="$statuses"
                                :name="'status'"
                                :select-value-attribute="'value'"
                                :select-value-label="'label'"
                                :placeholder="__('general.common.status')"
                                :isRequired="true"
                            />
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <h5 class="mb-2">{{ __('general.common.security_information') }}</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <x-form.form-input
                                :id="'password'"
                                :name="'password'"
                                :type="'password'"
                                :label="__('general.common.password')"
                                :placeholder="__('general.common.password')"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="mb-3 text-center">
                    <h5 class="mb-2">{{ __('general.common.avatar') }}</h5>
                    <x-form.form-upload
                        :id="'user_avatar'"
                        :name="'user_avatar'"
                        :label="__('general.common.avatar')"
                        accept="image/*"
                    />
                </div>
            </div>

            <div class="col-lg-8">
                <div class="mb-3">
                    <x-buttons.submit :label="__('general.common.complete')"/>
                </div>
            </div>
        </div>
    </x-form.form-layout>

    <x-slot:footerFiles>
        <script src="{{ asset('plugins/filepond/filepond.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/FilePondPluginImageExifOrientation.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/FilePondPluginImagePreview.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/FilePondPluginImageCrop.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/FilePondPluginImageResize.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/FilePondPluginImageTransform.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/filepondPluginFileValidateSize.min.js') }}"></script>
        <script src="{{ asset('js/custom/custom.js') }}"></script>
        <script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            FilePond.registerPlugin(
                FilePondPluginImagePreview,
                FilePondPluginImageExifOrientation,
                FilePondPluginFileValidateSize,
                FilePondPluginImageTransform,
                FilePondPluginFileEncode,
                FilePondPluginFileValidateType
            );

            document.addEventListener('DOMContentLoaded', () => {
            let userInteracted = false;

            document.addEventListener('click', () => { 
                userInteracted = true; 
            });

            const avatarEl = document.querySelector('#sProfilePicture');
                if (avatarEl) {
                    const userAvatar = FilePond.create(avatarEl, {
                    acceptedFileTypes: ['image/*'],
                    fileValidateTypeLabelExpectedTypes: 'phải là hình ảnh',
                    labelFileTypeNotAllowed: 'sai định dạng',
                    maxFileSize: '5MB',
                    labelMaxFileSizeExceeded: 'Tệp quá lớn',
                    labelMaxFileSize: 'Kích thước ảnh tối đa 5MB',
                    stylePanelLayout: 'compact circle',
                    labelIdle: 'Kéo & thả hoặc <span class=\"filepond--label-action\">chọn từ thiết bị</span>',
                    server: {
                        process: '/laravel-filepond/process',
                        revert: '/laravel-filepond/revert',
                        restore: '/laravel-filepond/restore/',
                        load: '/laravel-filepond/load/',
                    }
                    });

                    userAvatar.on('addfile', (error, file) => {
                    const previewImg = document.querySelector('#avatarPreviewImg');
                    if (file && file.file && previewImg) {
                        previewImg.src = URL.createObjectURL(file.file);
                    }

                    if (userInteracted && file && !file.file.type.startsWith('image/')) {
                        if (window.Swal) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi định dạng file!',
                            text: 'Chỉ chấp nhận file hình ảnh (JPG, PNG, GIF, etc.).',
                            confirmButtonText: 'Đã hiểu'
                        });
                        }
                        setTimeout(() => userAvatar.removeFile(file), 100);
                    }
                    });

                    userAvatar.on('removefile', () => {
                        const previewImg = document.querySelector('#avatarPreviewImg');
                        if (previewImg) {
                            previewImg.src = "{{ asset('images/default-avatar.png') }}";
                        }
                    });

                    userAvatar.on('error', (err, file, status) => {
                    if (userInteracted && status === 'file-type-not-allowed' && window.Swal) {
                        Swal.fire({
                        icon: 'warning',
                        title: 'Định dạng file không hợp lệ!',
                        text: 'Vui lòng chọn file hình ảnh.',
                        confirmButtonText: 'Đã hiểu'
                        });
                    }
                    });
                }
            });

            document.addEventListener('DOMContentLoaded', function () {
                if (window.jQuery && jQuery().select2) {
                    $('[data-enhanced="true"]').each(function () {
                        const $el = $(this);
                        $el.select2({
                            width: '100%',
                            placeholder: function () {
                                return $el.find('option[value=""]').text() || 'Choose';
                            },
                            allowClear: true,
                            closeOnSelect: !$el.data('multiple')
                        });
                    });
                }
            });
        </script>

    </x-slot:footerFiles>
</x-base-layout>
