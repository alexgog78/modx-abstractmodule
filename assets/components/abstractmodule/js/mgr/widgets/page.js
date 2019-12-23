'use strict';

abstractModule.page.abstract = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        //Custom settings
        url: Mailing.config.connectorUrl,
        formpanel: 'mailing-formpanel-template',
        components: []
    });
    abstractModule.page.abstract.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.page.abstract, MODx.Component, {
    _loadActionButtons: function () {
        this.config.buttons = this.config.buttons || this.getButtons();
        abstractModule.page.abstract.superclass._loadActionButtons.call(this);
    },

    getButtons: function () {
        return [];
    }
});
