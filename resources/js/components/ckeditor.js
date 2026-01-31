import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

function initCustomEditors(selector = '.custom-ckeditor') {
  document.querySelectorAll(selector).forEach(el => {
    if (el._ckEditorInitialized) return;
    ClassicEditor.create(el).then(editor => {
      el._ckEditorInstance = editor;
      el._ckEditorInitialized = true;
    }).catch(err => console.error('CKEditor init error:', err));
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => initCustomEditors());
} else {
  initCustomEditors();
}

export { initCustomEditors };
