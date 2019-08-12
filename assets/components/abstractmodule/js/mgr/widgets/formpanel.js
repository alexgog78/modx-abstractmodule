abstractModule.formPanel.abstract = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'abstract-formpanel';
    }
    if (!config.title) {
        config.title = '';
    }
    Ext.apply(config, {
        baseParams: {},
        border: false,
        baseCls: 'modx-formpanel',
        cls: 'container',
        useLoadingMask: true,
        items: [{
            html: '<h2>' + config.title + '</h2>',
            border: false,
            cls: 'modx-page-header',
            id: config.id + '-header'
        }, {
            xtype: 'modx-tabs',
            defaults: {
                autoHeight: true,
                layout: 'form',
                labelWidth: 150,
                msgTarget: 'under',
                bodyCssClass: 'tab-panel-wrapper',
                layoutOnTabChange: true
            },
            items: this.getPanelTabs(config)
        }],
        listeners: {
            'setup': {fn: this.setup, scope: this},
            'success': {fn: this.success, scope: this},
            'failure': {fn: this.failure, scope: this},
            'beforeSubmit': {fn: this.beforeSubmit, scope: this},
            'fieldChange': {fn: this.onFieldChange, scope: this},
            'failureSubmit': {fn: this.failureSubmit, scope: this}
        }
    });
    abstractModule.formPanel.abstract.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.formPanel.abstract, MODx.FormPanel, {
    formInputs: {},

    getPanelTabs: function (config) {
        return [];
    },

    getPanelTab: function (title, description, content) {
        return {
            title: title,
            items: [{
                html: '<p>' + description + '</p>',
                bodyCssClass: 'panel-desc'
            }, {
                cls: 'main-wrapper form-with-labels',
                labelAlign: 'top',
                items: content
            }]
        };
    },

    getFormInput: function (name, config) {
        var formInput = Ext.apply(this.formInputs[name], {
            name: name
        }, config);
        return formInput;
    },

    setup: function () {
        this.fireEvent('ready');
    },

    success: function (o) {

    },

    failure: function (o) {

    },

    beforeSubmit: function (o) {

    },

    onFieldChange: function (o) {

    },

    failureSubmit: function (o) {

    }
});
Ext.reg('abstractmodule-forpanel-abstract', abstractModule.formPanel.abstract);