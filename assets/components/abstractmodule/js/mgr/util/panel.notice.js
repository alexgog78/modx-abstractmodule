Ext.namespace('abstractModule.util.panelNotice');
abstractModule.util.panelNotice.abstract = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'abstractmodule-util-panelnotice';
    }
    Ext.applyIf(config, {
        html: this.panelHtml,
        cls: 'panel-desc',
        style: {
            fontSize: '170%',
            textAlign: 'center'
        }
    });
    abstractModule.util.panelNotice.abstract.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.util.panelNotice.abstract, Ext.Container, {
    panelHtml: null
});
Ext.reg('abstractmodule-util-panelnotice-abstract', abstractModule.util.panelNotice.abstract);