define([
    'ko',
    'domReady!'
], function (ko) {
    'use strict';

    let loyaltyPoint = ko.observable(null),
        isApplied = ko.observable(null);

    return {
        loyaltyPoint: loyaltyPoint,
        isApplied: isApplied,

        /**
         * @return {*}
         */
        getLoyaltyPoint: function () {
            return loyaltyPoint;
        },

        /**
         * @return {Boolean}
         */
        getIsApplied: function () {
            return isApplied;
        },

        /**
         * @param loyaltyPointValue
         */
        setLoyaltyPoint: function (loyaltyPointValue) {
            loyaltyPoint(loyaltyPointValue);
        },

        /**
         * @param {Boolean} isAppliedValue
         */
        setIsApplied: function (isAppliedValue) {
            isApplied(isAppliedValue);
        }
    };
});
