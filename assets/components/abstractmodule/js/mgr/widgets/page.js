'use strict';

abstractModule.page.abstract = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        //Custom settings
        url: null,
        formpanel: null,
        components: [],
        recordActions: {
            create: {
                action: null,
            },
            update: {
                action: null,
            },
            remove: {
                action: null
            },
            close: {
                loadPage: function () {
                    MODx.loadPage('', '');
                }
            }
        },

        //Core settings
        buttons: []
    });
    abstractModule.page.abstract.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.page.abstract, MODx.Component, {
    initComponent: function () {
        abstractModule.page.abstract.superclass.initComponent.call(this);
    },

    _loadActionButtons: function () {
        this.config.buttons = this.getButtons(this.config);
        abstractModule.page.abstract.superclass._loadActionButtons.call(this);
    },

    getButtons: function (config) {
        return this.config.buttons;
    },

    getCreateButton: function(config) {
        return {
            text: _('save'),
            process: config.recordActions.create.action,
            method: 'remote',
            reload: true,
            cls: 'primary-button',
            keys: [{
                key: MODx.config.keymap_save || 's',
                ctrl: true
            }]
        };
    },

    getUpdateButton: function (config) {
        return {
            text: _('save'),
            process: config.recordActions.update.action,
            method: 'remote',
            cls: 'primary-button',
            keys: [{
                key: MODx.config.keymap_save || 's',
                ctrl: true
            }]
        };
    },

    getDeleteButton: function (config) {
        return {
            text: _('delete'),
            handler: this._removeRecord,
            scope: this
        };
    },

    getCloseButton: function (config) {
        return {
            text: _('close'),
            handler: this._close,
            scope: this
        };
    },

    _removeRecord: function () {
        MODx.msg.confirm({
            title: _('delete'),
            text: _('confirm_remove'),
            url: this.config.url,
            params: {
                action: this.recordActions.remove.action,
                id: this.config.record.id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        this.recordActions.close.loadPage.call(this);
                    }, scope: this
                }
            }
        });
    },

    _close: function () {
        this.recordActions.close.loadPage.call(this);
    }
});
