/*
 * idiomas.js
 * Author: jequeto@gmail.com
 * Sice: 2013 december
 */

function set_lang(lang, url) {
	
	// Creamos la cookie para el idioma, caduca en un año.
	setCookie("lang", lang, 365, "/");
	window.location.assign(url);
		
}