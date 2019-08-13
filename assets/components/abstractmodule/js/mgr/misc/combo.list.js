abstractModule.combo.selectLocal = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        displayField: null,
        valueField: null,
        fields: [],
        store: [],
        name: config.name || 'selectlocal',
        hiddenName: config.name || 'selectlocal',
        anchor: '99%',
        typeAhead: true,
        editable: true,
        allowBlank: true,
        emptyText: _('no')
    });
    abstractModule.combo.selectLocal.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.combo.selectLocal, MODx.combo.ComboBox);
Ext.reg('abstract-combo-selectlocal', abstractModule.combo.selectLocal);