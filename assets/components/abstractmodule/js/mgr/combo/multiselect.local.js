'use strict';

abstractModule.combo.multiSelectLocal = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        //Custom settings
        store: new Ext.data.SimpleStore({
            id: null,
            fields: ['value'],
            data: []
        }),
        displayField: null,
        valueField: 'value',
        dataIndex: null,
        allowAddNewData: false,

        //Core settings
        name: config.name || 'multiselect-local',
        mode: 'local',
        minChars: 1,
        allowBlank: true,
        //emptyText: _('no'),
        emptyText: false,
        msgTarget: 'under',
        addNewDataOnBlur: true,
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
        }
    });
    if (!config.hiddenName) {
        config.hiddenName = config.name;
    }
    config.name += '[]';
    config.hiddenName += '[]';
    abstractModule.combo.multiSelectLocal.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.combo.multiSelectLocal, Ext.ux.form.SuperBoxSelect);
