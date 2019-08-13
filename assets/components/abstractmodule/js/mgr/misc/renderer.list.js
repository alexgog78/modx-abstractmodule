abstractModule.renderer = {
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