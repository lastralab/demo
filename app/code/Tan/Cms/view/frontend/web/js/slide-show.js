define([
    'vue',
    'jquery'
], function (Vue, $) {
    'use strict';

    return function (imagesURLs) {
        console.log('imagesURLs:', imagesURLs, typeof imagesURLs);

        let imagesArray = [];
        if (typeof imagesURLs === 'string') {
            imagesArray = imagesURLs.split(',');
        } else if (typeof imagesURLs === 'object' && typeof imagesURLs.imagesURLs === 'string') {
            imagesArray = imagesURLs.imagesURLs.split(',');
        } else if (Array.isArray(imagesURLs)) {
            imagesArray = imagesURLs;
        }
        imagesArray = imagesArray.map(url => url.replace(/\/$/, ''));
        console.log(imagesArray);
        return new Vue({
            el: '#slide-show',
            data: {
                images: imagesArray,
                currentNumber: 0,
                timer: null
            },
            mounted: function () {
                this.startRotation();
            },
            methods: {
                startRotation: function () {
                    this.timer = setInterval(this.next, 2000);
                },

                stopRotation: function () {
                    clearTimeout(this.timer);
                    this.timer = null;
                },

                next: function () {
                    this.currentNumber = (this.currentNumber + 1) % this.images.length;
                },
                prev: function() {
                    this.currentNumber = (this.currentNumber - 1 + this.images.length) % this.images.length;
                }
            }
        });
    }
});
