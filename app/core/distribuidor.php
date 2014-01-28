<?php
namespace core;

/**
 * @author Jesús María de Quevedo Tomé <jequeto@gmail.com>
 * @since 2013-01-30
 */
class Distribuidor {

	private static $controlador_instanciado = null;
	private static $metodo_invocado = null;
	
	
	/**
	 * Realiza el estudio del requerimiento http recibido y elige el 
	 * controlador y método que se ejecutará para atenderla.
	 * Solo debe invocarse desde la clase aplicación.
	 * Para ejecutar un controlador desde otro controlador (forwarding) debe
	 * usarse el método cargar_controlador() de esta clase.
	 * 
	 * @author Jesús Mª de Quevedo
	 */
	public static function estudiar_query_string() {		
		
		$controlador = isset($_GET['menu']) ? \core\HTTP_Requerimiento::get('menu') : \core\HTTP_Requerimiento::get('p1');
		$metodo = isset($_GET['submenu']) ? \core\HTTP_Requerimiento::get('submenu'): \core\HTTP_Requerimiento::get('p2');		
		
		if ( $controlador  == null || (boolean)\core\Validaciones::errores_identificador($controlador) )
			$controlador = strtolower(\core\Configuracion::$controlador_por_defecto);
		if ( ! $metodo || (boolean)\core\Validaciones::errores_identificador($metodo) )
			$metodo = strtolower(\core\Configuracion::$metodo_por_defecto);
		
		self::cargar_controlador($controlador, $metodo);
		
	}
	
	

	
	
	/**
	 * Carga la clase controladora indicada en los parámetros y ejecuta el método de esa clase pasado en los parámetros. Al método se le pasa el array
	 * de datos pasado como parámetro.
	 * 
	 * @param string $controlador Clase controladora a instanciar
	 * @param string $metodo Método a ejecutar
	 * @param array $datos Datos para el método
	 */
	public static function cargar_controlador($controlador, $metodo="index", array $datos = array()) {
		
		$metodo = ($metodo ? $metodo : "index"); // Asignamos el método por defecto
		
		// Comprobamos que el usuario tiene permisos. Si no los tiene se redirige hacia otro controlador.
		if (\core\Usuario::tiene_permiso($controlador, $metodo) === false ) {
			if (\core\Usuario::$login == 'anonimo') {
				$controlador = 'usuarios';
				$metodo = 'form_login';
			}
			else {
				$datos['mensaje'] = "No tienes permisos para esta opción [$controlador][$metodo].";
				$controlador = 'mensajes';
				$metodo = 'index';
			}
		}
		
		$fichero_controlador = strtolower(PATH_APP."controladores/$controlador.php");
		$controlador_clase = strtolower("\\controladores\\$controlador");
		
		if (file_exists($fichero_controlador)) {			
			\core\Aplicacion::$controlador = new $controlador_clase();
			// Memorizamos el nombre del controlador para reutilizarlo en formularios
			\core\Aplicacion::$controlador->datos['controlador_clase'] = strtolower($controlador);
			self::$controlador_instanciado = strtolower($controlador);
			
			if (method_exists(\core\Aplicacion::$controlador, $metodo)) {
				// Memorizamos el nombre del método para reutilizarlo en formularios
				\core\Aplicacion::$controlador->datos['controlador_metodo'] = strtolower($metodo);
				self::$metodo_invocado = strtolower($metodo);
				return \core\Aplicacion::$controlador->$metodo($datos);
				
			}
			else {
				$datos['mensaje'] = "El método <b>$metodo</b> no está definido en la clase <b>$controlador_clase</b> (.php).";
				return self::cargar_controlador("errores", "error_404", $datos);
			}
		}
		else {
			$datos['mensaje'] = "La clase <b>$controlador_clase</b> no existe.";
			return self::cargar_controlador("errores", "error_404", $datos);
		}
	}
	
	
	
	public static function get_controlador_instanciado() {
		
		return self::$controlador_instanciado;
		
	}
	
	
	public static function get_metodo_invocado() {
		
		return self::$metodo_invocado;
		
	}
	
	
} // Fin de la clase