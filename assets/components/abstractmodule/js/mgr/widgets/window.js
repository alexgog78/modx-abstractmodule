'use strict';

abstractModule.window.abstract = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'abstractmodule-window';
    }
    Ext.applyIf(config, {
        //Custom settings
        url: null,
        fields: {
            layout: 'form',
            defaults: {
                msgTarget: 'under',
                anchor: '100%'
            },
            items: this.renderFormFields()
        },

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

    renderFormFields: function () {
        var _this = this;
        var form = [];
        Ext.iterate(_this.formInputs, function (name, field) {
            var formInput = _this.renderFormInput(name);
            form.push(formInput);
        });
        return form;
    },

    renderFormInput: function (name, config) {
        var formInput = Ext.apply(this.formInputs[name], {
            name: name,
            hiddenName: name
        }, config);
        return formInput;
    },

    renderForm: function () {
        abstractModule.window.abstract.superclass.renderForm.call(this);
        this.setValues(this.defaultValues);
        this.setValues(this.record);
    }
});
Ext.reg('abstractmodule-window-abstract', abstractModule.window.abstract);