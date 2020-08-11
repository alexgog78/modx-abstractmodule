'use strict';

abstractModule.window.abstract = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        //Custom settings
        url: null,
        action: null,
        record: [],
        fields: [],

        //Core settings
        width: config.width || 600,
        //autoHeight: true,
    });
    abstractModule.window.abstract.superclass.constructor.call(this, config);
    this.on('beforeshow', this.beforeshow, this);
    this.on('hide', this.onhide, this);
    this.on('beforeSubmit', this.beforeSubmit, this);
    this.on('success', this.success, this);
    this.on('failure', this.failure, this);
};
Ext.extend(abstractModule.window.abstract, MODx.Window, {
    defaultValues: {},

    renderForm: function () {
        this.setValues(this.defaultValues);
        this.setValues(this.record);
        abstractModule.window.abstract.superclass.renderForm.call(this);
    },

    getFields: function (config) {
        return [];
    },

    getFormInput: function (name, config = {}) {
        return abstractModule.component.inputField(name, config);
    },

    _loadForm: function () {
        if (this.config.fields.length == 0) {
            this.config.fields = this.getFields(this.config);
        }
        abstractModule.window.abstract.superclass._loadForm.call(this);
    },

    beforeshow: function () {
        this.reset();
        return true;
    },

    onhide: function () {
        return true;
    },

    beforeSubmit: function (record) {
        return true;
    },

    success: function (result) {
    },

    failure: function (result) {
    }
});
