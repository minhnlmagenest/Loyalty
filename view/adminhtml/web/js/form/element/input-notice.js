define([
    'TieuMinh_Loyalty/js/form/element/input'
], function (SingleInput) {
    'use strict';

    return SingleInput.extend({
        defaults: {
            notices: [],
            tracks: {
                notice: true
            }
        },

        /**
         * Choose notice on initialization
         *
         * @returns {*|void|Element}
         */
        initialize: function () {
            this._super()
                .chooseNotice();

            return this;
        },

        /**
         * Choose notice function
         *
         * @returns void
         */
        chooseNotice: function () {
            let checkedNoticeNumber = Number(this.checked());

            this.notice = this.notices[checkedNoticeNumber];
        },

        /**
         * Choose notice on update
         *
         * @returns void
         */
        onUpdate: function () {
            this._super();
            this.chooseNotice();
        }
    });
});
