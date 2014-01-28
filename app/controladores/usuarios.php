<?php
namespace controladores;


class usuarios extends \core\Controlador {
	

	
	public function index(array $datos = array()) {
		
		$clausulas['order_by'] = 'login';
		
		$datos['filas'] = \modelos\Modelo_SQL::table("usuarios")->select($clausulas);
		
		$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos);
		$http_body = \core\Vista_Plantilla::generar('plantilla_principal', $datos);
		\core\HTTP_Respuesta::enviar($http_body);
		
	}
	
	
	
	public function form_login(array $datos = array()) {
		
		// Anulamos la conexión por https hasta que el servidor esté disponible
		if ( \core\Configuracion::$https_login && ((isset($_SERVER["REQUEST_SCHEME"]) && $_SERVER["REQUEST_SCHEME"] == "http") 
				|| (isset($_SERVER["SERVER_PORT"]) && $_SERVER["SERVER_PORT"] == 80))) {
			\core\HTTP_Respuesta::set_header_line("location", \core\URL::https_generar("usuarios/form_login"));
			\core\HTTP_Respuesta::enviar();
		}
		elseif (\core\Usuario::$login == "anonimo") {
			$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos, true);
			$http_body = \core\Vista_Plantilla::generar('plantilla_principal', $datos, true);
			\core\HTTP_Respuesta::enviar($http_body);
		}
		else {
			$datos["mensaje"] = "El usuario actual es <b>".\core\Usuario::$login."</b>. Para cambiar de usuario debes desconectarte primero." ;
			$this->cargar_controlador('mensajes', 'mensaje', $datos);
		}
		
		
	}
	
	
	public function form_login_validar(array $datos = array()) {
//		print(__METHOD__.__LINE__); exit();
		if (\core\Usuario::$login != "anonimo") {
			$datos["mensaje"] = "Ya te encuentras conectado. Utiliza el menú para navegar.";
			\core\Distribuidor::cargar_controlador("mensajes", "mensaje", $datos);
		}
		elseif ( ! \core\HTML_Tag::form_autenticar("form_login", "post")) {
			// El formulario no se ha enviado con anterioridad desde el servidor
			$datos["mensaje"] = "Error: formulario no identificado.";
			\core\Distribuidor::cargar_controlador("errores", "mensaje", $datos);
		}
		else {
			
				// El formulario sí se ha enviado desde el servidor
				$validaciones = array(
					'login' => 'errores_requerido && errores_login',
					'password' => 'errores_requerido && errores_password'
				);

				$validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos);
				if ($validacion && \core\Configuracion::$form_login_catcha) {
					require_once(PATH_APP.'lib/php/recaptcha-php-1.11/recaptchalib.php');
					$privatekey = "6Lem1-sSAAAAAPfnSmYe5wyruyuj1B7001AJ3CBh";
					$resp = recaptcha_check_answer ($privatekey,
												  $_SERVER["REMOTE_ADDR"],
												  $_POST["recaptcha_challenge_field"],
												  $_POST["recaptcha_response_field"]);

					if ( ! $resp->is_valid) {
							$validacion = false;
							$datos['errores']['validacion'] = 'Error de intruducción del captcha.';
//							\core\Distribuidor::cargar_controlador("usuarios", "form_login", $datos);
					}

				}
				if ($validacion) {
					$respuesta =  \modelos\Modelo_SQL::tabla("usuarios")->validar_usuario($datos['values']['login'], $datos['values']['password']);

					if  ($respuesta == 'existe') {
							$datos['errores']['validacion'] = 'Error en login o password';
							\core\Distribuidor::cargar_controlador("usuarios", "form_login", $datos);
					}
					elseif ($respuesta == 'existe_autenticado') {
							$datos['mensaje'] = "Falta confimación del usuario {$datos['values']['login']}. Consulta tu correo electrónico" ;
							$this->cargar_controlador('mensajes', 'mensaje', $datos);
					}
					elseif ($respuesta == 'existe_autenticado_confirmado') {
							$datos['login'] = $datos['values']['login'];

							$clausulas['where'] = " login = '{$datos['values']['login']}' ";
							$filas = \modelos\Modelo_SQL::table("usuarios")->select($clausulas);

							\core\Usuario::nuevo($datos['values']['login'], $filas[0]['id']);
							$datos["mensaje"] = "Bienvenido la aplicación: <b>{$datos['values']['login']}</b>." ;
							$this->cargar_controlador('mensajes', 'mensaje', $datos);
							
					}
				}
				else {
					\core\Distribuidor::cargar_controlador("usuarios", "form_login", $datos);
				}
			}
			
	}
		
	
	
	
	
	/**
	 * Esta función se invocará después de regenerar una session, por lo que los datos
	 * guardados en $_SESSION se habrán perdido antes de ejecutar esta función.
	 * 
	 * @param array $datos
	 * @return boolean
	 */
	public function desconectar(array $datos = array()) {
		
		\core\Usuario::cerrar_sesion();
		if ( ! isset($datos['desconexion_razon']))
			$datos['desconexion_razon'] = null;
	
		if ($datos['desconexion_razon'] === null) {
			$datos['mensaje'] = 'Adios';
		}
		elseif ($datos['desconexion_razon'] == 'inactividad') {
			$datos['mensaje'] = 'Has superado el tiempo de inactividad que es de <b>'.\core\Configuracion::$sesion_minutos_inactividad.'</b> minutos.';
			$datos['url_continuar'] = \core\URL::generar("inicio");
		}
		elseif ($datos['desconexion_razon'] == 'tiempo_sesion_agotado') {
			$datos['mensaje'] = 'Has agotado el tiempo de tu sesión que es de <b>'.\core\Configuracion::$sesion_minutos_inactividad.'</b> minutos.<br />Vuelve a conectarte para seguir trabajando.';
			 $datos['url_continuar'] = \core\URL::generar("inicio");
		}
				
		return $this->cargar_controlador("mensajes", "desconexion", $datos);
		
	}
	
	
	public function form_login_email(array $datos = array()) {
		
		$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos);
		$http_body_content = \core\Vista_Plantilla::generar("plantilla_principal",$datos);
		\core\HTTP_Respuesta::enviar($http_body_content);
		
	}
	
	
	
	public function form_login_email_validar(array $datos = array()) {
		$validaciones = array(
			'login' => 'errores_texto',
			'email' => 'errores_email',
			'password' => 'errores_requerido'
		);
		
		$validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos);
		
		if ($validacion) {		
			if ( ! strlen($datos['values']['login']) && ! strlen($datos['values']['login'])) {
				$datos['errores']['validacion'] = 'Introduce el login o el dni';
				$validacion = false;
			}
			elseif ( ! strlen($datos['values']['login']) && ! strlen($datos['values']['login'])) {
				$datos['errores']['validacion'] = 'Introduce solo uno de los dos: login o el dni';
				$validacion = false;
			}
		}
		
		if ($validacion) {		
			$respuesta =  \modelos\Modelo_SQL::tabla("usuarios")->validar_usuario_login_email($datos['values']);
			if  ($respuesta == 'existe') {
					$datos['error_validacion'] = 'Error en usuario o contraseña';
					\core\Distribuidor::cargar_controlador("usuarios","form_login_email",$datos);
			}
			elseif ($respuesta == 'existe_autenticado') {
					$datos['login'] = $datos['values']['login'];
					$datos["mensaje"] = "Falta confirmación" ;
					$this->cargar_controlador('mensajes', 'mensaje', $datos);
			}
			elseif ($respuesta == 'existe_autenticado_confirmado') {
					$datos['login'] = $datos['values']['login'];
					\core\Usuario::nuevo($datos['values']['login']);
					$datos["mensaje"] = "Bienvenido la aplicación: <b>{$datos['values']['login']}</b>." ;
					$this->cargar_controlador('mensajes', 'mensaje', $datos);
			}
			else {
				$validacion = false;
			}
		}
		if (! $validacion) {
			$datos['errores']['validacion'] = 'Error de usuario o contraseña';
			\core\Distribuidor::cargar_controlador("usuarios", "form_login_email",$datos);
		}
	}
	
	
	
	
	public function form_modificar(array $datos = array()) {
	
		if ( ! isset($datos["errores"])) {
			//Comprobar que la petición se ha recibido por post.
			if ( \core\HTTP_Requerimiento::method() != "POST" ) {
				$datos['mensaje'] = "Petición invalida";
				return \core\Distribuidor::cargar_controlador("mensajes", "mensaje",$datos);
			}
			// Recuperar fila de la base de datos
			// Primero buscamos el valor del id que se habrá recibido
			$validaciones = array(
				"id" => "errores_requerido && errores_referencia:id/usuarios/id"
			);
			if ( ! $validacion = !\core\Validaciones::errores_validacion_request($validaciones, $datos)) {
				return $this->cargar_controlador("mensajes", "mensaje", array("mensaje" => "Usuario no identificado o no existente."));
			}
			// Debe recibirse por post
			if (\core\HTTP_Requerimiento::method() != "POST") {
				return $this->cargar_controlador("mensajes", "mensaje", array("mensaje" => "Utiliza los elementos del menú y botones de la aplicación."));
			}
			$clausulas = array("where" => " id = {$datos["values"]["id"]} ");
			$filas = \modelos\Modelo_SQL::table("usuarios")->select($clausulas );
			$datos["values"] = $filas[0];
			$datos["values"]["fecha_alta"] = \core\Conversiones::fecha_hora_mysql_a_es($datos["values"]["fecha_alta"]);
			$datos["values"]["fecha_confirmacion_alta"] = \core\Conversiones::fecha_hora_mysql_a_es($datos["values"]["fecha_confirmacion_alta"]);
		}
		
		// Enviar formulario		
		$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos, true);
		$http_body = \core\Vista_Plantilla::generar('plantilla_principal', $datos, true);
		\core\HTTP_Respuesta::enviar($http_body);
		
	}
	
	
	
	public function form_modificar_validar(array $datos = array()) {
		
		if ($validacion = ! \core\Validaciones::errores_validacion_request(\modelos\usuarios::$validaciones_update, $datos)) {
			$datos["values"]["fecha_alta"] = \core\Conversiones::fecha_hora_es_a_mysql($datos["values"]["fecha_alta"]);
			$datos["values"]["fecha_confirmacion_alta"] = \core\Conversiones::fecha_hora_es_a_mysql($datos["values"]["fecha_confirmacion_alta"]);
//			
			$validacion = \modelos\modelo_SQL::table("usuarios")->update($datos["values"]);
		}
		
		
		if ( ! $validacion ) {
			\core\Distribuidor::cargar_controlador("usuarios", "form_modificar", $datos);
		}
		else {
			$_SESSION["alerta"] = "Se ha modificado correctamente el usuario";
			//Cambiamos la url del navegador del cliente para que pida el controlador de usuarios y método index(por defecto), para evitar el comportamiento de la tecla F5 o recarga de página.
			
			\core\HTTP_Respuesta::set_header_line("Location", \core\URL::generar("usuarios"));
			\core\HTTP_Respuesta::enviar();
		}
		
	}


	public function form_borrar(array $datos = array())	{
		
		if ( \core\HTTP_Requerimiento::method() != "POST" ) {
			$datos['mensaje'] = "Petición invalida. Usa el menú y los botones de la aplicación";
			return \core\Distribuidor::cargar_controlador("mensajes", "mensaje",$datos);
				
		}
		
		$validaciones = array(
				"id" => "errores_requerido && errores_referencia:id/usuarios/id"
		);
		if ( ! $validacion = !\core\Validaciones::errores_validacion_request($validaciones, $datos)) {
			return $this->cargar_controlador("mensajes", "mensaje", array("mensaje" => "Usuario no identificado o no existente."));
		}
		// Debe recibirse por post
		if (\core\HTTP_Requerimiento::method() != "POST") {
			return $this->cargar_controlador("mensajes", "mensaje", array("mensaje" => "Utiliza los elementos del menú y botones de la aplicación."));
		}
		$clausulas = array("where" => " id = {$datos["values"]["id"]} ");
		$filas = \modelos\Modelo_SQL::tabla("usuarios")->select($clausulas);
		$datos["values"] = $filas[0];
		
		
		// Enviar formulario		
		$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos, true);
		$http_body = \core\Vista_Plantilla::generar('plantilla_principal', $datos, true);
		\core\HTTP_Respuesta::enviar($http_body);
		
	}
	
	
	
	public function form_borrar_validar(array $datos = array())	{
		
		// Debe recibirse por post
		if (\core\HTTP_Requerimiento::method() != "POST") {
			return $this->cargar_controlador("mensajes", "mensaje", array("mensaje" => "Utiliza los elementos del menú y botones de la aplicación."));
		}
		$validaciones = array(
				"id" => "errores_requerido && errores_referencia:id/usuarios/id"
		);
		if ( ! $validacion = !\core\Validaciones::errores_validacion_request($validaciones, $datos)) {
			return $this->cargar_controlador("mensajes", "mensaje", array("mensaje" => "Usuario no identificado o no existente."));
		}
		if ( ! $validacion = \modelos\Modelo_SQL::table("usuarios")->delete_row($datos["values"])) {
			return $this->cargar_controlador("mensajes", "mensaje", array("mensaje" => "NO se ha podido borrar el usuario."));
		}
		else {
			$_SESSION["alerta"] = "Se ha borrado correctamente el usuario";
			\core\HTTP_Respuesta::set_header_line("Location", \core\URL::generar("usuarios"));
			\core\HTTP_Respuesta::enviar();
		}
		
	}
	
	
	
	public function form_insertar_interno(array $datos = array()) {
		
		$datos['view_content'] = \core\Vista::generar("form_insertar", $datos, true);
		$http_body = \core\Vista_Plantilla::generar('plantilla_principal', $datos, true);
		\core\HTTP_Respuesta::enviar($http_body);
		
	}
	
	
	public function form_insertar_interno_validar(array $datos = array()) {
		
		// Añadimos la fecha confirmaciondel alta, que no ha venido por post.
		$_POST["fecha_confirmacion_alta"] = date("d/m/Y H:i:s");
		$_REQUEST["fecha_confirmacion_alta"] = date("d/m/Y H:i:s");
		
		if (self::form_insertar_validar($datos)) {
			
			$_SESSION["alerta"] = "Se ha insertado correctamente el usuario";
			\core\HTTP_Respuesta::set_header_line("Location", \core\URL::generar("usuarios"));
			\core\HTTP_Respuesta::enviar();
			
		}
		else {
			
			\core\Distribuidor::cargar_controlador("usuarios", "form_insertar_interno",$datos);
			
		}
		
	}
	
	
	public function form_cambiar_password(array $datos = array()) {
	
		if ( ! isset($datos["errores"])) {
			// Recuperar fila de la base de datos
			// Primero buscamos el valor del id que se habrá recibido
			$validaciones = array(
				"id" => "errores_requerido && errores_referencia:id/usuarios/id"
			);
			if ( ! $validacion = !\core\Validaciones::errores_validacion_request($validaciones, $datos)) {
				return $this->cargar_controlador("mensajes", "mensaje", array("mensaje" => "Usuario no identificado o no existente."));
			}
			// Debe recibirse por post
			if (\core\HTTP_Requerimiento::method() != "POST") {
				return $this->cargar_controlador("mensajes", "mensaje", array("mensaje" => "Utiliza los elementos del menú y botones de la aplicación."));
			}
			$clausulas = array(
				"columnas" => "id, login"
				,"where" => " id = {$datos["values"]["id"]} "
			);
			$filas = \modelos\Modelo_SQL::table("usuarios")->select($clausulas );
			$datos["values"] = $filas[0];
			
		}
		
		// Enviar formulario	
		
		$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos, true);
		$http_body = \core\Vista_Plantilla::generar('plantilla_principal', $datos, true);
		\core\HTTP_Respuesta::enviar($http_body);
		
	}
	
	
	
	public function form_cambiar_password_validar(array $datos = array()) {
		
		if ( $validacion = ! \core\Validaciones::errores_validacion_request(\modelos\usuarios::$validaciones_password_update, $datos)) {
			if ($datos["values"]["password"] != $datos["values"]["password2"]) {
				$validacion = false;
				$datos["errores"]["password2"] = "El valor repetido no coincide.";
			}
			else {
				unset($datos["values"]["password2"]);
				if ( $validacion = \modelos\modelo_SQL::table("usuarios")->update($datos["values"])) {
					$_SESSION["alerta"] = "Se ha modificado correctamente la contraseña.";
					\core\HTTP_Respuesta::set_header_line("Location", \core\URL::generar("usuarios"));
					\core\HTTP_Respuesta::enviar();
				}
			}
		
		}
		if ( ! $validacion) {
			\core\Distribuidor::cargar_controlador("usuarios", "form_cambiar_password", $datos);
		}
		
	}
	
	
	
	
	public function form_insertar_externo(array $datos = array()) {
		
		
		$datos['view_content'] = \core\Vista::generar("form_insertar", $datos, true);
		$http_body = \core\Vista_Plantilla::generar('plantilla_principal', $datos, true);
		\core\HTTP_Respuesta::enviar($http_body);
		
	}
	
	
	public function form_insertar_externo_validar(array $datos = array()) {
		
		// Guardamos la password sin cifrar
		$password = \core\Array_Datos::contenido("password", $_REQUEST);
		
		// Datos que no han venido en el formulario
		$_REQUEST['clave_confirmacion'] = \core\Random_String::generar(30);
		
		$validacion_catcha = true; // Iniciamos la variable.
		
		if ( \core\Configuracion::$form_insertar_externo_catcha) {
					require_once(PATH_APP.'lib/php/recaptcha-php-1.11/recaptchalib.php');
					$privatekey = "6Lem1-sSAAAAAPfnSmYe5wyruyuj1B7001AJ3CBh";
					$resp = recaptcha_check_answer ($privatekey,
												  $_SERVER["REMOTE_ADDR"],
												  $_POST["recaptcha_challenge_field"],
												  $_POST["recaptcha_response_field"]);

					if (!$resp->is_valid) {
							$validacion_catcha = false;
							$datos['errores']['validacion'] = 'Error de intruducción del captcha.';
//							\core\Distribuidor::cargar_controlador("usuarios", "form_login", $datos);
					}

		}
		
		if (self::form_insertar_validar($datos) && $validacion_catcha) {
						
			$datos["mensaje"] = "Se ha grabado correctamente el usuario. ";
			
			// Envío del email
			$url = \core\URL::generar("usuarios/confirmar_alta/{$datos['values']['id']}/".urlencode($datos['values']['clave_confirmacion']));
			
			
			$to = $datos["values"]["email"];
			$subject = "Confirmación de alta de usuario en ".TITULO;
			$message = "
<html>
<head>
  <title>Confirmación cuenta en esmvcphp</title>
</head>
<body>
	<h3>Confirmación cuenta en esmvcphp</h3>
	<p>Login: <b>{$datos['values']['login']}</b> Password: <b>$password</b></p>
	<p>Para confirmar tu registro en la aplicación ".TITULO." pulsa en el siguiente hipervínculo o sino cópialo en la ventana de direcciones de tu navegador. <a href='$url' target='_blank' >$url</a>
	</p>
	<p>Si no funciona el hipervínculo, cópialo y pégalo en la barra de direcciones de tu navegador.</p>
</body>
</html>";
			$additional_headers = "From: ".  \core\Configuracion::$email_noreply . "\r\n";
			$additional_headers .= 'MIME-Version: 1.0' . "\r\n";
			$additional_headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$additional_headers .= 'X-Mailer: PHP/' . phpversion();
			
			if ( $envio_email = mail($to, $subject, $message, $additional_headers))  {
				$datos["mensaje"] .= "Se ha enviado un correo electrónico a la cuenta de email que has aportado. Haz la confirmación pinchando en vínculo que se te ha enviado, pinchando en el enlace que se envía <a href='$url' target='_blank'>$url</a>. ";
			}
			else {
				// Si falla el envío del email
				$datos["mensaje"] .= "No se ha podido enviar el correo electrónico. Tu cuenta está pendiente de confirmar por email, pinchando en el enlace que se envía <a href='$url' target='_blank'>$url</a>";
			}
						
			$this->cargar_controlador('mensajes', 'mensaje', $datos);
			
		}
		else {
			if ( ! $validacion_catcha) {
				usset($datos["errores"]);
				$datos["errores"]["validacion"] = "Errores en el código catcha.";
			}
			\core\Distribuidor::cargar_controlador("usuarios", "form_insertar_externo",$datos);
		}
		
	}
		
	
	private function form_insertar_validar(array &$datos = array()) {
		
		$validaciones = \modelos\usuarios::$validaciones_insert;
		
		
		$validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos);
		
		if (( !isset($datos["errores"]["email"]) && !isset($datos["errores"]["email2"])) && ($datos["values"]["email"] != $datos["values"]["email2"]) ) {
				$datos["errores"]["email2"] = "La repetición del email no coincide.";
				$validacion = false;
		}
		if (( !isset($datos["errores"]["password"]) && !isset($datos["errores"]["password2"])) && ($datos["values"]["password"] != $datos["values"]["password2"]) ) {
				$datos["errores"]["password2"] = "La repetición del password no coincide.";
				$validacion = false;
		}
		
		
		if ($validacion)
		{
			unset($datos["values"]["email2"]);
			unset($datos["values"]["password2"]);
			
			$datos['values']['password'] = md5($datos['values']['password']);
			
			$datos['values']['fecha_alta'] = \core\Conversiones::fecha_hora_es_a_mysql($datos['values']['fecha_alta']);
			$datos['values']['fecha_confirmacion_alta'] = \core\Conversiones::fecha_hora_es_a_mysql($datos['values']['fecha_confirmacion_alta']);
			
			$datos['values']['id'] = \modelos\Modelo_SQL::tabla("usuarios")->insert($datos['values'], 'usuarios');
			
//			$datos['values']['id'] = \modelos\Modelo_SQL::last_insert_id();
			
		}
		
		return $validacion;
	}
	
	
	
	public function confirmar_alta(array $datos = array()) {
		
		$validaciones = array(
			'id' => 'errores_requerido && errores_numero_entero_positivo && errores_referencia:id/usuarios/id'
			,'key' => 'errores_requerido'
		);
		
		// Añadimos la fecha confirmaciondel alta, que no ha venido por post.
		$_POST["fecha_confirmacion_alta"] = date("d/m/Y H:i:s");
		$_REQUEST["fecha_confirmacion_alta"] = date("d/m/Y H:i:s");
		
		if ( ! $validacion  = ! \core\Validaciones::errores_validacion_request($validaciones, $datos)) {
			$datos['mensaje'] = 'Petición incorrecta.';
			return \core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);
			
		}
		else {
			$datos["values"]["key"] = urldecode($datos["values"]["key"]);
			$clausulas['where'] = " id = {$datos['values']['id']} and clave_confirmacion = '{$datos['values']['key']}' and fecha_confirmacion_alta is not null " ;
			$filas = \modelos\Modelo_SQL::table("usuarios")->select($clausulas);
			
			if (count($filas)) {
				// El usuario esta confirmado previamente
				$datos['mensaje'] = "Este proceso de confirmación lo realizaste en una fecha anterior: ".\core\Conversiones::fecha_hora_mysql_a_es($filas[0]['fecha_confirmacion_alta']);
				\core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);
				return;
			}
			else {
				$clausulas['where'] = " id = {$datos['values']['id']} and clave_confirmacion = '{$datos['values']['key']}' and fecha_confirmacion_alta is null " ;
				$filas = \modelos\Modelo_SQL::table("usuarios")->select($clausulas);
				
				if (count($filas) == 1) {
					// El usuario es correcto y está sin confirmar
					unset($datos['values']['key']);
					$datos['values']['fecha_confirmacion_alta'] = gmdate("Y-m-d h:i:s");
					$resultado = \modelos\Modelo_SQL::tabla("usuarios")->update($datos['values']);
					$datos['mensaje'] = "Proceso de confirmación completado fecha: {$datos['values']['fecha_confirmacion_alta']}. Ya puedes loguearte";
					$datos['url_continuar'] = \core\URL::generar_sin_idioma("usuarios/form_login");
					\core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);
						
				}
				else {
					$datos['mensaje'] = "Error indeterminado. Disculpa las molestias.";
					\core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);
				}
			}	
		}	
	} // Fin de método
	
	
	
	
	
	
	
} // Fin de la clase