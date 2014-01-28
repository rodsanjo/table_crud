<?php
namespace core;

class Usuario extends \core\Clase_Base {
	
	private static $depuracion = false;
	
	public static $id;
	public static $login = 'anonimo';
	private static $permisos = array();
	public static $sesion_segundos_duracion = 0;
	public static $sesion_segundos_inactividad = 0;
	
	/**
	 * Reconocer el usuario que ha iniciado la sesión de trabajo o que continúa dentro de una sesión de trabajo.
	 */
	public static function iniciar() {
		
		// Recuperamos datos desde $_SESSION a las propiedades de la clase
		if (isset($_SESSION['usuario']['login'])) {
			if ($_SESSION["usuario"]["REMOTE_ADDR"] != $_SERVER["REMOTE_ADDR"]) {
				$datos["mensaje"] = "Error fatal: La IP de sesión se ha cambiado dentro de la misma sesión de trabajo.";
				\core\Distribuidor::cargar_controlador("error", "mensaje", $datos);
				exit(0);
			}
			self::$login = $_SESSION['usuario']['login'];
			self::$id = $_SESSION['usuario']['id'];
			
		}
		else {
			self::nuevo('anonimo');
		}
		
		if (isset($_SESSION['usuario']['permisos'])) {
			self::$permisos = $_SESSION['usuario']['permisos'];
		}
		else {
			self::recuperar_permisos_bd(self::$login);
		}
		
//		var_dump(self::$permisos);
		
		if (isset($_SESSION['usuario']['contador_paginas_visitadas']))
			$_SESSION['usuario']['contador_paginas_visitadas']++;
		else 
			$_SESSION['usuario']['contador_paginas_visitadas'] = 1;
		
		self::sesion_control_tiempos();

		if (self::$depuracion) {
			echo(__METHOD__." .self::\$permisos = ");
			print_r(self::$permisos);
		}
	}
	
	
	/**
	 * Si el parámetro $login no es una cadena, es una cadena vacía, o es una cadena que contiene caracteres que no sean letras, numeros y _ lanza una execpción con un mensaje.
	 * Si no salta la excepción el valor del parámetro $login es asignado a la propiedad $login de la clase. Borra la entrada $_SESSION['usuario'] y la vuelve a crear asignándo a la entrada $_SESSION['usuario']['login'] el valor del parámetro $login. Después llama al método de esta misma clase recuperar_permisos($login).
	 * 
	 * @param string $login
	 */
	public static function nuevo($login, $id = null) {
		
		self::$login = $login;
		self::$id = $id;
		\core\SESSION::regenerar_id(); // Seguridad
		$_SESSION["usuario"]["contador_paginas_visitadas"] = 1;
		$_SESSION["usuario"]["login"] = $login;
		$_SESSION["usuario"]["id"] = $id;
		$_SESSION["usuario"]["sesion_inicio"] = $_SERVER["REQUEST_TIME"];
		$_SESSION["usuario"]["REMOTE_ADDR"] = $_SERVER["REMOTE_ADDR"];
		
		self::recuperar_permisos_bd(self::$login);
		self::sesion_control_tiempos();
		
		if (self::$depuracion) {
			echo __METHOD__." ".__LINE__." ".self::$login." ".self::$id;
		}
	}
	
	
	public static function cerrar_sesion() {
		
		//self::$login = 'anonimo';
		unset($_SESSION['usuario']);
		\core\SESSION::destruir();
		self::nuevo('anonimo');
		self::sesion_control_tiempos();
		
	}
	
	
	
	private static function recuperar_permisos_bd($login) {
		
		self::$permisos = \modelos\usuarios::permisos_usuario($login);
		$_SESSION['usuario']['permisos'] = self::$permisos;
		
	}
	
	
	public static function tiene_permiso($controlador = "inicio", $metodo = 'index') {
		
		if ( ! \core\Configuracion::$control_acceso_recursos) {
			return true;
		}
		
		$autorizado = false;
		
		// La siguiente línea hace que el usuario que tenga asignado el método form_insertar
		// también pueda acceder al método form_insertar_validar
		$metodo = preg_replace("/_validar|validar_/", "", $metodo);
				
		// El usuario tiene acceo a todos los recursos
		if (isset(self::$permisos['*']['*']))
			$autorizado = true;
		// El usuario o todos los usuarios tienen acceso a todos los métodos del controlador
		elseif (isset(self::$permisos[$controlador]['*']))
			$autorizado = true;
		// El usuario o todos los usuarios tienen acceso al controlador y método determinado
		elseif (isset(self::$permisos[$controlador][$metodo]))
			$autorizado = true;	
		
		return $autorizado;
		
	}
	
	
	
	
	
	private static function sesion_control_tiempos() {
		
		// Tiempo de inactividad
		if (isset($_SESSION['usuario']['sesion_request_time']))
			self::$sesion_segundos_inactividad = $_SERVER['REQUEST_TIME'] - $_SESSION['usuario']['sesion_request_time'];
		else
			self::$sesion_segundos_inactividad = 0;
		
		// Duración de la sesión
		if (isset($_SESSION['usuario']['sesion_inicio']))
			self::$sesion_segundos_duracion = $_SERVER['REQUEST_TIME'] - $_SESSION['usuario']['sesion_inicio'];
		else
			self::$sesion_segundos_duracion = 0;
		
		// Memorizamos la hora de la petición actual para tenerlo en cuenta en la siguiente petición que realice el usuario.
		$_SESSION['usuario']['sesion_request_time'] = $_SERVER['REQUEST_TIME'];
		
	}
	
	
	
} // Fin de la clase