abstractModule.panel.abstract = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'abstractmodule-panel';
    }
    Ext.applyIf(config, {
        cls: 'container',
        items: [{
            id: config.id + '-header',
            html: '<h2>' + this.pageHeader + '</h2>',
            cls: 'modx-page-header'
        }, {
            xtype: 'modx-tabs',
            stateful: true,
            stateId: config.id + '-tabs',
            stateEvents: ['tabchange'],
            getState: function () {
                return {
                    activeTab: this.items.indexOf(this.getActiveTab())
                };
            },
            items: this.renderPanelTabs(config)
        }]
    });
    abstractModule.panel.abstract.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.panel.abstract, MODx.Panel, {
    pageHeader: null,

    renderPanelTabs: function (config) {
        var tab = this.renderPanelTab(
            null,
            [
                this.renderPanelDescription(null),
                this.renderPanelContent(null)
            ]
        );
        return [tab];
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
            html: '<p>' + html + '</p>',
            bodyCssClass: 'panel-desc'
        };
    },

    renderPanelContent: function (content) {
        return {
            cls: 'main-wrapper form-with-labels',
            labelAlign: 'top',
            items: content
        };
    }
});
Ext.reg('abstractmodule-panel-abstract', abstractModule.panel.abstract);