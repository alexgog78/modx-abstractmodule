abstractModule.renderer = {
    image: function(value, cell, row) {
        if(/(jpg|png|gif|jpeg)$/i.test(value)) {
            if(!/^\//.test(value)) {value = '/'+value;}
            return '<img src="'+value+'" height="35" alt="">';
        }
    },

    //Пользователь
    /*renderUser: function(value, cell, row) {
        if(row.get('user_name')) value = row.get('user_name');
        if(row.get('user_active')==0 || row.get('user_blocked')==1) value = '<span class="red">'+value+'</span>';
        return value;
    },*/

    //Цвет
    /*renderColor: function(value, cell, row) {
        return '<div style="width: 30px; height: 20px; border-radius: 3px; background: #'+value+'">&nbsp;</div>'
    }*/
};