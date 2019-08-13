abstractModule.panel.abstract = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'abstract-panel';
    }
    Ext.apply(config, {
        cls: 'container',
        items: [
            this.getPanelHeader(),
            {
                xtype: 'modx-tabs',
                stateful: true,
                stateId: config.id + '-vtabs',
                stateEvents: ['tabchange'],
                getState: function () {
                    return {
                        activeTab: this.items.indexOf(this.getActiveTab())
                    };
                },
                items: this.getPanelTabs()
            }
        ]
    });
    abstractModule.panel.abstract.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.panel.abstract, MODx.Panel, {
    pageHeader: '',

    panelTabs: [],

    getPanelHeader: function () {
        return {
            html: '<h2>' + this.pageHeader + '</h2>',
            cls: 'modx-page-header'
        };
    },

    getPanelTabs: function () {
        panelTabs = [];
        Ext.each(this.panelTabs, function (tab) {
            panelTabs.push({
                title: tab.title,
                layout: 'anchor',
                items: [{
                    html: '<p>' + tab.description + '</p>',
                    bodyCssClass: 'panel-desc'
                }, {
                    xtype: tab.xtype,
                    cls: 'main-wrapper'
                }]
            });
        });
        return panelTabs;
    }
});
Ext.reg('abstractmodule-panel-abstract', abstractModule.panel.abstract);