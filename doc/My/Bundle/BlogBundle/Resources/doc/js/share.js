var viadeoWidgetsJsUrl = document.location.protocol + "//widgets.viadeo.com";
(function () {
    var e = document.createElement('script');
    e.type = 'text/javascript';
    e.async = true;
    e.src = viadeoWidgetsJsUrl + '/js/viadeowidgets.js';
    var s = document.getElementsByTagName('head')[0];
    s.appendChild(e);
})();

$(function () {
    Socialite.load($('.share')[0]);
});