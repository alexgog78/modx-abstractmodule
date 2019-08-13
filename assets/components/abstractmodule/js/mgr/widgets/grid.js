abstractModule.grid.abstract = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'abstract-grid';
    }
    Ext.applyIf(config, {
        url: null,
        baseParams: {
            action: null
        },
        save_action: null,
        paging: true,
        remoteSort: true,
        autosave: true,
        fields: this.getGridFields(),
        columns: this.getGridColumns(config),
        tbar: [
            this.createButton(config),
            '->',
            this.searchPanel(config)
        ],
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

    getGridColumns: function (config) {
        if (this.gridColumns.length) {
            return this.gridColumns;
        }
        var columns = [];
        Ext.each(this.gridFields, function (field) {
            columns.push({header: _(config.namespace + '.field.' + field), dataIndex: field, sortable: true});
        });
        return columns;
    },

    createButton: function (config) {
        return {
            text: _(config.lexicon_namespace + '.controls.create'),
            cls: 'primary-button',
            handler: this.createRecord,
            scope: this
        };
    },

    searchPanel: function (config) {
        return [{
            xtype: 'textfield',
            name: 'search',
            id: config.id + '-filter-search',
            cls: 'x-form-filter',
            emptyText: _(config.lexicon_namespace + '.controls.search'),
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
            text: _(config.lexicon_namespace + '.controls.search_clear'),
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

    getMenu: function () {
        return [{
            text: _(this.config.lexicon_namespace + '.controls.update'),
            handler: this.updateRecord,
            scope: this
        }, '-', {
            text: _(this.config.lexicon_namespace + '.controls.remove'),
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
            title: _(this.config.lexicon_namespace + '.controls.create'),
            parent: this
        }, this.createRecordForm));
        window.show(e.target);
    },

    updateRecord: function (btn, e) {
        var window = Ext.getCmp(this.updateRecordForm.xtype);
        if (window) {
            window.close();
        }
        window = MODx.load(Ext.apply({
            title: _(this.config.lexicon_namespace + '.controls.update'),
            parent: this
        }, this.updateRecordForm));
        window.setRecord(this.menu.record);
        window.show(e.target);
    },

    removeRecord: function (btn, e) {
        MODx.msg.confirm(Ext.apply({
            title: _(this.config.lexicon_namespace + '.controls.remove'),
            text: _(this.config.lexicon_namespace + '.controls.remove_confirm'),
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