define([
    'TieuMinh_Loyalty/js/view/messages',
    'TieuMinh_Loyalty/js/model/payment/discount-messages'
], function (Component, messageContainer) {
    'use strict';

    return Component.extend({
        /** @inheritdoc */
        initialize: function (config) {
            return this._super(config, messageContainer);
        }
    });
});
