define(['jquery'], function ($) {
    'use strict'

    return function (element, duration) {
        $(element).hide().fadeIn(duration || 2000)
    }
})
