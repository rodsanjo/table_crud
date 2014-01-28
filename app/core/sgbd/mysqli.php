<?php
namespace core\sgbd;


/**
 * Clase para conectar y operar con Mysql
 */
class mysqli implements \core\sgbd\SQL_interface {
	
	/**
	 * Variable usada para facilitar la ocultación del resultado de setencias de depuración.
	 * @var boolean 
	 */
	private static $depuracion = true;
	
	/**
	 * Resource o Link que guarda la conexión con el SGBD
	 * @var resource|link 
	 */
	protected static $connection;
	
	
	/**
	 * Nombre de la base de datos utilizada por defecto
	 * @var type 
	 */
	private static $db_name;
	
	
	
	/**
	 * Prefijo que utilizarán las tablas en la base de datos.
	 * @var string 
	 */
	private static $prefix_ = '';
	
	
	/**
	 * Nombre de la tabla que se usuará por defecto cuando no se especifique en los métodos donde
	 * se requiere como parámetro.
	 * @var string
	 */
	private static $table_name;
	
	
	
	
	/**
	 * Almacena la última consulta ejecutada.
	 * @var string
	 */
	protected static $query = "";
	
	/**
	 * Almacena el resultado de la ejecución de la última sentencia SQL ejecutada.
	 * 
	 * @var Resource Elemento específico del SGBD que contiene el resultado (es similar a un CURSOR)
	 */
	protected static $result;
	
	
	/**
	 * Prevista para que se puedan instanciar las clases específica que se creen para manipular cada tabla en la carpeta app\datos\nombre_tabla.php
	 * 
	 * @param string $table
	 */
	public function __construct() 	{
		
		self::connect();
		
	}
	

	
	public static function connect() {
		
		self::$connection = mysqli_connect(\core\Configuracion::$db['server'], \core\Configuracion::$db['user'], \core\Configuracion::$db['password'],\core\Configuracion::$db['db_name']);
		
		if ( ! self::$connection) {
			throw new \Exception(__METHOD__.' Mysql: Could not connect: ' );
		}
		
		self::$prefix_ = \core\Configuracion::$db['prefix_'];
		
		self::$db_name = \core\Configuracion::$db['db_name'];
		
		return self::$connection;
		
	}
	
	
	
	
	
	public static function disconnect() {
		
		return mysqli_close(self::$connection);
		
	}
	
	
	
	
	
	
	
	public static function get_prefix_tabla($table_name = null, $db_name = null) {
		
		// Si no aportan nombre de tabla tomamos el definida en la clase
		if ( ! $table_name) {
			if ( ! self::$table_name)
				throw new \Exception(__METHOD__." -> Debes especificar valor para \$table_name.");
			$table_name = self::$table_name;
		}
		
		$partes = explode(".", $table_name); // Separamos el nombre de la base de datos y el de la tablas si se aportasen.
		
		if (count($partes) == 2) {
			return self::get_prefix_tabla($partes[1], $partes[0]);
		}
		else {
			if ( ! self::$db_name)
				throw new \Exception(__METHOD__." -> Debes especificar valor para \$db_name.");
			if ($db_name) {
				return $db_name.".".self::$prefix_.$table_name;
			}
			else {
				// si no se aporta $db_name ni está incluído en $table_name, se toma la bd por defecto
				return self::get_prefix_tabla($table_name, self::$db_name);
			}	
		}

	}
	
	
	
	
	public static function set_table_name($table_name) {
		
		self::$table_name = strtolower($table_name);
		
		
	}

	public static function get_table_name() {
		
		return self::$table_name;
		
	}

	
	
	
	
	public static function execute($sql) {
		
		if (self::$depuracion) {echo __METHOD__." \$sql = $sql <br />";}
		
		self::$query = $sql; // Guardamos la consulta a ejecutar.
		
		self::$result = mysqli_query(self::$connection,$sql,MYSQLI_USE_RESULT);
		
		if ( self::$result === false) {
			if (self::$depuracion) {
				throw new \Exception(__METHOD__." Consulta= $sql <br />Error = ".  mysqli_error(self::$connection));
			}
			else {
				throw new \Exception("Error fatal");
			}
		}
		elseif (is_object(self::$result)) {
			return self::get_rows();
		}
		else { 
			if (preg_match("/insert /i", $sql))
				return mysqli_insert_id(self::$connection);
			else
				return self::$result;
		}
		
	}
	
	
	
	


	
	public static function get_rows($sql = null) {
		
		if (is_string($sql) and strlen($sql))
			return self::execute($sql);
		
		$filas = array(); // Creo un array vacío para guardar las filas (tuplas=arrays) del resultado.
		
		while ($fila = mysqli_fetch_assoc(self::$result)) {
			array_push($filas, $fila);
		}
		
		mysqli_free_result(self::$result);
		
		return $filas;
		
	}


	
	private static function columnas_set(array $fila) {
		
		if ( ! count($fila))
			throw new \Exception(__METHOD__." -> El parámetro \$fila debe contener por lo menos una  entrada.");
		
		$columnas_set = " ";
		$i = 0;
		foreach ($fila as $key => $value) {
			if ($value == '' || strlen($value) == 0 )
				$columnas_set .= "$key = default ";
			elseif (is_numeric($value))
				$columnas_set .= "$key = $value ";
			elseif (strtoupper($value) == 'DEFAULT')
				$columnas_set .= "$key = $value ";
			elseif (strtoupper($value) == 'NULL'|| $value == null )
				$columnas_set .= "$key = NULL ";
			else // suponemos que es una cadena
				$columnas_set .= "$key = '$value' ";

			if ($i < count($fila)-1)
				$columnas_set .= ", ";
			$i++;
		}
		return $columnas_set;
		
	}
	
	
	
