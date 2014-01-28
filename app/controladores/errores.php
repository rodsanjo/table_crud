<?php
namespace controladores;

class errores extends \core\Controlador {
	
	
	
	public function index(array $datos = array()) {
		
		$this->mensaje($datos);
		
	}


	
	
	public function error_404(array $datos = array()) {
		
		$contenido = \core\Vista_Plantilla::generar("plantilla_errores", $datos);
		\core\HTTP_Respuesta::set_http_header_status("404");
		\core\HTTP_Respuesta::enviar($contenido);
				
	}
	
	
	public function mensaje(array $datos = array()) {
		
		$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos);
		$http_body = \core\Vista_Plantilla::generar('plantilla_errores', $datos);
		\core\HTTP_Respuesta::set_http_header_status("404");
		\core\HTTP_Respuesta::enviar($http_body);
		
		
	}
	
	
} // Fin de la clase