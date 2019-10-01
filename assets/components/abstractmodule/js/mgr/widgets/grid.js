'use strict';

abstractModule.grid.abstract = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'abstractmodule-grid';
    }
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
        record: {
            xtype: null,
            action: {
                create: null,
                update: null,
                remove: null
            }
        },

        //Core settings
        paging: true,
        remoteSort: true,
        anchor: '100%',
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: 0,
            emptyText: config.emptyText || _('ext_emptymsg'),
            getRowClass: this.getRowClass,
            cssClasses: {
                'grid-row-inactive': {
                    'is_active': 0
                }
            }
        }
    });
    abstractModule.grid.abstract.superclass.constructor.call(this, config)
};
Ext.extend(abstractModule.grid.abstract, MODx.grid.Grid, {
    gridColumns: [],

    initComponent: function() {
        this.columns = this.renderGridColumns();
        this.tbar = this.renderToolbar();
        abstractModule.grid.abstract.superclass.initComponent.call(this);
    },

    getMenu: function () {
        return [{
            text: _('edit'),
            handler: this.updateRecord,
            scope: this
        }, '-', {
            text: _('delete'),
            handler: this.removeRecord,
            scope: this
        }];
    },

    getRowClass: function (record, index, rowParams, store) {
        var rowCssClasses = [];
        for (var cssClass in this.cssClasses) {
            if (!this.cssClasses.hasOwnProperty(cssClass)) {
                continue;
            }
            var conditions = this.cssClasses[cssClass];
            for (var field in conditions) {
                if (!conditions.hasOwnProperty(field)) {
                    continue;
                }
                var value = conditions[field];
                if (record.get(field) == value) {
                    rowCssClasses.push(cssClass);
                }
            };
        };
        return rowCssClasses.join(' ');
    },

    renderGridColumns: function () {
        if (this.gridColumns.length) {
            return this.gridColumns;
        }
        var columns = [];
        Ext.each(this.config.fields, function (field) {
            columns.push({header: field, dataIndex: field, sortable: true});
        });
        return columns;
    },

    renderToolbar: function () {
        return [
            this.renderCreateButton(),
            '->',
            this.renderSearchPanel()
        ];
    },

    renderCreateButton: function () {
        return {
            text: _('add'),
            cls: 'primary-button',
            handler: this.createRecord,
            scope: this
        };
    },

    renderSearchPanel: function () {
        return [
            this.renderSearchField(),
            this.renderClearSearchButton()
        ];
    },

    renderSearchField: function () {
        return {
            xtype: 'textfield',
            name: 'search',
            id: this.config.id + '-filter-search',
            cls: 'x-form-filter',
            emptyText: _('search_ellipsis'),
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
        };
    },

    renderClearSearchButton: function () {
        return {
            xtype: 'button',
            id: this.config.id + '-filter-clear',
            cls: 'x-form-filter-clear',
            text: _('filter_clear'),
            listeners: {
                'click': {fn: this.clearFilter, scope: this},
                'mouseout': {
                    fn: function (evt) {
                        this.removeClass('x-btn-focus');
                    }
                }
            }
        };
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

    //TODO
    /*createRecordForm: {
        xtype: 'abstractmodule-window-abstract',
        baseParams: {
            action: null,
        }
    },*/

    /*updateRecordForm: {
        xtype: 'abstractmodule-window-abstract',
        baseParams: {
            action: null,
        }
    },*/

    removeRecordForm: {
        baseParams: {
            action: null
        },
    },

    createRecord: function (btn, e) {
        var window = Ext.getCmp(this.config.record.xtype);
        if (window) {
            window.close();
        }
        window = MODx.load({
            xtype: this.config.record.xtype,
            title: _('create'),
            parent: this,
            record: {},
            baseParams: {
                action: this.config.record.action.create
            }
        });
        if (window) {
            window.show(e.target);
        }
    },
    /*_createRecord: function (btn, e) {
        var window = Ext.getCmp(this.createRecordForm.xtype);
        if (window) {
            window.close();
        }
        window = MODx.load(Ext.apply({
            title: _('create'),
            parent: this,
            record: {}
        }, this.createRecordForm));
        window.show(e.target);
    },*/

    updateRecord: function (btn, e) {
        var window = Ext.getCmp(this.config.record.xtype);
        if (window) {
            window.close();
        }
        window = MODx.load({
            xtype: this.config.record.xtype,
            title: _('create'),
            parent: this,
            record: this.menu.record,
            baseParams: {
                action: this.config.record.action.update
            }
        });
        if (window) {
            window.show(e.target);
        }
    },
    /*_updateRecord: function (btn, e) {
        var window = Ext.getCmp(this.updateRecordForm.xtype);
        if (window) {
            window.close();
        }
        window = MODx.load(Ext.apply({
            title: _('edit'),
            parent: this,
            record: this.menu.record
        }, this.updateRecordForm));
        window.show(e.target);
    },*/

    removeRecord: function (btn, e) {
        MODx.msg.confirm({
            title: _('delete'),
            text: _('confirm_remove'),
            url: this.config.url,
            params: {
                action: this.config.record.action.remove,
                id: this.menu.record.id
            },
            listeners: {
                success: {fn: this.refresh, scope: this},
            }
        });
    }
});
Ext.reg('abstractmodule-grid-abstract', abstractModule.grid.abstract);