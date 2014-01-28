<?php
namespace modelos;

class Libros_En_Array {
	
	private static $libros = array(
	/*	array(
			"titulo" => "cadena",
			"autor" => "cadena",
			"comentario" => "cadena"
		), */
		array(
			"titulo" => "Título A",
			"autor" => "Autor de A",
			"comentario" => "Comentario sobre A"
		),
		array(
			"titulo" => "Título B",
			"autor" => "Autor de B",
			"comentario" => "Comentario sobre B"
		),
	);

	public static function get_libros() {
		
		return self::$libros;

	}

}

