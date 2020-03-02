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
    formInputs: {},
    defaultValues: {},

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
        return this.renderFormPanel(this.formInputs);
    },

    renderFormPanel: function (formInputs) {
        var form = [];
        Ext.iterate(formInputs, function (name, config) {
            var formInput = abstractModule.function.getFormInput(name, config);
            form.push(formInput);
        }, this);
        return [{
            layout: 'form',
            labelAlign: 'top',
            labelSeparator: '',
            border: false,
            defaults: {
                msgTarget: 'under',
                anchor: '100%'
            },
            items: form
        }];
        //return form;
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
        //if (this.initialized) { this.clearDirty(); return true; }
        console.log(this.record);
        this.setValues(this.defaultValues);
        this.setValues(this.record);
        //console.log(this)
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
        this.record = o.result.object;
        //this.getForm().setValues(o.result.object);
        //console.log(this.record);
        return true;
    },

    beforeSubmit: function (o) {
        return true;
    },

    //TODO renderFormInput
});
