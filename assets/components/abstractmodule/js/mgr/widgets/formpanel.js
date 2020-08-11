'use strict';

abstractModule.formPanel.abstract = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        //Custom settings
        title: null,
        components: [],
        url: null,
        baseParams: {
            action: null
        },

        //Core settings
        items: [],
        cls: 'container',
        bodyStyle: '',
        //header: false,
        useLoadingMask: true,
        listeners: {
            'setup': {fn: this.setup, scope: this},
            'success': {fn: this.success, scope: this},
            'beforeSubmit': {fn: this.beforeSubmit, scope: this}
        }
    });
    abstractModule.formPanel.abstract.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.formPanel.abstract, MODx.FormPanel, {
    /*initComponent: function () {
        if (this.title) {
            this.items.push(this._getHeader(this.title));
            this.title = '';
        }
        console.log(this.items);
        abstractModule.formPanel.abstract.superclass.initComponent.call(this);
    },*/


    setup: function () {
        //if (this.initialized) { this.clearDirty(); return true; }
        console.log(this.config.record);
        //this.setValues(this.defaultValues);
        //this.setValues(this.record);
        //console.log(this)

        this.fireEvent('ready',this.config.record);
        MODx.fireEvent('ready');
        //this.initialized = true;

        /*if (!this.recordId) {
            this.fireEvent('ready');
            return true;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: this.baseParams.actionGet,
                id: this.recordId
            },
            listeners: {
                success: {
                    fn: function (response) {
                        var object = response.object;
                        //TODO remove
                        console.log(object);
                        this.setValues(object);
                        this.fireEvent('ready', object);

                        //if (MODx.onLoadEditor) { MODx.onLoadEditor(this); }
                        //this.clearDirty();

                        MODx.fireEvent('ready');
                    }, scope: this
                }
            }
        });*/
    },

    /*setValues: function (object) {
        this.getForm().setValues(object);
    },*/

    success: function (o) {
        this.record = o.result.object;
        //this.getForm().setValues(o.result.object);
        //console.log(this.record);
        return true;
    },

    beforeSubmit: function (o) {
        return true;
    },

    _getHeader: function (html) {
        return abstractModule.component.panelHeader(html);
    },
});
