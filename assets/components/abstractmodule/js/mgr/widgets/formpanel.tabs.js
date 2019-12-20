'use strict';

abstractModule.formPanel.tabs = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        //Custom settings
        pageHeader: '',
        panelContent: []
    });
    abstractModule.formPanel.tabs.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.formPanel.tabs, abstractModule.formPanel.simple, {
    /*renderMain: function (html) {
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
    }*/
});
