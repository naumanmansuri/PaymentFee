define([
    'ko',
    'Ktpl_PaymentFee/js/view/checkout/summary/fee',
    'Magento_Checkout/js/model/quote',
    'Magento_Catalog/js/price-utils',
    'Magento_Checkout/js/model/totals'
], function (ko, Component, quote, priceUtils, totals) {
    'use strict';

    var show_hide_extrafee = window.checkoutConfig.show_hide_extrafee;
    var fee_title = window.checkoutConfig.fee_title;
    var extra_fee_amount = window.checkoutConfig.extra_fee_amount;

    return Component.extend({
        totals: quote.getTotals(),
        canVisibleCustomFeeBlock: show_hide_extrafee,        
        getExtraFeeTitle: ko.observable(fee_title),
        isDisplayed: function () {
            return (this.getValue() != this.getFormattedPrice(0) && quote.paymentMethod() != null);
        },
        getValue: function () {
            var price = 0;
            if (this.totals() && totals.getSegment('fee_amount')) {
                price = totals.getSegment('fee_amount').value;
            }            
            return this.getFormattedPrice(price);
        },
        getFormattedPrice: function (price) {
            //todo add format data
            return priceUtils.formatPrice(price, quote.getPriceFormat());
        }
    });
});
