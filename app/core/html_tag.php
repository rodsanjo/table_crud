<?php
namespace core;



/**
 * Esta clase genera etiquetas html.
 * Cada etiqueta se definine en un método específico.
 *
 * @author Jesús María de Quevedo Tomé <jequeto@gmail.com>
 * @since 20130130
 */
class HTML_Tag extends \core\Clase_Base {

	protected static $depuracion = false;


	private static $use_post_request_form = false;

	
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Genera el script html para una etiqueta <span>
	 * 
	 * @param string $input_id
	 * @param array $datos
	 * @return string Script html con una etiqueta <span>
	 */
	public static function span_error($input_id, array $datos) {
		
		return "<span id='error_$input_id' class='input_error' style='color: red;'>".(isset($datos['errores'][$input_id]) ? $datos['errores'][$input_id]:'')."</span>"; 
			
	}

	
	
	/**
	 * Registra el envío de un formulario al cliente web.
	 * Genera un id aleatorio para el formulario que se guardará en el array $_SESSION para luego poder ser validado cuando se reciba el
	 * formulario desde el cliente.
	 * Se emplea para evitar el tratamiento repetido de un mismo formulario. Evita ataques de hackers o reenvíos (F5).
	 * 
	 * @param string $name = null
	 * @return string
	 */
	public static function form_registrar($name = null, $method = "post") {
		
		$form_id  = rand(1000,9999); 
		$_SESSION["formularios"]["form_id"][$form_id ] = $name ;
		$_SESSION["formularios"]["method"][$form_id ] = $method;
		
		return ("<input type='hidden' name='form_id' value='$form_id ' />\n");
		
	}
	
	
	
	public static function form_autenticar($form_name = null, $method = null) {
		
		$resultado = false;
		if ( isset($_REQUEST["form_id"])) {
			$form_id = (integer)$_REQUEST["form_id"]; // Se convierte a integer porque por HTTP ha venido como string.
			if (isset($_SESSION["formularios"]["form_id"][$form_id])) {
				if (is_string($form_name) && strlen($form_name)) {
					$resultado = ($_SESSION["formularios"]["form_id"][$form_id] == $form_name);
					if (is_string($method) && strlen($method)) {
						$resultado = ($resultado && ($_SESSION["formularios"]["method"][$form_id] == $method) && (strtoupper($_SERVER["REQUEST_METHOD"]) == strtoupper($method)));
					}
				}
				// Anulo la entrada del form_id recibido en el array $_SESSION["formularios"]
				unset($_SESSION["formularios"]["form_id"][$form_id]);
				unset($_SESSION["formularios"]["method"][$form_id]);
			}
		}
		
		return $resultado;
		
	}
	
	
	
	/**
	 * Genera un elemento &lt;li&gt; para ser usado en un ménu si el usuario tiene permisos para ejecutar el controlador y métodos indicados en la query string
	 * 
	 * @param string $clases Ejemplo: "clase1 clase2 clase3 ..."
	 * @param array $query_string Ejemplo: array("controlador", "metodo") array("articulos", "index")
	 * @param string $texto Ejemplo: "Artículos"
	 * @param array $otros_argumentos Ejemplo: array("target"=>"_blank", ...)
	 * @return string
	 */
	public static function li_menu
	(
			$clases,
			array $query_string = array("inicio", "index"),
			$texto,
			array $otros_argumentos = array()
	) {
		
		$link = "";
		$controlador = isset($query_string[0]) ? $query_string[0] : "inicio";
		$metodo = isset($query_string[1]) ? $query_string[1] : "index";
		
		if ( ! \core\Usuario::tiene_permiso($controlador, $metodo)) {
			return $link;
		};
			
		$argumentos ="";
		foreach ($otros_argumentos as $key => $value) {
			$argumentos .= " $key ='$value' ";
		}
		$uri = \core\URL::http_generar($query_string);
		$link = "<li class='$clases' $argumentos>".self::a_boton("", $query_string, $texto)."</li>";
		return $link;
		
	}
	
	
	
	
	/**
	 * Genera un link si el usuario tiene permisos para ejecutar el controlador y métodos 
	 * indicados en la query string
	 * 
	 * @param string $clases Ejemplo: "clase1 clase2 clase3 ..."
	 * @param array $query_string Ejemplo: array("controlador", "metodo", id) array("usuarios", "form_modificar", 5)
	 * @param string $texto Ejemplo: "Modificar"
	 * @param array $otros_argumentos Ejemplo: array("target"=>"_blank", ...)
	 * @return string
	 */
	public static function a_boton(
			$clases,
			array $query_string = array("inicio", "index"),
			$texto,
			array $otros_argumentos = array()
	) {
		
		$link = "";
		$controlador = isset($query_string[0]) ? $query_string[0] : "inicio";
		$metodo = isset($query_string[1]) ? $query_string[1] : "index";
		
		if ( ! \core\Usuario::tiene_permiso($controlador, $metodo)) {
			return $link;
		};
		
		$argumentos ="";
		foreach ($otros_argumentos as $key => $value) {
			$argumentos .= " $key ='$value' ";
		}
		$uri = \core\URL::http_generar($query_string);
		$link = "<a class='$clases' href='$uri' $argumentos >$texto</a>";
		return $link;
		
	}
	
	
	
