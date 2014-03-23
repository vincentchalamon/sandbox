jQuery(document).ready(function($){
    var config = {
        "crack": {
            "angle": -12,
            "image": "/bundles/myblog/js/breakit/crack.png",
            "image_left": 99,
            "image_top": 67,
            "animate": 1,
            "duration": 500,
            "easing": ""
        },
        "swing": {
            "angle": 120,
            "animate": 1,
            "duration": 750,
            "angle_attenuation": 0.75,
            "speed_attenuation": 0.7
        }
    };
//    $('.container > h1').breakIt('swing', config);
    $('.header > a').breakIt('crack', config);
});