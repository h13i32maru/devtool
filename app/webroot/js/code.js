Code = {
    langs:{
        'Auto':'auto',
        'Plain Text': 'no-highlight'
    },

    addCodeForm: function(){
        var langs = this.langs;
        var $select = $('<select/>').attr('name', 'class[]');
        for (var lang in langs) {
            $('<option/>').attr('value', langs[lang]).text(lang).appendTo($select);
        }

        var $textarea = $('<textarea/>').attr('name', 'code[]');

        $('.code-form').eq(0).append($select).append($textarea);
    },

    deleteConfirm: function(e){
        if (!confirm('Do you delete this code?')){
            return false;
        }
    }
}

