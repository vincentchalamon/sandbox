$(function(){
    var codes = ['c', 'cpp', 'csharp', 'css', 'flex', 'sh', 'html', 'java', 'javascript', 'javascript_dom', 'perl', 'php', 'python', 'ruby', 'sql', 'xml'];
    $('pre:not([class])').addClass('log');
    $.each(codes, function(index, value){
        $("pre." + value).snippet(value, {
            style: value == 'html' ? 'desert' : 'golden',
            clipboard: "/bundles/vincentblog/js/snippet/ZeroClipboard.swf"
        });
    });
    $('.comment .infos a:not(:contains(http://))').click(function(event){
        event.preventDefault();
        $('.comment form:visible').not($('form', $(this).closest('.comment'))).slideUp('fast');
        $('form', $(this).closest('.comment')).slideDown('fast');
    });
});