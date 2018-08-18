var mouseClicks = 0;

function resetStats() {
    mouseClicks = 0;
}

window.onmousedown = function() {
    mouseClicks++;
    console.log("mouseClicks: " + mouseClicks);
}
