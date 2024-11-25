String.prototype.ucWords = function () {
    return this.toLowerCase().replace(/\b[a-z]/g, function (letter) {
        return letter.toUpperCase();
    });
}
$.extend($.fn.dataTable.defaults, {
    responsive: true,
    language: {
        search: "_INPUT_",
        searchPlaceholder: "Search",
    },
    lengthMenu: [[10, 20, 50, 100, 200, 500, -1], [10, 20, 50, 100, 200, 500, 'ALL']],
});

var mapBlockUI = function () {
    mApp.blockPage({
        overlayColor: '#000000',
        type: 'loader',
        state: 'primary',
        message: "Please Wait...",
        fadeIn: 0
    });
    $(".blockUI.blockMsg .m-blockui").removeAttr("style");
    return true;
}

var mapUnblockUI = function () {
    mApp.unblockPage();
    return true;
}

$(document)
    .ajaxStart(function () {
        mapBlockUI();
    })
    .ajaxStop(function () {
        mapUnblockUI();
    });

var setInputFilter = function (textbox, inputFilter) {
    ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function (event) {
        textbox.addEventListener(event, function () {
            if (inputFilter(this.value)) {
                this.oldValue = this.value;
                this.oldSelectionStart = this.selectionStart;
                this.oldSelectionEnd = this.selectionEnd;
            } else if (this.hasOwnProperty("oldValue")) {
                this.value = this.oldValue;
                this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
            } else {
                this.value = "";
            }
        });
    });
};

(function ($) {
    $.fn.donetyping = function (callback, delaySeconds = 1000) {
        var _this = $(this);
        var x_timer;
        _this.keyup(function () {
            clearTimeout(x_timer);
            x_timer = setTimeout(clear_timer, delaySeconds);
        });

        function clear_timer() {
            clearTimeout(x_timer);
            callback.call(_this);
        }
    };
})(jQuery);