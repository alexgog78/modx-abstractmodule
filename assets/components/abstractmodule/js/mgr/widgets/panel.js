'use strict';

abstractModule.panel.abstract = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        //Custom settings
        tabs: false,
        pageHeader: '',
        panelContent: [],

        //Core settings
        cls: 'container'
    });
    abstractModule.panel.abstract.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.panel.abstract, MODx.Panel, {
    initComponent: function() {
        this.panelContent = this.getContent();
        var content = this.renderMainPlain(this.panelContent);
        if (this.tabs) {
            content = this.renderMainTabs(this.panelContent);
        }
        this.items = [
            this.renderHeader(this.pageHeader),
            content,
        ];
        abstractModule.panel.abstract.superclass.initComponent.call(this);
    },

    getContent: function () {
        return this.panelContent;
    },

    renderMainPlain: function (html) {
        return abstractModule.function.getPanelMainPart(html);
    },

    renderMainTabs: function (tabs) {
        return abstractModule.function.getTabs(tabs);
    },

    renderHeader: function (html) {
        return abstractModule.function.getPanelHeader(html);
    },

    renderDescription: function (html) {
        return abstractModule.function.getPanelDescription(html);
    },

    renderContent: function (html) {
        return abstractModule.function.getPanelContent(html);
    }
});
