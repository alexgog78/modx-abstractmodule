'use strict';

//TODO maybe remove
abstractModule.tabs.abstract = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'abstractmodule-tabs';
    }
    Ext.applyIf(config, {
        //Custom settings
        stateful: true,

        //Core settings
        stateEvents: ['tabchange'],
    });
    abstractModule.tabs.abstract.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.tabs.abstract, MODx.Tabs, {
    initComponent: function() {
        this.stateId = this.id + '-tabs';
        abstractModule.tabs.abstract.superclass.initComponent.call(this);
    },

    getState: function () {
        return {
            activeTab: this.items.indexOf(this.getActiveTab())
        };
    }
});
//TODO
Ext.reg('abstractmodule-tabs-abstract', abstractModule.tabs.abstract);
