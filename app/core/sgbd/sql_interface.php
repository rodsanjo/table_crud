<?php
namespace core\sgbd;


/**
 * Esta clase a se debe extender por el programador para construir la clase que intercambie datos con el SGBD elegido (mysql, db2, oracle, etc).
 */
 interface SQL_interface  {
	 
	/**
	 * Propiedades estáticas a definir en la clase que se implemente a partir de este interface
	 */
	/**
	 * Variable usada para facilitar la ocultación del resultado de setencias de depuración.
	 * @var boolean 
	 */
	// private static $depuracion = false;
	
	/**
	 * Resource o Link que guarda la conexión con el SGBD
	 * @var resource|link 
	 */
	// protected static $conexion;
	
	 
	 /**
	 * Nombre de la base de datos.
	 * @var string 
	 */
	// protected static $db_name = '';
	 
	/**
	 * Prefijo que utilizarán las tablas en la base de datos.
	 * @var string 
	 */
	// protected static $prefix_ = '';
	 
	/**
	 * Nombre de la tabla que se usuará por defecto cuando no se especifique en los métodos donde
	 * se requiere como parámetro.
	 * @var string
	 */
	//protected static $table_name; 
	 
	 
	 
	/**
	 * Inicia una conexión con el servidor de bases de datos, cuyos parámetros de configuración están en \core\configuracion.php
	 * <br />
	 * También debe cargar el la propiedad self::$db_name  self::$prefix_ el valor del prefijo usado para los elementos guardados en la base de datos.
	 * 
	 * @return false|resource Devuelve false si fallo y un objeto si éxito. El retorno deverá quedar guardado en la propiedad self::$connexion
	 */
	public static function connect() ;
	
	
	/**
	 * Cierra la conexión con el SGBD.
	 * 
	 * @return boolean Devuelve false si fallo y true si éxito.
	 */
	public static function disconnect() ;
	
	
	/**
	 * Ejecuta la consulta SQL que se pasa en el parámetro $consulta.
	 * Se ejecuta sobre la conexión iniciada con el SGBD.
	 * Devuelve false si fallo, true si éxito para consultas que no devuelven filas, y array conteniendo un array por cada fila para las consultas que devuelven filas.
	 * 
	 * @param string $consulta Cadena con la consulta SQL
	 * @return fasle|array false|array()|array(0=>array('col1'=>val1, 'col2'=>val2, ...), 1=>array('col1'=>val1, 'col2'=>val2, ...), ...) Devuleve false si hubo un error de ejecución de la consulta. Devuelve array vacío si no hay resultado.
	 */
	 public static function execute($consulta) ;
	
	
	/**
	 * Ejecuta la consulta que se pase como parámetro o si no se pasa, se supone que se ha ejecutado la consulta previamente.
	 * Recupera el resultado de la ejecución de la consulta de self::$resultado y a partír de ahí obtiene un array de índice entero, conteniendo en cada entrada otro array asociativo con los datos de cada una fila de las filas recuperadas por la ejecución de la consulta.
	 * Es solo válido para consultas que devuelvan filas.
	 * 
	 * @param string $consulta Cadena con la consulta SQL
	 * @return fasle|array array()|array(0=>array('col1'=>val1, 'col2'=>val2, ...), 1=>array('col1'=>val1, 'col2'=>val2, ...), ...) Devuleve false si hubo un error de ejecución de la consulta. Devuelve array vacío si no hay resultado.
	 */
	 public static function get_rows($consulta = null) ;


	 /**
	 * Recupera filas de $tabla.
	 * Si $clausulas['where'] no se aporta o es una cadena vacía se recuperan todas las filas.
	 * <br />Si $clausulas['columnas'] no se aporta o es una cadena vacía se recuperan todas las columnas.
	 * <br />Si $tabla no se aporta se toma self::$tabla.
	 * 
	 * @param array $clausulas array(
				'columnas' => '',
				'where' => '',
				'group_by' => '',
				'having' => '',
				'order_by' => ''
			)
	 * @param string $tabla Si no se aporta se usa el valor de self::$tabla
	 * @return fasle|array array()|array(0=>array('col1'=>val1, 'col2'=>val2, ...), 1=>array('col1'=>val1, 'col2'=>val2, ...), ...) Devuleve false si hubo un error de ejecución de la consulta. Devuelve array vacío si no hay resultado. 
	 */
	public static function select(
			$clausulas = array(
				'columnas' => '',
				'where' => '',
				'group_by' => '',
				'having' => '',
				'order_by' => ''
			),
			$tabla = null
	) ;
	
	/**
	 * Devuelve el nombre de la tabla precedido del prefijo (definido en \core\configuracion.php) si lo hubiera y del nombre de la base de datos.
	 * Ejemplos de retorno: 
	 * get_prefix_tabla("table_name") => bd_name.prefix_table_name
	 * get_prefix_tabla("table_name") => bd_name_conf.prefix_table_name
	 * get_prefix_tabla("table_name", "bd_name") => bd_name.prefix_table_name
	 * get_prefix_tabla("bd_other.table_name", "bd_name") => bd_other.prefix_table_name
	 * 
	 * @param string $tabla
	 * @return string|\Exeption Ejemplo: "bd_name.prefix_table_name"
	 */
	public static function get_prefix_tabla($table_name, $db_name = null) ;

	
	/**
	 * Define el nombre de la tabla que se utilizará por defecto, dentro de la base de datos usada por defecto.
	 * No debe incluir ni el nombre de la base de datos ni el prefijo.
	 * Ejemplo: set_table_name("usuarios");
	 * 
	 * @param type $table_name
	 */
	public static function set_table_name($table_name) ;
	
	/**
	 * Inserta la fila cuyos datos están contenidos en las entradas del array $fila. Los datos que no se aporten deberán poderse sustituir por null o valores por defecto en la tabla.
	 * 
	 * @param array $fila Array asociativo con las columnas de la fila a modifiar.
	 * @param string $tabla Tabla en la que insertar
	 * @return boolean True si éxito, false si error de sintáxis.
	 */
	 public static function insert_row(array &$fila , $tabla) ;
	
	
	
	/**
	 * Inserta la fila cuyos datos están contenidos en las entradas del array $fila. Los datos que no se aporten deberán poderse sustituir por null o valores por defecto en la tabla.
	 * 
	 * @param array $fila Array asociativo con las columnas de la fila a modifiar.
	 * @param string $tabla Tabla en la que insertar
	 * @return boolean True si éxito, false si error de sintáxis.
	 */
	public static function insert(array &$fila, $tabla) ;
	
	
	/**
	 * Modifica la fila cuyo id está contenido en $fila['id'] con los valores contenidos en el resto de entradas de $fila
	 * 
	 * @param array $fila Array asociativo con las columnas de la fila a modifiar.
	 * @return boolean True si éxito, false si error de sintáxis.
	 */		
	 public static function update_row(array &$fila , $tabla) ;
	
	
	/**
	 * Borrar la fila de la tabla cuyo id es el valor de la entrada $fila['id']. Si la entrada no se aporta o no tiene valor se genera una execpción.
	 * 
	 * @param array $fila Array asociativo con la entrada id
	 * @param string $tabla Nombre de la tabla
	 * @return boolean True si éxito en ejecución, False si error de sintáxis.
	 */
	public static function delete_row(array &$fila, $tabla = null) ;
	
	
	/**
	 * Borra filas que cumplan las condiciones pasadas en la entrada where del array $clausulas.
	 * ¡Si no se pasa la entrada $clausulas['where'] o si es una cadena vacía, se borra toda la tabla!
	 * 
	 * @param array $clausulas Ejemplo de contenido: array(	
				'where' => ' col2 = val1 and col2 = val2 ',
				'order_by' => ' col1, col2, ...')
	 * @param string $tabla Nombre de la tabla. Si no aporta se usa la tabla definida en self::$tabla
	 * @return boolean True si éxito False si fallo de sintáxis.
	 */
	 public static function delete(
			$clausulas = array(	
				'where' => '',
				'order_by' => '',
			),
			$tabla = null
	) ;

	
	
	/**
	 * Devuelve el último valor generado por una cláusula auto increment.
	 * 
	 * @return integer Devuelve el último valor generado por una cláusula auto_increment
	 */
	 public static function last_insert_id();
	 
	 
	 /**
	  * Devuelve el código sql de la última consulta ejecutada.
	  * 
	  * @return string código sql de la última consulta ejecutada
	  */
	 public static function last_query();
	 
	 
	 
	 /**
	  * Inicia una transacción
	  */
	 public static function start_transacction();
	 
	 
	 /**
	  * Confirma una transacción
	  */
	 public static function commit_transacction();
	 
	 
	 /**
	  * Deshace una transacción
	  */
	 public static function rollback_transacction();
	 
	
	 
	 /**
	  * Retorna la cadena con los caracteres especiales definidos en el sgbd escapados
	  * @param type $cadena Cadena con los caracteres especiales definidos en el sgbd escapados
	  */
	 public static function escape_string($cadena);
	 
} // Fin de la clase