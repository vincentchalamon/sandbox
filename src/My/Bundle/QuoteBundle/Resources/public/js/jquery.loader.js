/**
 * jQuery loader Plugin
 *
 * Version 1.0, April 11th 2013, by Vincent Chalamon <vincentchalamon@gmail.com>
 *
 * Usage :
 *      $.loader.show();
 *      $.loader.hide();
 */
(function($) {

    $.loader = {
        show: function() {
            if ($('.jquery-loader-overlay, .jquery-loader-spinner').length) {
                return this;
            }

            // Build elements
            $('body').append($('<div>').addClass('jquery-loader-overlay').hide())
                .append($('<div>').addClass('jquery-loader-spinner').hide());
            if ($.browser.msie) {
                $('.jquery-loader-overlay, .jquery-loader-spinner').addClass('ie-' + parseInt($.browser.version, 10));
            }
            $('.jquery-loader-spinner').css({
                top: ($(window).height()/2)-($('.jquery-loader-spinner').height()/2),
                left: ($(window).width()/2)-($('.jquery-loader-spinner').width()/2)
            });

            // Show elements
            $('.jquery-loader-overlay, .jquery-loader-spinner').fadeIn(200);

            return this;
        },
        hide: function() {
            $('.jquery-loader-overlay, .jquery-loader-spinner').fadeOut(200, function() {
                $(this).remove();
            });
            return this;
        }
    }

})(jQuery);