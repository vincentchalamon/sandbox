$(function() {
    // Autosize textarea
    $('textarea').autosize();

    // Custom HTML5 validation messages
    $('input').each(function () {
        this.oninvalid = function () {
            this.setCustomValidity('');
            if (this.validity.valueMissing) {
                this.setCustomValidity($(this).attr('data-validation-required-message'));
            } else if (!this.validity.valid) {
                this.setCustomValidity($(this).attr('data-validation-invalid-message'));
            }
        };
        this.oninput = function () {
            this.setCustomValidity('');
        };
    });
});