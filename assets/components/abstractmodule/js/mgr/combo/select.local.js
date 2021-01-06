'use strict';

Ext.namespace('abstractModule.combo.selectLocal');

abstractModule.combo.select.local.abstract = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        //Custom settings
        store: [],
        fields: [],
        displayField: null,
        valueField: null,

        //Core settings
        queryMode: 'local',
        name: config.name || 'select-local',
        typeAhead: true,
        editable: true,
        allowBlank: true,
        emptyText: _('no'),
    });
    if (!config.hiddenName) {
        config.hiddenName = config.name;
    }
    abstractModule.combo.select.local.abstract.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.combo.select.local.abstract, MODx.combo.ComboBox);
