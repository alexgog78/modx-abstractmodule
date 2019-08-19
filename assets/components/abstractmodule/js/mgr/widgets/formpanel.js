abstractModule.formPanel.abstract = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'abstractmodule-forpanel';
    }
    Ext.applyIf(config, {
        url: null,
        baseParams: {
            getAction: null
        },
        baseCls: 'modx-formpanel',
        cls: 'container',
        useLoadingMask: true,
        items: [{
            id: config.id + '-header',
            html: '<h2>' + this.pageHeader + '</h2>',
            cls: 'modx-page-header'
        }, {
            xtype: 'modx-tabs',
            /*stateful: true,
            stateId: config.id + '-vtabs',
            stateEvents: ['tabchange'],
            getState: function () {
                return {
                    activeTab: this.items.indexOf(this.getActiveTab())
                };
            },*/
            defaults: {
                autoHeight: true,
                layout: 'form',
                labelWidth: 150,
                msgTarget: 'under',
                bodyCssClass: 'tab-panel-wrapper',
                layoutOnTabChange: true
            },
            items: this.renderPanelTabs(config)
        }],
        listeners: {
            'setup': {fn: this.setup, scope: this},
            'success': {fn: this.success, scope: this},
            'failure': {fn: this.failure, scope: this},
            'beforeSubmit': {fn: this.beforeSubmit, scope: this},
            'fieldChange': {fn: this.fieldChange, scope: this},
            'failureSubmit': {fn: this.failureSubmit, scope: this}
        }
    });
    abstractModule.formPanel.abstract.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.formPanel.abstract, MODx.FormPanel, {
    pageHeader: null,

    formInputs: {},

    renderPanelTabs: function (config) {
        var tab = this.renderPanelTab(
            null,
            [
                this.renderPanelDescription(null),
                this.renderPanelContent(null)
            ]
        );
        return [tab];
    },

    renderPanelTab: function (title, items) {
        return {
            title: title,
            layout: 'anchor',
            items: items
        };
    },

    renderPanelDescription: function (html) {
        return {
            html: '<p>' + html + '</p>',
            bodyCssClass: 'panel-desc'
        };
    },

    renderPanelContent: function (content) {
        return {
            cls: 'main-wrapper form-with-labels',
            labelAlign: 'top',
            items: content
        };
    },

    renderFormInput: function (name, config) {
        if (!this.formInputs[name]) {
            return {
                xtype: 'displayfield',
                fieldLabel: '<span class="x-form-invalid-msg">Undefined field "' + name + '"</span>'
            };
        }
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

    fieldChange: function (o) {

    },

    failureSubmit: function (o) {

    }
});
Ext.reg('abstractmodule-forpanel-abstract', abstractModule.formPanel.abstract);