$(function () {
    $('#timeline').timeline({
        elements: [
            {
                start: '2006-09-01',
                end: '2008-08-31',
                css: 'jquery-timeline-item-formation',
                label: 'DUT Services et Réseaux de Communication'
            },                 {
                start: '2007-03-12',
                end: '2007-04-14',
                label: 'Wacan Communication'
            },                 {
                start: '2007-07-01',
                end: '2007-07-31',
                label: 'Nice Matin'
            },                 {
                start: '2008-04-14',
                end: '2008-07-31',
                label: 'AureXus'
            },                 {
                start: '2008-09-01',
                end: '2011-12-31',
                label: 'EP Factory'
            },                 {
                start: '2008-09-01',
                end: '2009-08-31',
                css: 'jquery-timeline-item-formation',
                label: 'Licence professionnelle Développement Informatique Multi-supports'
            },                 {
                start: '2012-01-01',
                end: '2012-11-30',
                label: 'PL Com'
            },                 {
                start: '2012-12-01',
                end: '2013-03-31',
                label: 'Mayflower'
            },                 {
                start: '2013-04-01',
                label: 'Ylly'
            }
        ],
        onSelect: function(event, index){
            if (!$('.skill:eq(' + index + '):visible').length) {
                if ($('.skill:visible').length) {
                    $('.skill:visible').fadeOut('fast', function(){
                        $('.skill:eq(' + index + ')').fadeIn('fast');
                    });
                } else {
                    $('.skill:eq(' + index + ')').fadeIn('fast');
                }
            }
        }
    });
});