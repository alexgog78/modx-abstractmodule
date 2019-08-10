abstractModule.grid.abstract = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'abstract-grid';
    }
    Ext.applyIf(config, {
        paging: true,
        remoteSort: true,
        anchor: '97%',
        autosave: true,
        fields: [],
        columns: [],
        //Settings
        /*id: 'ms2bundle-grid-groups',
        url: ms2Bundle.config.connectorUrl,
        baseParams: {
            action: 'mgr/group/getlist'
        },
        paging: true,
        remoteSort: true,
        anchor: '97%',
        save_action: 'mgr/group/updatefromgrid',
        autosave: true,

        //Grid
        fields: [
            'id',
            'name',
            'description',
            'template_id',
            'active'
        ],
        columns: [
            {header: _('id'), dataIndex: 'id', sortable: true, width: 0.05},
            {header: _('ms2bundle.field.name'), dataIndex: 'name', sortable: true, width: 0.2, editor: {xtype: 'textfield'}},
            {header: _('ms2bundle.field.template'), dataIndex: 'template_id', sortable: true, width: 0.2},
            {header: _('ms2bundle.field.active'), dataIndex: 'active', sortable: true, width: 0.1, editor: {xtype: 'combo-boolean', renderer: 'boolean'}}
        ],

        //Toolbar
        tbar: [
            //Search panel
            {
                xtype: 'textfield',
                id: config.id + '-search-filter',
                emptyText: _('ms2bundle.controls.search'),
                listeners: {
                    'change': {fn: ms2Bundle.function.search, scope: this},
                    'render': {
                        fn: function (cmp) {
                            new Ext.KeyMap(cmp.getEl(), {
                                key: Ext.EventObject.ENTER,
                                fn: function () {
                                    this.fireEvent('change', this);
                                    this.blur();
                                    return true;
                                },
                                scope: cmp
                            });
                        }, scope: this
                    }
                }
            },
            //Create button
            {
                text: _('ms2bundle.controls.create'),
                handler: this.createFunction,
                scope: this,
                cls: 'primary-button'
            }
        ],*/

        //Grid row additional classes
        viewConfig: {
            forceFit: true,
            getRowClass: function (record, index, rowParams, store) {
                var rowClass = [];
                if (record.get('active') == 0 || record.get('blocked') == 1) {
                    rowClass.push('grid-row-inactive');
                }
                return rowClass.join(' ');
            }
        }
    });
    abstractModule.grid.abstract.superclass.constructor.call(this, config)
};
Ext.extend(abstractModule.grid.abstract, MODx.grid.Grid, {
    //Context menu function
    /*getMenu: function () {
        return [{
            text: _('ms2bundle.controls.update'),
            handler: this.updateFunction
        }, '-', {
            text: _('ms2bundle.controls.remove'),
            handler: ms2Bundle.function.removeRecord,
            baseParams: {
                action: 'mgr/group/remove'
            }
        }];
    },

    //Create function
    createFunction: function (btn, e) {
        MODx.loadPage('mgr/group/create', 'namespace=ms2bundle');
    },

    //Update function
    updateFunction: function (btn, e) {
        MODx.loadPage('mgr/group/update', 'namespace=ms2bundle&id=' + this.menu.record.id);
    }*/
});
Ext.reg('abstractmodule-grid', abstractModule.grid.abstract);