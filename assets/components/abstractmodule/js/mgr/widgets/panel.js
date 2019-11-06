'use strict';

abstractModule.panel.abstract = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'abstractmodule-panel';
    }
    Ext.applyIf(config, {
        //Custom settings
        pageHeader: '',
        panelContent: [],

        //Core settings
        cls: 'container'
    });
    abstractModule.panel.abstract.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.panel.abstract, MODx.Panel, {
    initComponent: function() {
        this.items = [
            this.renderPanelHeader(this.pageHeader),
            this.renderPanel()
        ];
        abstractModule.panel.abstract.superclass.initComponent.call(this);
    },

    renderPanel: function () {
        return [];
    },

    renderSimplePanel: function () {
        return {
            cls: 'x-form-label-left',
            items: this.panelContent
        }
    },

    renderTabsPanel: function () {
        return {
            xtype: 'abstractmodule-tabs-abstract',
            id: this.id + '-tabs',
            items: this.panelContent
        };
    },

    renderPanelHeader: function (html) {
        return {
            xtype: 'modx-header',
            itemId: '',
            html: html
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
            //TODO
            //labelAlign: 'top',
            items: content
        };
    },

    renderPanelTab: function (title, items) {
        return {
            title: title,
            layout: 'anchor',
            items: items
        };
    }
});
