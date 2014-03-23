Date.prototype.getDayOfYear = function(){
    var start = new Date();
    start.setDate(1);
    start.setMonth(0);
    start.setFullYear(this.getFullYear());
    return Math.round((this.getTime()-start.getTime()) / (24*3600*1000));
};

/*
 * Timeline - jQuery Plugin
 * Generate a css timeline with jquery
 *
 * Copyright (c) 2012 Vincent CHALAMON <vincentchalamon@gmail.com>
 *
 * Version: 1.0 (2012-11-22)
 * Developped in: jQuery v1.8.3
 */
(function($) {
    $.fn.timeline = function(elements, settings){
        
        /**
         * Convert dates to Date objects
         */
        $.each(elements, function(index, value){
            elements[index].start = new Date(value.start);
            elements[index].end = value.end ? new Date(value.end) : null;
            elements[index].active = false;
        });
        
        /**
         * Init settings
         */
        var self = this;
        this.settings = $.extend({
            onSelect: function(event, index){},
            onZoomIn: function(){},
            onZoomOut: function(){}
        }, $.extend(settings, {
            start: elements[0].start.getFullYear()
        }));

        /**
         * Calculate css position
         */
        this.getPosition = function(date){
            return ((date.getFullYear()-self.settings.start)*100)+((date.getDayOfYear()-1)*(100/365))
        };

        /**
         * Is date busy
         */
        this.isBusy = function(date, current){
            for (var index = 0; index < elements.length; index++){
                if (elements[index].active && date >= elements[index].start && date <= (elements[index].end ? elements[index].end : new Date())) {
                    return true;
                }
            };
            elements[current].active = true;
            return false;
        };

        /**
         * Build HTML
         */
        this.each(function(){
            var $self = $(this);
            var width = 75;
            var outerWidth = $self.width()-70;
            // Build grid
            var $grid = $('<div>').addClass('jquery-timeline-grid').prepend(
                $('<span>').addClass('jquery-timeline-grid-item jquery-timeline-grid-item-last').append(
                    $('<span>').addClass('jquery-timeline-grid-point')
                ).append(
                    $('<span>', {html: "Aujourd'hui"}).addClass('jquery-timeline-grid-date')
                )
            );
            for (var year = new Date().getFullYear(); year >= self.settings.start; year--) {
                var $item = $('<span>').addClass('jquery-timeline-grid-item').append(
                    $('<span>').addClass('jquery-timeline-grid-point')
                ).append(
                    $('<span>', {html: year}).addClass('jquery-timeline-grid-date')
                );
                if (year == new Date().getFullYear()) {
                    $item.width(new Date().getDayOfYear()*100/365);
                    width += new Date().getDayOfYear()*100/365;
                } else {
                    width += 100
                }
                $grid.prepend($item);
            }
            
            // Build items
            var $items = $('<div>').addClass('jquery-timeline-items');
            $.each(elements, function(index, value){
                $items.append(
                    $('<a>', {href: '#', title: value.label, html: value.label}).addClass('jquery-timeline-item' + (value.css ? ' ' + value.css : '')).css({
                        left: self.getPosition(value.start)+15,
                        width: self.getPosition(value.end ? value.end : new Date())-22-self.getPosition(value.start)+(value.end ? 0 : 20),
                        top: self.isBusy(value.start, index) ? 40 : 0
                    }).on('click', function(event){
                        event.preventDefault();
                        $(this).addClass('active').siblings().removeClass('active');
                        self.settings.onSelect(event, index);
                    }).tipsy({gravity: 'sw'})
                );
            });
            
            // Display timeline
            $self.append(
                $('<a>', {href: '#'}).addClass('jquery-timeline-prev').on('click', function(event){
                    event.preventDefault();
                    var right = -1*parseInt($('.jquery-timeline-grid', $self).css('right'));
                    if (Math.floor(width-right-outerWidth)) {
                        $('.jquery-timeline-grid, .jquery-timeline-items', $self).animate({
                            right: '-=' + (width-outerWidth >= outerWidth ? outerWidth : width-outerWidth)
                        });
                    }
                })
            ).append(
                $('<div>').addClass('jquery-timeline-container').append(
                    $grid.width(width)
                ).append(
                    $items.width(width)
                ).width(outerWidth)
            ).append(
                $('<a>', {href: '#'}).addClass('jquery-timeline-next').on('click', function(event){
                    event.preventDefault();
                    var right = -1*parseInt($('.jquery-timeline-grid', $self).css('right'));
                    if (Math.floor(right)) {
                        $('.jquery-timeline-grid, .jquery-timeline-items', $self).animate({
                            right: '+=' + (Math.floor(right) >= outerWidth ? outerWidth : Math.floor(right))
                        });
                    }
                })
            );
        }).find('.jquery-timeline-item:last').trigger('click');

        return this;
    };
})(jQuery);