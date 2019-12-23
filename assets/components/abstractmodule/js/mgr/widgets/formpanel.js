'use strict';

abstractModule.formPanel.abstract = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        //Custom settings
        tabs: false,
        pageHeader: '',
        panelContent: [],
        recordId: null,
        record: null,
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
    abstractModule.formPanel.abstract.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.formPanel.abstract, MODx.FormPanel, {
    initComponent: function () {
        this.panelContent = this.getContent();
        var content = this.renderMainPlain(this.panelContent);
        if (this.tabs) {
            content = this.renderMainTabs(this.panelContent);
        }
        this.items = [
            this.renderHeader(this.pageHeader),
            content,
        ];
        abstractModule.formPanel.abstract.superclass.initComponent.call(this);
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
    },

    setup: function () {
        if (this.initialized) { this.clearDirty(); return true; }
        this.setValues(this.record);
        console.log(this)
        this.fireEvent('ready');
        this.initialized = true;
        /*if (!this.recordId) {
            this.fireEvent('ready');
            return true;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: this.baseParams.actionGet,
                id: this.recordId
            },
            listeners: {
                success: {
                    fn: function (response) {
                        var object = response.object;
                        //TODO remove
                        console.log(object);
                        this.setValues(object);
                        this.fireEvent('ready', object);

                        //if (MODx.onLoadEditor) { MODx.onLoadEditor(this); }
                        //this.clearDirty();

                        MODx.fireEvent('ready');
                    }, scope: this
                }
            }
        });*/
    },

    setValues: function (object) {
        this.getForm().setValues(object);
    },

    success: function (o) {
        this.getForm().setValues(o.result.object);
        return true;
    },

    beforeSubmit: function (o) {
        return true;
    },
});
