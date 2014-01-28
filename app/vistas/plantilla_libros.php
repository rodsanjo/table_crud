<!DOCTYPE HTML>
<html>
	<head>
		<title><?php echo TITULO; ?></title>
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
		
		<link rel="stylesheet" type="text/css" href="<?php echo URL_ROOT; ?>recursos/css/revista/principal.css" />
		<style type="text/css" >
			/* Definiciones hoja de estilos interna */
		</style>

		<script type="text/javascript" src=""></script>
		
		<script type="text/javascript" >
			/* l�neas del script */
			
		</script>
		
	</head>

	<body>
	
		<!-- Contenido que se visualizar� en el navegador, organizado con la ayuda de etiquetas html -->
		<div id="inicio"></div>
		<div id="encabezado">
			<img src="<?php echo URL_ROOT; ?>recursos/imagenes/ipv_ies_palomeras.png" alt="logo" title="Logo" onclick="window.location.assign('http://www.iespalomeras.net/');"/>
			<img src="<?php echo URL_ROOT; ?>recursos/imagenes/departamento_informatica.png" alt="logo" title="Logo departamento"  onclick="window.location.assign('http://www.iespalomeras.net/index.php?option=com_wrapper&view=wrapper&Itemid=86');" />
			<h1 id="titulo">Libros leídos</h1>
			<img src="<?php echo URL_ROOT; ?>recursos/imagenes/MVC_imagen2.png" alt="MVC_imagen2.png" title="Imagen de patrón MVC"  onclick="window.location.assign('<?php echo \core\URL::generar("inicio"); ?>');" height="100px" />
		</div>
		
		<div id="div_menu" >
			<fieldset>
				<legend>Menú - Índice - Barra de navegación:</legend>
					<ul id="menu" class="menu">
						
					</ul>
			</fieldset>
		</div>

		<div id="view_content">

			<?php
				echo $datos['view_content'];
			?>
			
		</div>

	
		<div id="pie">
			<hr />
			Pie del documento.<br />
			Documento creado por Jesús María de Quevedo Tomé. <a href="mailto:jequeto@gmail.com">Contactar</a><br />
			Fecha última actualización: 04 de noviembre de 2013.
		</div>
		
		<div id='globals'>
			<?php
				print "<pre>"; print_r($GLOBALS);print "</pre>";
			?>
		</div>
		
		<?php
			if (isset($_SESSION["alerta"])) {
				echo "
					<script type='text/javascript'>
						alert('{$_SESSION["alerta"]}');
					</script>
				";
				unset($_SESSION["alerta"]);
			}
		?>
		
	</body>

</html>
