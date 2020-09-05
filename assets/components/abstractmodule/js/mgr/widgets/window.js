'use strict';

AbstractModule.window.abstract = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        //Custom settings
        url: null,
        action: null,
        record: [],
        fields: [],
        width: config.width || 600,

        //Core settings
        //autoHeight: true,
    });
    AbstractModule.window.abstract.superclass.constructor.call(this, config);
    this.on('beforeshow', this.beforeshow, this);
    this.on('hide', this.onhide, this);
    this.on('beforeSubmit', this.beforeSubmit, this);
    this.on('success', this.success, this);
    this.on('failure', this.failure, this);
};
Ext.extend(AbstractModule.window.abstract, MODx.Window, {
    defaultValues: {},

    renderForm: function () {
        this.setValues(this.defaultValues);
        this.setValues(this.record);
        AbstractModule.window.abstract.superclass.renderForm.call(this);
    },

    getFields: function (config) {
        return this.fields;
    },

    getFormInput: function (name, config = {}) {
        return AbstractModule.component.inputField(name, config);
    },

    _loadForm: function () {
        this.config.fields = this.getFields(this.config);
        AbstractModule.window.abstract.superclass._loadForm.call(this);
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
