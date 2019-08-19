//TODO
abstractModule.combo.multiSelectRemote = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        /*store: new Ext.data.JsonStore({
            id: (config.id || 'multiselect-remote') + '-store',
            url: null,
            baseParams: {
                action: null,
                combo: true
            },
            fields: [],
            root: 'results',
            totalProperty: 'total',
            autoLoad: true,
            autoSave: false,
        }),*/
        displayField: null,
        valueField: null,
        allowAddNewData: false,
        pageSize: 200,
        xtype: 'superboxselect',
        name: config.name || 'multiselect-remote',
        mode: 'remote',
        minChars: 1,
        allowBlank: true,
        msgTarget: 'under',
        addNewDataOnBlur: true,
        //resizable: true,
        anchor: '100%',
        extraItemCls: 'x-tag',
        expandBtnCls: 'x-form-trigger',
        clearBtnCls: 'x-form-trigger',
        triggerAction: 'all',
        listeners: {},
    });
    config.name += '[]';
    abstractModule.combo.multiSelectRemote.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.combo.multiSelectRemote, Ext.ux.form.SuperBoxSelect);
Ext.reg('abstract-combo-multiselect-remote', abstractModule.combo.multiSelectRemote);