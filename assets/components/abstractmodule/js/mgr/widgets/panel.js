'use strict';

abstractModule.panel.abstract = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'abstractmodule-panel';
    }
    Ext.applyIf(config, {
        cls: 'container'
    });
    abstractModule.panel.abstract.superclass.constructor.call(this, config);
    this.config = config;
};
Ext.extend(abstractModule.panel.abstract, MODx.Panel, {
    pageHeader: '',

    panelTabs: [],

    initComponent: function() {
        this.items = [
            this.renderPanelHeader(this.pageHeader),
            {
                xtype: 'modx-tabs',
                stateful: true,
                stateId: this.id + '-tabs',
                stateEvents: ['tabchange'],
                getState: function () {
                    return {
                        activeTab: this.items.indexOf(this.getActiveTab())
                    };
                },
                items: this.renderPanelTabs(),
            }
        ];
        abstractModule.panel.abstract.superclass.initComponent.call(this);
    },

    renderPanelTabs: function () {
        return this.panelTabs;
    },

    renderPanelHeader: function (html) {
        return {
            xtype: 'modx-header',
            itemId: '',
            html: html
        };
    },

    renderPanelTab: function (title, items) {
        return {
            title: title,
            layout: 'anchor',
            items: items
        };
    },

    renderPanelDescription: function (html) {
        return {
            xtype: 'modx-description',
            itemId: '',
            html: '<p>' + html + '</p>'
        };
    },

    renderPanelContent: function (content) {
        return {
            layout: 'anchor',
            cls: 'main-wrapper',
            //labelAlign: 'top',
            items: content
        };
    }
});
Ext.reg('abstractmodule-panel-abstract', abstractModule.panel.abstract);