	public static function insert_row( array &$fila , $table=null) {
		
		if (isset($fila['id']))
			throw new \Exception(__METHOD__." Error: no pude insertarse la columna id.");
		
		$columnas_set = self::columnas_set($fila);
		
		$sql = "insert into	".self::get_prefix_tabla($table)."
			set $columnas_set
		;
		";
		
		return self::execute($sql);
	}
	
	
	
	
	
	
	
	public static function insert(array &$fila, $table=null) {
	
		return self::insert_row($fila, $table);
		
	}
	
	

	
	
		
	public static function update_row(array &$fila , $table=null, $where=null) {
		
		if ( ! isset($fila['id']) && ! strlen($where))
			throw new \Exception(__METHOD__." Error: debe aportarse la id or \$where.");
		
		$columnas_set = self::columnas_set($fila);
		
		
		if (isset($where) && strlen($where))
			$where = " where $where";
		elseif (isset($fila['id']))
			$where = " where id = {$fila['id']}";
		else {
			throw new \Exception(__METHOD__." Error: debe aportarse la id or \$where.");
		}
		
		$sql = "
			update	".self::get_prefix_tabla($table)."
			set $columnas_set
			$where
		;
		";
		
		return self::execute($sql);
	}
	
	
	public static function update(array &$fila , $table=null, $where=null) {
		
		return self::update_row($fila, $table, $where);
		
	}

	
	
	
	

	
	
	
	public static function delete_row(array &$fila, $table = null, $where=null) {
		
		if ( ! isset($fila['id']))
			throw new \Exception(__METHOD__." Error: debe aportarse la id.");
		
		$sql = "
			delete
			from ".self::get_prefix_tabla($table)."
			where id = {$fila['id']}
			limit 1
			;
		";
		
		return self::execute($sql);
	}
	
	
	
	

	
	public static function delete( $clausulas = array(), $table = null, $where = null) {
		
		if ( ! isset($clausulas['id']) && ! strlen($where))
			throw new \Exception(__METHOD__." Error: debe aportarse la id or \$where.");
		
		
		if (is_string($clausulas) and is_array($table)) {
			// Vienen cambiados y los intercambiamos
			$columnas_aux = $table;
			$table = $columnas;
			$columnas = $columnas_aux;
		}
		
		
		if (isset($where) && strlen($where))
			$where = " where $where";
		elseif (isset($clausulas['where']) && strlen($clausulas['where']))
			$where = " where {$clausulas['where']}";
		elseif (isset($clausulas['id']))
			$where = " where id = {$clausulas['id']}";
		else {
			$where = "";
		}
		
		$order_by = ( isset($clausulas['order_by']) ? " order by ".$clausulas['order_by'] : "");
		
		$sql = "
			delete from ".self::get_prefix_tabla($table)."
				$where
				$order_by
			;
		";
		
		return self::execute($sql);
		
	}
	
	
	
	
	
	
	
	public static function select(
			 $clausulas = array(
				'columnas' => '',
				'where' => '',
				'group_by' => '',
				'having' => '',
				'order_by' => ''
			),
			$table = null
	) {
		
		if (is_string($clausulas) and is_array($table)) {
			// Vienen cambiados y los intercambiamos
			$aux = $table;
			$table = $clausulas;
			$clausulas = $aux;
		}
		
		$columnas = ((isset($clausulas['columnas']) and strlen($clausulas['columnas'])) ? $clausulas['columnas'] : '*');
		$where = ((isset($clausulas['where']) and strlen($clausulas['where'])) ? "where ".$clausulas['where'] : '');
		$order_by = ((isset($clausulas['order_by']) and strlen($clausulas['order_by'])) ? "order by ".$clausulas['order_by'] : '');	
		$group_by = ((isset($clausulas['group_by']) and strlen($clausulas['group_by'])) ? "group by ".$clausulas['group_by'] : '');
		$having = ((isset($clausulas['having']) and strlen($clausulas['having'])) ? "having ".$clausulas['having'] : '');
		
		$sql = "
				select $columnas
				from ".self::get_prefix_tabla($table)."
				$where
				$group_by
				$having
				$order_by
				;
		";
		
		return self::get_rows($sql);
		
	}
	
	
	
	
	
	
	
	
	public static function last_insert_id() {
		
//		$sql = " select last_insert_id() as id;";
//		$filas = self::get_rows($sql);
//		return $filas[0]['id'];
		
		return mysqli_insert_id(self::$connection);
		
	}
	
	
	
	public static function start_transacction() {
		
		$sql = "
			start transaction;
		";
		
		return execute($sql);
		
	}
	
	
	
	public static function commit_transacction() {
		
		$sql = "
			commit;
		";
		
		return execute($sql);
		
	}
	
	
	
	public static function rollback_transacction() {
		
		$sql = "
			rollback;
		";
		
		return execute($sql);
		
	}
	
	
	public static function escape_string($cadena) {
		
		//TODO Descomentar la siguiente línea y borrar la última cuando funcione mysqli
		return mysqli_real_escape_string(self::$connection, $cadena);
//		return $cadena;
	}
	
	
	public static function last_query() {
		
		return self::$query;
		
	}
	
	
} // Fin de la clase