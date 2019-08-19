abstractModule.combo.selectRemote = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        url: null,
        baseParams: {
            action: null,
            combo: true
        },
        fields: [],
        displayField: null,
        valueField: null,
        name: config.name || 'select-remote',
        hiddenName: config.name || 'select-remote',
        anchor: '100%',
        typeAhead: true,
        editable: true,
        allowBlank: true,
        emptyText: _('no'),
        pageSize: 20,
        //hideMode: 'offsets',
    });
    abstractModule.combo.selectRemote.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.combo.selectRemote, MODx.combo.ComboBox);
Ext.reg('abstract-combo-select-remote', abstractModule.combo.selectRemote);