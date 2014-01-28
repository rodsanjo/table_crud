/* f_cookies.js  v05 */
/* En este fichero se recopilan funciones que pueden ser usadas para trabajar con cookies en javascript */
/*

/*
Recopilado por Jesús Mª de Quevedo Tomé
Created: Noviembre, 2010
Updated: Decembre 2013

Fuentes:
http://w3schools.com/js/js_cookies.asp

*/

/**
 * Crea una cookie
 * 
 * @param {string} cookie_name
 * @param {string} cookie_value
 * @param {int} expiredays Días para la expiración , puede ser positivo (pervive) o negativo (caduca)
 * @param {type} path
 * @param {type} domain
 * @param {type} secure
 * @param {type} httponly
 * @returns {undefined}
 */
function setCookie(cookie_name, cookie_value, expiredays, path, domain, secure, httponly) {
	// Save a cookie in our computer
	// expiredays in a integer with + day to the future from today.
	var exdate = new Date();
	exdate.setDate(exdate.getDate() + expiredays);
	cookie = cookie_name + "=" +escape(cookie_value)+ ((expiredays==null) ? "" : ";expires="+exdate.toUTCString());
	cookie += (path==null) ? "" : ";path="+path;
	cookie += (domain==null) ? "" : ";domain="+domain;
	cookie += (secure==null) ? "" : ";secure="+secure;
	cookie += (httponly==null) ? "" : ";httponly="+httponly;
	document.cookie = cookie;
//  alert("setCookie -> "+cookie);
}

function getCookie(cookie_name) {
  // Rescue a cookie from our computer
  // Return the cookie value or false
  // A cookie string has the next structure: cookieName=cookieValue;
  // In document.cookie return a string width the cookies, and it has the next structure:
  //  "cookieName1=cookieValue1;cookieName2=cookieValue2;[...]"
  if (document.cookie.length>0)  { // Comprueba si hay cookies
    c_start=document.cookie.indexOf(cookie_name + "="); // Busca donde empieza la cookie buscando su nombre seguido de =
    if (c_start!=-1) { // Pregunta si ha encontrado la cookie
      c_start=c_start + cookie_name.length+1;
      c_end=document.cookie.indexOf(";",c_start); // Busca d�nde acaba el valor de la cookie buscando el ;
      if (c_end==-1) c_end=document.cookie.length; // Si no hay ; al final, toma como final la longitud de la cadena
      return unescape(document.cookie.substring(c_start,c_end)); //Extrae el valor de la cookie
    }
  }
  return false;
}

function deleteCookie(cookie_name) {
  // alert("Borrando ... "+cookie_name);
  setCookie(cookie_name, "", -1);
}

function deleteAllCookies() {
  //alert("Borrando todas las cookies ...");
  var patron_cookie_name = /\w{1,}\=/ig;
  var cookie_name;

  //while (document.cookie.length > 0) {
    //alert("document.cookie="+document.cookie);
    var cadena_cookies = document.cookie;
    cookie_names = cadena_cookies.match(patron_cookie_name);
    //alert(typeof(cookie_names)+":"+cookie_names);
    //cookie_names = cookie_names.split("=,");
    for (i in cookie_names) {
      //alert("Borrando ... "+i+" "+cookie_names[i]);
      cookie_name = cookie_names[i].substring(0,cookie_names[i].length-1);
      
      deleteCookie(cookie_name);
    }
    //deleteCookie(cookie_name);
  //}
}