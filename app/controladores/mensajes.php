<?php
namespace controladores;

class mensajes extends \core\Controlador {
	
	
	public function index(array $datos = array()) {
		
		return \core\Distribuidor::cargar_controlador("mensajes", "mensaje", $datos);
		
	}
	
	
	
	/*
	 * Envía al cliente un mensaje cuyo texto se recuperará desde la variable $_SESSON["mensaje"]
	 * Este método responde a una requerimiento directo desde el cliente.
	 */
	public function mensaje(array $datos = array()) {
		
		if ( ! isset($_SESSION["mensaje"]) && isset($datos["mensaje"]) ) {
			$_SESSION["mensaje"] = $datos["mensaje"];
			if ( isset($datos['url_continuar']) &&  ! isset($_SESSION["url_continuar"])) {
				$_SESSION["url_continuar"] = $datos['url_continuar'];
			}
			
			\core\HTTP_Respuesta::set_header_line("location", \core\URL::http_generar("mensajes/mensaje"));
			\core\HTTP_Respuesta::enviar();
		}
		else {		
			$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos);
			$http_body = \core\Vista_Plantilla::generar('plantilla_principal', $datos);
			\core\HTTP_Respuesta::enviar($http_body);
		}
		
	}
	
	
	public function desconexion(array $datos = array()) {
		
		if ( ! isset($_SESSION["mensaje"]) && isset($datos["mensaje"]) ) {
			$_SESSION["mensaje"] = $datos["mensaje"];
			if ( isset($datos['url_continuar']) &&  ! isset($_SESSION["url_continuar"])) {
				$_SESSION["url_continuar"] = $datos['url_continuar'];
			}
		}
		{		
			$datos['view_content'] = \core\Vista::generar("mensaje", $datos);
			$http_body = \core\Vista_Plantilla::generar('plantilla_principal', $datos);
			\core\HTTP_Respuesta::enviar($http_body);
		}
		
	}
	
	
	
} // Fin de la clase