'use strict';

abstractModule.notice.abstract = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        html: this.panelHtml,
        cls: 'panel-desc',
        style: {
            fontSize: '170%',
            textAlign: 'center'
        }
    });
    abstractModule.notice.abstract.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.notice.abstract, Ext.Container, {
    panelHtml: null
});
