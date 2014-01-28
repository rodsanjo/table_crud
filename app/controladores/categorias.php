<?php
namespace controladores;

class categorias extends \core\Controlador {

	
	
	/**
	 * Presenta una <table> con las filas de la tabla con igual nombre que la clase.
	 * @param array $datos
	 */
	public function index(array $datos=array()) {
		
		$clausulas['order_by'] = 'nombre';
		//$datos["filas"] = \modelos\categorias::select($clausulas, "categorias"); // Recupera todas las filas ordenadas
		$datos["filas"] = \modelos\Modelo_SQL::table("categorias")->select($clausulas); // Recupera todas las filas ordenadas
		
		$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos);
		$http_body = \core\Vista_Plantilla::generar('plantilla_principal', $datos);
		\core\HTTP_Respuesta::enviar($http_body);
		
	}
	
	
	public function form_insertar(array $datos=array()) {
		
		$datos["form_name"] = __FUNCTION__;
		$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos);
		$http_body = \core\Vista_Plantilla::generar('plantilla_principal', $datos);
		\core\HTTP_Respuesta::enviar($http_body);
		
	}

	public function validar_form_insertar(array $datos=array()) {
		
		
		
		$validaciones = array(
			 "nombre" =>"errores_requerido && errores_texto && errores_unicidad_insertar:nombre/categorias/nombre"
			, "descripcion" => "errores_texto"

		);
		if ( ! $validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos))
            $datos["errores"]["errores_validacion"]="Corrige los errores.";
		else {
			
			if ( ! $validacion = \modelos\Modelo_SQL::insert($datos["values"], 'categorias')) // Devuelve true o false
				$datos["errores"]["errores_validacion"]="No se han podido grabar los datos en la bd.";
		}
		if ( ! $validacion) //Devolvemos el formulario para que lo intente corregir de nuevo
			\core\Distribuidor::cargar_controlador('categorias', 'form_insertar', $datos);
		else
		{
			// Se ha grabado la modificación. Devolvemos el control al la situacion anterior a la petición del form_modificar
			//$datos = array("alerta" => "Se han grabado correctamente los detalles");
			// Definir el controlador que responderá después de la inserción
			//\core\Distribuidor::cargar_controlador('categorias', 'index', $datos);
			$_SESSION["alerta"] = "Se han grabado correctamente los detalles";
			//header("Location: ".\core\URL::generar("categorias/index"));
			\core\HTTP_Respuesta::set_header_line("location", \core\URL::generar("categorias/index"));
			\core\HTTP_Respuesta::enviar();
		}
	}

	
	
	public function form_modificar(array $datos = array()) {
		
		$datos["form_name"] = __FUNCTION__;
		
		if ( ! isset($datos["errores"])) { // Si no es un reenvío desde una validación fallida
			$validaciones=array(
				"id" => "errores_requerido && errores_numero_entero_positivo && errores_referencia:id/categorias/id"
			);
			if ( ! $validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos)) {
				$datos['mensaje'] = 'Datos erróneos para identificar el artículo a modificar';
				\core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);
				return;
			}
			else {
				$clausulas['where'] = " id = {$datos['values']['id']} ";
				if ( ! $filas = \modelos\Datos_SQL::select( $clausulas, 'categorias')) {
					$datos['mensaje'] = 'Error al recuperar la fila de la base de datos';
					\core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);
					return;
				}
				else {
					$datos['values'] = $filas[0];
					
				}
			}
		}
		
		$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos);
		$http_body = \core\Vista_Plantilla::generar('plantilla_principal', $datos);
		\core\HTTP_Respuesta::enviar($http_body);
	}

	
	
	
	
	public function validar_form_modificar(array $datos=array()) {	
		
		$validaciones=array(
			 "id" => "errores_requerido && errores_numero_entero_positivo && errores_referencia:id/categorias/id"
			, "nombre" =>"errores_requerido && errores_texto && errores_unicidad_modificar:id,nombre/categorias/nombre,id"
			, "descripcion" => "errores_texto"
			
		);
		if ( ! $validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos)) {
			
            $datos["errores"]["errores_validacion"] = "Corrige los errores.";
		}
		else {
			
			if ( ! $validacion = \modelos\Datos_SQL::update($datos["values"], 'categorias')) // Devuelve true o false
					
				$datos["errores"]["errores_validacion"]="No se han podido grabar los datos en la bd.";
				
		}
		if ( ! $validacion) //Devolvemos el formulario para que lo intente corregir de nuevo
			\core\Distribuidor::cargar_controlador('categorias', 'form_modificar', $datos);
		else {
			$datos = array("alerta" => "Se han modificado correctamente.");
			// Definir el controlador que responderá después de la inserción
			\core\Distribuidor::cargar_controlador('categorias', 'index', $datos);		
		}
		
	}

	
	
	public function form_borrar(array $datos=array()) {
		
		$datos["form_name"] = __FUNCTION__;
		$validaciones=array(
			"id" => "errores_requerido && errores_numero_entero_positivo && errores_referencia:id/categorias/id"
		);
		if ( ! $validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos)) {
			$datos['mensaje'] = 'Datos erróneos para identificar el artículo a borrar';
			$datos['url_continuar'] = \core\URL::http('?menu=categorias');
			\core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);
			return;
		}
		else {
			$clausulas['where'] = " id = {$datos['values']['id']} ";
			if ( ! $filas = \modelos\Datos_SQL::select( $clausulas, 'categorias')) {
				$datos['mensaje'] = 'Error al recuperar la fila de la base de datos';
				\core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);
				return;
			}
			else {
				$datos['values'] = $filas[0];
			}
		}
		
		$datos['view_content'] = \core\Vista::generar(__FUNCTION__, $datos);
		$http_body = \core\Vista_Plantilla::generar('plantilla_principal', $datos);
		\core\HTTP_Respuesta::enviar($http_body);
	}

	
	
	
	
	
	public function validar_form_borrar(array $datos=array()) {	
		
		$validaciones=array(
			 "id" => "errores_requerido && errores_numero_entero_positivo && errores_referencia:id/categorias/id"
		);
		if ( ! $validacion = ! \core\Validaciones::errores_validacion_request($validaciones, $datos)) {
			$datos['mensaje'] = 'Datos erróneos para identificar el artículo a borrar';
			$datos['url_continuar'] = \core\URL::http('?menu=categorias');
			\core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);
			return;
		}
		else
		{
			if ( ! $validacion = \modelos\Datos_SQL::delete($datos["values"], 'categorias')) {// Devuelve true o false
				$datos['mensaje'] = 'Error al borrar en la bd';
				$datos['url_continuar'] = \core\URL::http('?menu=categorias');
				\core\Distribuidor::cargar_controlador('mensajes', 'mensaje', $datos);
				return;
			}
			else
			{
			$datos = array("alerta" => "Se borrado correctamente.");
			\core\Distribuidor::cargar_controlador('categorias', 'index', $datos);		
			}
		}
		
	}
	
	
	public function listado_pdf(array $datos=array()) {
		
		$validaciones = array(
			"nombre" => "errores_texto"
		);
		\core\Validaciones::errores_validacion_request($validaciones, $datos);
		if (isset($datos['values']['nombre'])) 
			$select['where'] = " nombre like '%{$datos['values']['nombre']}%'";
		$select['order_by'] = 'nombre';
		$datos['filas'] = \modelos\Datos_SQL::select( $select, 'categorias');		
		
		$datos['html_para_pdf'] = \core\Vista::generar(__FUNCTION__, $datos);
		
		require_once(PATH_APP."lib/php/dompdf/dompdf_config.inc.php");

		$html =
		  '<html><body>'.
		  '<p>Put your html here, or generate it with your favourite '.
		  'templating system.</p>'.
		  '</body></html>';

		$dompdf = new \DOMPDF();
		$dompdf->load_html($datos['html_para_pdf']);
		$dompdf->render();
		$dompdf->stream("sample.pdf", array("Attachment" => 0));
		
		// \core\HTTP_Respuesta::set_mime_type('application/pdf');
		// $http_body = \core\Vista_Plantilla::generar('plantilla_principal', $datos);
		// \core\HTTP_Respuesta::enviar($datos, 'plantilla_pdf');
		
	}
	

	/**
	 * Genera una respuesta json.
	 * 
	 * @param array $datos
	 */
	public function listado_js(array $datos=array()) {
		
		$validaciones = array(
			"nombre" => "errores_texto"
		);
		\core\Validaciones::errores_validacion_request($validaciones, $datos);
		if (isset($datos['values']['nombre'])) 
			$select['where'] = " nombre like '%{$datos['values']['nombre']}%'";
		$select['order_by'] = 'nombre';
		$datos['filas'] = \modelos\Datos_SQL::select($select, 'categorias');
				
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		
		\core\HTTP_Respuesta::set_mime_type('text/json');
		$http_body = \core\Vista_Plantilla::generar('plantilla_json', $datos);
		\core\HTTP_Respuesta::enviar($http_body);
		
	}
	
	/**
	 * Genera una respuesta json con un array que contiene objetos, siendo cada objeto una fila.
	 * @param array $datos
	 */
	public function listado_js_array(array $datos=array()) {
		
		$validaciones = array(
			"nombre" => "errores_texto"
		);
		\core\Validaciones::errores_validacion_request($validaciones, $datos);
		if (isset($datos['values']['nombre'])) 
			$select['where'] = " nombre like '%{$datos['values']['nombre']}%'";
		$select['order_by'] = 'nombre';
		$datos['filas'] = \modelos\Datos_SQL::select( $select, 'categorias');
				
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		
		\core\HTTP_Respuesta::set_mime_type('text/json');
		$http_body = \core\Vista_Plantilla::generar('plantilla_json', $datos);
		\core\HTTP_Respuesta::enviar($http_body);
		
	}
	
	
	/**
	 * Genera una respuesta xml.
	 * 
	 * @param array $datos
	 */
	public function listado_xml(array $datos=array()) {
		
		$validaciones = array(
			"nombre" => "errores_texto"
		);
		\core\Validaciones::errores_validacion_request($validaciones, $datos);
		if (isset($_datos['values']['nombre'])) 
			$select['where'] = " nombre like '%{$_datos['values']['nombre']}%'";
		$select['order_by'] = 'nombre';
		$datos['filas'] = \modelos\Datos_SQL::select( $select, 'categorias');
				
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		
		\core\HTTP_Respuesta::set_mime_type('text/xml');
		$http_body = \core\Vista_Plantilla::generar('plantilla_xml', $datos);
		\core\HTTP_Respuesta::enviar($http_body);
		
	}
	
	
	
	
	/**
	 * Genera una respuesta excel.
	 * @param array $datos
	 */
	public function listado_xls(array $datos=array()) {
		
		$validaciones = array(
			"nombre" => "errores_texto"
		);
		\core\Validaciones::errores_validacion_request($validaciones, $datos);
		if (isset($_datos['values']['nombre'])) 
			$select['where'] = " nombre like '%{$_datos['values']['nombre']}%'";
		$select['order_by'] = 'nombre';
		$datos['filas'] = \modelos\Datos_SQL::select( $select, 'categorias');
				
		$datos['contenido_principal'] = \core\Vista::generar(__FUNCTION__, $datos);
		
		\core\HTTP_Respuesta::set_mime_type('application/excel');
		$http_body = \core\Vista_Plantilla::generar('plantilla_xls', $datos);
		\core\HTTP_Respuesta::enviar($http_body);
		
	}
	
	
} // Fin de la clase