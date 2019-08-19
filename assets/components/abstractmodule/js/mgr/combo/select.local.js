abstractModule.combo.selectLocal = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        store: [],
        fields: [],
        displayField: null,
        valueField: null,
        name: config.name || 'select-local',
        hiddenName: config.name || 'select-local',
        anchor: '100%',
        typeAhead: true,
        editable: true,
        allowBlank: true,
        emptyText: _('no'),
    });
    abstractModule.combo.selectLocal.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.combo.selectLocal, MODx.combo.ComboBox);
Ext.reg('abstract-combo-select-local', abstractModule.combo.selectLocal);