define([], function () {
    'use strict'
    return function (originalMessages) {
        return originalMessages.extend({
            defaults: {
                hideTimeout: 1000,
                hideSpeed: 1000
            }
        });
    }
})
