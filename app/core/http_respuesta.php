<?php
namespace core;


/* Referencias: 
 * http://www.tutorialspoint.com/http/http_messages.htm
 * http://es1.php.net/manual/es/function.header.php
 */

/**
 * Se encarga de enviar la respuesta que se genera en la aplicación.
 * La respuesta será por defecto del tipo MIME 'text/html';
 */
class HTTP_Respuesta extends \core\Clase_Base {
	
	private static $http_header_protocol = "HTTP 1.1";
	private static $http_header_status = "200";
	
	
	private static $http_header_lines = array(
		"Content-Type" => "text/html",
	);
	
	private static $http_body_content = "";
	
	
	// Array que almacenará las cookies que se enviarán al cliente
	private static $cookies = array(); 
	
	
	
	public static function set_header_line($key, $value) {
		
		self::$http_header_lines[$key] = $value;
		
	}


	/**
	 * Cambia el tipo MIME de la respuesta HTTP que define el contenido de la línea Content-Type del HEADER.
	 * Por defeto las respuestas se envía con el tipo 'text/plain'.
	 * 
	 * @param string $tipo_mime
	 * @throws \Exception
	 */
	public static function set_mime_type($tipo_mime) {
		
		if (\core\Array_Datos::contiene($tipo_mime, \core\Configuracion::$tipos_mime_reconocidos)) {
			self::set_header_line('Content-Type', $tipo_mime);
			if ($tipo_mime == 'application/excel') {
				self::set_header_line('Content-Disposition', "attachment;filename=libro.xls");
			}
		}
		else {
			throw new \Exception(__METHOD__." Error: tipo mime <b>$tipo_mime</b> no válido, solo se admite uno de los siguientes:".  implode(' , ', \core\Configuracion::$tipos_mime_reconocidos));
		}
		
	}
	
	
	
	/**
	 * Envía el header y el cuerpo de la respuesta HTTP al cliente web.
	 * Si el el parámetro $http_body_conten se deja vacío solo se enviará el encabezado.
	 * 
	 * @param string|binary $http_body_content Contenido del cuerpo de la respuesta HTTP
	 */
	public static function enviar($http_body_content = null) {
		
		if ($http_body_content) {
			self::set_http_body($http_body_content);
		}
		
		// Añadimos a la cabecera la longitud del cuerpo, si es mayor que cero
		if (strlen(self::$http_body_content)) {
			self::set_header_line("Content-Length", (string) strlen(self::$http_body_content) );
		}
		
		// Enviar HEADER
		self::send_header();
		
		// Enviar COOKIES
		self::cookies_send();
		
		// Enviar BODY
		self::send_body();
		
	}
	
	
	private static function send_header() {
		
		$fichero = ''; // Almacena información en caso de header enviado
		$linea = ''; // Almacena información en caso de header enviado
		
		if ( ! headers_sent($fichero, $linea)) { // Enviamos en encabezado HTTP
			if ( ! isset(self::$http_header_lines['Content-Type']) ) {
				self::$http_header_lines['Content-Type'] = \core\Configuracion::$tipo_mime_por_defecto;
			}
//			http_response_code(self::$http_header_status); // Enviamos el código de la respuesta. Atención solo válidopara php >=5.4.1
			header(" ", true, (integer) self::$http_header_status); // Equivalente a la línea anterior y es válido desd php >=5.3
			
			foreach (self::$http_header_lines as $key => $value) {
				// Enviamos las líneas del header
				header("$key: $value");
			}
		}
		else { // El encabezado HTTP ya se ha enviado
			echo __METHOD__." Warning: El encabezado php se originó en el fichero <b>$fichero</b> , en la línea <b>$linea</b>.<br />";
		}
		
	}
	
	
	public static function set_http_body($content) {
		
		self::$http_body_content = $content;
		
	}
	
	
	private static function send_body() {
		
		echo self::$http_body_content;
		
	}

	
	public static function set_http_header_status($http_header_status) {
		
		self::$http_header_status = $http_header_status;
		
	}
	
	/**
	 * Recoge datos para preparar la cookie que se enviará con el método 
	 * \core\HTTP_Respuesta::enviar()
	 * 
	 * @param string $name
	 * @param string $value
	 * @param int $expire
	 * @param string $path
	 * @param string $domain
	 * @param string $secure
	 * @param string $httponly
	 */
	public static function setcookie($name, $value = null, $expire = 0 , $path = null , $domain = null , $secure = false , $httponly = false ) {
		
		$cookie = array(
			"name" => $name,
			"value" => $value,
			"expire" => $expire,
			"path" => $path,
			"domain" => $domain,
			"secure" => $secure,
			"httponly" => $httponly
		);
		
		// Añado la cookie al array de cookies
		array_push(self::$cookies, $cookie);
		
	}
	
	
	private static function cookies_send() {
		
		foreach (self::$cookies as $cookie) {
			setcookie ( $cookie["name"] , $cookie["value"] , $cookie["expire"] , $cookie["path"], $cookie["domain"], $cookie["secure"], $cookie["httponly"] );
		}
		
	}
	
	
	
}