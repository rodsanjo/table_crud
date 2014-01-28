
<?php
//xdebug_start_code_coverage();


// Definiciones constantes
define("DS", DIRECTORY_SEPARATOR);

define("PATH_ROOT", __DIR__.DS ); // Finaliza en DS

define("PATH_APP", __DIR__.DS."app".DS ); // Finaliza en DS


/**
 * URL_ROOT es la url que incluye esquema, servidor y carpeta en la que está alojada la aplicación o, lo que es equivalente, el fichero index.php que se ejecuta para arrancar la aplicación.
 */
define("URL_ROOT", (isset($_SERVER['REQUEST_SCHEME'])?$_SERVER['REQUEST_SCHEME']:($_SERVER['SERVER_PORT']==80?"http":"https"))."://".$_SERVER['SERVER_NAME'].str_replace("index.php", '', $_SERVER['SCRIPT_NAME'])); // Finaliza en DS

define('TITULO', 'Aplicación MVC');


// Preparar el autocargador de clases.
// Este y el contenido en \core\Autoloader() serán los únicos require/include de toda la aplicación

require PATH_APP.'core/autoloader.php'; 
$autoloader = new \core\Autoloader();
//spl_autoload_register(array('\core\Autoloader', 'autoload'));

//require_once PATH_APP."core/aplicacion.php";
// Cargamos la aplicación
//$aplicacion = new \core\Aplicacion();
\core\Aplicacion::iniciar();
// Fin de index.php