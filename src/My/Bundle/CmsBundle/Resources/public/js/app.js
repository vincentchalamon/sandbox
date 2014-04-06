$(function() {
    $('.carousel').carousel().on('slide.bs.carousel', function () {
        var item = $('.carousel-inner .item.active').next('.item');
        if (!item.length) {
            item = $('.carousel-inner .item:first')
        }
        $('img.lazy', item).lazyload({
            effect: 'fadeIn',
            threshold: 100,
            skip_invisible: false
        });
    });

    $('#contact textarea').autosize();

    $('#contact').on('submit', 'form', function (event) {
        event.preventDefault();
        $('.alert').trigger('clean');
        var l = Ladda.create($('button[type=submit]', $(this)).get(0));
        l.start();
        $(':input', $(this)).attr('readonly', 'readonly');
        $.post($(this).attr('action'), $(this).serialize(), function (response) {
            if (response.code == 'success') {
                $(':input:not(:hidden)', $(event.target)).val('');
                $('#contact form button:button').trigger('click');
                $('<div>').addClass('alert')
                          .addClass('alert-success')
                          .addClass('animated bounceInDown')
                          .css('left', ($(window).width()-249)/2)
                          .text('Votre message a bien été envoyé.')
                          .prependTo('body');
                setTimeout(function () {
                    $('.alert').trigger('clean');
                }, 5000);
            } else {
                $(event.target).replaceWith(response.form);
                $('<div>').addClass('alert')
                    .addClass('alert-danger')
                    .addClass('animated bounceInDown')
                    .css('left', ($(window).width()-399)/2)
                    .text('Une erreur est survenue durant l\'envoi de votre message.')
                    .prependTo('body');
                setTimeout(function () {
                    $('.alert').trigger('clean');
                }, 5000);
            }
        }, 'json').always(function () {
            $(':input', $(event.target)).removeAttr('readonly');
            l.stop();
        }).fail(function () {
            $('<div>').addClass('alert')
                .addClass('alert-danger')
                .addClass('animated bounceInDown')
                .css('left', ($(window).width()-399)/2)
                .text('Une erreur est survenue durant l\'envoi de votre message.')
                .prependTo('body');
            setTimeout(function () {
                $('.alert').trigger('clean');
            }, 5000);
        });
    });
    $('#article article').emoticons('img/emoticons');

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

    $('body').on('click', 'a[href^=#]:not([data-toggle=modal], .jquery-timeline a)', function (event){
        event.preventDefault();
        $('body, html').animate({
            scrollTop: $(event.target).attr('href') == '#' ? 0 : $($(event.target).attr('href')).offset().top
        }, 500);
    }).on('click', 'h1[id], h2[id], h3[id], h4[id], h5[id], h6[id]', function () {
        $(location).attr('href', '#' + $(this).attr('id'));
    }).on('lazy', function () {
        $(':not(.carousel) img.lazy').lazyload({
            effect: 'fadeIn',
            threshold: 100
        });
        $('.carousel .item.active img.lazy').lazyload({
            effect: 'fadeIn',
            threshold: 100,
            skip_invisible: false
        });
    }).on('clean', '.alert', function () {
        $(this).removeClass('bounceInDown').addClass('bounceOutUp')
               .one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                $(this).slideUp('fast', function () {
                    $(this).remove();
                });
            });
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