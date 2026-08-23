(function ($) {
    function flagUrlFromCode(code) {
        if (!code) return "";
        return (
            "/admin/images/flags/" + String(code).toLowerCase() + ".webp"
        );
    }

    function formatWithFlag(state) {
        if (!state || !state.element)
            return state && state.text ? state.text : "";
        var $opt = $(state.element);
        var url = $opt.data("flagUrl") || $opt.data("flag-url");
        if (!url) {
            var code = $opt.data("flag");
            url = flagUrlFromCode(code);
        }
        if (!url) return state.text;
        return (
            '<span class="d-inline-flex align-items-center">' +
            state.text +
            '<img src="' +
            url +
            '" width="20" height="14" style="margin-inline-start:6px;object-fit:cover;">' +
            "</span>"
        );
    }

    window.initSelect2Flags = function (selectElOrSelector, options) {
        var $els = $(selectElOrSelector);
        if (!$els.length || !$.fn.select2) return;

        $els.each(function () {
            var $el = $(this);
            if ($el.hasClass("select2-hidden-accessible")) {
                $el.select2("destroy");
            }
            var opts = $.extend(
                true,
                {
                    templateResult: formatWithFlag,
                    templateSelection: formatWithFlag,
                    escapeMarkup: function (m) {
                        return m;
                    },
                    width: "100%",
                    dropdownParent: $el.parent(),
                },
                options || {},
            );
            $el.select2(opts);
        });
    };

    $(function () {
        if (!$.fn.select2) return;
        window.initSelect2Flags('select[data-select2-flags="1"]');
    });
})(jQuery);
