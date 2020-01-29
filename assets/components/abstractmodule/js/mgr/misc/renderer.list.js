'use strict';

abstractModule.renderer = {
    boolean: function(value, cell, row) {
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

    image: function(value, cell, row) {
        if (/(jpg|png|gif|jpeg)$/i.test(value)) {
            if (!/^\//.test(value)) {
                var src = '/' + value;
            }
            return '<img src="' + src + '" height="35" alt="" class="grid-image">';
        }
    },

    color: function(value, cell, row) {
        return '<div style="width: 30px; height: 20px; border-radius: 3px; background: #' + value + '">&nbsp;</div>';
    },

    user: function(value, cell, row) {
        if (!value) {
            return '';
        }
        var id = row.get('id');
        if (!id) {
            return value;
        }
        return String.format(
            '<a href="?a=security/user/update&id={0}" target="_blank">{1}</a>',
            id,
            value
        );
    }
};
