<!DOCTYPE HTML>
<html>
	<head>
		<title>TABLE CRUD</title>
		<meta name="Description" content="Explicación de la página" /> 
		<meta name="Keywords" content="palabras en castellano e ingles separadas por comas" /> 
		<meta name="Generator" content="esmvcphp framewrok" /> 
	 	<meta name="Origen" content="esmvcphp framework" /> 
		<meta name="Author" content="Jesús María de Quevedo Tomé" /> 
		<meta name="Locality" content="Madrid, España" /> 
		<meta name="Lang" content="es" /> 
		<meta name="Viewport" content="maximum-scale=10.0" /> 
		<meta name="revisit-after" content="1 days" /> 
		<meta name="robots" content="INDEX,FOLLOW,NOODP" /> 
		<meta http-equiv="Content-Type" content="text/html; charset=utf8" /> 
		<meta http-equiv="Content-Language" content="es"/>
	
		<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<link href="favicon.ico" rel="icon" type="image/x-icon" /> 
		
		<link rel="stylesheet" type="text/css" href="<?php echo URL_ROOT; ?>recursos/css/principal.css" />
		<style type="text/css" >
			/* Definiciones hoja de estilos interna */
		</style>
		<?php if (isset($_GET["administrator"])): ?>
		<link rel="stylesheet" type="text/css" href="<?php echo URL_ROOT; ?>recursos/css/administrator.css" />
		<?php endif; ?>
		
		<script type='text/javascript' src="<?php echo URL_ROOT."recursos".DS."js".DS."jquery".DS."jquery-1.10.2.min.js"; ?>" ></script>
		<script type='text/javascript' src="<?php echo URL_ROOT."recursos".DS."js".DS."general.js"; ?>" ></script>
		<script type="text/javascript" src=""></script>
		
	</head>

	<body onload='onload();'>
	
		<!-- Contenido que se visualizará en el navegador, organizado con la ayuda de etiquetas html -->
		<div id="inicio"></div>
		<div id="encabezado">
			<img src="<?php echo URL_ROOT; ?>recursos/imagenes/ipv_ies_palomeras.png" alt="logo" title="Logo" onclick="window.location.assign('http://www.iespalomeras.net/');"/>
			<img src="<?php echo URL_ROOT; ?>recursos/imagenes/departamento_informatica.png" alt="logo" title="Logo departamento"  onclick="window.location.assign('http://www.iespalomeras.net/index.php?option=com_wrapper&view=wrapper&Itemid=86');" />
			<h1 id="titulo">
			<?php if (isset($_GET["administrator"])): ?>
				Administrator:
			<?php endif; ?>
			EJERCICIO TABLE CRUD</h1>
                        <h2 style="text-align: right;">Jorge Rodríguez Sanz</h2>
		</div>
		
		<div id="div_menu" >
			<fieldset>
				<legend>Menú - Índice - Barra de navegación:</legend>
					<ul id="menu" class="menu">
<!--						<li class="item"><a href="<?php //echo \core\URL::generar(); ?>" title="Inicio">Inicio</a></li>-->
						<?php echo \core\HTML_Tag::li_menu("item", array("inicio"), "Inicio"); ?>						
<!--						<li class="item"><a href="<?php //echo \core\URL::generar("categorias"); ?>" title="Categorías">Categorías</a></li>-->
						<?php echo \core\HTML_Tag::li_menu("item", array("tabla"), "Tabla"); ?>

					</ul>
			</fieldset>
		</div>

		<div id="view_content">
			
			<?php
				echo $datos['view_content'];
			?>
			
		</div>

	
		<div id="pie">
			
			Pie del documento.<br />
			Documento creado por Jorge Rodríguez Sanz. <a href="mailto:jergo23@gmail.com">Contactar</a><br />
			Fecha última actualización: 28 de enero de 2014.
		</div>
		
		<?php echo \core\HTML_Tag::post_request_form(); ?>
		
		
		<script type="text/javascript" />
			var alerta;
			function onload() {
				visualizar_alerta();
			}

			function visualizar_alerta() {
				if (alerta != undefined) {
					$("body").css("opacity","0.3").css("filter", "alpha(opacity=30)");
					alert(alerta);
					alerta = undefined;
					$("body").css("opacity","1.0").css("filter", "alpha(opacity=100)");
				}
			}

		</script>

	
<?php
if (isset($_SESSION["alerta"])) {
	echo <<<heredoc
<script type="text/javascript" />
	// alert("{$_SESSION["alerta"]}");
	var alerta = '{$_SESSION["alerta"]}';
</script>
heredoc;
	unset($_SESSION["alerta"]);
}
elseif (isset($datos["alerta"])) {
	echo <<<heredoc
<script type="text/javascript" />
	// alert("{$datos["alerta"]}");
	var alerta = '{$datos["alerta"]}';
</script>
heredoc;
}
?>	

		<div id='globals'>
			<?php                    
                            //var_dump($datos);
                            print "<pre>"; 
                             //print_r($GLOBALS);
                            print("\$_GET "); print_r($_GET);
                            print("\$_POST ");print_r($_POST);
                            print("\$_COOKIE ");print_r($_COOKIE);
                            print("\$_REQUEST ");print_r($_REQUEST);
                            print("\$_SESSION ");print_r($_SESSION);
                            print("\$_SERVER ");print_r($_SERVER);
                            print "</pre>";
                            print("xdebug_get_code_coverage() ");
                            var_dump(xdebug_get_code_coverage());
			?>
		</div>
		
		
		
	</body>

</html>