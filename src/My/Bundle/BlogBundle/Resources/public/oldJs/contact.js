$(document).ready(function(){
    $('form').live('success', function(event, datas){
        parent.jQuery.notifyBar({
            html: datas,
            cls: 'success',
            delay: 3000,
            close: 'true'
        });
        parent.jQuery.fancybox.close();
    }).live('error', function(event, datas){
        parent.jQuery.notifyBar({
            html: datas,
            cls: 'error',
            delay: 3000,
            close: 'true'
        });
    });
});