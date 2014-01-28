<?php
namespace core\sgbd;

class db2 implements \core\sgbd\SQL_interface {
	
	
	
	
	
	public static function ejecutar_consulta($sql) {
		
		
		return db2_exec($sql);
		
	}
	
	
	
	
}

