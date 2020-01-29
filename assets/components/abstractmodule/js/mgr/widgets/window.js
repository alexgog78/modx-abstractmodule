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
        //autoHeight: true,
        //TODO
        //allowDrop: false,
        //resizable: false,
        listeners: {
            beforeSubmit: {fn: this.beforeSubmit, scope: this},
            success: {fn: this.success, scope: this},
        }
    });
    abstractModule.window.abstract.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.window.abstract, MODx.Window, {
    formInputs: {},
    defaultValues: {},

    _loadForm: function() {
        this.config.fields = this.renderFormPanel(this.formInputs);
        abstractModule.window.abstract.superclass._loadForm.call(this);
    },

    renderForm: function () {
        console.log(this.record);
        this.setValues(this.defaultValues);
        this.setValues(this.record);
        abstractModule.window.abstract.superclass.renderForm.call(this);
    },

    renderFormPanel: function (formInputs) {
        var form = [];
        Ext.iterate(formInputs, function (name, config) {
            var formInput = abstractModule.function.getFormInput(name, config);
            form.push(formInput);
        }, this);
        return form;
    },

    renderFormFieldset: function (fields = {}) {
        var fieldset = [];
        Ext.each(fields, function (name) {
            var formInput = abstractModule.function.getFormInput(name, this.formInputs[name]);
            fieldset.push(formInput);
        }, this);
        return fieldset;
    },

    beforeSubmit: function (record) {
        return true;
    },

    success: function () {
        this.config.parent.refresh();
    },
});
