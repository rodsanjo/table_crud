<?php
namespace core;

/**
 * Conversiones de tipos de datos y de fechas
 * Como consideración básica debe suponerse que todos los datos que se 
 * 
 * @author Jesús María de Quevedo Tomé <jequeto@gmail.com>
 * @since 20130130
 */
class Conversiones {

	/**
	 * Convierte un número o cadena con un valor numérico con punto decimal
	 * a un una cadena conteniendo el mismo valor con coma decimal.
	 * 
	 * @param string|decimal $decimal
	 * @return string
	 */
	public static function decimal_punto_a_coma($decimal = null) {
		
		if (! is_null($decimal)) {
			$decimal = (string)$decimal; // Convertimos a string por si fuera un valor que viene del modelo de datos
			$decimal = str_replace(array(",", "."), array(".", ",") , $decimal);
		}
		return $decimal;
	}

	/**
	 * Transforma un numero tipo europeo a numero en formato inglés sin separador de miles.
	 * 
	 * @param string $decimal Entrada string que provendrá de un formulario
	 * @return string
	 */
	public static function decimal_coma_a_punto($decimal = null) {
		
		if (! is_null($decimal)) {
			$decimal = (string)$decimal;
			$decimal = str_replace(array(".", ","), array("", ".") , $decimal);
		}
		return $decimal;
		
	}
	
	/**
	 * Pone el . de separación de miles. a un número que estará escrito con coma decimal,
	 * es decir, en formato europeo.
	 * 
	 * @param string $decimal Numero con , decimal y sin separador de miles
	 * @return string
	 */
	public static function poner_punto_separador_miles($decimal) {
		
		$decimal=(string)$decimal;		
		// Números positivos sin signo y negativos con signo y con parte decimal opcional separada por ,
		$patron="/^((-){0,1}\d{1,})((,){1}\d{1,}){0,1}$/";
		if ( ! preg_match($patron, $decimal))
			return;
		
		// Quitamos el signo si lo hubiera.
		if (preg_match("/^\-/",$decimal)) {
			$signo = "-";
			$decimal = substr($decimal, 1);
		}
		else {
			$signo = "";
		}
		
		$partes = explode(",",$decimal); // Separamos parte entera y parte decimal.

		$resto = strlen($partes[0])%3;
		$parte_entera = $partes[0];
		
		if ($resto) {
			$nueva_longitud = strlen($partes[0]) + 3 - $resto;
			// Rellenamos con espacios en blanco por la izquierda hasta una longitud múltiplo de 3
			$parte_entera = str_pad($partes[0], $nueva_longitud, " ", STR_PAD_LEFT);
		}
		
		// Dividimos la cadena en subcadenas de 3 caracteres
		$miles = str_split($parte_entera,3);
		// Intercalamos el . entre las subcadenas de 3 caracteres
		$parte_entera =  implode(".", $miles);
		// Añadimos la parte decimal
		$parte_decimal = (isset($partes[1]) ? ",".$partes[1] : "");
		
		// Retornamos el resultado quitando los espacios añadidos a la izaquierda y después añadiendo el signo
		return $signo.trim($parte_entera.$parte_decimal);
		
	}
	
	
	/**
	 * Convertirá un valor que vendrá del modelo de datos para pasarlo a formato de presentación
	 * en español.
	 * 
	 * @param decimal|string $decimal
	 * @return string
	 */
	public static function decimal_punto_a_coma_y_miles($decimal) {
		
		$decimal=(string)$decimal;
		$decimal = self::decimal_punto_a_coma($decimal);
		
		return self::poner_punto_separador_miles($decimal);
		
	}
	
	
	/**
	 * Convierte una fecha y hora formato de mysql a formato europeo
	 * 
	 * @param string $fecha_hora_mysql
	 * @return string
	 */
	public static function fecha_hora_mysql_a_es($fecha_hora_mysql) {
		
		$patron_fecha_hora="/^\d{4}\-\d{1,2}\-\d{1,2} \d{2}\:\d{2}\:\d{2}$/";
		if (preg_match($patron_fecha_hora, $fecha_hora_mysql)) {
			// Creamos un objeto de la clase \DateTime que nos servirá para la conversión.
			$fecha = \DateTime::createFromFormat("Y-m-d H:i:s", $fecha_hora_mysql);
			return($fecha->format("d/m/Y H:i:s"));
		}
		else
			return $fecha_hora_mysql;
		
	}

	
	/**
	 * Convierte una fecha y hora en formato europeo a formato mysql
	 * 
	 * @param string $fecha_hora_es
	 * @return string
	 */
	public static function fecha_hora_es_a_mysql($fecha_hora_es) {
		
		$patron_fecha_hora="/^\d{1,2}\/\d{1,2}\/\d{4} \d{2}\:\d{2}\:\d{2}$/";
		if (preg_match($patron_fecha_hora, $fecha_hora_es)) {
			// Creamos un objeto de la clase \DateTime que nos servirá para la conversión.
			$fecha = \DateTime::createFromFormat("d/m/Y H:i:s", $fecha_hora_es);
			return($fecha->format("Y-m-d H:i:s"));
		}
		else
			return $fecha_hora_es;
		
	}
	
	
} // Fin de la clase