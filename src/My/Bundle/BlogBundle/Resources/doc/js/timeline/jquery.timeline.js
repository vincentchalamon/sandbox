Date.prototype.getDayOfYear = function () {
    var start = new Date();
    start.setDate(1);
    start.setMonth(0);
    start.setFullYear(this.getFullYear());

    return Math.round((this.getTime() - start.getTime()) / (24 * 3600 * 1000));
};

Date.prototype.eve = function () {
    this.setDate(31);
    this.setMonth(11);

    return this;
};

/*
 * Timeline jQuery Plugin
 * Generate a css timeline with jQuery 1.8+
 *
 * Copyright (c) Vincent CHALAMON <vincentchalamon@gmail.com>
 *
 * Version: 1.1
 */
(function($) {
    function Timeline() {
        this.regional = [];
        this.regional[''] = {
            today: 'Today',
            previous: 'Previous',
            next: 'Next'
        };
        this.settings = {
            end: new Date().getFullYear(),
            start: null,
            item: {
                width: 100,
                height: 32
            },
            elements: [],
            onSelect: null
        };
        $.extend(this.settings, this.regional['']);
    }

    $.extend(Timeline.prototype, {
        setDefaults: function (settings) {
            $.extend(this.settings, settings || {});

            return this;
        },
        getLeft: function (date) {
            return ((date.getFullYear() - this.settings.start) * this.settings.item.width) + ((date.getDayOfYear() * this.settings.item.width) / new Date().eve().getDayOfYear() + 30);
        }
    });

    $.fn.timeline = function (options) {
        var plugin = $.timeline.setDefaults(options);

        /* Check empty collection */
        if (!this.length || !plugin.settings.elements.length) {
            return this;
        }

        /* Build Timeline */
        this.each(function () {
            var $self = $(this);

            /* Convert string date in Date object */
            $.each(plugin.settings.elements, function (index, element) {
                if (typeof element.start === 'string') {
                    plugin.settings.elements[index].start = new Date(element.start);
                }
                if (typeof element.end === 'string') {
                    plugin.settings.elements[index].end = new Date(element.end);
                }
            });

            /* Set default start with first year */
            if (!plugin.settings.start) {
                plugin.settings.start = plugin.settings.elements[0].start.getFullYear();
            }

            /* Build HTML */
            $self.addClass('jquery-timeline').html('').append(
                $('<div>').addClass('jquery-timeline-container').css({
                    width: plugin.settings.elements.length * plugin.settings.item.width,
                    height: 60,
                    right: 'auto',
                    left: ((plugin.settings.elements.length * plugin.settings.item.width) - $self.width()) * -1
                }).append(
                    $('<div>').addClass('jquery-timeline-grid').append(
                        $('<span>').addClass('jquery-timeline-grid-item').append(
                            $('<span>').text(plugin.settings.today).addClass('jquery-timeline-grid-date')
                        )
                    )
                )
            ).append(
                $('<a>', {href: '#', title: plugin.settings.previous}).addClass('jquery-timeline-prev').on('click', function (event) {
                    event.preventDefault();
                    var container = $('.jquery-timeline-container', $self);
                    var left      = container.position().left;

                    /*
                     * Container width is lte timeline width
                     * Container is out of the box
                     * Container position is lte timeline width
                     */
                    if (container.width() <= $self.width() || left >= 0 || (left * -1) <= $self.width()) {
                        container.animate({left: 0});

                    /* Move container */
                    } else {
                        container.animate({left: '+=' + $self.width()});
                    }
                })
            ).append(
                $('<a>', {href: '#', title: plugin.settings.next}).addClass('jquery-timeline-next').on('click', function (event) {
                    event.preventDefault();
                    var container = $('.jquery-timeline-container', $self);

                    /* Container width is lte timeline width */
                    if (container.width() <= $self.width()) {
                        container.animate({left: 0});

                    /* Container width is lt timeline double width */
                    } else if (container.width() < ($self.width() * 2)) {
                        container.animate({left: $self.width() - container.width()});

                    /* Container width is gte timeline double width */
                    } else {
                        var left     = container.position().left > 0 ? 0 : container.position().left;
                        var position = container.width() - (left * -1) - $self.width();

                        /* No need to move container */
                        if (position > 0) {
                            /* Reset container position */
                            if (position <= $self.width()) {
                                container.animate({left: ((plugin.settings.elements.length * plugin.settings.item.width) - $self.width()) * -1});

                            /* Move container */
                            } else {
                                container.animate({left: left - $self.width()});
                            }
                        }
                    }
                })
            );

            /* Build items */
            $.each(plugin.settings.elements, function (index, element) {
                var level = $.grep(plugin.settings.elements, function (e, i) {
                    return $('#jquery-timeline-item-' + i, $self).length && element.start >= e.start && element.start <= (e.end ? e.end : new Date().eve());
                }).length;
                if (!plugin.settings.maxLevel || plugin.settings.maxLevel < level) {
                    plugin.settings.maxLevel = level;
                }

                /* Build item */
                $('<a>', {href: '#', title: element.label, id: 'jquery-timeline-item-' + index}).text(element.label).css({
                        left: $.timeline.getLeft(element.start),
                        width: $.timeline.getLeft(element.end ? element.end : new Date().eve()) - $.timeline.getLeft(element.start) - 2,
                        top: level * plugin.settings.item.height
                    }).on('click', function (event) {
                        event.preventDefault();
                        $(this).addClass('active').siblings().removeClass('active');
                        plugin.settings.onSelect(event, index);
                    }).addClass($.trim('jquery-timeline-item ' + (element.css ? element.css : '')))
                    .appendTo($('.jquery-timeline-container', $self));
            });

            /* Build grid */
            for (var year = plugin.settings.end; year >= plugin.settings.start; year--) {
                $('<span>').addClass('jquery-timeline-grid-item').append(
                    $('<span>').text(year == plugin.settings.start ? '' : year).addClass('jquery-timeline-grid-date')
                ).prependTo($('.jquery-timeline-grid', $self));
            }

            /* Fix timeline & container height */
            if ($('.jquery-timeline-container', $self).height() < (plugin.settings.item.height * (plugin.settings.maxLevel + 2))) {
                $('.jquery-timeline-container', $self).add($self).animate({height: plugin.settings.item.height * (plugin.settings.maxLevel + 2)});
            }

            /* Enable last element */
            $('jquery-timeline-item:last', $self).trigger('click');

            /* Fix container position on window resize */
            $(window).resize(function () {
                var container = $('.jquery-timeline-container', $self);
                var left      = container.position().left;
                if (left >= 0) {
                    container.css({left: 0});
                } else if ((left * -1) <= container.width()) {
                    container.css({left: ((plugin.settings.elements.length * plugin.settings.item.width) - $self.width()) * -1})
                }
            });
        });
    };

    /* Singleton instance */
    $.timeline = new Timeline();

    /* Translate in french */
    $.timeline.regional['fr'] = {
        today: 'Aujourd\'hui',
        previous: 'Précédent',
        next: 'Suivant'
    };
    $.timeline.setDefaults($.timeline.regional['fr']);

})(jQuery);