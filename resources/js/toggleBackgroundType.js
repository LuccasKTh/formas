var checkbox = document.getElementById('backgroundType');
var color = document.getElementById('color');
var background = document.getElementById('background');

document.addEventListener('DOMContentLoaded', function () {
    toogleBackgroundType(this);
    checkbox.addEventListener('change', function () {
        toogleBackgroundType(this);
    })
})

function toogleBackgroundType(checkbox) {
    checkbox.checked
        ? [color.setAttribute('disabled', true), background.removeAttribute('disabled')]
        : [background.setAttribute('disabled', true), color.removeAttribute('disabled')];
}
