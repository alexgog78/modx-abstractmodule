'use strict';

abstractModule.grid.abstract = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        //Custom settings
        url: null,
        baseParams: {
            action: null
        },
        autosave: true,
        save_action: null,
        saveParams: {},
        fields: [],
        columns: [],
        recordActions: {
            quickCreate: {
                xtype: null,
                action: null,
            },
            quickUpdate: {
                xtype: null,
                action: null,
            },
            create: function () {
            },
            update: function () {
            },
            remove: {
                action: null
            }
        },
        enableDragDrop: true,
        sortByField: 'sort_order',

        //Core settings
        tbar: [],
        paging: true,
        remoteSort: true,
        anchor: '100%',
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            scrollOffset: 0,
            autoFill: true,
            showPreview: true,
        },
        plugins: [],
    });
    abstractModule.grid.abstract.superclass.constructor.call(this, config)
};
Ext.extend(abstractModule.grid.abstract, MODx.grid.Grid, {
    _recordEditWindow: null,

    initComponent: function () {
        this.columns = this._getGridColumns();
        this.tbar = this.getToolbar();
        this.viewConfig = Ext.applyIf(this.config.viewConfig, {
            getRowClass: this.getRowClass
        });
        if (this.enableDragDrop) {
            this.plugins.push(new Ext.ux.dd.GridDragDropRowOrder({
                copy: false,
                scrollable: true,
                targetCfg: {},
                listeners: {
                    'afterrowmove': {fn: this._onAfterRowMove, scope: this},
                }
            }));
        }
        abstractModule.grid.abstract.superclass.initComponent.call(this);
    },

    getToolbar: function () {
        return [
            this.getQuickCreateButton(),
            '->',
            this.getSearchPanel()
        ];
    },

    getRowClass: function (record) {
        return record.data.is_active
            ? 'grid-row-active'
            : 'grid-row-inactive';
    },

    getMenu: function () {
        return [{
            text: _('edit'),
            handler: this._quickUpdateRecord,
            scope: this
        }, '-', {
            text: _('delete'),
            handler: this._removeRecord,
            scope: this
        }];
    },

    getQuickCreateButton: function (config = {}) {
        return Ext.applyIf(config, {
            text: _('add'),
            cls: 'primary-button',
            handler: this._quickCreateRecord,
            scope: this
        });
    },

    getCreateButton: function (config = {}) {
        return Ext.applyIf(config, {
            text: _('add'),
            cls: 'primary-button',
            handler: this._createRecord,
            scope: this
        });
    },

    getSearchPanel: function () {
        return [
            this._getSearchField(),
            this._getClearSearchButton()
        ];
    },

    getGridColumn: function (name, config = {}) {
        return abstractModule.component.gridColumn(name, config);
    },

    _getGridColumns: function () {
        if (this.config.columns.length > 0) {
            return this.config.columns;
        }
        Ext.each(this.config.fields, function (field) {
            this.config.columns.push(this.getGridColumn(field));
        }, this);
        return this.config.columns;
    },

    _getSearchField: function () {
        return {
            xtype: 'textfield',
            name: 'search',
            id: this.config.id + '-filter-search',
            cls: 'x-form-filter',
            emptyText: _('search_ellipsis'),
            listeners: {
                'change': {fn: this._filterSearch, scope: this},
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
        };
    },

    _getClearSearchButton: function () {
        return {
            xtype: 'button',
            id: this.config.id + '-filter-clear',
            cls: 'x-form-filter-clear',
            text: _('filter_clear'),
            listeners: {
                'click': {fn: this._filterClear, scope: this},
                'mouseout': {
                    fn: function (evt) {
                        this.removeClass('x-btn-focus');
                    }
                }
            }
        };
    },

    _filterSearch: function (tf, newValue, oldValue) {
        this.getStore().baseParams.query = newValue || tf.getValue();
        this.getBottomToolbar().changePage(1);
        return true;
    },

    _filterClear: function () {
        this.getStore().baseParams.query = null;
        Ext.getCmp(this.config.id + '-filter-search').reset();
        this.getBottomToolbar().changePage(1);
    },

    _quickCreateRecord: function (btn, e) {
        if (this._recordEditWindow) {
            this._recordEditWindow.close();
        }
        let window = Ext.apply({
            title: _('create'),
            listeners: {
                success: {
                    fn: this.refresh
                    , scope: this
                }
            }
        }, this.recordActions.quickCreate);
        this._recordEditWindow = new MODx.load(window);
        this._recordEditWindow.show(e.target);
    },

    _quickUpdateRecord: function (btn, e) {
        if (this._recordEditWindow) {
            this._recordEditWindow.close();
        }
        let window = Ext.apply({
            title: _('update'),
            record: this.menu.record,
            listeners: {
                success: {
                    fn: this.refresh
                    , scope: this
                }
            }
        }, this.recordActions.quickUpdate);
        this._recordEditWindow = new MODx.load(window);
        this._recordEditWindow.show(e.target);
    },

    _createRecord: function (btn, e) {
        this.recordActions.create.call(this);
    },

    _updateRecord: function (btn, e) {
        this.recordActions.update.call(this);
    },

    _removeRecord: function (btn, e) {
        let params = Ext.apply({
            id: this.menu.record.id
        }, this.recordActions.remove);
        MODx.msg.confirm({
            title: _('delete'),
            text: _('confirm_remove'),
            url: this.config.url,
            params: params,
            listeners: {
                success: {fn: this.refresh, scope: this},
            }
        });
    },

    _onAfterRowMove: function(dt, sri, ri, sels) {
        var store = this.getStore();
        var record = store.data.items[ri];
        record.set(this.config.sortByField, ri);
        this.fireEvent('afteredit', {
            grid: this,
            record: record,
            cancel:false
        });
        record.commit();
        return true;
    },
});
