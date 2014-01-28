<?php
namespace modelos;

class Idiomas {
	
	/**
	 * Almacena los textos en distintos idiomas asociados a claves y secciones del documento o aplicación.
	 * 
	 * @var array $textos["lang"]["section"]["key"] = "text"
	 */
	private static $textos = array(
		/*
		array(
			"es" => array(
				"seccion" => array(
					"clave" => "texto",
					...
					),
		 		...
		 		),
			"en" => array(
				"seccion" => array(
					"clave" => "texto",
					...
					),
		 		...
		 		),
		),
		*/
	);

	
	/**
	 * Lee las líneas del fichero, descarta la primera línea, y cada una
	 * de ellas las guarda como un array dentro del array self::$textos.
	 */
	private static function leer_de_fichero($seccion, $lang) {
		
		$file_path = PATH_APP."modelos/idiomas/$seccion"."_"."$lang.txt";
		
		if ( ! is_file($file_path))
			throw new \Exception(__METHOD__." :  No existe el fichero $file_path");
		
		$lineas = file($file_path, FILE_IGNORE_NEW_LINES); // Lee las líneas y genera un array de índice entero con una cadena de caracteres en cada entrada del array. FILE_IGNORE_NEW_LINES es una constante entera de valor 2 que hace que no se incluya en la líneas los caracteres de fin de línea y nueva línea.
//		print "<pre>\$lineas = "; print_r($lineas);print "</pre>";
		foreach ($lineas as $numero => $linea) {
			// Dividimos la línea por los ";"
			
			// Ponemos cada trozo de línea en un elemento del array $item
			$partes = explode("|", $linea); 
			//print "<pre>"; print_r($libro);print "</pre>";
			
			// Llenamos el array self::$libros, excluimos la línea 0 que tiene el nombre de las columnas
			// $numero va a ser el id del libro
			if ($numero != 0) {
				self::$textos[$lang][$seccion][$partes[0]] = $partes[1]; 
			}
		}
//		print "<pre>"; print_r(self::$textos);print "</pre>";
	}
	
	

		
	/**
	 * get_libros() Devuelve el array self::$libros con todos los libros del fichero si el parámetro id no se aporta.
	 * get_libros(int) Devuelve un array contiendo los datos del libro cuyo id se aporta.
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function get($key, $seccion, $lang = null) {
		
		if ( ! $lang )
			$lang = \core\Configuracion::$idioma_seleccionado;
		if ( ! $lang )
			$lang = \core\Configuracion::$idioma_por_defecto;
		
		if ( ! isset(self::$textos[$lang][$seccion]))
			self::leer_de_fichero($seccion, $lang);
		
		$texto = "Error: $key not found in file $seccion\_$lang.txt";
		if (isset(self::$textos[$lang][$seccion][$key]))
			$texto = self::$textos[$lang][$seccion][$key] ? self::$textos[$lang][$seccion][$key] : $key;
		
		return $texto;

	}	

} // Fin de la clase