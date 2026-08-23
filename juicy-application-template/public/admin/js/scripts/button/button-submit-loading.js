$(document).on('submit', 'form', function(submitEvent) {
    var nativeEvent = submitEvent.originalEvent || submitEvent;
    var submitButtonOrInput = nativeEvent.submitter;

    if (!submitButtonOrInput) {
        var activeElement = document.activeElement;
        if (activeElement && activeElement.matches && activeElement.matches('button[type="submit"], input[type="submit"]')) {
            submitButtonOrInput = activeElement;
        }
    }

    if (!submitButtonOrInput && this.querySelector) {
        submitButtonOrInput = this.querySelector('button[type="submit"], input[type="submit"]');
    }

    if (!submitButtonOrInput || (submitButtonOrInput.dataset && submitButtonOrInput.dataset.submitLoading === '1')) {
        return;
    }

    submitButtonOrInput.disabled = true;
    if (submitButtonOrInput.dataset) submitButtonOrInput.dataset.submitLoading = '1';
    submitButtonOrInput.setAttribute('aria-busy', 'true');

    var loadingText = (submitButtonOrInput.dataset && submitButtonOrInput.dataset.loadingText)
        ? submitButtonOrInput.dataset.loadingText
        : 'Loading...';

    if (submitButtonOrInput.tagName === 'INPUT') {
        if (submitButtonOrInput.dataset && !submitButtonOrInput.dataset.originalValue) submitButtonOrInput.dataset.originalValue = submitButtonOrInput.value;
        submitButtonOrInput.value = loadingText;
        return;
    }

    if (submitButtonOrInput.dataset && submitButtonOrInput.dataset.submitSpinnerInserted !== '1') {
        $(submitButtonOrInput).prepend('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>');
        submitButtonOrInput.dataset.submitSpinnerInserted = '1';
    }

    if (submitButtonOrInput.dataset && submitButtonOrInput.dataset.loadingText && submitButtonOrInput.childElementCount === 0) {
        if (!submitButtonOrInput.dataset.originalText) submitButtonOrInput.dataset.originalText = submitButtonOrInput.textContent.trim();
        submitButtonOrInput.textContent = loadingText;
    }
});
