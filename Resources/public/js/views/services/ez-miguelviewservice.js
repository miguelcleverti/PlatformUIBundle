/*
 * Copyright (C) eZ Systems AS. All rights reserved.
 * For full copyright and license information view LICENSE file distributed with this source code.
 */
YUI.add('ez-miguelviewservice', function (Y) {
    'use strict';
    /**
     * Provides the view service component for the trash view
     *
     * @module ez-trashviewservice
     */
    Y.namespace('eZ');

    /**
     * Miguel view service.
     *
     * Do stuff
     *
     * @namespace eZ
     * @class TrashViewService
     * @constructor
     * @extends eZ.ViewService
     */
    Y.eZ.MiguelViewService = Y.Base.create('miguelViewService', Y.eZ.ViewService, [], {

        initializer: function () {
            this.on('behat:bacalhau', function (e) {
                console.log('miguel fired');
            });
            Y.fire('behat:bacalhau');
        },

        /**
         * Gets the ez-rest-client instance.
         *
         * @method _loadCapi
         * @protected
         * @param {Function} callback
         */
        _goMiguel: function () {
            console.log('miguel fired');
        },
    }, {
        ATTRS: {
        }
    });
});
