$(document).ready(function(){
    $('#slides').slides({
        preload: true,
        preloadImage: '/bundles/myblog/js/slide/loading.gif',
        generatePagination: true,
        play: 5000,
        pause: 2500,
        hoverPause: true,
        start: 1
    });
});