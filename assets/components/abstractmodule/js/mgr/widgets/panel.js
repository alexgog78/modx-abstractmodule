'use strict';

abstractModule.panel.abstract = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        //Custom settings
        //tabs: false,
        //header: true,
        //title: 111,
        components: [],

        //Core settings
        cls: 'container',
        items: []
    });
    abstractModule.panel.abstract.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.panel.abstract, MODx.Panel, {
    initComponent: function() {
        if (this.title) {

            //this.header = false;
            this.items.push(this.renderHeader(this.title));
            this.title = '';
        }

        var content = this.getContent();
        var panel = '';
        if (content.length > 1) {
            panel = this.renderMainTabs(content);
        } else {
            panel = this.renderMainPlain(content);
        }
        this.items.push(panel);

        abstractModule.panel.abstract.superclass.initComponent.call(this);
    },

    getContent: function () {
        return this.components;
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
