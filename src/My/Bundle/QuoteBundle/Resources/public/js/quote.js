/**
 * Author: Vincent Chalamon <vincentchalamon@gmail.com>
 */
String.prototype.numberFormat = function () {
    var x = parseFloat(this).toFixed(2).toString().split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? ',' + x[1] : '';
    while (/(\d+)(\d{3})/.test(x1)) {
        x1 = x1.replace(/(\d+)(\d{3})/, '$1' + ' ' + '$2');
    }

    return x1 + x2;
};

$(function () {
    var length = $('#quote table:first tbody tr').length;
    $('#quote table:first').on('click', '.remove-delivery', function (event) {
        event.preventDefault();
        $(this).closest('tr').remove();
    }).on('init', 'tr', function () {
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
        $('input', $(this)).numeric();
    }).on('click', 'tfoot a', function (event) {
        event.preventDefault();
        $('<tr></tr>').html($(this).attr('data-prototype').replace(/__name__/g, length))
                      .appendTo($('tbody', $(this).closest('table'))).trigger('init');
        length++;
    }).on('change keyup', ':input[name*=price]', function () {
        $('span', $(this).closest('td').prev('td')).text($(this).val() ? $(this).val().toString().numberFormat() : '');
        var total = 0;
        $(':input[name*=price]', $(this).closest('tbody')).each(function () {
            if ($(this).val()) {
                total += parseFloat($(this).val());
            }
        });
        $('tfoot span:last', $(this).closest('table')).text(total.toString().numberFormat());
    });
    $('#quote table:first tbody tr').trigger('init');
    $('#quote :input[name*=price]').trigger('change');
});