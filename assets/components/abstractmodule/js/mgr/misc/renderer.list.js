'use strict';

abstractModule.renderer = {
    boolean: function (value, cell, row) {
        switch (value) {
            case 0:
            case '0':
            case false:
                cell.css = 'red';
                return _('no');
                break;
            case 1:
            case '1':
            case true:
                cell.css = 'green';
                return _('yes');
                break;
            default:
                return '-';
                break;
        }
    },

    image: function (value, cell, row) {
        cell.css = 'grid-image';
        if (!/(jpg|png|gif|jpeg)$/i.test(value)) {
            value = 'assets/components/abstractmodule/css/mgr/no-photo.png';
        }
        let src = MODx.config.connectors_url + 'system/phpthumb.php?src=' + value + '&w=70&h=35&zc=0&f=png&bg=ffffff';
        value = '<img src="' + src + '" alt="">';
        return value;
    },

    color: function (value, cell, row) {
        return '<div style="width: 30px; height: 20px; border-radius: 3px; background: #' + value + '">&nbsp;</div>';
    },

    user: function (value, cell, row) {
        let name = row.get('user_fullname') ?? value;
        return String.format(
            '<a href="?a=security/user/update&id={0}" target="_blank">{1}</a>',
            value,
            name
        );
    },

    resource: function (value, cell, row) {
        let name = row.get('resource_pagetitle') ?? value;
        return String.format(
            '<a href="?a=resource/update&id={0}" target="_blank">{1}</a>',
            value,
            name
        );
    },
};
