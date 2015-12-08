/**
   Align the hero element background image by setting the background-position
   CSS property. Due to Edin theme not being easily "pluggable" it's easiest to do
   this in JavaScript - although not ideal.
*/
var path = document.getElementById('hero-aligner').src;
var align = decodeURI(getQueryVariable('align',path));

align = align.replace(/[^a-zA-Z0-9]/g,' ');
align = align.replace(/\s+/g,' ');

var style = document.createElement("style");

/* FIXME This is a bit ugly but works */
style.appendChild(document.createTextNode('.hero.with-featured-image { background-position: ' + align + '; }'));
document.head.appendChild(style);

function getQueryVariable(variable,path) {
    var query = path;
    var vars = query.split("?");
    for (var i=0; i<vars.length; i++) {
	var pair = vars[i].split("=");
	if (pair[0] == variable) {
	    return pair[1];
	}
    }
}
