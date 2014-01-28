<?php
namespace core;

/**
 * Esta clase la han de heredar todos los controladores.
 */
class Controlador extends \core\Clase_Base {	
	
	public function index(array $datos = array()) {
		
		echo __METHOD__." => Respuesta por defecto. Método index sin redefinir en el controlador {$this->datos['controlador_clase']}";
		//print_r($datos);
		
	}
	
	
	/**
	 * Genera un script para incorporar js desde ficheros externos.
	 * <br />Hay que aportar el path y el nombre del fichero.
	 * <br />Ejemplo de invocación: this->js_script_tag('recursos/js/fichero.js');
	 * @param type $path_y_fichero
	 * @return type
	 */
	public function js_script_tag($path_y_fichero, array &$datos = null) {
		
		if ( ! preg_match('/recursos\/js\//i', $path_y_fichero))
			$path_y_fichero = "recursos/js/$path_y_fichero";
		if ( ! preg_match('/\.js$/i', $path_y_fichero))
			$path_y_fichero .= '.js';
		$path_js_fichero = PATH_ROOT.$path_y_fichero;
		if ( !is_file($path_js_fichero)) {
			throw new \Exception(__METHOD__." => El fichero $path_js_fichero no existe en el disco.");
		}
		$url_js_fichero = \core\URL::http('')."$path_y_fichero";
		$js_script_tag = "<script type='text/javascript' src='$url_js_fichero'></script>\n";
		if (is_array($datos)) 
			$datos['js'][$js_script_tag] = true;
		else
		return $js_script_tag;
		
	}
	
	
	
	public function js_script_vistas_tag($nombre_fichero, array &$datos = null) {
		
		if ( ! preg_match('/\.js$/i', $nombre_fichero))
			$nombre_fichero .= '.js';
		$path_js_fichero = PATH_ROOT."recursos/js/".\core\Distribuidor::get_controlador_instanciado()."/$nombre_fichero";
		if ( !is_file($path_js_fichero)) {
			throw new \Exception(__METHOD__." => El fichero $nombre_fichero no existe como $path_js_fichero en el disco.");
		}
		
		$url_js_fichero = \core\URL::http('')."recursos/js/".\core\Distribuidor::get_controlador_instanciado()."/$nombre_fichero";
		$js_script_tag = "<script type='text/javascript' src='$url_js_fichero'></script>\n";
		if (is_array($datos)) 
			$datos['js'][$js_script_tag] = true;
		else
		return $js_script_tag;
		
	}
	
	
	
	
	public function css_link_tag($path_y_fichero, array &$datos = null) {
		
		if ( ! preg_match('/recursos\/css\//i', $path_y_fichero))
			$path_y_fichero = "recursos/css/$path_y_fichero";
		if ( ! preg_match('/\.css$/i', $path_y_fichero))
			$path_y_fichero .= '.css';
		$path_fichero = PATH_ROOT.$path_y_fichero;
		
		if ( ! is_file($path_fichero)) {
			throw new \Exception(__METHOD__." => El fichero $path_fichero no existe en el disco.");
		}
		
		$url_css_fichero = \core\URL::http('')."$nombre_fichero";
		$css_link_tag = "<link rel='stylesheet' type='text/css' href='$url_css_fichero' />\n";
		if (is_array($datos)) 
			$datos['css'][$css_link_tag] = true;
		else
			return $css_link_tag;
		
	}
	
	
	public function css_link_vistas_tag($nombre_fichero, array &$datos = null) {
		
		if ( ! preg_match('/\.css$/i', $nombre_fichero))
			$nombre_fichero .= '.css';
		
		$path_fichero = PATH_ROOT."recursos/css/".\core\Distribuidor::get_controlador_instanciado()."/$nombre_fichero";
		if ( !is_file($path_fichero)) {
			throw new \Exception(__METHOD__." => El fichero $nombre_fichero no existe como $path_fichero en el disco.");
		}
		
		$url_css_fichero = \core\URL::http('')."recursos/js/".\core\Aplicacion::$controlador->datos['controlador_clase']."/$nombre_fichero";
		$css_link_tag = "<link rel='stylesheet' type='text/css' href='$url_css_fichero' />\n";
		if (is_array($datos)) 
			$datos['css'][$css_link_tag] = true;
		else
			return $css_link_tag;
		
	}
	
	
	
	
	/**
	 * Genera la vista y la devuelve como una cadena de caracteres.
	 * @param string $nombre Nombre del fichero que contiene la vista en app\vistas\nombre_clase\nombre_vista.php
	 * @param array $datos
	 * @param boolean $buffer
	 * @return string
	 */
	public function vista_generar($nombre , array $datos = array(), $buffer = true) {
	
		// Añadimos los datos del objeto controlador que contienen el nombre de la clase y el nombre del método que se está ejecutando
		$datos = array_merge($datos, $this->datos);
		return \core\Vista::generar($nombre, $datos, $buffer);
	
	}
	
	
	/**
	 * Genera la vista de la plantilla y la devuelve como una cadena de caracteres.
	 * @param string $nombre Nombre del fichero que contiene la plantilla en app\vistas\nombre_plantilla.php
	 * @param array $datos
	 * @param boolean $buffer
	 * @return string
	 */
	public function vista_plantilla_generar($nombre , array $datos = array(), $buffer = true) {
	
		// Añadimos los datos del objeto controlador que contienen el nombre de la clase y el nombre del método que se está ejecutando
		$datos = array_merge($datos, $this->datos);
		return \core\Vista_Plantilla::generar($nombre, $datos, $buffer);
	
	}
	
	public function respuesta_enviar(array $datos = array(), $plantilla = null) {
		
		if ( ! $plantilla ) {
			$plantilla = \core\Configuracion::$plantilla_por_defecto;
		}
		\core\HTTP_Respuesta::enviar(\core\Vista_Plantilla::generar($plantilla, $datos));
		
	}
	
	public function get_nombre() {
		
		return $this->datos['controlador_clase'];
		
	}
	
	public function get_metodo() {
		
		return $this->datos['controlador_metodo'];
		
	}
	
	
	
} // Fin de la clase