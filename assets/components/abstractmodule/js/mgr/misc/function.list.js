'use strict';

abstractModule.function = {
    getPanelHeader: function (html) {
        if (!html) {
            this.header = true;
            return {};
        }
        return {
            xtype: 'modx-header',
            //id: this.id ? this.id + '-header' : '',
            itemId: '',
            html: html
        };
    },

    getPanelDescription: function (html) {
        if (!html) {
            return {};
        }
        return {
            xtype: 'modx-description',
            itemId: '',
            html: '<p>' + html + '</p>'
        };
    },

    getPanelMainPart: function (html) {
        return {
            cls: 'x-form-label-left',
            items: html || []
        }
    },

    getPanelContent: function (html) {
        return {
            layout: 'anchor',
            cls: 'main-wrapper',
            preventRender: true,
            items: html || []
        };
    },

    getFormInput: function (name, config = {}) {
        return Ext.applyIf(config, {
            xtype: 'textfield',
            name: name,
            hiddenName: name,
            fieldLabel: name,
            anchor: '100%'
        });

        /*return Ext.apply({
            xtype: 'textfield',
            name: name,
            hiddenName: name,
            fieldLabel: name,
            anchor: '100%'
        }, config);*/
    },

    //TODO check
    //TODO save state
    getTabs: function (tabs) {
        var html = [];
        Ext.iterate(tabs, function (tabData) {
            var tab = this.getTab(tabData.title, tabData.items);
            html.push(tab);
        }, this);
        return {
            xtype: 'modx-tabs',
            defaults: {
                layout: 'form',
                border: false,
                autoHeight: true,
                layoutOnTabChange: true,
                labelAlign: 'top'
            },
            deferredRender: false,
            /*defaults: {
                autoHeight: true,
                layout: 'form',
                labelWidth: 150,
                bodyCssClass: 'tab-panel-wrapper',
                layoutOnTabChange: true
            },*/
            items: html
            /*xtype: 'modx-tabs',
            //layout: 'form',
            items: html,
            //items: v
            cls: 'structure-tabs'*/
        }
    },

    getVerticalTabs: function (tabs) {
        var html = [];
        Ext.iterate(tabs, function (tabData) {
            var tab = this.getTab(tabData.title, tabData.items);
            html.push(tab);
        }, this);
        return {
            xtype: 'modx-vtabs',
            //autoTabs: true,
            //border: false,
            //plain: true,
            deferredRender: false,
            //id: this.id + '-vtabs',
            items: html,
            //items: [],
        };
    },

    getTab: function (title, html) {
        return {
            defaults: {
                msgTarget: 'under',
                anchor: '100%'
            },
            title: title,
            items: html
        };
    }
}
