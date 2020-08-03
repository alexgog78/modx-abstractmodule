'use strict';

abstractModule.page.abstract = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        //Custom settings
        url: null,
        formpanel: null,
        components: [],

        //Core settings
        buttons: []
    });
    abstractModule.page.abstract.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.page.abstract, MODx.Component, {
    initComponent: function () {
        abstractModule.page.abstract.superclass.initComponent.call(this);
    },

    _loadActionButtons: function () {
        this.config.buttons = this.config.buttons.length ? this.config.buttons : this.getButtons(); //TODO no IF!!!
        abstractModule.page.abstract.superclass._loadActionButtons.call(this);
    },

    getButtons: function () {
        return [];
    }
});
