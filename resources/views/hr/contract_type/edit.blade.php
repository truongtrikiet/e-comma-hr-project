<x-hr.base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ __('general.menu.contract_type_management.contract_type') }}
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
        <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">

        <style>
            .editor-paper-wrapper {
                padding: 30px 0;
            }

            .editor-paper-wrapper .ck-editor {
                margin: 0 auto;
            }

            .editor-paper-wrapper .ck-editor__editable {
                min-height: 900px;
                padding: 60px 70px;
                background: #ffffff;
                box-shadow: 0 0 15px rgba(0,0,0,0.08);
                border: 1px solid #d1d5db;
                font-size: 15px;
                line-height: 1.8;
            }

            .ck.ck-toolbar {
                position: sticky;
                top: 0;
                z-index: 10;
            }
        </style>

    </x-slot:headerFiles>

    <!-- Breadcrumb -->
    <x-custom.breadcrumb
        :breadcrumb-items="[
            __('general.menu.contract_type_management.contract_type') => route('hr.contract_type.index'),
            __('general.menu.contract_type_management.edit_contract_type') => '',
        ]"
    />

    <x-form.form-layout
        :form-id="'general-settings'"
        :form-url="route('hr.contract_type.update', $contractType->id)"
        :form-method="'PUT'"
        :card-title="__('general.menu.contract_type_management.edit_contract_type')"
        :custom-col="'col-lg-12'"
    >
        <div class="row">
            <div class="col-lg-12">
                <div class="mb-3">
                    <h5 class="mb-4">{{ __('general.common.information') }}</h5>

                    @if (session('school_name') === config('subdomain.system_main'))
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
                            :selected="old('school_id', $contractType?->school_id)"
                        />
                    @else
                        <input type="hidden" name="school_id" value="{{ session('school_id') }}">
                    @endif

                    <x-form.form-select-multiple
                        :id="'contract_attributes'"
                        :name="'contract_attribute_ids'"
                        :label="__('general.common.contract_attribute')"
                        :placeholder="__('general.common.contract_attributes')"
                        :data-values="$contractAttributes->pluck('name', 'id')"
                        :isRequired="false"
                        :selected="old('contract_attribute_ids', $contractType->contractAttributes->pluck('id')->toArray())"
                    />

                    <div class="col-md-12 mt-4">
                        <div id="contract-attribute-preview"></div>
                    </div>

                    <x-form.form-input
                        :id="'name'"
                        :name="'name'"
                        :label="__('general.common.name')"
                        :placeholder="__('general.common.name')"
                        :isRequired="true"
                        :value="old('name', $contractType->name)"
                    />

                    <div class="editor-paper-wrapper">
                        <label>{{ __('general.common.content') }}</label>
                        <div id="editor"></div>
                        <textarea id="content" name="content" hidden>{!! old('content', $contractType->content) !!}</textarea>
                    </div>

                    @error('content')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-lg-8">
                <x-buttons.submit :label="__('general.common.complete')" />
            </div>
        </div>
    </x-form.form-layout>

    <x-slot:footerFiles>
        <script src="{{ asset('vendor/select2/js/select2.full.min.js') }}"></script>
        <script src="{{ asset('js/plugins-init/select2-init.js') }}"></script>
        <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/super-build/ckeditor.js"></script>
        <script>
            @php
                $contractAttributesMap = [];
                foreach ($contractAttributes->keyBy('id') as $id => $a) {
                    $contractAttributesMap[$id] = [
                        'id' => $a->id,
                        'name' => $a->name,
                        'key' => $a->key,
                    ];
                }
            @endphp

            window.contractAttributesMap = @json($contractAttributesMap);
        </script>
        <script>
            CKEDITOR.ClassicEditor
            .create(document.getElementById("editor"), {
                ckfinder: {
                    uploadUrl: '{{ route('admin.editor_upload', ['_token' => csrf_token()]) }}',
                    options: {
                        resourceType: 'Images'
                    }
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
                exportPdf: {
                    stylesheets: [
                        'EDITOR_STYLES'
                    ],
                    dataCallback: (editor) => {
                        const content = editor.getData();

                        return fetch('/editor-uploads-export', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ content: content })
                        })
                        .then(response => response.json())
                        .then(data => {
                            let processedContent = data.content;
                            return processedContent;
                        })
                        .catch(error => {
                            console.error('Có lỗi xảy ra khi lấy dữ liệu:', error);
                        });
                    }
                },
                exportWord: {
                    stylesheets: [
                        'EDITOR_STYLES'
                    ],
                    dataCallback: (editor) => {
                        const content = editor.getData();

                        return fetch('/editor-uploads-export', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ content: content })
                        })
                        .then(response => response.json())
                        .then(data => {
                            let processedContent = data.content;
                            return processedContent;
                        })
                        .catch(error => {
                            console.error('Có lỗi xảy ra khi lấy dữ liệu:', error);
                        });
                    }
                },
                list: {
                    properties: {
                        styles: true,
                        startIndex: true,
                        reversed: true
                    }
                },
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                        { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                        { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                    ]
                },
                placeholder: '{{ __('general.common.content') }}',
                fontFamily: {
                    options: [
                        'default',
                        'Arial, Helvetica, sans-serif',
                        'Courier New, Courier, monospace',
                        'Georgia, serif',
                        'Lucida Sans Unicode, Lucida Grande, sans-serif',
                        'Tahoma, Geneva, sans-serif',
                        'Times New Roman, Times, serif',
                        'Trebuchet MS, Helvetica, sans-serif',
                        'Verdana, Geneva, sans-serif'
                    ],
                    supportAllValues: true
                },
                fontSize: {
                    options: [ 10, 12, 14, 'default', 18, 20, 22 ],
                    supportAllValues: true
                },
                htmlSupport: {
                    allow: [
                        {
                            name: /.*/,
                            attributes: true,
                            classes: true,
                            styles: true
                        }
                    ]
                },
                htmlEmbed: {
                    showPreviews: true
                },
                link: {
                    decorators: {
                        addTargetToExternalLinks: true,
                        defaultProtocol: 'https://',
                        toggleDownloadable: {
                            mode: 'manual',
                            label: 'Downloadable',
                            attributes: {
                                download: 'file'
                            }
                        }
                    }
                },
                mention: {
                    feeds: [
                        {
                            marker: '@',
                            feed: [
                                '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                                '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
                                '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
                                '@sugar', '@sweet', '@topping', '@wafer'
                            ],
                            minimumCharacters: 1
                        }
                    ]
                },
                removePlugins: [
                    'AIAssistant',
                    'CKBox',
                    'EasyImage',
                    'MultiLevelList',
                    'RealTimeCollaborativeComments',
                    'RealTimeCollaborativeTrackChanges',
                    'RealTimeCollaborativeRevisionHistory',
                    'PresenceList',
                    'Comments',
                    'TrackChanges',
                    'TrackChangesData',
                    'RevisionHistory',
                    'Pagination',
                    'WProofreader',
                    'MathType',
                    'SlashCommand',
                    'Template',
                    'DocumentOutline',
                    'FormatPainter',
                    'TableOfContents',
                    'PasteFromOfficeEnhanced',
                    'CaseChange'
                ]
            })
            .then(editor => {
                editor.model.document.on('change:data', () => {
                    let data = editor.getData();
                    const textarea = document.getElementById("content");
                    if (textarea) {
                        textarea.value = data;
                        textarea.dispatchEvent(new Event('input'));
                    }
                });

                try {
                    const existing = document.getElementById('content')?.value || '';
                    if (existing) {
                        editor.setData(existing);
                        try {
                            editor.editing.view.focus();
                            editor.model.change(writer => {
                                const root = editor.model.document.getRoot();
                                writer.setSelection(root, 'end');
                            });
                        } catch (focusErr) {
                            console.warn('CKEditor focus/selection failed', focusErr);
                        }
                    }
                } catch (e) {
                    console.warn('Failed to initialize editor content/focus', e);
                }
            })
            .catch(error => {
                console.error('There was a problem initializing the editor.', error);
            });

            // Attribute dynamic
            document.addEventListener('DOMContentLoaded', function () {
                const select = document.getElementById('contract_attributes');
                const wrapper = document.getElementById('contract-attribute-preview');
                const map = window.contractAttributesMap || {};

                if (!select || !wrapper) return;

                function getSelectedIds() {
                    if (window.jQuery && $(select).data('select2')) {
                        return $(select).val() || [];
                    }

                    return select.value ? select.value.split(',') : [];
                }

                function render() {
                    wrapper.innerHTML = '';

                    const selectedIds = getSelectedIds();

                    if (!selectedIds.length) {
                        wrapper.innerHTML = `
                            <div class="alert alert-secondary">
                                {{ __('general.common.no_attributes_selected') }}
                            </div>
                        `;
                        return;
                    }

                    selectedIds.forEach(id => {
                        const attr = map[id];
                        if (!attr) return;

                        const placeholder = '@{{ $' + attr.key + ' }}';

                        const row = document.createElement('div');
                        row.className = 'row align-items-center mb-3';

                        row.innerHTML = `
                            <!-- Name -->
                            <div class="col-md-3">
                                <input type="text"
                                    class="form-control"
                                    value="${attr.name}"
                                    readonly>
                            </div>

                            <!-- Key -->
                            <div class="col-md-3">
                                <input type="text"
                                    class="form-control text-muted"
                                    value="${attr.key}"
                                    readonly>
                            </div>

                            <!-- Placeholder + Copy -->
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text"
                                        class="form-control font-monospace"
                                        value="${placeholder}"
                                        readonly>

                                    <button type="button"
                                        class="btn btn-outline-secondary btn-copy"
                                        data-copy="${placeholder}">
                                        Copy
                                    </button>
                                </div>
                            </div>
                        `;

                        wrapper.appendChild(row);
                    });
                }

                setTimeout(render, 300);

                if (window.jQuery) {
                    $(select).on('change.select2', render);
                } else {
                    select.addEventListener('change', render);
                }

                wrapper.addEventListener('click', function (e) {
                    if (!e.target.classList.contains('btn-copy')) return;

                    const text = e.target.dataset.copy;
                    navigator.clipboard.writeText(text).then(() => {
                        e.target.innerText = 'Copied';
                        setTimeout(() => e.target.innerText = 'Copy', 1200);
                    });
                });
            });
        </script>
        
    </x-slot:footerFiles>
</x-hr.base-layout>
