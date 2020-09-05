'use strict';

AbstractModule.localGrid.abstract = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        /*fields: [
            'key',
            'value'
        ],
        columns: [
            {header: _('jpayments.field.key'), dataIndex: 'key', sortable: false, width: 0.3},
            {header: _('jpayments.field.value'), dataIndex: 'value', sortable: false, width: 0.7}
        ]*/
    });
    AbstractModule.localGrid.abstract.superclass.constructor.call(this, config);
};
Ext.extend(AbstractModule.localGrid.abstract, MODx.grid.LocalGrid, {});
