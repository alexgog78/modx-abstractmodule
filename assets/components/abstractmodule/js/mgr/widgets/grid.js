abstractModule.grid.abstract = function (config) {
    config = config || {};
    //console.log(config.id);
    //console.log(config.namespace);
    if (!config.id) {
        config.id = 'abstract-grid';
    }
    Ext.applyIf(config, {
        paging: true,
        remoteSort: true,
        anchor: '97%',
        autosave: true,
        fields: this.getGridFields(),
        columns: this.getGridColumns(config),

        //Toolbar
        /*tbar: [
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
    gridFields: [],

    gridColumns: [],

    getGridFields: function () {
        return this.gridFields;
    },

    getGridColumns: function (config) {
        if (this.gridColumns.length) {
            return this.gridColumns;
        }
        var columns = [];
        Ext.each(this.gridFields, function(field) {
            columns.push({header: _(config.namespace + '.field.' + field), dataIndex: field, sortable: true});
        });
        return columns;
    },

    search: function (tf, nv, ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }

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
Ext.reg('abstractmodule-grid-abstract', abstractModule.grid.abstract);