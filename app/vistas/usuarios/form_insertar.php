<div >
	<h2>Alta de un nuevo usuario</h2>
	<?php //echo __FILE__; var_dump($datos); ?>
	<form method='post' action="<?php echo \core\URL::generar("usuarios/".\core\Distribuidor::get_metodo_invocado()."_validar"); ?>" >
		
		<input id='id'  name='id' type='hidden' value='<?php echo \core\Array_Datos::values('id', $datos); ?>' />

		Login: <input id='login' name='login' type='text' size='30'  maxlength='30' autocomplete='off' value='<?php echo \core\Array_Datos::values("login", $datos); ?>'/>
		<span class='requerido'>Requerido</span><?php echo \core\HTML_Tag::span_error('login', $datos); ?>
		<br />

		Email: <input id='email' name='email' type='text' size='100' maxlength='100' autocomplete='off' value='<?php echo \core\Array_Datos::values('email', $datos); ?>'/>
		<span class='requerido'>Requerido</span><?php echo \core\HTML_Tag::span_error('email', $datos); ?>
		<br />

		Repite email: <input id='email' name='email2' type='text' size='100' maxlength='100' autocomplete='off' value='<?php echo \core\Array_Datos::values('email2', $datos); ?>'/>
		<span class='requerido'>Requerido</span><?php echo \core\HTML_Tag::span_error('email2', $datos); ?>
		<br />

		Contraseña: <input id='password' name='password' type='password' size='30'  maxlength='30' autocomplete='off' value='<?php echo \core\Array_Datos::values('password', $datos); ?>'/>
		<span class='requerido'>Requerido</span><?php echo \core\HTML_Tag::span_error('password', $datos); ?>
		<br />

		Repite Contraseña: <input id='password2' name='password2' type='password' size='30'  maxlength='30' autocomplete='off' value='<?php echo \core\Array_Datos::values('password2', $datos); ?>'/>
		<span class='requerido'>Requerido</span><?php echo \core\HTML_Tag::span_error('password2', $datos); ?>
		<br />
		<?php
		if (\core\Distribuidor::get_metodo_invocado() == "form_insertar_externo" && \core\Configuracion::$form_insertar_externo_catcha) {
			require_once(PATH_APP.'lib/php/recaptcha-php-1.11/recaptchalib.php');
			$publickey = "6Lem1-sSAAAAAGBkb_xsqktWUMRvoYBT4z0DZL3U"; // you got this from the signup page
			echo recaptcha_get_html($publickey);
		}
		?>

		<br />
		<?php echo \core\HTML_Tag::span_error('validacion', $datos);?><br />
		<input type='submit' value='Enviar'>
		<?php if (\core\Distribuidor::get_metodo_invocado() != "form_borrar" ): ?>
			<input type='reset' value='Limpiar'>
		<?php endif; ?>
			<input type='button' value='Cancelar' onclick='window.location.assign("<?php echo \core\Datos::contenido("url_cancelar", $datos); ?>");'/>
	</form>

	
</div>