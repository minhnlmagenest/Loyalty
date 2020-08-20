define([
    'jquery',
    'jquery-ui-modules/widget'
], function ($) {
    'use strict';

    $.widget('mage.loyaltyPoint', {
        options: {
        },

        /** @inheritdoc */
        _create: function () {
            this.loyaltyPoint = $(this.options.loyaltyPointSelector);
            this.removeLoyaltyPoint = $(this.options.removeLoyaltyPointSelector);

            $(this.options.applyButton).on('click', $.proxy(function () {
                this.loyaltyPoint.attr('data-validate', '{required:true}','{min=0}');
                this.removeLoyaltyPoint.attr('value', '0');
                $(this.element).validation().submit();
            }, this));

            $(this.options.cancelButton).on('click', $.proxy(function () {
                this.loyaltyPoint.removeAttr('data-validate');
                this.removeLoyaltyPoint.attr('value', '1');
                this.element.submit();
            }, this));
        }
    });

    return $.mage.loyaltyPoint;
});
