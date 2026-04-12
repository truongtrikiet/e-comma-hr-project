<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.contract_management.contract') }}
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
            __('general.menu.contract_management.contract') => route('admin.contract.index'),
            __('general.menu.contract_management.edit_contract') => '',
        ]"
    />

    <x-form.form-layout
        :form-id="'general-settings'"
        :form-url="route('admin.contract.update', $contract->code)"
        :form-method="'PUT'"
        :card-title="__('general.menu.contract_management.edit_contract')"
        :custom-col="'col-lg-12'"
    >
        <div class="row">
            <div class="col-lg-12">
                <div class="mb-3">
                    <h5 class="mb-2">{{ __('general.common.information') }}</h5>
                    <div class="row">
                        <div class="col-md-8">
                            <x-form.form-input
                                :id="'code'"
                                :label="__('general.common.contract_code')"
                                :name="'code'"
                                :type="'text'"
                                :placeholder="__('general.common.contract_code')"
                                :isRequired="false"
                                :value="old('code', $contract->code)"
                                :readonly="true"
                            />

                            <x-form.form-select
                                :id="'sUser'"
                                :label="__('general.common.user')"
                                :data-values="$users"
                                :select-value-attribute="'id'"
                                :select-value-label="'name'"
                                :name="'user_id'"
                                :multiple="false"
                                :placeholder="__('general.common.user')"
                                :isRequired="true"
                                :selected="$contract->user_id"
                            />

                            <x-form.form-select
                                :id="'sContractType'"
                                :label="__('general.common.contract_type')"
                                :data-values="$contractTypes"
                                :select-value-attribute="'id'"
                                :select-value-label="'name'"
                                :name="'contract_type_id'"
                                :multiple="false"
                                :placeholder="__('general.common.contract_type')"
                                :isRequired="true"
                                :selected="$contract->contract_type_id"
                            />
                        </div>

                        <div class="col-md-12">
                             <div class="editor-paper-wrapper mb-5 mt-3">
                                <label>{{ __('general.common.content') }}</label>
                                <div id="editor"></div>
                                <textarea id="content" name="content" hidden></textarea>
                            </div>
                        </div>

                        @foreach($contractTypes as $item)
                            <input type="hidden" id="contractTypeContent{{ $item->id }}" value="{!! e($item->content) !!}">
                        @endforeach

                        <div class="col-md-12 mt-4">
                            <h5 class="mb-3">{{ __('general.common.contract_attributes') }}</h5>
                            <div id="contract-attributes-wrapper"></div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <x-form.form-input
                                :id="'signed_at'"
                                :label="__('general.common.signed_at')"
                                :name="'signed_at'"
                                :type="'date'"
                                :placeholder="__('general.common.signed_at')"
                                :isRequired="false"
                                :value="old('signed_at', optional($contract->signed_at)->format('Y-m-d'))"
                            />
                        </div>
                        <div class="col-md-6">
                            <x-form.form-input
                                :id="'expired_at'"
                                :label="__('general.common.expired_at')"
                                :name="'expired_at'"
                                :type="'date'"
                                :placeholder="__('general.common.expired_at')"
                                :isRequired="false"
                                :value="old('expired_at', optional($contract->expired_at)->format('Y-m-d'))"
                            />
                        </div>
                    </div>
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
        <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/super-build/ckeditor.js"></script>
        <script>
            window.contractAttributeValues = @json($contractAttributeValues ?? []);
            window.contractTypeAttributesMap = @json($contractTypeAttributesMap ?? []);
            window.oldAttributes = @json(old('attributes', []));
        </script>
        <script>
            const EditorClass = window.ClassicEditor || (window.CKEDITOR && window.CKEDITOR.ClassicEditor);
            if (!EditorClass) {
                console.error('CKEditor build not found. Make sure the CDN script loaded.');
            } else {
                EditorClass
                .create(document.getElementById("editor"), {
                ckfinder: {
                    uploadUrl: '{{ route('admin.editor_upload', ['_token' => csrf_token()]) }}',
                    options: { resourceType: 'Images' }
                },
                toolbar: {
                    items: [
                        'exportPDF','exportWord', '|',
                        'findAndReplace', 'selectAll', '|',
                        'heading', '|',
                        'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                        'bulletedList', 'numberedList', 'todoList', '|',
                        'outdent', 'indent', '|',
                        'undo', 'redo',
                        '-',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                        'alignment', '|',
                        'link', 'uploadImage', 'blockQuote', 'insertTable', '|',
                        'specialCharacters', 'horizontalLine', 'pageBreak',
                    ],
                    shouldNotGroupWhenFull: true
                },
                placeholder: '{{ __('general.common.content') }}',
                htmlSupport: { allow: [ { name: /.*/, attributes: true, classes: true, styles: true } ] },
                removePlugins: [ 'AIAssistant','CKBox','EasyImage','MultiLevelList','RealTimeCollaborativeComments','RealTimeCollaborativeTrackChanges','RealTimeCollaborativeRevisionHistory','PresenceList','Comments','TrackChanges','TrackChangesData','RevisionHistory','Pagination','WProofreader','MathType','SlashCommand','Template','DocumentOutline','FormatPainter','TableOfContents','PasteFromOfficeEnhanced','CaseChange' ]
            })
                .then(editor => {
                editor.model.document.on('change:data', () => {
                    const textarea = document.getElementById('content');
                    if (textarea) textarea.value = editor.getData();
                });

                const contractTypeSelect = document.getElementById('sContractType');
                if (contractTypeSelect) {
                    contractTypeSelect.addEventListener('change', function () {
                        const id = this.value;
                        const contentEl = document.getElementById('contractTypeContent' + id);
                        const content = contentEl ? contentEl.value : '';
                        try { editor.setData(content || ''); } catch (e) { console.warn(e); }
                    });
                    if (contractTypeSelect.value) {
                        const evt = new Event('change');
                        contractTypeSelect.dispatchEvent(evt);
                    }
                }
                })
                .catch(error => console.error('CKEditor init error', error));
            }

            // attribute dynamic
            document.addEventListener('DOMContentLoaded', function () {
                const contractTypeSelect = document.getElementById('sContractType');
                const wrapper = document.getElementById('contract-attributes-wrapper');

                if (!contractTypeSelect || !wrapper || !window.contractTypeAttributesMap) {
                    return;
                }

                function renderAttributes(contractTypeId) {
                    wrapper.innerHTML = '';

                    const attributes = window.contractTypeAttributesMap[contractTypeId] || [];

                    attributes.forEach((attr, index) => {
                        const value =
                            window.oldAttributes?.[attr.id]?.value
                            ?? window.contractAttributeValues?.[attr.id]
                            ?? '';

                        console.log('contract-attr-debug', { id: attr.id, name: attr.name, key: attr.key, value, oldAttr: window.oldAttributes?.[attr.id], savedValue: window.contractAttributeValues?.[attr.id] });

                        const row = document.createElement('div');
                        row.className = 'row align-items-center mb-3';

                        row.innerHTML = `
                            <div class="col-md-3">
                                <input type="text" class="form-control" value="${attr.name}" readonly>
                            </div>

                            <div class="col-md-4">
                                <input type="text" class="form-control text-muted" value="${attr.key}" readonly>
                            </div>

                            <div class="col-md-5">
                                <input type="hidden"
                                    name="attributes[${attr.id}][contract_type_attribute_id]"
                                    value="${attr.id}">

                                <input type="text"
                                    class="form-control"
                                    name="attributes[${attr.id}][value]"
                                    value="${value}">
                            </div>
                        `;

                        wrapper.appendChild(row);
                    });
                }

                contractTypeSelect.addEventListener('change', function () {
                    renderAttributes(this.value);
                });

                if (contractTypeSelect.value) {
                    renderAttributes(contractTypeSelect.value);
                }
            });
        </script>
    </x-slot:footerFiles>
</x-base-layout>
