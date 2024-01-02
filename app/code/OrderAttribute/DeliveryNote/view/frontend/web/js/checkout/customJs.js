define([
    'ko',
    'jquery',
    'uiComponent',
    'mage/url'
], function (ko, $, Component, url) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'OrderAttribute_DeliveryNote/checkout/customTextarea'
        },
        initObservable: function () {
            this._super().observe({
                CheckVals: ko.observable('')
            });

            var self = this;

            this.CheckVals.subscribe(function (newValue) {
                var linkUrls = url.build('orders/checkout/saveInQuote');
                $.ajax({
                    showLoader: true,
                    url: linkUrls,
                    data: { checkVal: newValue },
                    type: "POST",
                    dataType: 'json'
                })
                .done(function (data) {
                    console.log('success');
                })
                .fail(function (xhr, status, error) {
                    console.error('Error:', error);
                });
            });

            return this;
        }
    });
});
