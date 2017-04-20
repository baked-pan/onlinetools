String.prototype.colorHex = function() {
    var reg = /^#([0-9a-fA-f]{3}|[0-9a-fA-f]{6})$/;
    var that = this;
    if (/^(rgb|RGB)/.test(that)) {
        var aColor = that.replace(/(?:\(|\)|rgb|RGB)*/g, "").split(",");
        var strHex = "#";
        for (var i = 0; i < aColor.length; i++) {
            var hex = Number(aColor[i]).toString(16);
            if (hex === "0") {
                hex += hex;
            }
            strHex += hex;
        }
        if (strHex.length !== 7) {
            strHex = that;
        }
        return strHex;
    } else if (reg.test(that)) {
        var aNum = that.replace(/#/, "").split("");
        if (aNum.length === 6) {
            return that;
        } else if (aNum.length === 3) {
            var numHex = "#";
            for (var i = 0; i < aNum.length; i += 1) {
                numHex += (aNum[i] + aNum[i]);
            }
            return numHex;
        }
    } else {
        return that;
    }
};
function format() {
    $('#pictype a').bind('click', function() {
        var text = $('#apiText').attr('title');
        if (!text) {
            text = "http://2wm.ntcfu.net/";
        }
        text=encodeURIComponent(text);
        var vt = $(this).attr('data-format');
        var param = '';
        var fg = $('#fgcolor').attr('data-color');
        if (fg) {
            param += '&fg=' + fg.colorHex().replace('#', '');
        }
        var bg = $('#bgcolor').attr('data-color');
        if (bg) {
            param += '&bg=' + bg.colorHex().replace('#', '');
        }
        var pt = $('#ptcolor').attr('data-color');
        if (pt) {
            param += '&pt=' + pt.colorHex().replace('#', '');
        }
        var inpt = $('#inptcolor').attr('data-color');
        if (inpt) {
            param += '&inpt=' + inpt.colorHex().replace('#', '');
        }
        var el = $('#level').val();
        var m = parseInt($('#margin').val());
        var href = 'http://liantu.com/savevector.php?text=' + text + param + '&el=' + el + '&m=' + m + '&vt=' + vt;
        location.href = href;
        return false;
    })
}
format();