$(function() {
    // Autosize textarea
    $('#contact textarea').autosize();

    // Show ladda spinner on form submit
    $('form').on('submit', function () {
        Ladda.create($(':input:submit:first', $(this)).get(0)).start();
    });

    // Custom HTML5 validation messages
    $('input').each(function () {
        this.oninvalid = function () {
            this.setCustomValidity('');
            if (this.validity.valueMissing && $(this).attr('data-validation-required-message')) {
                this.setCustomValidity($(this).attr('data-validation-required-message'));
            } else if (!this.validity.valid && $(this).attr('data-validation-invalid-message')) {
                this.setCustomValidity($(this).attr('data-validation-invalid-message'));
            }
        };
        this.oninput = function () {
            this.setCustomValidity('');
        };
    });

    // Show toTop element
    $().UItoTop({
        easingType: 'easeOutQuart',
        min: 200
    });

    // Convert mailto hack
    $('a:contains("[at]")').each(function(){
        $(this).attr('href', $(this).attr('href').replace(/\[at\]/i, '@'));
        $(this).text($(this).text().replace(/\[at\]/i, '@'));
    });

    // Google Analytics on submit contact form
    $('form[role=contact]').on('submit', function () {
        _gaq.push(['_trackEvent', 'Contact', 'Envoyer']);
    });
});