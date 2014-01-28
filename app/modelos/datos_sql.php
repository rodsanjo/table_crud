<?php
namespace modelos;

class Datos_SQL extends \core\sgbd\bd {
	
	
	
	/**
	 * Define la tabla que se usará por defecto en los métodos de manipulación y recuperación
	 * que se vayan a utilizar a continuación para manejar modelos.
	 * El nombre de tabla aportado puede ser un nombre de una clase del namespace modelos
	 * 
	 * @param string $table_name Ejemplo: "usuarios"    "articulos"
	 * @return \core\sgbd\bd
	 */
	public static function table($table_name) {
				
		$path = PATH_APP."modelos".DS.strtolower($table_name).".php";
		
		self::set_table_name($table_name);
		
		if (is_file($path)) {
			$modelos_clase = "\\modelos\\".strtolower($table_name);
			return new $modelos_clase();
		}
		else {
			return new self;
		}
		
	}
	
	
	
	public static function tabla($table_name) {
		
		return self::table($table_name);
		
	}
	
	
}