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
        listeners: {
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
        this.setValues(this.defaultValues);
        this.setValues(this.record);
        abstractModule.window.abstract.superclass.renderForm.call(this);
    },

    renderFormPanel: function () {
        var form = [];
        Ext.iterate(this.formInputs, function (name, config) {
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
    }
});
Ext.reg('abstractmodule-window-abstract', abstractModule.window.abstract);