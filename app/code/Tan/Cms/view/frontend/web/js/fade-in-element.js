define(['jquery'], function ($) {
    'use strict'

    return function (element, duration) {
        if (!$(element).hasClass('widget-img')) {
            $(element).hide().fadeIn(duration || 2000)
        }
    }
})
