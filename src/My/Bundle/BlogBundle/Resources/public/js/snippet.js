$(document).ready(function(){
    var codes = ['c', 'cpp', 'csharp', 'css', 'flex', 'sh', 'html', 'java', 'javascript', 'javascript_dom', 'perl', 'php', 'python', 'ruby', 'sql', 'xml'];
    $('pre:not([class])').addClass('log');
    $.each(codes, function(index, value){
        $("pre." + value).snippet(value, {
            style: value == 'html' ? 'desert' : 'golden',
            clipboard: "/bundles/myblog/js/snippet/ZeroClipboard.swf"
        });
    });
    $('.comment .infos a:not(:contains(http://))').click(function(event){
        event.preventDefault();
        $('.comment form:visible').not($('form', $(this).closest('.comment'))).slideUp('fast');
        $('form', $(this).closest('.comment')).slideDown('fast');
    });
    $().UItoTop({
        easingType: 'easeOutQuart',
        min: 500
    });
    $('.contents a[href^=#]').click(function(event){
        event.preventDefault();
        var $target = $($(this).attr('href'));
        $('body, html').animate({
            scrollTop: $target.offset().top
        }, 500);
    });
});