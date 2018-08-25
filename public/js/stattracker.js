var mouseClicks = 0;
var keyPresses = 0;
var keyPressLog = [];
var seconds = 0;

function resetStats() {
    mouseClicks = 0;
    keyPresses = 0;
    keyPressLog = [];
    seconds = 0;
}

window.onmousedown = function() {
    mouseClicks++;
};

window.onkeydown = function(event) {
    keyPresses++;

    keyPressLog.push({
        key_code: event.code,
        time_elapsed: seconds
    });
};

setInterval(function() {
    seconds++;
}, 1000);
