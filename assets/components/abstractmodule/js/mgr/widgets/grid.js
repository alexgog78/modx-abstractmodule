abstractModule.grid.abstract = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'abstractmodule-grid';
    }
    Ext.applyIf(config, {
        url: null,
        baseParams: {
            action: null
        },
        autosave: true,
        save_action: null,
        saveParams: {},
        paging: true,
        remoteSort: true,
        fields: this.getGridFields(),
        columns: this.renderGridColumns(config),
        anchor: '100%',
        tbar: [
            this.renderCreateButton(config),
            '->',
            this.renderSearchPanel(config)
        ],
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: 0,
            emptyText: config.emptyText || _('ext_emptymsg'),
            getRowClass: this.getRowClass
        }
    });
    abstractModule.grid.abstract.superclass.constructor.call(this, config)
};
Ext.extend(abstractModule.grid.abstract, MODx.grid.Grid, {
    lexicons: {
        search: _('controls.search'),
        search_clear: _('controls.search_clear'),
        create: _('controls.create'),
        update: _('controls.update'),
        remove: _('controls.remove'),
        remove_confirm: _('controls.remove_confirm')
    },

    gridFields: [],

    gridColumns: [],

    createRecordForm: {
        xtype: 'abstractmodule-window-abstract',
        baseParams: {
            action: null,
        }
    },

    updateRecordForm: {
        xtype: 'abstractmodule-window-abstract',
        baseParams: {
            action: null,
        }
    },

    removeRecordForm: {
        baseParams: {
            action: null
        },
    },

    getGridFields: function () {
        return this.gridFields;
    },

    renderGridColumns: function (config) {
        if (this.gridColumns.length) {
            return this.gridColumns;
        }
        var columns = [];
        Ext.each(this.gridFields, function (field) {
            columns.push({header: field, dataIndex: field, sortable: true});
        });
        return columns;
    },

    renderCreateButton: function (config) {
        return {
            text: this.lexicons.create,
            cls: 'primary-button',
            handler: this.createRecord,
            scope: this
        };
    },

    renderSearchPanel: function (config) {
        return [{
            xtype: 'textfield',
            name: 'search',
            id: config.id + '-filter-search',
            cls: 'x-form-filter',
            emptyText: this.lexicons.search,
            listeners: {
                'change': {fn: this.searchFilter, scope: this},
                'render': {
                    fn: function (cmp) {
                        new Ext.KeyMap(cmp.getEl(), {
                            key: Ext.EventObject.ENTER,
                            fn: this.blur,
                            scope: cmp
                        });
                    }, scope: this
                }
            }
        }, {
            xtype: 'button',
            id: config.id + '-filter-clear',
            cls: 'x-form-filter-clear',
            text: this.lexicons.search_clear,
            listeners: {
                'click': {fn: this.clearFilter, scope: this},
                'mouseout': {
                    fn: function (evt) {
                        this.removeClass('x-btn-focus');
                    }
                }
            }
        }];
    },

    getRowClass: function (record, index, rowParams, store) {
        if (record.get('is_active') == 0) {
            return 'grid-row-inactive';
        }
    },

    getMenu: function () {
        return [{
            text: this.lexicons.update,
            handler: this.updateRecord,
            scope: this
        }, '-', {
            text: this.lexicons.remove,
            handler: this.removeRecord,
            scope: this
        }];
    },

    searchFilter: function (tf, newValue, oldValue) {
        var query = newValue || tf.getValue();
        this.getStore().baseParams.query = query;
        this.getBottomToolbar().changePage(1);
    },

    clearFilter: function() {
        console.log(this.config);
        this.getStore().baseParams = {
            action: this.config.baseParams.action
        };
        Ext.getCmp(this.config.id + '-filter-search').reset();
        this.getBottomToolbar().changePage(1);
    },

    createRecord: function (btn, e) {
        var window = Ext.getCmp(this.createRecordForm.xtype);
        if (window) {
            window.close();
        }
        window = MODx.load(Ext.apply({
            title: this.lexicons.create,
            parent: this,
            record: {}
        }, this.createRecordForm));
        window.show(e.target);
    },

    updateRecord: function (btn, e) {
        var window = Ext.getCmp(this.updateRecordForm.xtype);
        if (window) {
            window.close();
        }
        window = MODx.load(Ext.apply({
            title: this.lexicons.update,
            parent: this,
            record: this.menu.record
        }, this.updateRecordForm));
        window.show(e.target);
    },

    removeRecord: function (btn, e) {
        MODx.msg.confirm(Ext.apply({
            title: this.lexicons.remove,
            text: this.lexicons.remove_confirm,
            url: this.config.url,
            params: {
                action: this.removeRecordForm.baseParams.action,
                id: this.menu.record.id
            },
            listeners: {
                success: {fn: this.refresh, scope: this},
            }
        }, this.removeRecordForm));
    }
});
Ext.reg('abstractmodule-grid-abstract', abstractModule.grid.abstract);