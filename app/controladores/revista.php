<?php
namespace controladores;


class revista extends \core\Controlador {
	
	public function index(array $datos = array()) {
		
		$seccion = \core\HTTP_Requerimiento::get('seccion');
				
		if ( ! $seccion ) {
			$seccion = "seccion1";
		}
			
		$fichero_abs_path = PATH_APP."vistas/revista/$seccion.php";
		
		if (! is_file($fichero_abs_path)) {
			$seccion = "error";
		}
			
		$datos['view_content'] = \core\Vista::generar($seccion, $datos, true);
		$http_body = \core\Vista_Plantilla::generar('plantilla_revista', $datos, true);
		\core\HTTP_Respuesta::enviar($http_body);
		
	}
	
} // Fin de la clase