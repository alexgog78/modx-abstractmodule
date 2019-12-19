'use strict';

abstractModule.panel.tabs = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        //Custom settings
        pageHeader: '',
        panelContent: []
    });
    abstractModule.panel.tabs.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.panel.tabs, abstractModule.panel.simple, {
    renderMain: function (html) {
        return {
            xtype: 'modx-tabs',
            defaults: {
                border: false,
                autoHeight: true
            },
            border: true,
            items: html || []
        }
    },

    renderTab: function (title, items) {
        return {
            title: title,
            layout: 'anchor',
            items: items
        };
    }
});
