<?php
namespace core;
//require_once PATH_APP."core/clase_base.php";
//require_once PATH_APP."core/respuesta.php";


/**
 * Aplicación principal
 *
 * @author Jesús María de Quevedo Tomé <jequeto@gmail.com>
 * @since 20130130
 */
class Aplicacion extends \core\Clase_Base {
	
	/**
	 * Almacenará el objeto resultado de instanciar la clase Controlador que se encargará
	 * de atender la petición HTTP recibida.
	 * 
	 * @var \core\Controlador 
	 */
	public static $controlador;

	
//	public function __construct() {
	public static function iniciar() {

		
		$display_errors = strtolower(\core\Configuracion::$display_errors);
		if ($display_errors == "on"
				or $display_errors == "off") {
			ini_set("display_errors", $display_errors);
		}
		// Interpretar url amigable, pasa parmametros /dato1/dato2/dato3/ 
		// a parámetros $_GET[p1]=dato1 $_GET[p2]=dato2  $_GET[p3]=dato3 ....
		\core\Rutas::interpretar_url_amigable();
		
		\core\sgbd\bd::connect();
		
		\core\SESSION::iniciar();
		\core\URL::registrar();
		
			
		// Reconocer el usuario que ha iniciado la sesión de trabajo o que continúa dentro de una sesión de trabajo.
		\core\Usuario::iniciar();
				
		// Los permisos los usamos si trabajamos con la ACL (Access Control List) para definir los permisos de los usuarios
		// \core\Permisos::iniciar();
		
		
		
		// Estudio del idioma, después de que la url amigable se pase a parámetros GET
		\core\Idioma::init();
		
		// Distribuidor
		\core\Distribuidor::estudiar_query_string();

		// Cerrar conexión a la base de datos
		\core\sgbd\bd::disconnect();	
		
	}
	
} // Fin de la clase