<?php
namespace core;

/**
 * Clase con métodos útiles para extraer información del arrays.
 * Todos los métodos devuelven el valor asociado a la entrada del array
 * valor o null en caso de que no exista la entrada.
 */
class Datos {
	
	
	/**
	 * Extrae el contenido del array $datos['values']
	 * 
	 * @param string $indice
	 * @param array $datos
	 * @return mixed
	 */
	public static function values($indice , array $datos ) {
		
		return ( (array_key_exists('values', $datos) and array_key_exists($indice, $datos['values'])) ? $datos['values'][$indice] : null);		
		
	}
	
	
	
	public static function errores($indice , array $datos ) {
		
		return ( (array_key_exists('errores', $datos) and array_key_exists($indice, $datos['errores'])) ? $datos['errores'][$indice] : null);		
		
	}
	
	/**
	 * Devuelve el contenido de una entrada del array 
	 * 
	 * que se pasa por parámetro.
	 * Si la entrada no existe devuelve null.
	 * 
	 * @param string|integer $indice
	 * @param array $array
	 * @return mixed
	 */
	public static function contenido($indice, $datos) {
		if ( ! is_string($indice) && ! is_integer($indice))
			throw new \Exception(__METHOD__." Error: parámetro \$indice=$indice debe ser entero o string");
		elseif ( !is_array($datos))
			throw new \Exception(__METHOD__." Error: parámetro \$datos debe ser un array");
		
		return (array_key_exists($indice, $datos) ? $datos[$indice] : null);
	}
	
	
	
	/**
	 * Retorna true si el conetenido está en alguna entrada del array que se pasa por parámetro.
	 * 
	 * @param scalar $contenido
	 * @param array $array
	 * @return boolean
	 */
	public static function contiene($contenido, array $array) {
		$busqueda = false;
		foreach ($array as $value) {
			if ($value === $contenido) {
				$busqueda = true;
			}
			elseif (is_array($value)) {
				$busqueda = self::contiene($contenido, $value);
			}
			if ($busqueda) {
				break;
			}
		}
		
		return $busqueda;
	}
	
}
