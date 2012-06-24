$(function(){
    $('time').each(function(){
        $this = $(this);
        var utc = $(this).attr('datetime');
        utc = utc.replace(/-/g, '/');
        var date = new Date(utc);
        var y = date.getFullYear();
        var m = 1 + date.getMonth();
        if(m < 10) { m = "0" + m; }
        var d = date.getDate();
        var H = date.getHours();
        var M = date.getMinutes();
        if (M < 10) { M = "0" + M; }
        var S = date.getSeconds();
        $this.text(y + "/" + m + "/" + d + " " + H + ":" + M + ":" + S);
    });
});
