<div id='errores_mensaje'>
<?php
	if ( ! isset($datos['mensaje'])) {
			echo "<p>Error indefinido</p>";
	}
	else {
		echo "<p>{$datos['mensaje']}</p>";
	}

	if (isset($datos['url_continuar']))
		echo "<p><a href='{$datos['url_continuar']}'>Continuar</a></p>";

?>
</div>