<!DOCTYPE HTML>
<html>
	<head>
		<title>Error</title>
		<meta name="Description" content="Explicaci�n de la p�gina" /> 
		<meta name="Keywords" content="palabras en castellano e ingles separadas por comas" /> 
		<meta name="Generator" content="con qu� se ha hecho" /> 
	 	<meta name="Origen" content="Qu�en lo ha hecho" /> 
		<meta name="Author" content="nombre del autor" /> 
		<meta name="Locality" content="Madrid, Espa�a" /> 
		<meta name="Lang" content="es" /> 
		<meta name="Viewport" content="maximum-scale=10.0" /> 
		<meta name="revisit-after" content="1 days" /> 
		<meta name="robots" content="INDEX,FOLLOW,NOODP" /> 
		<meta http-equiv="Content-Type" content="text/html;charset=utf8" /> 
		
		<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<link href="favicon.ico" rel="icon" type="image/x-icon" /> 
		
		<link rel="stylesheet" type="text/css" href="" />
		<style type="text/css" >
			/* Definiciones hoja de estilos interna */
		</style>

		<script type="text/javascript" src=""></script>
		
		<script type="text/javascript" >
			
		</script>
		
	</head>

	<body style='border: 1px solid; width: 995px; margin-left: auto; margin-right: auto;  padding: 5px;'>
		<h1>Error en la aplicación: </h1>
		<?php 
			if (isset($datos['mensaje']))
			echo "<p>{$datos['mensaje']}</p>";
		?>
	</body>
</html>
