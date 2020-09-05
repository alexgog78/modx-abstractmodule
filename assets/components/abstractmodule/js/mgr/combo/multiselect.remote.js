'use strict';

AbstractModule.combo.multiSelectRemote = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        //Custom settings
        store: new Ext.data.JsonStore({
            id: null,
            url: null,
            baseParams: {
                action: null,
                combo: true,
            },
            fields: [],
            root: 'results',
            totalProperty: 'total',
            autoLoad: false,
            autoSave: false,
        }),
        displayField: 'value',
        valueField: 'value',
        //allowAddNewData: false,

        //Core settings
        name: config.name,
        dataIndex: config.name,
        allowAddNewData: true,
        //hiddenName: config.name || 'multiselect-remote',
        mode: 'remote',
        pageSize: 10,
        minChars: 1,
        allowBlank: true,
        //emptyText: _('no'),
        emptyText: false,
        msgTarget: 'under',
        //addNewDataOnBlur: true,//TODO check
        extraItemCls: 'x-tag',
        expandBtnCls: 'x-form-trigger',
        clearBtnCls: 'x-form-trigger',
        triggerAction: 'all',
        //pinList: false,
        //resizable: true,
        //lazyInit: false,
        //TODO
        // fix for setValue
        addValue : function(value){
            if(Ext.isEmpty(value)){
                return;
            }
            var values = value;
            if(!Ext.isArray(value)){
                value = '' + value;
                values = value.split(this.valueDelimiter);
            }
            Ext.each(values,function(val){
                var record = this.findRecord(this.valueField, val);
                if(record){
                    this.addRecord(record);
                }
                this.remoteLookup.push(val);
            },this);
            if(this.mode === 'remote'){
                var q = this.remoteLookup.join(this.queryValuesDelimiter);
                this.doQuery(q,false, true);
            }
        },
        // fix similar queries
        shouldQuery : function(q){
            if(this.lastQuery){
                return (q !== this.lastQuery);
            }
            return true;
        },
    });
    if (!config.hiddenName) {
        config.hiddenName = config.name;
    }
    config.name += '[]';
    config.hiddenName += '[]';
    AbstractModule.combo.multiSelectRemote.superclass.constructor.call(this, config);
};
Ext.extend(AbstractModule.combo.multiSelectRemote, Ext.ux.form.SuperBoxSelect);
