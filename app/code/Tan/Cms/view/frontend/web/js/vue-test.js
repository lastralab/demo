define([
    'vue',
    'jquery',
    'Tan_Cms/js/jquery-log'
], function (Vue, $) {
    'use strict'

    // $.log('Testing output to the console.')

    return function (config, element) {
        return new Vue({
            el: '#' + element.id,
            data: {
                loading: config.loading
            }
        })
    }
})
