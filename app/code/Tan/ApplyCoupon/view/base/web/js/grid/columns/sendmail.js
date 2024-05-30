define([
    'Magento_Ui/js/grid/columns/column',
    'jquery',
    'mage/template',
    'mage/validation',
    'text!Tan_ApplyCoupon/templates/grid/cells/sendcouponlink/sendmail.html',
    'Magento_Ui/js/modal/modal'
], function (Column, $, mageTemplate, validation, sendmailPreviewTemplate) {
    'use strict';

    return Column.extend({
        defaults: {
            bodyTmpl: 'ui/grid/cells/html',
            fieldClass: {
                'data-grid-html-cell': true
            }
        },
        gethtml: function (row) {
            return row[this.index + '_html'];
        },
        getFormaction: function (row) {
            return row[this.index + '_formaction'];
        },
        getFormkey: function (row) {
            return row[this.index + '_formkry'];
        },
        getEntityid: function (row) {
            return row[this.index + '_entity_id'];
        },
        getLabel: function (row) {
            return row[this.index + '_html']
        },
        getTitle: function (row) {
            return row[this.index + '_title']
        },
        getCouponlink: function (row) {
            return row[this.index + '_couponlink']
        },
        getCoupon: function (row) {
            return row[this.index + '_coupon']
        },
        getWithCoupon: function (row) {
            return row[this.index + '_couponwithlink']
        },
        preview: function (row) {
            var modalHtml = mageTemplate(
                sendmailPreviewTemplate,
                {
                    html: this.gethtml(row),
                    title: this.getTitle(row),
                    label: this.getLabel(row),
                    formaction: this.getFormaction(row),
                    formakey: this.getFormkey(row),
                    coupon: this.getCoupon(row),
                    couponwithlink:this.getWithCoupon(row),
                    couponlink: this.getCouponlink(row),
                    entityid: this.getEntityid(row),
                    name: $.mage.__('Name'),
                    email: $.mage.__('Email'),
                    comment: $.mage.__('Comment'),
                    withlink: $.mage.__('Please select Link Redirection'),
                    withlinkoption: $.mage.__('Link With Redirection'),
                    withoutlinkoption: $.mage.__('Link Without Redirection')

                }
            );
            var previewPopup = $('<div/>').html(modalHtml);
            previewPopup.modal({
                title: $.mage.__( this.getTitle(row)),
                innerScroll: true,
                modalClass: '_email-box',
                buttons: [{
                    type:'submit',
                    text: $.mage.__('Send Now'),
                    class: 'action close-popup wide',
                    click: function () {
                        $("form").validation().submit();
                    }}
                ]}).trigger('openModal');
        },
        getFieldHandler: function (row) {
            return this.preview.bind(this, row);
        }
    });
});
