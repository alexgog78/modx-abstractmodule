abstractModule.renderer = {
    boolean: function(value, cell, row) {
        switch (value) {
            case 0:
            case '0':
            case false:
                cell.css = 'red';
                value = _('no');
                break;
            case 1:
            case '1':
            case true:
                cell.css = 'green';
                value = _('yes');
                break;
            default:
                value = '-';
                break;
        }
        return value;
    },
    image: function(value, cell, row) {
        if(/(jpg|png|gif|jpeg)$/i.test(value)) {
            if(!/^\//.test(value)) {value = '/'+value;}
            return '<img src="'+value+'" height="35" alt="">';
        }
    },
    color: function(value, cell, row) {
        return '<div style="width: 30px; height: 20px; border-radius: 3px; background: #'+value+'">&nbsp;</div>'
    }
};