define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'TieuMinh_Loyalty/js/action/set-loyalty-point',
    'TieuMinh_Loyalty/js/action/cancel-loyalty-point',
    'TieuMinh_Loyalty/js/model/loyalty',
    'Magento_Checkout/js/model/totals',
    'TieuMinh_Loyalty/js/model/payment/discount-messages'
], function ($, ko, Component, quote, setLoyaltyPointAction, cancelLoyaltyPointAction, loyalty, totalModel, messageContainer) {
    'use strict';

    let totals = quote.getTotals(),
        loyaltyPoint = loyalty.getLoyaltyPoint(),
        isApplied = loyalty.getIsApplied();

    if (totals()) {
        loyaltyPoint(totalModel.getSegment('custom_amount').value);
    }

    if (loyaltyPoint() === null || loyaltyPoint() == 0) {
        isApplied(false)
    } else {
        isApplied(true)
    }
    return Component.extend({
        defaults: {
            template: 'TieuMinh_Loyalty/checkout/summary/loyalty_apply'
        },
        loyaltyPoint: loyaltyPoint,

        /**
         * Applied flag
         */
        isApplied: isApplied,

        /**
         * Coupon code application procedure
         */
        apply: function () {
            let inputPoint = document.getElementById('loyaltypoint').value,
                isPoint = inputPoint.indexOf('.'),
                isPositive = parseInt(inputPoint);

             if (isPoint !== -1) {
                 messageContainer.addErrorMessage({
                     'message': 'Please input an integer number!!!'
                 });
                // alert('Please input an integer number!!!');
            } else if (isPositive < 0){
                 messageContainer.addErrorMessage({
                     'message': 'Please input number equal or greater than 0!!!'
                 });
           } else {
                 if (this.validate()) {
                     setLoyaltyPointAction(loyaltyPoint(), isApplied);
                 }
             }
        },

        /**
         * Cancel using coupon
         */
        cancel: function () {
            if (this.validate()) {
                loyaltyPoint('');
                cancelLoyaltyPointAction(isApplied);
            }
        },
        /**
         * Coupon form validation
         *
         * @returns {Boolean}
         */
        validate: function () {
            let form = '#loyalty';
            return $(form).validation() && $(form).validation('isValid');
        }
    });
});
