define([
    'TieuMinh_Loyalty/js/form/element/input-notice'
], function (Input) {
    'use strict';

    return Input.extend({
        defaults: {
            imports: {
                toggleDisabled: '${ $.parentName }.loyalty rule:value'
            }
        },

        /**
         * Toggle element disabled state according to simple action value.
         *
         * @param {String} action
         */
        toggleDisabled: function (action) {
            switch (action) {
                case 'by_fixed':
                    this.disabled(true);
                    break;
                case 'by_step':
                    this.disabled(true);
                    break;
                default:
                    this.disabled(false);
            }

            if (this.disabled()) {
                this.checked(false);
            }
        }
    });
});
