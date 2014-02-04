<?php
namespace controladores;

class errores extends \core\Controlador {
	
	
	
	public function index(array $datos = array()) {
	
            $datos['mensaje'] = "Documento no encontrado.";
            
		$this->mensaje($datos);
                            
	}


	
	
	public function error_404(array $datos = array()) {
                        
            $contenido = \core\Vista_Plantilla::generar("plantilla_errores", $datos);
            \core\HTTP_Respuesta::set_http_header_status("404");
            \core\HTTP_Respuesta::enviar($contenido);
				
	}
	
	
	public function mensaje(array $datos = array()) {
		
            //"mensaje" (__FUNCTION__) es un html en la carpeta vistas/errores, me guardo su contenido en $datos['view_contetnt']
            //y después lo inserto en la plantilla guardandolo en $http_body
            //Por lo general tenemos carpetas en vistas de todos lo controladores y en las estas carpetas de vistas existen archivos php con los nombres de las funciones de la clase
		
		$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos);
		//$http_body = \core\Vista_Plantilla::generar('plantilla_errores', $datos);
                $http_body = \core\Vista_Plantilla::generar('plantilla_principal', $datos);
		\core\HTTP_Respuesta::set_http_header_status("404");
		\core\HTTP_Respuesta::enviar($http_body);
		
		
	}
	
	
} // Fin de la clase