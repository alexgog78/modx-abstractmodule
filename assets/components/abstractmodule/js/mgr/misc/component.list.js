'use strict';

abstractModule.component = {
    panelHeader: function (html, config = {}) {
        return Ext.applyIf(config, {
            xtype: 'modx-header',
            itemId: '',
            html: html
        });
    },

    panelDescription: function (html, config = {}) {
        return Ext.applyIf(config, {
            xtype: 'modx-description',
            itemId: '',
            html: '<p>' + html + '</p>'
        });
    },

    panelContent: function (items, config = {}) {
        return Ext.applyIf(config, {
            xtype: 'panel',
            //layout: 'anchor',
            //cls: 'main-wrapper',
            layout: 'form',
            cls: 'main-wrapper',
            preventRender: true,
            border: false,
            labelAlign: 'top',
            labelSeparator: '',
            defaults: {msgTarget: 'under', anchor: '100%'},
            items: items || []
        });
    },

    inputField: function (name, config = {}) {
        return Ext.applyIf(config, {
            xtype: 'textfield',
            name: name,
            hiddenName: name,
            fieldLabel: name,
            anchor: '100%',
        });
    },

    gridColumn: function (name, config = {}) {
        return Ext.applyIf(config, {
            dataIndex: name,
            header: name,
            sortable: true,
        });
    },

    tabs: function (tabs, config = {}) {
        if (config.stateId) {
            Ext.applyIf(config, {
                stateful: true,
                stateEvents: ['tabchange'],
                getState: function () {
                    return {activeTab: this.items.indexOf(this.getActiveTab())};
                },
            });
        }
        let items = [];
        Ext.iterate(tabs, function (tabData) {
            items.push(this.tab(tabData));
        }, this);
        return Ext.applyIf(config, {
            xtype: 'modx-tabs',
            defaults: {
                layout: 'form',
                border: false,
                autoHeight: true,
                layoutOnTabChange: true,
                labelAlign: 'top'
            },
            forceLayout: true,
            deferredRender: false,
            anchor: '100%',
            items: items,
        });
    },

    verticalTabs: function (tabs, config = {}) {
        if (config.stateId) {
            Ext.applyIf(config, {
                stateful: true,
                stateEvents: ['tabchange'],
                getState: function () {
                    return {activeTab: this.items.indexOf(this.getActiveTab())};
                },
            });
        }
        let items = [];
        Ext.iterate(tabs, function (tabData) {
            items.push(this.tab(tabData));
        }, this);
        return Ext.applyIf(config, {
            xtype: 'modx-vtabs',
            /*defaults: {
                layout: 'form',
                border: false,
                autoHeight: true,
                layoutOnTabChange: true,
                labelAlign: 'top'
            },*/
            deferredRender: false,
            anchor: '100%',
            items: items,
        });
    },

    tab: function (tab, config = {}) {
        return Ext.applyIf(config, {
            defaults: {
                msgTarget: 'under',
                anchor: '100%'
            },
            anchor: '100%',
            title: tab.title,
            items: tab.items
        });
    },

    notice: function (html, config = {}) {
        return Ext.applyIf(config, {
            html: html,
            cls: 'panel-desc',
            style: {
                fontSize: '170%',
                textAlign: 'center'
            }
        });
    },

    progressBarMessage: function (config = {}) {
        return Ext.Msg.show(Ext.applyIf(config, {
            title: _('please_wait'),
            msg: _('saving'),
            width: 410,
            progress: true,
            closable: false,
        }));
    },

    waitMessage: function (config = {}) {
        return Ext.Msg.show(Ext.applyIf(config, {
            title: _('please_wait'),
            msg: _('saving'),
            width: 300,
            wait: true,
            closable: false,
        }));
    },
};
