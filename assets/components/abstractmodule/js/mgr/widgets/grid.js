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
            create: {
                xtype: null,
                action: null,
                loadPage: function () {
                    MODx.loadPage('', '');
                }
            },
            update: {
                xtype: null,
                action: null,
                loadPage: function () {
                    MODx.loadPage('', '');
                }
            },
            remove: {
                action: null
            }
        },

        //Core settings
        paging: true,
        remoteSort: true,
        anchor: '100%',
    });
    abstractModule.grid.abstract.superclass.constructor.call(this, config)
};
Ext.extend(abstractModule.grid.abstract, MODx.grid.Grid, {
    _recordEditWindow: null,

    initComponent: function() {
        this.columns = this._getGridColumns();
        this.tbar = this.getToolbar();
        this.viewConfig = Ext.applyIf(this.config.viewConfig, {
            getRowClass: this.getRowClass
        });
        abstractModule.grid.abstract.superclass.initComponent.call(this);
    },

    getToolbar: function () {
        return [
            this.getQuickCreateButton(),
            '->',
            this.getSearchPanel()
        ];
    },

    getRowClass: function(record) {
        return record.data.is_active ? 'grid-row-active' : 'grid-row-inactive';
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
        var query = newValue || tf.getValue();
        this.getStore().baseParams.query = query;
        this.getBottomToolbar().changePage(1);
    },

    _filterClear: function() {
        this.getStore().baseParams.query = null;
        Ext.getCmp(this.config.id + '-filter-search').reset();
        this.getBottomToolbar().changePage(1);
    },

    _quickCreateRecord: function (btn, e) {
        if (this._recordEditWindow) {
            this._recordEditWindow.close();
        }
        this._recordEditWindow = new MODx.load({
            xtype: this.recordActions.create.xtype,
            title: _('create'),
            action: this.recordActions.create.action,
            listeners: {
                success: {
                    fn: this.refresh
                    ,scope: this
                }
            }
        });
        this._recordEditWindow.show(e.target);
    },

    _createRecord: function (btn, e) {
        this.recordActions.create.loadPage.call(this);
    },

    _quickUpdateRecord: function (btn, e) {
        if (this._recordEditWindow) {
            this._recordEditWindow.close();
        }
        this._recordEditWindow = new MODx.load({
            xtype: this.recordActions.update.xtype,
            title: _('update'),
            action: this.recordActions.update.action,
            record: this.menu.record,
            listeners: {
                success: {
                    fn: this.refresh
                    ,scope: this
                }
            }
        });
        this._recordEditWindow.show(e.target);
    },

    _updateRecord: function (btn, e) {
        this.recordActions.update.loadPage.call(this);
    },

    _removeRecord: function (btn, e) {
        MODx.msg.confirm({
            title: _('delete'),
            text: _('confirm_remove'),
            url: this.config.url,
            params: {
                action: this.recordActions.remove.action,
                id: this.menu.record.id
            },
            listeners: {
                success: {fn: this.refresh, scope: this},
            }
        });
    }
});
