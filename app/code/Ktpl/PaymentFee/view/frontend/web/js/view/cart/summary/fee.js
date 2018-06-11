/*global define*/
define(
        [
            'Magento_Checkout/js/view/summary/abstract-total',
            'Magento_Checkout/js/model/quote',
            'Magento_Checkout/js/model/totals'
        ],
        function (Component, quote, totals) {
            "use strict";
            return Component.extend({
                defaults: {
                    template: 'Ktpl_PaymentFee/cart/summary/fee'
                },
                totals: quote.getTotals(),
                isDisplayed: function () {                    
                    return (this.getPureValue() != this.getFormattedPrice(0) && quote.paymentMethod() != null);
                },
                getPaymentFee: function () {
                    if (!this.totals()) {
                        return null;
                    }
                    return totals.getSegment('fee_amount').value;
                },
                getPureValue: function () {
                    var price = 0;
                    if (this.totals() && totals.getSegment('fee_amount').value) {
                        price = parseFloat(totals.getSegment('fee_amount').value);
                    }
                    return price;
                },
                getValue: function () {
                    return this.getFormattedPrice(this.getPureValue());
                },
                getCustomFeeLabel: function () {
                    var title = "Processing Fee";
                    if (quote.paymentMethod() != null) {
                        if (window.checkoutConfig.MethodWiseLabel[quote.paymentMethod().method] != "undefined") {
                            title = window.checkoutConfig.MethodWiseLabel[quote.paymentMethod().method];
                        }
                    }
//                    $.ajax({
//                        'async': false,
//                        url: urlBuilder.build('paymentfee/checkout/labels'),
//                        data: {priceData: price},
//                        type: 'post',
//                        dataType: 'json',
//                        /** @inheritdoc */
//                        success: function (resData) {
//                            //elem.attr('disabled', null);
//                            tital = resData.title;
//                        }
//
//                    });                    
                    return title;
                }
            });
        }
);