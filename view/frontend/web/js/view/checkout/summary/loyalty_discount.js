define(
    [
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/quote',
        'Magento_Catalog/js/price-utils',
        'Magento_Checkout/js/model/totals'
    ],
    function (Component, quote, priceUtils, totals) {
        "use strict";
        return Component.extend({
            defaults: {
                isFullTaxSummaryDisplayed: window.checkoutConfig.isFullTaxSummaryDisplayed || false,
                template: 'TieuMinh_Loyalty/checkout/summary/loyalty_discount'
            },
            totals: quote.getTotals(),
            isTaxDisplayedInGrandTotal: window.checkoutConfig.includeTaxInGrandTotal || false,

            isDisplayed: function() {
                return this.isFullMode() && this.getPureValue() !== 0;
            },

            getValue: function() {
                let price = 0;
                if (this.totals()) {
                    price = totals.getSegment('custom_amount').value;
                }
                return this.getFormattedPrice(price);
            },
            getPureValue: function() {
                let price = 0;
                if (this.totals()) {
                    price = totals.getSegment('custom_amount').value;
                }
                return price;
            }
        });
    }

);
