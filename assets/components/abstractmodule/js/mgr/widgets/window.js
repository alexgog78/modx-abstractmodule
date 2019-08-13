abstractModule.window.abstract = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'abstract-window';
    }
    Ext.applyIf(config, {
        url: null,
        width: config.width || 600,
        fields: this.getFormFields(),
        listeners: {
            success: {fn: config.parent.refresh, scope: config.parent},
            hide: {fn: function () {
                config.parent.refresh
            }}
        }
    });
    abstractModule.window.abstract.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.window.abstract, MODx.Window, {
    formFields: [],

    defaultValues: {},

    renderForm: function() {
        abstractModule.window.abstract.superclass.renderForm.call(this);
        this.setDefault();
    },

    getFormFields: function () {
        return this.formFields;
    },

    setDefault: function () {
        this.setValues(this.defaultValues);
    },

    setRecord: function (record) {
        this.setValues(record);
    }
});
Ext.reg('abstractmodule-window-abstract', abstractModule.window.abstract);