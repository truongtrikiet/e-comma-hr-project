@once
    @push('footerFiles')
        <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.js-ckeditor').forEach(function (el) {
                    if (!el.dataset.ckeditorInitialized) {
                        CKEDITOR.replace(el, {
                            height: 150,
                            removeButtons: 'Image,Table,HorizontalRule,SpecialChar',
                        });
                        el.dataset.ckeditorInitialized = true;
                    }
                });
            });
        </script>
    @endpush
@endonce
