<?php
namespace controladores;

class inicio extends \core\Controlador {
	
	public function index(array $datos = array()) {

		$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos, true);
		$http_body = \core\Vista_Plantilla::generar('plantilla_principal', $datos);
		\core\HTTP_Respuesta::enviar($http_body);
		
	}
	
	
	
	public function internacional(array $datos = array()) {
		
//		echo \core\Idioma::text("title", "plantilla_internacional", "en"); 
//		echo \core\Idioma::text("no existe", "plantilla_internacional", "en"); 
//		echo \core\Idioma::text("title", "plantilla_internacional", "es"); 
//		echo \core\URL::generar("", false); exit(0);
		$http_body = \core\Vista_Plantilla::generar('plantilla_internacional');
		\core\HTTP_Respuesta::enviar($http_body);
		
	}
	
	
} // Fin de la clase