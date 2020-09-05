'use strict';

AbstractModule.panel.abstract = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        title: null,
        components: [],

        //Core settings
        cls: 'container',
        items: [],
    });
    AbstractModule.panel.abstract.superclass.constructor.call(this, config);
};
Ext.extend(AbstractModule.panel.abstract, MODx.Panel, {
    initComponent: function() {
        if (this.items.length == 0) {
            if (this.title) {
                this.items.push(this._getHeader(this.title));
                this.title = '';
            }
            this.components = this.getComponents(this.initialConfig);
            this.items.push(this.components);
        }
        AbstractModule.panel.abstract.superclass.initComponent.call(this);
    },

    getComponents: function (config) {
        return this.components;
    },

    renderPlainPanel: function (items) {
        return {
            cls: 'x-form-label-left',
            items: items,
        };
    },

    renderTabsPanel: function (items) {
        return AbstractModule.component.tabs(items);
    },

    getDescription: function (html, config = {}) {
        return AbstractModule.component.panelDescription(html, config);
    },

    getContent: function (items, config = {}) {
        return AbstractModule.component.panelContent(items, config);
    },

    _getHeader: function (html) {
        return AbstractModule.component.panelHeader(html);
    },
});
