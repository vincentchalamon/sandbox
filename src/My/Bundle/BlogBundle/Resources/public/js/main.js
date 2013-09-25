var viadeoWidgetsJsUrl = document.location.protocol+"//widgets.viadeo.com";

var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-36266524-1']);
_gaq.push(['_setDomainName', window.location.hostname]);
_gaq.push(['_setAllowLinker', true]);
_gaq.push(['_trackPageview']);
(function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();

$(document).ready(function () {
    // Emoticons
    $('.container .contents p, .container .comment .message').emoticons('/bundles/myblog/img/emoticons');

    // Mailto
    $('a[href^=mailto]').each(function () {
        $(this).attr('href', $(this).attr('href').replace(/\[at\]/i, '@'));
    });
    $('textarea').autosize();

    $(window).resize(function () {
        $('.loader .ball').css({
            top: $(this).height()/2,
            left: $(this).width()/2
        });
        $('.loader .ball1').css({
            top: $(this).height()/2+25,
            left: $(this).width()/2+10
        });
    }).trigger('resize');

    // Close modal if open on escape
    $(document).keyup(function(e) {
        if (e.keyCode == 27 && $('.md-modal:visible').length) {
            $('.md-close').click();
        }
    });

    // Pager
    $('body').on('success', '#pager', function () {
        $('div:hidden', $(this).parent()).emoticons('/bundles/myblog/img/emoticons');
    }).on('click', '#pager', function (event) {
        event.preventDefault();
        var $link = $(this);
        $.get($(this).attr('href'),function (data) {
            $link.fadeOut('fast', function () {
                $(this).parent().append($('<div style="display:none">' + data + '</div>'));
                $(this).trigger('success');
                $('div:hidden', $(this).parent()).fadeIn();
                $(this).remove();
            });
        }, 'html').error(function () {
            $.notifyBar({
                html: 'Une erreur est survenue !',
                cls: 'error',
                delay: 3000,
                close: 'true'
            });
        });

    // Contact
    }).on('click', 'a[href$=contact]', function (event) {
        event.preventDefault();
    }).on('submit', '.md-modal form', function (event) {
        event.preventDefault();
        $('.loader').addClass('show');
        $('div.error, ul.error', $(this)).remove();
        var xhr = $.post($(this).attr('action'), $(this).serialize(), function (data) {
            console.log(data);
        }, 'json').fail(function (xhr) {
            // Form has errors
            if (xhr.status == 400) {
                $('<div>').addClass('error').text(xhr.responseJSON.message).prependTo($(event.target));
                $.each(xhr.responseJSON.form.children, function (key, field) {
                    if (field.vars.errors.length) {
                        $('#' + field.vars.id).addClass('error');
                        var errors = $('<ul>').addClass('error').insertAfter('#' + field.vars.id);
                        $.each(field.vars.errors, function (index, error) {
                            $('<li>').text(error).appendTo(errors);
                        });
                    }
                });
            // An error occured while processing request or response
            } else {
                $('<div>').addClass('error').text('Une erreur est survenue.').prependTo($(event.target));
            }
        }).always(function () {
            $('.loader').removeClass('show');
        });
    });
});