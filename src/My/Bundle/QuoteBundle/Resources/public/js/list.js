/**
 * Author: Vincent Chalamon <vincentchalamon@gmail.com>
 */
$(function () {
    $('.modal').on('click', '.modal-footer .btn-primary', function (event) {
        event.preventDefault();
        $('.modal-body form', $(this).closest('.modal')).trigger('submit');
    }).on('submit', '.modal-body form', function (event) {
        event.preventDefault();
        $.loader.show();
        $('body > .container-fluid .alert').remove();
        $.post($(this).attr('action'), $(this).serialize(), function (data) {
            $.loader.hide();
            if (data.code == 'success') {
                $('<div>').addClass('alert').addClass('alert-success').text(data.message).prependTo('body > .container-fluid');
                $(event.target).closest('.modal').modal('hide');
            } else {
                $(this).replaceWith(data.html);
            }
        }, 'json');
    }).on('shown', function () {
        $('textarea', $(this)).redactor('focusEnd');
    }).each(function () {
        $('textarea', $(this)).redactor({
            imageUploadErrorCallback: function (datas) {
                $('.redactor_error', $(this).closest('.redactor_box')).trigger('close');
                $('<div>').addClass('redactor_error').text(datas.error).insertAfter($(this)).on('close', function () {
                    $(this).slideUp('fast', function () {
                        $(this).remove();
                    });
                }).slideDown('fast').delay(5000).trigger('close');
            },
            fileUploadErrorCallback: function (datas) {
                $('.redactor_error', $(this).closest('.redactor_box')).trigger('close');
                $('<div>').addClass('redactor_error').text(datas.error).insertAfter($(this)).on('close',function () {
                    $(this).slideUp('fast', function () {
                        $(this).remove();
                    });
                }).slideDown('fast').delay(5000).trigger('close');
            },
            imageUpload: Routing.generate('redactor-upload'),
            fileUpload: Routing.generate('redactor-upload'),
            lang: 'fr',
            buttons: [
                'html', '|',
                'bold', 'italic', 'deleted', '|',
                'unorderedlist', 'orderedlist', '|',
                'image', 'table', 'link', '|',
                'alignment'
            ]
        });
    });
});