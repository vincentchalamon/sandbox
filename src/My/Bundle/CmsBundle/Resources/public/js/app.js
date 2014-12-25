$(function () {
    // Textarea autosize
    $('textarea').autosize();

    // Show ladda spinner on form submit
    $('form').on('submit', function () {
        Ladda.create($(':input:submit:first', $(this)).get(0)).start();
    });

    // Convert mailto hack
    $('a:contains("[at]")').each(function(){
        $(this).attr('href', $(this).attr('href').replace(/\[at\]/i, '@'));
        $(this).text($(this).text().replace(/\[at\]/i, '@'));
    });

    // Google Analytics on submit contact form
    if (typeof _gaq != 'undefined') {
        $('form[role=contact]').on('submit', function () {
            _gaq.push(['_trackEvent', 'Contact', 'Envoyer']);
        });
    }
});