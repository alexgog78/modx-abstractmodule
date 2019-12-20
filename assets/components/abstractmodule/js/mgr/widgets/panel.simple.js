'use strict';

abstractModule.panel.simple = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        //Custom settings
        pageHeader: '',
        panelContent: [],

        //Core settings
        cls: 'container'
    });
    abstractModule.panel.simple.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.panel.simple, MODx.Panel, {
    initComponent: function() {
        this.panelContent = this.getContent();
        this.items = [
            this.renderHeader(this.pageHeader),
            this.renderMain(this.panelContent),
        ];
        abstractModule.panel.simple.superclass.initComponent.call(this);
    },

    getContent: function () {
        return this.panelContent;
    },

    renderHeader: function (html) {
        if (!html) {
            return {};
        }
        return {
            xtype: 'modx-header',
            id: this.id ? this.id + '-header' : '',
            itemId: '',
            html: html
        };
    },

    renderMain: function (html) {
        return {
            cls: 'x-form-label-left',
            items: html || []
        }
    },

    renderDescription: function (html) {
        if (!html) {
            return {};
        }
        return {
            xtype: 'modx-description',
            itemId: '',
            html: '<p>' + html + '</p>'
        };
    },

    renderContent: function (html) {
        return {
            layout: 'anchor',
            cls: 'main-wrapper',
            //TODO
            //labelAlign: 'top',
            items: html || []
        };
    }
});
