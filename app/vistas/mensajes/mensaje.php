<div id='mensaje'>
<?php
	if (isset($datos['mensaje'])) {
		echo "<p>{$datos['mensaje']}</p>";
	}
	elseif (isset($_SESSION["mensaje"])) {
		echo "<p>{$_SESSION["mensaje"]}</p>";
		unset($_SESSION["mensaje"]);
	}
	else {
		echo "<p>Mensaje indefinido</p>";
	}

	if ( ! isset($datos['url_continuar']) && isset($_SESSION["url_continuar"])) {
		$datos['url_continuar'] = $_SESSION["url_continuar"];
		unset($_SESSION["url_continuar"]);
	}
	if (isset($datos['url_continuar']))
		echo "<p><a href='{$datos['url_continuar']}'>Continuar</a></p>";
		
?>
</div>