Code = {
    init: function()
    {
        var close = localStorage.getItem('code:attention-close');
        if (close === 'true') {
            this.onCloseAttention.apply($('.attention').get(0));
        }
    },

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
    },

    onCloseAttention: function(){
        var parentNode = this.parentNode;
        parentNode.removeChild(this);
        parentNode.appendChild(this);
        $('.close', this).remove();

        localStorage.setItem('code:attention-close', true);
        return false;
    }
}

$(function(){
    Code.init();
    $('.attention').bind('close', Code.onCloseAttention);
});

