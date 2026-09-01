$(document).on('change', '[data-fetch-url]', function () {
    var $this = $(this);
    var $target = $($this.data('ajax-target'));
    var child = $this.data('ajax-child');
    var val = $this.val();

    if (!$target.length) return;

    // Reset child if specified
    if (child) {
        $(child).prop('disabled', true).find('option:not(:first)').remove();
    }

    // Reset target and disable
    $target.prop('disabled', true).find('option:not(:first)').remove();

    if (!val) return;

    // Append loading spinner inside target container
    var $wrapper = $target.parent().addClass('position-relative');
    var $spinner = $('<span class="spinner-border spinner-border-sm text-primary position-absolute" style="top:50%; margin-top:-8px; inset-inline-end:25px; z-index:5;" role="status"></span>');
    $wrapper.append($spinner);

    var locale = $('html').attr('lang') || 'ar';

    $.get($this.data('fetch-url'), { column: $this.data('ajax-col'), value: val }, function (res) {
        $.each(res.data || [], function (i, item) {
            var title = typeof item.title === 'object' ? (item.title[locale] || item.title['ar']) : item.title;
            $target.append('<option value="' + item.id + '">' + title + '</option>');
        });
        
        $target.prop('disabled', false);

        var selectedVal = $target.data('selected');
        if (selectedVal) {
            $target.val(selectedVal);
            $target.removeAttr('data-selected');
            $target.trigger('change');
        }
    }).always(function () {
        $spinner.remove();
    });
});

$(document).ready(function() {
    $('[data-fetch-url]').each(function() {
        if ($(this).val()) {
            $(this).trigger('change');
        }
    });
});
