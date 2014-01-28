<?php
namespace core;


class Random_String {
	
	/**
	 * Genera una cadena de caracteres aleatoria.
	 * Por defecto los caracteres utilizados son solo letras minúsculas y sin acentos.
	 * 
	 * @param int $length = 10 Longitud de la cadena a generar
	 * @param boolean $uc = true Si en la cadena aparecerán letras mayúsculas
	 * @param boolean $n = true Si en la cadena aparecerán números
	 * @param boolean $sc = false Si en la cadena aparecerán caracteres especiales '|@#~$%()=^*+[]{}-_'
	 * @return string Cadena de caracteres aleatoria
	 */
	public static function generar($length = 10, $uc =  true, $n = true, $sc = true) {
		$source = 'abcdefghijklmnopqrstuvwxyz';
		if($uc == 1) $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		if($n == 1) $source .= '1234567890';
		if($sc == 1) $source .= '@#~$()^*+[]{}-_';
		if($length > 0){
			$rstr = "";
			$source = str_split($source, 1);
			for ($i = 1; $i <= $length; $i++) {
				//mt_srand((double)microtime() * 1000000);
				$num = mt_rand(0, count($source)-1);
				$rstr .= $source[$num];
			}
		}
		return $rstr;
	}
	
	
}
