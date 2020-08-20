/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Customer store credit(balance) application
 */
define([
    'jquery',
    'Magento_Checkout/js/model/quote',
    'TieuMinh_Loyalty/js/model/resource-url-manager',
    'Magento_Checkout/js/model/error-processor',
    'TieuMinh_Loyalty/js/model/payment/discount-messages',
    'mage/storage',
    'Magento_Checkout/js/action/get-payment-information',
    'Magento_Checkout/js/model/totals',
    'mage/translate',
    'Magento_Checkout/js/model/full-screen-loader',
    'Magento_Checkout/js/action/recollect-shipping-rates'
], function ($, quote, urlManager, errorProcessor, messageContainer, storage, getPaymentInformationAction, totals, $t,
  fullScreenLoader, recollectShippingRates
) {
    'use strict';

    let successCallbacks = [],
        action,
        callSuccessCallbacks;

    /**
     * Execute callbacks when a coupon is successfully canceled.
     */
    callSuccessCallbacks = function () {
        successCallbacks.forEach(function (callback) {
            callback();
        });
    };

    /**
     * Cancel applied coupon.
     *
     * @param {Boolean} isApplied
     * @returns {Deferred}
     */
    action =  function (isApplied) {
        let quoteId = quote.getQuoteId(),
            url = urlManager.getCancelLoyaltyPointUrl(quoteId),
            message = $t('Your point was successfully removed.');

        messageContainer.clear();
        fullScreenLoader.startLoader();

        return storage.delete(
            url,
            false,
            null
        ).done(function () {
            let deferred = $.Deferred();

            totals.isLoading(true);
            recollectShippingRates();
            getPaymentInformationAction(deferred);
            $.when(deferred).done(function () {
                isApplied(false);
                totals.isLoading(false);
                fullScreenLoader.stopLoader();
                //Allowing to tap into coupon-cancel process.
                callSuccessCallbacks();
            });
            messageContainer.addSuccessMessage({
                'message': message
            });
        }).fail(function (response) {
            totals.isLoading(false);
            fullScreenLoader.stopLoader();
            errorProcessor.process(response, messageContainer);
        });
    };

    /**
     * Callback for when the cancel-coupon process is finished.
     *
     * @param {Function} callback
     */
    action.registerSuccessCallback = function (callback) {
        successCallbacks.push(callback);
    };

    return action;
});
