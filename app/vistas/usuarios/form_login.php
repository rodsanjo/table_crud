<div >
	<h2>Identificación de usuario</h2>
	<form name='form_login' method='post' action='<?php echo \core\URL::generar("usuarios/form_login_validar"); ?>'>

		<?php echo \core\HTML_Tag::form_registrar("form_login", "post"); ?>

		Login: <input type='text' id='login' name='login' maxsize='50' value='<?php echo \core\Datos::values('login', $datos) ?>'/><span class='requerido'>Requerido</span><?php echo \core\HTML_Tag::span_error('login', $datos); ?><br />
		Password: <input type='password' id='password' name='password' maxsize='50' value='<?php echo \core\Datos::values('password', $datos) ?>'/><span class='requerido'>Requerido</span><?php echo \core\HTML_Tag::span_error('password', $datos); ?><br />
		<br />	

		<?php echo \core\HTML_Tag::span_error('validacion', $datos);?><br />

		<?php
		if (\core\Configuracion::$form_login_catcha) {
			require_once(PATH_APP.'lib/php/recaptcha-php-1.11/recaptchalib.php');
			$publickey = "6Lem1-sSAAAAAGBkb_xsqktWUMRvoYBT4z0DZL3U"; // you got this from the signup page
			echo recaptcha_get_html($publickey);
		}
		?>

		<input type='submit' value='enviar' />
		<input type='button' value='cancelar' onclick='window.location.assign("<?php echo \core\URL::generar("inicio"); ?>");'/>
	</form>
</div>