@push('headerFiles')
    <style>
        .ck-editor__editable[role="textbox"] {
            min-height: 600px;
        }
        .ck-content .image {
            max-width: 80%;
            margin: 20px auto;
        }
    </style>
@endpush

<div id="editor"></div>

@push('footerFiles')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/super-build/ckeditor.js"></script>

    <script>
        $(document).ready(function () {
            CKEDITOR.ClassicEditor
                .create(document.getElementById("editor"), {
                    initialData: '{{ __('general.common.content') }}',
                    placeholder: '{{ __('general.common.content') }}',
                    toolbar: {
                        items: [
                            'exportPDF','exportWord', '|',
                            'findAndReplace', '|',
                        ],
                        shouldNotGroupWhenFull: true
                    },
                    list: {
                        properties: {
                            styles: true,
                            startIndex: true,
                            reversed: true
                        }
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
                    removePlugins: [
                        'AIAssistant',
                        'CKBox',
                        'CKFinder',
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
                    ],
                })
                .then(editor => {
                    editor.enableReadOnlyMode('readonly');

                    $('#contractType').on('change', function () {
                        const optionId = $('#contractType').find('option:selected').val();

                        $(`#contractTypeContent${optionId}`)
                        ? editor.setData($(`#contractTypeContent${optionId}`).val())
                        : editor.setData('');
                    });

                    $(document).ready(function() {
                        const optionId = $('#contractType').find('option:selected').val();

                        $(`#contractTypeContent${optionId}`)
                        ? editor.setData($(`#contractTypeContent${optionId}`).val())
                        : editor.setData('');
                    });
                })
                .catch(error => {
                    console.error('There was a problem initializing the editor.', error);
                });
        });
    </script>
@endpush


