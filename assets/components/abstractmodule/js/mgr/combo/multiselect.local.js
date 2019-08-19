abstractModule.combo.multiSelectLocal = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        store: new Ext.data.JsonStore({
            id: (config.name || 'multiselect-local') + '-store',
            fields: [],
            data: []
        }),
        displayField: null,
        valueField: null,
        dataIndex: null,
        allowAddNewData: false,
        xtype: 'superboxselect',
        name: config.name || 'multiselect-local',
        mode: 'local',
        minChars: 1,
        allowBlank: true,
        emptyText: false,
        msgTarget: 'under',
        addNewDataOnBlur: true,
        anchor: '100%',
        extraItemCls: 'x-tag',
        expandBtnCls: 'x-form-trigger',
        clearBtnCls: 'x-form-trigger',
        triggerAction: 'all',
        listeners: {
            newitem: function (bs, value) {
                var valueField = this.valueField;
                var newItem = {};
                newItem[valueField] = value;
                bs.addNewItem(newItem);
            }
        },
    });
    config.name += '[]';
    abstractModule.combo.multiSelectLocal.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.combo.multiSelectLocal, Ext.ux.form.SuperBoxSelect);
Ext.reg('abstract-combo-multiselect-local', abstractModule.combo.multiSelectLocal);