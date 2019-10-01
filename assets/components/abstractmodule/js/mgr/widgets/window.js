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
        this.config.fields = {
            layout: 'form',
            defaults: {
                msgTarget: 'under',
                anchor: '100%'
            },
            items: this.renderFormFields()
        };
        abstractModule.window.abstract.superclass._loadForm.call(this);
    },

    renderForm: function () {
        this.setValues(this.defaultValues);
        this.setValues(this.record);
        abstractModule.window.abstract.superclass.renderForm.call(this);
    },

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
        var formInput = Ext.applyIf(this.formInputs[name], {
            xtype: 'textfield',
            name: name,
            hiddenName: name,
            fieldLabel: name,
            anchor: '100%'
        }, config);
        return formInput;
    }
});
Ext.reg('abstractmodule-window-abstract', abstractModule.window.abstract);