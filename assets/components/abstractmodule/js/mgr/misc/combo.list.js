abstractModule.combo.selectLocal = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        name: config.name || 'select-local',
        hiddenName: config.name || 'select-local',
        displayField: null,
        valueField: null,
        fields: [],
        store: [],
        anchor: '100%',
        typeAhead: true,
        editable: true,
        allowBlank: true,
        emptyText: _('no')
    });
    abstractModule.combo.selectLocal.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.combo.selectLocal, MODx.combo.ComboBox);
Ext.reg('abstract-combo-select-local', abstractModule.combo.selectLocal);


abstractModule.combo.multiSelectLocal = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        xtype: 'superboxselect',
        name: config.name || 'multiselect-local',
        displayField: null,
        valueField: null,
        dataIndex : null,
        mode: 'local',
        store: new Ext.data.JsonStore({
            id: (config.name || 'multiselect-local') + '-store',
            fields: [],
            data: []
        }),
        minChars: 1,
        allowBlank: true,
        emptyText: false,
        msgTarget: 'under',
        allowAddNewData: false,
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
        }
    });
    config.name += '[]';
    abstractModule.combo.multiSelectLocal.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.combo.multiSelectLocal, Ext.ux.form.SuperBoxSelect);
Ext.reg('abstract-combo-multiselect-local', abstractModule.combo.multiSelectLocal);




/*//Organizations select
vendorsClub.combo.organizationSelect = function(config) {
	config = config || {};
	Ext.applyIf(config, {
		name: config.name || 'organization_id',
		hiddenName: config.name || 'organization_id',
		displayField: 'organization_name',
		valueField: 'organization_id',
		editable: true,
		fields: ['organization_id', 'organization_name'],
		pageSize: 20,
		emptyText: _('no'),
		hideMode: 'offsets',
		url: vendorsClub.config.connectorUrl,
		baseParams: {
			action: 'vendor/getlist',
			combo: true
		}
	});
	vendorsClub.combo.organizationSelect.superclass.constructor.call(this,config);
};
Ext.extend(vendorsClub.combo.organizationSelect, MODx.combo.ComboBox);
Ext.reg('vclub-combo-organization', vendorsClub.combo.organizationSelect);*/


/*ms2Bundle.combo.templateMultiselect = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        xtype:'superboxselect',
        allowBlank: true,
        msgTarget: 'under',
        allowAddNewData: true,
        addNewDataOnBlur : true,
        resizable: true,
        name: config.name || 'template_ids',
        anchor:'99%',
        minChars: 1,
        pageSize: 200,
        store: new Ext.data.JsonStore({
            id: (config.id || 'template_ids') + '-store',
            root:'results',
            autoLoad: true,
            autoSave: false,
            totalProperty:'total',
            fields:['id', 'templatename'],
            url: MODx.config.connector_url,
            baseParams: {
                action: 'element/template/getlist',
                combo: 1
            }
        }),
        mode: 'remote',
        displayField: 'templatename',
        valueField: 'id',
        triggerAction: 'all',
        extraItemCls: 'x-tag',
        expandBtnCls: 'x-form-trigger',
        clearBtnCls: 'x-form-trigger',
        listeners: {}
    });
    config.name += '[]';
    ms2Bundle.combo.templateMultiselect.superclass.constructor.call(this, config);
};
Ext.extend(ms2Bundle.combo.templateMultiselect, Ext.ux.form.SuperBoxSelect);*/





abstractModule.combo.Browser = function(config){
    config = config || {};

    if (config.length != 0 && typeof config.openTo !== "undefined") {
        if (!/^\//.test(config.openTo)) {
            config.openTo = '/' + config.openTo;
        }
        if (!/$\//.test(config.openTo)) {
            var tmp = config.openTo.split('/')
            delete tmp[tmp.length - 1];
            tmp = tmp.join('/');
            config.openTo = tmp.substr(1)
        }
    }

    Ext.applyIf(config,{
        width: 300
        ,triggerAction: 'all'
    });
    abstractModule.combo.Browser.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(abstractModule.combo.Browser, Ext.form.TriggerField,{
    browser: null

    ,onTriggerClick : function(btn){
        if (this.disabled){
            return false;
        }

        if (this.browser === null) {
            this.browser = MODx.load({
                xtype: 'modx-browser'
                ,id: Ext.id()
                ,multiple: true
                ,source: this.config.source || MODx.config.default_media_source
                ,rootVisible: this.config.rootVisible || false
                ,allowedFileTypes: this.config.allowedFileTypes || ''
                ,wctx: this.config.wctx || 'web'
                ,openTo: this.config.openTo || ''
                ,rootId: this.config.rootId || '/'
                ,hideSourceCombo: this.config.hideSourceCombo || false
                ,hideFiles: this.config.hideFiles || true
                ,listeners: {
                    'select': {fn: function(data) {
                            this.setValue(data.fullRelativeUrl);
                            var field = Ext.getCmp('imgbrowser-'+this.config.name);
                            if(field){
                                field.setValue('<img src="/connectors/system/phpthumb.php?&w=150&aoe=0&far=0&src='+data.fullRelativeUrl+'" alt="">');
                            }
                        },scope:this}
                }
            });
        }
        this.browser.win.buttons[0].on('disable',function(e) {this.enable()})
        this.browser.win.tree.on('click', function(n,e) {
                path = this.getPath(n);
                this.setValue(path);
            },this
        );
        this.browser.win.tree.on('dblclick', function(n,e) {
                path = this.getPath(n);
                this.setValue(path);
                this.browser.hide()
            },this
        );
        this.browser.show(btn);
        return true;
    }
    ,onDestroy: function(){
        abstractModule.combo.Browser.superclass.onDestroy.call(this);
    }
    ,getPath: function(n) {
        if (n.id == '/') {return '';}
        data = n.attributes;
        path = data.path + '/';

        return path;
    }
});
Ext.reg('ms2bundle-combo-browser', abstractModule.combo.Browser);