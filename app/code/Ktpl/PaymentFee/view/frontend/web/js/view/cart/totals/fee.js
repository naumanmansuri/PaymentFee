
/*global define*/
define(
    [
        'Ktpl_PaymentFee/js/view/cart/summary/fee'
    ],
    function (Component) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'Ktpl_PaymentFee/cart/totals/fee'
            },
            /**
             * @override
             *
             * @returns {boolean}
             */
            isDisplayed: function () {                
                return this.getPureValue() != 0;
            }
        });
    }
);
