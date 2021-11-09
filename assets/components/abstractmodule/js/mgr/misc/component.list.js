'use strict';

abstractModule.component = {
    panelHeader: function (html, config = {}) {
        return Ext.applyIf(config, {
            xtype: 'modx-header',
            itemId: '',
            html: html
        });
    },

    panelDescription: function (html, config = {}) {
        return Ext.applyIf(config, {
            xtype: 'modx-description',
            itemId: '',
            html: '<p>' + html + '</p>'
        });
    },

    panelContent: function (items, config = {}) {
        return Ext.applyIf(config, {
            xtype: 'panel',
            layout: 'form',
            cls: 'main-wrapper',
            preventRender: true,
            border: false,
            labelAlign: 'top',
            labelSeparator: '',
            defaults: {msgTarget: 'under', anchor: '100%'},
            items: items || []
        });
    },

    inputField: function (name, config = {}) {
        return Ext.applyIf(config, {
            xtype: 'textfield',
            name: name,
            hiddenName: name,
            fieldLabel: name,
            anchor: '100%',
        });
    },

    checkboxField: function (name, config = {}) {
        Ext.applyIf(config, {
            xtype: 'xcheckbox',
        });
        return this.inputField(name, config);
    },

    //TODO maybe xtype, or maybe not ¯\_(ツ)_/¯
    imageField: function (name, config = {}) {
        Ext.applyIf(config, {
            id: name,
        });
        let input = this.inputField(name, config);
        let preview = this.inputField(name + '_preview', {
            xtype: 'displayfield',
            cls: 'formpanel-image',
            id: config.id + '-preview',
            fieldLabel: '',
            value: '<img src="' + MODx.config.connectors_url + 'system/phpthumb.php?&src=assets/components/abstractmodule/css/mgr/no-photo.png&h=100&aoe=0&far=0" alt="">',
        });
        return [
            input,
            preview,
        ];
    },

    datetimeField: function (name, config = {}) {
        Ext.applyIf(config, {
            xtype: 'xdatetime',
            allowBlank: true,
            dateFormat: MODx.config.manager_date_format,
            timeFormat: MODx.config.manager_time_format,
            startDay: parseInt(MODx.config.manager_week_start),
        });
        return this.inputField(name, config);
    },

    gridColumn: function (name, config = {}) {
        return Ext.applyIf(config, {
            dataIndex: name,
            header: name,
            sortable: true,
        });
    },

    tabs: function (tabs, config = {}) {
        if (config.stateId) {
            Ext.applyIf(config, {
                stateful: true,
                stateEvents: ['tabchange'],
                getState: function () {
                    return {activeTab: this.items.indexOf(this.getActiveTab())};
                },
            });
        }
        let items = [];
        Ext.iterate(tabs, function (tabData) {
            items.push(this.tab(tabData));
        }, this);
        return Ext.applyIf(config, {
            xtype: 'modx-tabs',
            defaults: {
                layout: 'form',
                border: false,
                autoHeight: true,
                layoutOnTabChange: true,
                labelAlign: 'top',
                anchor: '100%',
            },
            forceLayout: true,
            deferredRender: true,//TODO check
            anchor: '100%',
            items: items,
        });
    },

    verticalTabs: function (tabs, config = {}) {
        if (config.stateId) {
            Ext.applyIf(config, {
                stateful: true,
                stateEvents: ['tabchange'],
                getState: function () {
                    return {activeTab: this.items.indexOf(this.getActiveTab())};
                },
            });
        }
        let items = [];
        Ext.iterate(tabs, function (tabData) {
            items.push(this.tab(tabData));
        }, this);
        return Ext.applyIf(config, {
            xtype: 'modx-vtabs',
            defaults: {
                bodyCssClass: 'vertical-tabs-body',
                autoScroll: true,
                autoHeight: true,
                autoWidth: true,
                layout: 'form',
                anchor: '100%',
            },
            forceLayout: true,
            deferredRender: true,//TODO check
            anchor: '100%',
            items: items,
        });
    },

    tab: function (tab, config = {}) {
        return Ext.applyIf(config, {
            defaults: {
                msgTarget: 'under',
                anchor: '100%'
            },
            anchor: '100%',
            title: tab.title,
            items: tab.items
        });
    },

    notice: function (html, config = {}) {
        return Ext.applyIf(config, {
            html: html,
            cls: 'panel-desc',
            style: {
                fontSize: '170%',
                textAlign: 'center'
            }
        });
    },

    progressBarMessage: function (config = {}) {
        return Ext.Msg.show(Ext.applyIf(config, {
            title: _('please_wait'),
            msg: _('saving'),
            width: 410,
            progress: true,
            closable: false,
        }));
    },

    waitMessage: function (config = {}) {
        return Ext.Msg.show(Ext.applyIf(config, {
            title: _('please_wait'),
            msg: _('saving'),
            width: 300,
            wait: true,
            closable: false,
        }));
    },
};
