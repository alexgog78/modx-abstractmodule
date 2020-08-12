'use strict';

abstractModule.formPanel.abstract = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        //Custom settings
        url: null,
        baseParams: {
            action: null
        },
        title: null,
        components: [],

        //Core settings
        items: [],
        cls: 'container form-with-labels',
        listeners: {
            'setup': {fn: this.setup, scope: this},
            'success': {fn: this.success, scope: this},
            'beforeSubmit': {fn: this.beforeSubmit, scope: this}
        }
    });
    abstractModule.formPanel.abstract.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.formPanel.abstract, MODx.FormPanel, {
    defaultValues: {},

    initComponent: function() {
        if (this.items.length == 0) {
            if (this.title) {
                this.items.push(this._getHeader(this.title));
                this.title = '';
            }
            this.components = this.getComponents(this.initialConfig);
            this.items.push(this.components);
        }
        abstractModule.formPanel.abstract.superclass.initComponent.call(this);
    },

    setup: function () {
        this._setValues(this.defaultValues);
        this._setValues(this.record);
        console.log(this.record);
        this.fireEvent('ready', this.record);
        MODx.fireEvent('ready');
    },

    success: function (o) {
        this.record = o.result.object;
        return true;
    },

    beforeSubmit: function (o) {
        return true;
    },

    getComponents: function (config) {
        return this.components;
    },

    renderPlainPanel: function (items) {
        return {
            cls: 'x-form-label-left',
            items: items,
        };
    },

    renderTabsPanel: function (items) {
        return abstractModule.component.tabs(items, {
            bodyCssClass: 'tab-panel-wrapper'
        });
    },

    getDescription: function (html) {
        return abstractModule.component.panelDescription(html);
    },

    getContent: function (items) {
        return abstractModule.component.panelContent(items);
    },

    getFormInput: function (name, config = {}) {
        return abstractModule.component.inputField(name, config);
    },

    _getHeader: function (html) {
        return abstractModule.component.panelHeader(html);
    },

    _setValues: function (object) {
        this.getForm().setValues(object);
    },
});
