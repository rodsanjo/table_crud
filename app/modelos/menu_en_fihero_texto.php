<?php
/*
.../aplicacion/app/modelos/menu.txt
href;title;texto_visualizado[enter]
?menu=revista&seccion=seccion1;Sección I de la revista bla, bla, bla;Seccion I[enter]
?menu=revista&seccion=seccion2;Sección II de la revista bla, bla, bla;Seccion II[enter]
...

*/
namespace modelos;

class Menu_En_Fichero {
	
	private static $items = array();

	
	public static function get_items() {
		
		$file_path = PATH_APP."modelos/menu.txt";
		$lineas = file($file_path);
		foreach ($lineas as $numero => $linea) {
			// Dividimos la línea por los ;
			// Ponemos cada trozo de línea en un elemento del array $item
			$item = explode(";", $linea); 
			
			// Llenamos el array $items
			if ($numero != 0) {
			$items[$numero-1]["href"] = $linea[0]; 
			$items[$numero-1]["title"] = $linea[1];
			$items[$numero-1]["texto_visualizado"] = $linea[2];
			}
		}
		
		return self::$items;

	}

}
