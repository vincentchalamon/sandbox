$(document).ready(function(){
    $('.container input:text, input[type="email"], input:radio, input:checkbox, input:password, select, textarea').uniform();
    $('textarea').autosize({
        callback: function(){
            var $height = $(this).closest('.container').height();
            $('.fancybox-inner', $(parent.window.document)).animate({
                height: $height+30
            }, 'fast');
        }
    });
    $('form.ajax').live('submit', function(event){
        event.preventDefault();
        var $form = $(this);
        $form.ajaxSubmit({
            url: $form.attr('action'),
            dataType: 'json',
            beforeSend: function(){
                $('.notice, .success, .error').remove();
                $('input:submit', $form).hide();
                if ($.fancybox != undefined) {
                    $.fancybox.showLoading();
                } else {
                    parent.jQuery.fancybox.showLoading();
                }
            },
            complete: function(){
                $('input:submit', $form).show();
                if ($.fancybox != undefined) {
                    $.fancybox.hideLoading();
                } else {
                    parent.jQuery.fancybox.hideLoading();
                }
            },
            error: function(){
                $.notifyBar({
                    html: "Une erreur est survenue !",
                    cls: "error",
                    delay: 3000,
                    close: "true"
                });
            },
            success: function(data){
                if (!$form.hasClass('disableNotify')) {
                    $.notifyBar({
                        html: data.datas,
                        cls: data.code,
                        delay: 3000,
                        close: "true"
                    });
                }
                if(data.code == 'success')
                {
                    $form.find('input:not(:submit, :hidden), textarea').each(function(){
                        $(this).val('');
                    });
                }
                $form.trigger(data.code, data.datas);
            }
        });
    });
});