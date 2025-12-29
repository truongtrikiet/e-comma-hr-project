(function($) {
    "use strict"

    // Initialize pickadate for elements with .datepicker-default
    var pickers = [];
    $('.datepicker-default').each(function(){
        var $el = $(this);
        if (!$el.pickadate) return;
        $el.pickadate();
        var picker = $el.pickadate('picker');
        if (!picker) return;
        // ensure closed on init
        try{ picker.close(); }catch(e){}
        // Wrap close to blur focused descendant before picker's aria-hidden toggles
        try {
            var _origClose = picker.close && picker.close.bind(picker);
            if (_origClose) {
                picker.close = function(force) {
                    try {
                        var active = document.activeElement;
                        if (active && picker.$root && picker.$root[0] && picker.$root[0].contains(active)) {
                            active.blur();
                        }
                    } catch (e) {}
                    return _origClose(force);
                };
            }
        } catch (e) {}

        pickers.push({input: $el, picker: picker});
        // open on focus or click
        $el.on('focus click', function(){ picker.open(); });
    });

    // Close any open picker when clicking/tapping outside
    $(document).on('mousedown touchstart', function(e){
        var $t = $(e.target);
        pickers.forEach(function(p){
            if ($t.closest('.picker').length === 0 && !$t.is(p.input) && $t.closest(p.input).length === 0) {
                try{ p.picker.close(); }catch(e){}
            }
        });
    });

})(jQuery);