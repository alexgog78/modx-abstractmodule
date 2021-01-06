'use strict';

Ext.namespace('abstractModule.combo.browser');

abstractModule.combo.browser.abstract = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        source: config.source || MODx.config.default_media_source
    });
    abstractModule.combo.browser.abstract.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.combo.browser.abstract, MODx.combo.Browser, {
    onTriggerClick: function (btn) {
        if (this.disabled) {
            return false;
        }
        this.browser = MODx.load({
            xtype: 'modx-browser',
            closeAction: 'close',
            id: Ext.id(),
            multiple: true,
            source: this.config.source || MODx.config.default_media_source,
            hideFiles: this.config.hideFiles || false,
            rootVisible: this.config.rootVisible || false,
            allowedFileTypes: this.config.allowedFileTypes || '',
            wctx: this.config.wctx || 'web',
            openTo: this.config.openTo || '',
            rootId: this.config.rootId || '/',
            hideSourceCombo: this.config.hideSourceCombo || false,
            listeners: {
                'select': {
                    fn: function (data) {
                        this.setValue(data.fullRelativeUrl);
                        let field = Ext.getCmp(this.config.name + '-preview');
                        if (this.config.id) {
                            field = Ext.getCmp(this.config.id + '-preview');
                        }
                        if (field) {
                            field.setValue('<img src="/connectors/system/phpthumb.php?&h=100&aoe=0&far=0&src=' + data.fullRelativeUrl + '" alt="">');
                        }
                    }, scope: this
                }
            }
        });
        this.browser.show(btn);
        return true;
    }
});
