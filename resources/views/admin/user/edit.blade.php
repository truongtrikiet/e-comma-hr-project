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

        <link rel="stylesheet" type="text/css" href="{{asset('plugins/tomSelect/tom-select.default.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('plugins/filepond/filepond.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('plugins/filepond/FilePondPluginImagePreview.min.css')}}">

    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.user_management.user') => route('admin.user.index'),
            __('general.menu.user_management.edit_user') => '',
        ]"
    />

    <x-form.form-layout
        :form-id="'general-settings'"
        :form-url="route('admin.user.update', $user->id)"
        :form-method="'PUT'"
        :card-title="__('general.menu.user_management.edit_user')"
        :custom-col="'col-lg-12'"
        enctype="multipart/form-data"
    >
        <div class="row">
            <div class="col-lg-8">
                <div class="mb-3">
                    <h5 class="mb-2">{{ __('general.common.information') }}</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <x-form.form-input
                                :id="'sLastName'"
                                :label="__('general.common.last_name')"
                                :name="'last_name'"
                                :placeholder="__('general.common.last_name')"
                                :isRequired="true"
                                :value="$user->last_name"
                            />
                        </div>
                        <div class="col-md-6">
                            <x-form.form-input
                                :id="'sFirstName'"
                                :label="__('general.common.first_name')"
                                :name="'first_name'"
                                :placeholder="__('general.common.first_name')"
                                :isRequired="true"
                                :value="$user->first_name"
                            />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-form.form-input
                                :id="'sEmail'"
                                :label="__('general.common.email')"
                                :name="'email'"
                                :placeholder="__('general.common.email')"
                                :type="'email'"
                                :isRequired="true"
                                :value="$user->email"
                            />
                        </div>
                        <div class="col-md-6">
                            <x-form.form-input
                                :id="'sPhoneNumber'"
                                :label="__('general.common.phone_number')"
                                :name="'phone_number'"
                                :placeholder="__('general.common.phone_number')"
                                :type="'number'"
                                :isRequired="true"
                                :value="$user->phone_number"
                            />
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-6">
                            <x-form.form-select
                                :id="'sRolesSelect'"
                                :label="__('general.common.role')"
                                :name="'roles'"
                                :data-values="$roles"
                                :select-value-attribute="'id'"
                                :select-value-label="'name'"
                                :multiple="false"
                                :placeholder="__('general.common.role')"
                                :isRequired="true"
                                :selected="$user->roles->pluck('id')->first()"
                            />
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <h5 class="mb-2">{{ __('general.common.personal_information') }}</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <x-form.date-picker 
                                :id="'sDateOfBirth'"
                                :name="'birth'"
                                :label="__('general.common.birth')"
                                :placeholder="__('general.common.birth')"
                                :isRequired="false"
                                :type="'text'"
                                :value="isset($user->birth) ? $user->birth->format('Y-m-d') : null"
                            />
                        </div>
                        <div class="col-md-6">
                            <x-form.form-select
                                :id="'sGendersSelect'"
                                :label="__('general.common.gender')"
                                :name="'gender'"
                                :data-values="App\Enum\GenderEnum::options(true)"
                                :select-value-attribute="'id'"
                                :select-value-label="'label'"
                                :multiple="false"
                                :placeholder="__('general.common.gender')"
                                :isRequired="false"
                            />
                        </div>
                    </div>

                    <!-- <div class="row mt-2">
                        <div class="col-12">
                            <x-form.form-input
                                :id="'sAddress'"
                                :label="__('general.common.address')"
                                :name="'address'"
                                :placeholder="__('general.common.address')"
                                :isRequired="false"
                            />
                        </div>
                    </div> -->
                </div>

                <div class="mb-3">
                    <h5 class="mb-2">{{ __('general.common.security_information') }}</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <x-form.form-input
                                :id="'sPassword'"
                                :label="__('general.common.password')"
                                :name="'password'"
                                :placeholder="__('general.common.password')"
                                :type="'password'"
                                :isRequired="false"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="mb-3 text-center">
                    <h5 class="mb-2">{{ __('general.common.avatar') }}</h5>
                    <!-- <div class="mb-2">
                        <img id="avatarPreviewImg" src="{{ asset('images/default-avatar.png') }}" alt="avatar preview" class="img-fluid rounded-circle" style="max-width:180px; max-height:180px;" />
                    </div> -->
                    <x-form.form-upload 
                        :id="'sProfilePicture'"
                        :label="''"
                        :name="'profile_picture'"
                        :placeholder="__('general.common.choose')"
                        :isRequired="false"
                        accept="image/*"
                    />
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 text-end">
                <x-buttons.submit :label="__('general.common.confirm')" />
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

            document.addEventListener('click', () => { userInteracted = true; });

            const avatarEl = document.querySelector('#sProfilePicture') || document.querySelector('#sAvatar');
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
        </script>

    </x-slot:footerFiles>
</x-base-layout>
