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

    //TODO check
    getTabs: function (tabs) {
        var html = [];
        Ext.iterate(tabs, function (tab) {
            var tab = this.getTab(tab.title, tab.items);
            html.push(tab);
        }, this);
        return {
            xtype: 'modx-tabs',
            defaults: {
                layout: 'form',
                border: false,
                autoHeight: true,
                layoutOnTabChange: true,

                //layout: 'form',
                labelAlign: 'top',
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

    getTab: function (title, html) {
        return {
            title: title,
            items: html
        };
    }
}
