<?php
namespace modelos;

class Menu_En_Array {
	
	private static $items = array(
	/*	array(
			"href" => "cadena",
			"title" => "cadena",
			"texto_visualizado" => "cadena"
		), */
		array(
			"href" => "?menu=revista&seccion=seccion1",
			"title" => "Sección 1 de la revista bla, bla, bla ",
			"texto_visualizado" => "Seccion I"
		),
		array(
			"href" => "?menu=revista&seccion=seccion2",
			"title" => "Sección 2 de la revista bla, bla, bla ",
			"texto_visualizado" => "Seccion II"
		),
	);

	public static function get_items() {
		
		return self::$items;

	}

}

