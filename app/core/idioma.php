<?php
namespace core;


/**
 * Description of Idioma
 *
 * @author jesus
 */
class Idioma {
	
	/**
	 * Estudia el requerimiento para definir el idioma seleccionado por el cliente.
	 */
	public static function init() {
		
		$patron = "/^(".\core\Configuracion::$idiomas_reconocidos.")$/";
		$patron_HTTP_ACCEPT_LANGUAGE = "/^(".\core\Configuracion::$idiomas_reconocidos.").*/";
		if (\core\HTTP_Requerimiento::get("lang") && preg_match($patron, \core\HTTP_Requerimiento::get("lang"))) {
			self::set(\core\HTTP_Requerimiento::get("lang"));
		}
		elseif (\core\HTTP_Requerimiento::cookie("lang") && preg_match($patron, \core\HTTP_Requerimiento::cookie("lang"))) {
			self::set(\core\HTTP_Requerimiento::cookie("lang"));
		}
		elseif (preg_match($patron_HTTP_ACCEPT_LANGUAGE, $_SERVER["HTTP_ACCEPT_LANGUAGE"]))
			self::set(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2));
	}
	
	
	public static function set($lang) {
		
		$patron = "/^(".\core\Configuracion::$idiomas_reconocidos.")$/";
		if (preg_match($patron, $lang)) {
			\core\Configuracion::$idioma_seleccionado = $lang;
			// Enviamos la cookie para que recuerde el idioma
			// Si es igual al idioma por defecto borramos la cookie
			if ( \core\Configuracion::$idioma_seleccionado == \core\Configuracion::$idioma_por_defecto) {
				// Borramos la cookie
//				\core\HTTP_Respuesta::setcookie("lang", "", time()-60*60*24*365, "/");
			}
			else {
				// Renovamos la cookie por un año
//				\core\HTTP_Respuesta::setcookie("lang", \core\Configuracion::$idioma_seleccionado, time()+60*60*24*365, "/");

			}
		}
			
//		echo "depuración:".__METHOD__." $lang<br />";
	}
	
	/**
	 * Retorna el idioma seleccionado por el cliente.
	 * 
	 * @return string
	 */
	public static function get() {
		
		return \core\Configuracion::$idioma_seleccionado ? \core\Configuracion::$idioma_seleccionado : \core\Configuracion::$idioma_por_defecto;
		
	}
	
	/**
	 * Devuelve un texto asociado a una clave, tomado del fichero seccion_lang.txt
	 * 
	 * @param string $key
	 * @param string $section
	 * @param string $lang
	 * @return string
	 */
	public static function text($key, $section , $lang = null ) {
		
		return \modelos\Idiomas::get($key, $section, $lang);
		
	}
	
}