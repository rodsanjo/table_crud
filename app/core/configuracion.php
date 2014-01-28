<?php
namespace core;

class Configuracion {
	
	public static $controlador_por_defecto = 'Inicio';
	
	public static $metodo_por_defecto = 'index';
	
	public static $plantilla_por_defecto = 'plantilla_principal';
	
	public static $sesion_minutos_inactividad = 20; // Minutos
	
	public static $sesion_minutos_maxima_duracion = 120;
	
	public static $url_amigable = true;
	
	// Control acceso a recursos
	public static $control_acceso_recursos = true;
	
	public static $display_errors = "on"; // Valores posibles "on" "off""

	public static $idioma_por_defecto = "es";
	public static $idioma_seleccionado;
	public static $idiomas_reconocidos = "es|en|fr";
	
	public static $https_login = false;
	public static $form_login_catcha = false;
	public static $form_insertar_externo_catcha = false;
	
	public static $email_info = "info@esmvcphp.es";
	public static $email_noreply = "noreply@esmvcphp.es";
	
	/**
	 *
	 * @var string Tipo MIME utilizado por defecto.
	 */
	public static $tipo_mime_por_defecto = 'text/html';
	
	/**
	 *
	 * @var array = Colección de tipos MIME soportados por esta aplicación. 
	 */
	public static $tipos_mime_reconocidos = array(
		'text/html', 'text/xml', 'text/json', 'application/excel', 
	);
	
	
	// localhost
	public static $db = array(
		'server'   => 'localhost',
		'user'     => 'daw2_user',
		'password' => 'daw2_user',
		'db_name'  => 'daw2',
		'prefix_'  => 'daw2_'
	);
	

	// hostinger
//	public static $db = array(
//		'server'   => 'mysql.hostinger.es',
//		'user'     => 'u452950836_daw2',
//		'password' => 'u452950836_daw2',
//		'db_name'   => 'u452950836_daw2',
//		'prefix_'  => 'daw2_'
//	);
	
	/**
	 * Define array llamado recursos_y_suariosla con la definición de todos los permisos de acceso a los recursos de la aplicación.
	 * * Recursos:
	 *  [*][*] define todos los recursos
	 *  [controlador][*] define todos los métodos de un controlador
	 * Usuarios:
	 *  * define todos los usuarios (anonimo más logueados)
	 *  ** define todos los usuarios logueados (anonimo no está incluido)
	 * 
	 * @var array =('controlador' => array('metodo' => ' nombres usuarios rodeados por espacios
	 */
	public static $recursos_y_usuarios = array(
		'*' =>	array(
					'*' => ' admin '
				),
		'inicio' => array (
						'*' => ' ** ',
						'index' => ' * ',
					),
	
		'mensajes' => array(
							'*' => ' * ',
							),
		'usuarios' => array(
							'*' => ' juan pedro ',
							'index' => ' anais ana olga ',
							'desconectar' => ' ** ',
							'form_login_email' => ' anonimo ',
							'validar_form_login_email' => ' anonimo ',
							)
	
	);
} // Fin de la clase 
