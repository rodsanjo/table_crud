<?php
namespace core;

/**
 * @author Jesús María de Quevedo Tomé <jequeto@gmail.com>
 * @since 2013 12 02
 */
class Rutas {
	
	
	/**
	 * Si la propiedad \core\Configuracion::$url_amibable es true, y además
	 * exites fichero .htacces en la carpeta root de la aplicación, y en él 
	 * RewriteEngine = on
	 * 
	 * Este método transfoma la URI /dato1/dato2/dato3/....
	 * En entradas nuevas en $_GET y $_REQUEST, llamadas [p1],[p2],[p3], ... a las
	 * que se asigna los valores dato1, dato2, dato3, ... respectivamente
	 * Si no se hubiesen recibido como parámetros de URI ?menu=...&submenu=...&id=....
	 * también creará las entradas [menu], [submenu] e [id] asignándolas los 
	 * valores datos1, dato2 y dato3 respectivamente.
	 *
	 * @author jequeto@gmail.com
	 * @since 125/11/2013
	 */
	public static function interpretar_url_amigable() {
		
		if ( \core\Configuracion::$url_amigable ) {
			// Sea la URL http://www.servidor.com/dato1/dato2/dato3/[...]
			// o sea la URL http://www.servidor.com/aplicacion/dato1/dato2/dato3/[...]
			// $_SERVER["SCRIPT_NAME"] almacenará la cadena /index.php o /aplicacion/index.php respectivamente.
			// $carpeta almacena la carpeta donde está alojada la aplicación dentro
			// del ServerRoot. Será "/aplicacion" o ""
			$carpeta = str_replace("/index.php", "", $_SERVER["SCRIPT_NAME"]);

			// $_SERVER["REQUEST_URI"] almacenará la cadena /dato1/dato2/dato3/[...]
			//  o /aplicacion/dato1/dato2/dato3/[...] respectivamente
			
			$query_string = str_replace($carpeta, "", $_SERVER["REQUEST_URI"]); 
			// Ahora $query_string será una cadena de la forma "/dato1/dato2/dato3/"
			// Quitamos la primera y la última barra si existen
			if (stripos($query_string, "/") == 0 )
				$query_string = substr($query_string, 1);
			if (strrpos($query_string, "/") == strlen($query_string)-1 )
				$query_string = substr($query_string, 0, strlen($query_string)-1);
			// Pasamos los parámetros amigables a un array llamado $parámetros, que tendrá índice entero.
			$parametros = explode("/", $query_string);
//			$parametros = array(); // Recogerá los parámetros pasados en forma amigable
			// Buscamos cada uno de los parámetros dato1/  dato2/  ...
			// $parametros = explode("/", $query_string);
//			preg_match_all("/\/\.+/i", $query_string, $parametros);
			
			
			if (isset($parametros[0])) {
				if ($parametros[0] == "administrator") {
					$_GET["administrator"] = strtolower($parametros[0]);
					$_REQUEST["administrator"] = strtolower($parametros[0]);
					array_shift($parametros); // Quitamos del array de encuentros el administrator
				}
			}
			if (isset($parametros[0])) {
				$patron_idiomas = "/^(".\core\Configuracion::$idiomas_reconocidos.")$/i";
				if (preg_match($patron_idiomas, $parametros[0])) {
					$_GET["lang"] = strtolower($parametros[0]);
					$_REQUEST["lang"] = strtolower($parametros[0]);
					array_shift($parametros); // Quitamos del array de encuentros el idioma
				}
			}
			
			$patron[0] = "/^[\w\-]+$/i"; // controlador
			$patron[1] = "/^[\w\-]+$/i"; // método
			$patron[2] = "/^[\w\-]+$/i"; // id
			$patron[3] = "/.*/"; // id y otros
			foreach ($parametros as $key => $value) {
				// Si el parámetro se ha recibido no se añade
				// Si lo añado, quito la / del inicio.
				$patron_parametro = $key < 3 ? $patron[$key] : $patron[3];
				if (preg_match($patron_parametro, $value))
					if ( ! isset($_GET["p".($key+1)]) ) $_GET["p".($key+1)] = $value;
			}
			
		}
		// Transformación los parámentros p1, p2, p3, p4, ...
		// a otros nombres más significativos menu, submenu, id, ...
		if ( ! isset($_REQUEST['menu']) and isset($_GET['p1'])) {
				$_GET['menu'] = $_GET['p1'];
				$_REQUEST['menu'] = $_GET['p1'];
		}
		if ( ! isset($_REQUEST['submenu']) and isset($_GET['p2'])) {
				$_GET['submenu'] = $_GET['p2'];
				$_REQUEST['submenu'] = $_GET['p2'];
		}
		if ( ! isset($_REQUEST['id']) and isset($_GET['p3'])) {
				$_GET['id'] = $_GET['p3'];
				$_REQUEST['id'] = $_GET['p3'];
		}
		if ( ! isset($_REQUEST['key']) and isset($_GET['p4'])) {
				$_GET['key'] = $_GET['p4'];
				$_POST['key'] = $_GET['p4'];
				$_REQUEST['key'] = $_GET['p4'];
		}
		//echo "<pre>"; print_r($parametros); print_r($GLOBALS);exit(0);
		
	}
	
	
} // Fin de la clase
