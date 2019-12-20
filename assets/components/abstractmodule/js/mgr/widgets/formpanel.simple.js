'use strict';

abstractModule.formPanel.simple = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        //Custom settings
        pageHeader: '',
        panelContent: [],
        recordId: null,
        baseParams: {},

        //Core settings
        cls: 'container',
        header: false,
        useLoadingMask: true,
        listeners: {
            'setup': {fn: this.setup, scope: this},
            'success': {fn: this.success, scope: this},
            'beforeSubmit': {fn: this.beforeSubmit, scope: this}
        }
    });
    abstractModule.formPanel.simple.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.formPanel.simple, MODx.FormPanel, {
    initComponent: function () {
        this.panelContent = this.getContent();
        this.items = [
            this.renderHeader(this.pageHeader),
            this.renderMain(this.panelContent),
        ];
        abstractModule.formPanel.simple.superclass.initComponent.call(this);
    },

    getContent: function () {
        return this.panelContent;
    },

    //TODO maybe function. Dublicate in Panel
    renderHeader: function (html) {
        if (!html) {
            this.header = true;
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
    },

    setup: function () {
        if (!this.recordId) {
            this.fireEvent('ready');
            return true;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: this.baseParams.action,
                id: this.recordId
            },
            listeners: {
                success: {
                    fn: function (response) {
                        var object = response.object;
                        this.setValues(object);

                        this.fireEvent('ready', object);
                        MODx.fireEvent('ready');
                    }, scope: this
                }
            }
        });
    },

    setValues: function (object) {
        this.getForm().setValues(object);
    },

    success: function (o) {
        return true;
    },

    beforeSubmit: function (o) {
        return true;
    },
});
