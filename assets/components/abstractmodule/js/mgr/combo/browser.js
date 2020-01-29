'use strict';

abstractModule.combo.browser = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        source: config.source || MODx.config.default_media_source
    });
    abstractModule.combo.browser.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.combo.browser, MODx.combo.Browser, {
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
                        this.fireEvent('select', data);
                    }, scope: this
                }
            }
        });
        this.browser.show(btn);
        return true;
    }
});
