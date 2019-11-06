'use strict';

//TODO
abstractModule.page.abstract = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        url: null,
        formpanel: null,
        buttons: [],
        components: [/*{
            xtype: 'ms2bundle-formpanel-productgroup',
            renderTo: 'modx-panel-holder',
            record_id: 0
        }*/]
    });
    abstractModule.page.abstract.superclass.constructor.call(this, config);
};
Ext.extend(abstractModule.page.abstract, MODx.Component, {
    //buttons: {}

    /*renderButtons: function () {

    }*/
});
