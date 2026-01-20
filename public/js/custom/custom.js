
//init js select2
document.addEventListener('DOMContentLoaded', function () {
    if (typeof $ === 'undefined' || !$.fn.select2) return;

    $('.form-select-enhanced').each(function () {
        const $select = $(this);
        if ($select.data('select2')) return;

        const isMultiple = $select.data('multiple') === 'true';

        $select.select2({
            width: '100%',
            placeholder: $select.find('option[value=""]').first().text() || 'Choose',
            closeOnSelect: !isMultiple,
            allowClear: !isMultiple
        });
    });
});