	/**
	 * Método pensados para evitar que se envíe por get el id de algunas peticiones como
	 * form_modificar o form_borrar. 
	 * La query string debe tener máximo tres argumentos, siendo el tercero el valor para el id
	 * 
	 * @param string $clases Ejemplo: "clase1 clase2 clase3 ..."
	 * @param array $query_string Ejemplo: array("controlador", "metodo", id) array("usuarios", "form_modificar", 5)
	 * @param string $texto Ejemplo: "Modificar"
	 * @param array $otros_argumentos Ejemplo: array("target"=>"_blank", ...)
	 * @return string
	 */
	public static function a_boton_onclick(
			$clases,
			array $query_string = array("inicio", "index"),
			$texto,
			array $otros_argumentos = array()
	) {
		
		self::$use_post_request_form = true;
		
		$link = "";
		$controlador = isset($query_string[0]) ? $query_string[0] : "inicio";
		$metodo = isset($query_string[1]) ? $query_string[1] : "index";
		
		if ( ! \core\Usuario::tiene_permiso($controlador, $metodo)) {
			return $link;
		};
		
		if (isset($query_string[2])) {
			$id = $query_string[2];
			unset($query_string[2]);
		}
		else {
			$id = 0;
		}
		$argumentos ="";
		foreach ($otros_argumentos as $key => $value) {
			$argumentos .= " $key ='$value' ";
		}
		$uri = \core\URL::http_generar($query_string);
		$link = "<a class='$clases' onclick='submit_post_request_form(\"$uri\", \"$id\");' $argumentos >$texto</a>";
		return $link;
		
	}
	
	
	/**
	 * Se utiliza en la plantilla para incluir un formulario vacío que se utilizará
	 * para enviar requerimientos por post.
	 * 
	 * @return string Con un formulario vacío que se rellena con una fución javascript
	 */
	public static function post_request_form() {

		if ( ! self::$use_post_request_form)
			return "";
	?>	
		<div>	
			<!-- Form que se utiliza para enviar post los requerimientos y que el id no se vea
				en la barra de direcciones -->
			<form id="post_request_form"
				action=""  
				method="post"
				style="display: none;"
			>
				<input name="id" id="id" type="hidden" />

			</form>

			<script type="text/javascript" />
				/**
				* Envía una petición por post para ocultar parámetros a usuario y evitar que juegue con
				* ellos modificando la URI mostrada .
				* 
				* @param string action
				* @param strin id
				* @returns {undefined}
				*/
				function submit_post_request_form(action, id) {
					$("#post_request_form").attr("action",action);
					$("#id").attr("value", id);
					$("#post_request_form").submit();
					// alert("post_request_form.submit("+$("#post_request_form").attr("action")+" , "+$("#id").val()+")");
				}
			</script>
		</div>
	<?php
	
	}
	

} // Fin de la clase