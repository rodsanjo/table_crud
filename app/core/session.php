<?php
namespace core;

class SESSION {
	
	public static function iniciar() {
		if (isset($_GET["administrator"])) {
			session_name("ADMINISTRATOR_PHPSESSID" );
		}
		else {
			session_name("PHPSESSID" );
		}
		session_start(); // Se crea el arry $_SESSION o se recupera si fue creado en una ejecución anterior del script.
		
	}
	
	
	
	public static function destruir() {
		
		// Borramos el cookie de sesion.
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			\core\HTTP_Respuesta::setcookie(session_name(), '', time() - 42000,
				$params["path"], $params["domain"],
				$params["secure"], $params["httponly"]
			);
		}
		
		session_destroy();
		
	}
	
	
	
	public static function regenerar_id() {
		
		session_regenerate_id(true);
		
	}
	
}