'use strict';

abstractModule.window.abstract = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'abstractmodule-window';
    }
    Ext.applyIf(config, {
        //Custom settings
        url: null,

        //Core settings
        width: config.width || 600,
        autoHeight: true,
        //TODO
        //allowDrop: false,
        //resizable: false,
        listeners: {
            beforeSubmit: {fn: this.beforeSubmit, scope: this},
            success: {fn: config.parent.refresh, scope: config.parent},
            hide: {
                fn: function () {
                    config.parent.refresh
                }
            }
        }
    });
    abstractModule.window.abstract.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.window.abstract, MODx.Window, {
    formInputs: {},

    defaultValues: {},

    _loadForm: function() {
        this.config.fields = this.renderFormPanel();
        abstractModule.window.abstract.superclass._loadForm.call(this);
    },

    renderForm: function () {
        this.setValues(this.record);
        if (!this.record) {
            this.setValues(this.defaultValues);
        }
        abstractModule.window.abstract.superclass.renderForm.call(this);
    },

    renderFormPanel: function (formInputs) {
        if (!formInputs) {
            formInputs = this.formInputs;
        }

        var form = [];
        Ext.iterate(formInputs, function (name, config) {
            var formInput = this.renderFormInput(name, config);
            form.push(formInput);
        }, this);
        return form;
    },

    renderFormFieldset: function (fields = {}) {
        var fieldset = [];
        Ext.each(fields, function (name) {
            var formInput = this.renderFormInput(name, this.formInputs[name]);
            fieldset.push(formInput);
        }, this);
        return fieldset;
    },

    renderFormInput: function (name, config = {}) {
        var formInput = Ext.applyIf(config, {
            xtype: 'textfield',
            name: name,
            hiddenName: name,
            fieldLabel: name,
            anchor: '100%'
        });
        return formInput;
    },

    beforeSubmit: function (record) {
        return true;
    }
});
