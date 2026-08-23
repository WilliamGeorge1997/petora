(function ($) {
    window.handleLangInputChange = function (selectEl) {
        var $group = $(selectEl).closest(".lang-input-group");
        if (!$group.length) return;

        var lang = $(selectEl).val();

        var $ar = $group.find(
            'input[name$="_ar"], textarea[name$="_ar"], input[name$="[name_ar]"], input[name$="[value_ar]"], textarea[name$="[name_ar]"]',
        );
        var $en = $group.find(
            'input[name$="_en"], textarea[name$="_en"], input[name$="[name_en]"], input[name$="[value_en]"], textarea[name$="[name_en]"]',
        );

        var $arErrors = $group.find(".lang-error-ar");
        var $enErrors = $group.find(".lang-error-en");

        if (!$ar.length || !$en.length) return;

        if (lang === "ar") {
            $ar.removeClass("d-none");
            $en.addClass("d-none");
            $arErrors.removeClass("d-none");
            $enErrors.addClass("d-none");
        } else {
            $en.removeClass("d-none");
            $ar.addClass("d-none");
            $enErrors.removeClass("d-none");
            $arErrors.addClass("d-none");
        }
    };

    window.handleLangPillChange = function (btn) {
        var $btn = $(btn);
        var $group = $btn.closest(".lang-input-group");
        if (!$group.length) return;

        var lang = $btn.data("lang");

        $group.find(".lang-flag-btn").removeClass("active");
        $btn.addClass("active");

        var $ar = $group.find(
            'input[name$="_ar"], textarea[name$="_ar"], input[name$="[name_ar]"], input[name$="[value_ar]"], textarea[name$="[name_ar]"]',
        );
        var $en = $group.find(
            'input[name$="_en"], textarea[name$="_en"], input[name$="[name_en]"], input[name$="[value_en]"], textarea[name$="[name_en]"]',
        );

        var $arErrors = $group.find(".lang-error-ar");
        var $enErrors = $group.find(".lang-error-en");

        if (lang === "ar") {
            $ar.removeClass("d-none");
            $en.addClass("d-none");
            $arErrors.removeClass("d-none");
            $enErrors.addClass("d-none");
            $ar.first().focus();
        } else {
            $en.removeClass("d-none");
            $ar.addClass("d-none");
            $enErrors.removeClass("d-none");
            $arErrors.addClass("d-none");
            $en.first().focus();
        }
    };
})(jQuery);
