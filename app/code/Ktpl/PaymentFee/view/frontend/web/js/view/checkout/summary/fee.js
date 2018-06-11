define(
        [
            'jquery',
            'Magento_Checkout/js/view/summary/abstract-total',
            'Magento_Checkout/js/model/quote',
            'Magento_Catalog/js/price-utils',
            'Magento_Checkout/js/model/totals'
        ],
        function ($, Component, quote, priceUtils, totals) {
            "use strict";
            return Component.extend({
                defaults: {
                    isFullTaxSummaryDisplayed: window.checkoutConfig.isFullTaxSummaryDisplayed || false,
                    template: 'Ktpl_PaymentFee/checkout/summary/fee'
                },
                totals: quote.getTotals(),
                isTaxDisplayedInGrandTotal: window.checkoutConfig.includeTaxInGrandTotal || false,
                isDisplayed: function () {                    
                    return (this.getValue() != this.getFormattedPrice(0) && quote.paymentMethod() != null);
                },
                getValue: function () {
                    var price = 0;
                    if (this.totals()) {
                        price = totals.getSegment('fee_amount').value;
                    }
                    
                    return this.getFormattedPrice(price);
                },
                getBaseValue: function () {
                    var price = 0;
                    if (this.totals()) {
                        price = this.totals().base_payment_charge;
                    }
                    return priceUtils.formatPrice(price, quote.getBasePriceFormat());
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