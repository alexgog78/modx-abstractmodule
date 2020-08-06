'use strict';

abstractModule.panel.abstract = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        title: null,
        components: [],

        //Core settings
        cls: 'container',
        items: [],
    });
    abstractModule.panel.abstract.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.panel.abstract, MODx.Panel, {
    initComponent: function() {
        if (this.title) {
            this.items.push(this._getHeader(this.title));
            this.title = '';
        }
        this.components = this.getComponents();
        if (this.components.length > 1) {
            this.items.push(this._renderTabsPanel());
        } else {
            this.items.push(this._renderPlainPanel());
        }
        abstractModule.panel.abstract.superclass.initComponent.call(this);
    },

    getComponents: function () {
        return this.getContent(this.components);
    },

    getDescription: function (html) {
        return abstractModule.component.panelDescription(html);
    },

    getContent: function (items) {
        return abstractModule.component.panelContent(items);
    },

    _getHeader: function (html) {
        return abstractModule.component.panelHeader(html);
    },

    _renderPlainPanel: function () {
        return {
            cls: 'x-form-label-left',
            items: this.components,
        };
    },

    _renderTabsPanel: function () {
        return abstractModule.component.tabs(this.components);
    },
});
