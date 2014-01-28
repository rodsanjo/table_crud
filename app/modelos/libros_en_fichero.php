<?php
/*
.../aplicacion/app/modelos/libros.txt
título;autor;comentario[enter]
Título A;Autor de A;Comentario de A[enter]
Título B;Autor de B;Comentario de B[enter]
...

*/
namespace modelos;

class Libros_En_Fichero {
	
	private static $fichero_nombre = "libros.txt";
	
	/**
	 * Almacena cada línea del fichero de texto como un array contenido dentro
	 * de este array
	 * 
	 * @var array 
	 */
	private static $libros = array(
		/* Este contiene un array por cada línea del fichero de texto. El array
		 * de cada línea tiene la siguiente estructura:
		array(
			"titulo" => "cadena",
			"autor" => "cadena",
			"comentario" => "cadena"
		), 
		*/
	);

	/**
	 * Devuelve el path absoluto al fichero en el disco del servidor que
	 * contiene los libros.
	 * 
	 * @return string
	 */
	private static function get_nombre_fichero() {
		
		return PATH_APP."modelos/".self::$fichero_nombre;
		
	}
	
	/**
	 * Lee las líneas del fichero, descarta la primera línea, y cada una
	 * de ellas las guarda como un array dentro del array self::$libros.
	 */
	private static function leer_de_fichero() {
		
		$file_path = self::get_nombre_fichero();
		
		self::$libros = array(); // Vaciamos el array por si tuviera datos de una lectura anterior.
		
		$lineas = file($file_path,FILE_IGNORE_NEW_LINES); // Lee las líneas y genera un array de índice entero con una cadena de caracteres en cada entrada del array. FILE_IGNORE_NEW_LINES es una constante entera de valor 2 que hace que no se incluya en la líneas los caracteres de fin de línea y nueva línea.
		//print "<pre>\$lineas = "; print_r($lineas);print "</pre>";
		foreach ($lineas as $numero => $linea) {
			// Dividimos la línea por los ";"
			
			// Ponemos cada trozo de línea en un elemento del array $item
			$libro = explode(";", $linea); 
			//print "<pre>"; print_r($libro);print "</pre>";
			
			// Llenamos el array self::$libros, excluimos la línea 0 que tiene el nombre de las columnas
			// $numero va a ser el id del libro
			if ($numero != 0) {
				self::$libros[$numero]["titulo"] = $libro[0]; 
				self::$libros[$numero]["autor"] = $libro[1];
				self::$libros[$numero]["comentario"] = $libro[2];
			}
		}
		//print "<pre>"; print_r(self::$libros);print "</pre>";
	}
	
	
	/**
	 * Escribe en el fichero el contenido del array self::$libros.
	 * Cada entrada del array genera una línea en el fichero de texto.
	 */
	private static function escribir_en_fichero() {
		
		$file_path = self::get_nombre_fichero();
		
		// Abrimos el fichero para escritura. Se borra su contenido anterior.
		$file = fopen($file_path, "w");
		
		// Escribimos la primera línea
		fwrite($file, "título;autor;comentario\n");
		
		//print_r(self::$libros);
		foreach (self::$libros as $libro) {
			$linea = implode(";", $libro)."\n";
			fwrite($file, $linea);
		}
		// Vaciamos el buffer del sistema de ficheros del SO
		fflush($file);
		
		fclose($file);
		
	}
	
	/**
	 * Inserta un libro al final de fichero de libros
	 * 
	 * @param array $libro array("titulo" => string, "autor" => string, "comentario" => string)
	 */
	public static function anexar_libro(array $libro) {
		
		/*
		 * Otra solución utilizando el array self::$libros
		 * 
		self::leer_de_fichero();
				
		array_push(self::$libros, array(
									"titulo" => $libro["titulo"],
									"autor" => $libro["autor"],
									"comentario" => $libro["comentario"]
									)
		);
		
		self::escribir_en_fichero();
		*/
		
		$file_path = self::get_nombre_fichero();
		$file = fopen($file_path, "a+");
		
		$linea = implode(";", $libro)."\n";
		fwrite($file, $linea);
		fflush($file);
		fclose($file);
		
	}
	
	/**
	 * Borra un libro.
	 * Primero lee los libros sobre el array self::$llibros.
	 * Segundo elimina la entrada del array correspondiente al libro a borrar cuyo id se pasa como parámetro.
	 * Tercero escribe el array en el fichero.
	 * 
	 * @param int $id
	 */
	public static function borrar_libro($id) {
		
		self::leer_de_fichero();
		
		unset(self::$libros[$id]);
		
		// print "$id<pre>"; print_r(self::$libros); print "</pre>"; exit(0);
		
		self::escribir_en_fichero();
		
	}
	
	/**
	 * Modifica un libro.
	 * Primero lee los libros sobre el array self::$llibros.
	 * Segundo modifica la entrada del array correspondiente al libro a modificar cuyos datos e pasan como parámetro.
	 * Tercero escribe el array en el fichero.
	 * @param array $libro array(id => integer, "titulo" => string, "autor" => string, "comentario" => string)
	 */
	public static function modificar_libro(array $libro) {
		//print_r($libro);
		self::leer_de_fichero();
		
		self::$libros[$libro["id"]]["titulo"] = $libro['titulo'];
		self::$libros[$libro["id"]]["autor"] = $libro['autor'];
		self::$libros[$libro["id"]]["comentario"] = $libro['comentario'];
		//print "<pre>"; print_r(self::$libros); print "</pre>"; exit(0);
		self::escribir_en_fichero();
		
	}
		
	/**
	 * get_libros() Devuelve el array self::$libros con todos los libros del fichero si el parámetro id no se aporta.
	 * get_libros(int) Devuelve un array contiendo los datos del libro cuyo id se aporta.
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function get_libros($id = null) {
		
		self::leer_de_fichero();
		
		if ($id)
			return self::$libros[$id];
		else
			return self::$libros;

	}	

} // Fin de la clase