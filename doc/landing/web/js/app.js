$(function () {
    $('textarea').autosize();

    $('body').on('submit', 'form', function (event) {
        event.preventDefault();
        $.post($(this).attr('action'), $(this).serialize(), function (data) {
            $(event.target).replaceWith(data.html);
            if (data.code == 'success') {
                $('#form_email, #form_message').val('');
                $('<p>').text('Votre message a bien été envoyé.').addClass('notice').prependTo('body')
                    .animate({top: 25}, 'fast').delay(3000).fadeOut('fast', function () {
                    $(this).remove();
                });
            } else {
                $('form .form-group ul').hide().fadeIn().parent().addClass('error');
            }
        }, 'json');
    });
});