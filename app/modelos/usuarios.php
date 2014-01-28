<?php
namespace modelos;

class usuarios extends \modelos\Modelo_SQL {


	/* Rescritura de propiedades de validación */
	public static $validaciones_insert = array(
		'login' => 'errores_requerido && errores_login && errores_unicidad_insertar:login/usuarios/login',
		'email' => 'errores_requerido && errores_email && errores_unicidad_insertar:email/usuarios/email',
		'email2' => 'errores_requerido && errores_email',
		'password' => 'errores_requerido && errores_password',
		'password2' => 'errores_requerido && errores_password',
		// fecha_alta: si no se aporta se crea en mysql.
		"fecha_alta" => "errores_fecha_hora",
		// fecha_confirmacion_alta: Sí debe ser aportada en la inserción interna
		"fecha_confirmacion_alta" => "errores_fecha_hora", 
		"clave_confirmacion" => "errores_texto",
	);
	
	public static $validaciones_update = array(
		"id" => "errores_requerido && errores_numero_entero_positivo && errores_referencia:id/usuarios/id",
		'login' => 'errores_requerido && errores_login && errores_unicidad_modificar:id,login/usuarios/id,login',
		'email' => 'errores_requerido && errores_email && errores_unicidad_modificar:id,email/usuarios/id,email',
		// fecha_alta: si no se aporta se crea en mysql.
		"fecha_alta" => "errores_fecha_hora",
		// fecha_confirmacion_alta: Sí debe ser aportada en la inserción interna
		"fecha_confirmacion_alta" => "errores_fecha_hora", 
		"clave_confirmacion" => "errores_texto",
	);
	
	
	public static $validaciones_password_update = array(
		"id" => "errores_requerido && errores_numero_entero_positivo && errores_referencia:id/usuarios/id",
		'login' => 'errores_requerido && errores_login',
		'password' => 'errores_requerido && errores_password',
		'password2' => 'errores_requerido',
	);
	
	
	
	public static $validaciones_delete = array(
		"id" => "errores_requerido && errores_numero_entero_positivo && errores_referencia:id/usuarios/id"
	);
	

	
	
	/**
	 * 
	 * @param type $login
	 * @param type $contrasena
	 * @return string  Valores: ''  'existe'  'existe_autenticado' 'existe_autenticado_confirmado'
	 */
	public static function validar_usuario($login, $contrasena) {
		
		$validacion = null;
		$contrasena = md5($contrasena);
		$sql = "
			select id, login, password, fecha_confirmacion_alta
			from ".self::get_prefix_tabla('usuarios')."
			where login = '$login' 
		";
		$filas = self::recuperar_filas($sql);
		
		if (count($filas) == 1) { // Usuario y contraseña correctos
			$validacion = "existe";
			
			if ($filas[0]['password'] == $contrasena) {
				$validacion .= "_autenticado";
				if ($filas[0]['fecha_confirmacion_alta'] != '') {
					$validacion .= "_confirmado";
				}		
			}
		}
		return ($validacion);
	}
	
	
	
	
	public static function validar_usuario_login_email($datos) 	{
		
		if (isset($datos['login']) && strlen($datos['login']))
			$where = "where login = '{$datos['login']}'";
		elseif (isset($datos['email']) && strlen($datos['email']))
			$where = "where email = '{$datos['email']}'";
		else {
			throw new \Exception(__METHOD__." Error: debe aportarse ['login'] o ['email']");
		}
			
		$validacion = "";
		$contrasena = md5($datos['password']);
		$sql = "
			select id, login, password, fecha_confirmacion_alta
			from ".self::get_prefix_tabla('usuarios')."
			$where and password = '$contrasena' 
		";
		$filas = self::recuperar_filas($sql);
		
		if (count($filas) == 1) { // Usuario y contraseña correctos
			$validacion = "existe";
			
			if ($filas[0]['password'] == $contrasena) {
				$validacion .= "_autenticado";
				if ($filas[0]['fecha_confirmacion_alta'] != '') {
					$validacion .= "_confirmado";
				}		
			}
		}
		//echo __METHOD__; var_dump($validacion);
		return ($validacion);
	}
	
	
	/**
	 * Recupera los permisos de un usuario de las tablas de la base de datos, y
	 * los devuelve en forma de array.
	 * 
	 * @param string $login
	 * @return array Array con los permisos de la forma permisos[controlador][metodo]=true para cada permiso recuperado
	 */
	public static function permisos_usuario($login) {
		
		$consulta = "
			select distinct controlador , metodo
			from ".self::get_prefix_tabla('usuarios_permisos')."
			where login = '$login' 
			union distinct
			select distinct controlador , metodo
			from ".self::get_prefix_tabla('roles_permisos')."
			where rol in  (select rol from ".self::get_prefix_tabla('usuarios_roles')." where login='$login')
			order by 1, 2
			;
		";
		
		$filas = self::recuperar_filas($consulta);
		
		$permisos = array();
		
		foreach ($filas as $key => $recurso) {
			$permisos[$recurso['controlador']][$recurso['metodo']] = true;
		}
		
		return $permisos;
		
	}	
	
	
	
	
}