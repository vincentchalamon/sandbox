$(document).ready(function(){
    $('.fancybox').fancybox({
        type: 'iframe',
        padding: 0,
        width: 930
    });
    $('.actions a.add').fancybox({
        type: 'ajax',
        beforeShow: function(){
            $('.fancybox-inner input:text').uniform();
            $('.fancybox-inner').on('submit', 'form', function(event){
                event.preventDefault();
                var $form = $(this);
                $form.ajaxSubmit({
                    url: $form.attr('action'),
                    dataType: 'json',
                    beforeSend: function(){
                        $form.find('.notice, .success, .error').remove();
                        $form.find('input:submit').hide();
                        $.fancybox.showLoading();
                    },
                    error: function(){
                        $.fancybox.hideLoading();
                        $form.find('input:submit').show();
                        $form.prepend('<p class="error">Une erreur est survenue !</p>');
                    },
                    success: function(data){
                        if(data.code == 'success') {
                            $(location).attr('href', data.datas);
                        } else {
                            $.fancybox.hideLoading();
                            $form.find('input:submit').show();
                            $form.prepend('<p class="error">' + data.datas + '</p>');
                        }
                    }
                });
            });
        }
    });
});