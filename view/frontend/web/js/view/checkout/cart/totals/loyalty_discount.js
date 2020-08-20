define(
    [
        'TieuMinh_Loyalty/js/view/checkout/summary/loyalty_discount'
    ],
    function (Component) {
        'use strict';

        return Component.extend({

            /**
             * @override
             */
            isDisplayed: function () {
                return this.getPureValue() !== 0;
            }
        });
    }
);
