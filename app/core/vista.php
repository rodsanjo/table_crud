<?php
namespace core;

class Vista extends \core\Clase_Base {
	/**
	 * Genera el código html y si buffer es true lo captura y lo devuelve por el return.
	 * 
	 * @param string $nombre Nombre del fichero que contiene la vista en la carpeta .../app/vistas/nombre_controlador/<b>nombre_vista</b>. El parámetro nombre puede ser <ul><li><b>nombre_vista</b></li> o <li><b>nombre_controlador/nombre_vista</li></ul></b>
	 * 
	 * @param array $datos
	 * @param boolean $buffer Opcional. Por defecto es true, que activa la captura del buffer
	 * @return string Código <html>
	 * @throws \Exception
	 */
	public static function generar($nombre , array &$datos = array(), $buffer = true) {
		$patron = "/^(\w{1,}(\\\|\/)){1,}\w{1,}$/i"; // carpeta1/subcarpeta/../fichero
		if (preg_match($patron, $nombre)) {
			$fichero_vista = strtolower(PATH_APP."vistas/").str_replace("\\", "/", $nombre).".php";
		}
		else {
			$fichero_vista = strtolower(PATH_APP."vistas/".\core\Aplicacion::$controlador->datos['controlador_clase']."/$nombre.php");
		}
		
		if ( ! file_exists($fichero_vista))
			throw new \Exception(__METHOD__." Error: no existe el fichero $fichero_vista .");
		
		$datos["controlador_clase"] = \core\Distribuidor::get_controlador_instanciado();
		$datos["controlador_metodo"] = \core\Distribuidor::get_metodo_invocado();
		if (! isset($datos["url_volver"]))
				$datos["url_volver"] = $_SESSION["url"]["btn_volver"];
		if (! isset($datos["url_cancelar"]))
				$datos["url_cancelar"] = $_SESSION["url"]["btn_volver"];
		
		if ($buffer) {
			ob_start ();
		}
		
		include $fichero_vista; // Script cuya salida se va a bufferear
		
		if ($buffer) {
			return(ob_get_clean());
		}
	}
			
}