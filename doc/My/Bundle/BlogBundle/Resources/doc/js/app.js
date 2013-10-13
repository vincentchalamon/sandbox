$(function() {
    $(window).resize(function () {
        if ($('body').height() < $('html').height()) {
            $('body').height($('html').height());
            $('footer').css({
                position: 'absolute',
                bottom: 0
            });
        }
    }).trigger('resize');

    $('#article article').emoticons('img/emoticons');

    $('input').each(function () {
        this.oninvalid = function (event) {
            this.setCustomValidity('');
            if (this.validity.valueMissing) {
                this.setCustomValidity($(this).attr('data-validation-required-message'));
            } else if (!this.validity.valid) {
                this.setCustomValidity($(this).attr('data-validation-invalid-message'));
            }
        };
        this.oninput = function (event) {
            this.setCustomValidity('');
        };
    });

    $('body').on('click', 'a[href^=#]:not([data-toggle=modal], .jquery-timeline a)', function (event){
        event.preventDefault();
        $('body, html').animate({
            scrollTop: $(event.target).attr('href') == '#' ? 0 : $($(event.target).attr('href')).offset().top
        }, 500);
    }).on('click', 'h1[id], h2[id], h3[id], h4[id], h5[id], h6[id]', function () {
        $(location).attr('href', '#' + $(this).attr('id'));
    });

    $().UItoTop({
        easingType: 'easeOutQuart',
        min: 200
    });

    $('a:contains("[at]")').each(function(){
        $(this).attr('href', $(this).attr('href').replace(/\[at\]/i, '@'));
        $(this).text($(this).text().replace(/\[at\]/i, '@'));
    });
});