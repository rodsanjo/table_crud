<?php
namespace core\sgbd;

/**
 * Esta es la clase que debe usarse para acceder a cualquier objeto de la base de datos.
 * Incorpora mediante herencia (extends) todos los metodos del sgbd usado en la aplicación.
 * 
 * Esta clase debe extender la clase en la que se implementa la conexión
 * con el SGBD elegido para la aplicación.
 * 
 * En este caso es mysqli. Si hubiese sido db2 pues se hubira extendido la clase que contuviera la implementación para db2.
 * 
 * Después, esta clase \core\sgbd\bd se extenderá cuando se creen las
 * clases específicas para implementar la manipulación y recuperación de datos en cada tabla, clases que estarán en la carpeta app\datos\nombre_tabla.php
 */
class bd extends \core\sgbd\mysqli {
	
	
	/**
	 * Define la tabla que se usará por defecto en los métodos de manipulación y recuperación
	 * que se vayan a utilizar a continuación para manejar datos.
	 * El nombre de tabla aportado puede ser un nombre de una clase del namespace datos
	 * 
	 * @param string $table_name Ejemplo: "usuarios"    "articulos"
	 * @return \core\sgbd\bd
	 */
	public static function table($table_name) {
		
		self::set_table_name($table_name);
		return new \core\sgbd\bd();
		
	}
	
	
	/**
	 * Alias de get_rows()
	 * 
	 * @param type $consulta
	 * @return false|array_of_rows
	 */
	public static function recuperar_filas($consulta = null) {
		
		return self::get_rows($consulta);
		
		
	}	
	
}