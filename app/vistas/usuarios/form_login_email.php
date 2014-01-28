<h1>Identificación de usuario</h1>
<p>Introduce tu login o tu email para identificarte.</p>
<form method='post' action="?menu=usuarios&submenu=form_login_email_validar" >
	Login: <input id='login' name='login' type='text' size='30' maxlength="30" value='<?php echo (\core\Array_Datos::values('login', $datos) ? \core\Array_Datos::values('login', $datos) : ''); ?>' onkeyup="limpiar_input('email');" onfocus="limpiar_inicial(this);"/>
	<?php echo \core\HTML_Tag::span_error('login', $datos); ?>
	<br />
	Email: <input id='email' name='email' type='text' size='100' maxlength="30" value='<?php echo (\core\Array_Datos::values('email', $datos) ? \core\Array_Datos::values('email', $datos) : ''); ?>' onkeyup="limpiar_input('login');"  onfocus="limpiar_inicial(this);"/>
	<?php echo \core\HTML_Tag::span_error('email', $datos); ?>
	<br />
	Contraseña: <input id='password' name='password' type='password' size='30'  value='<?php echo \core\Array_Datos::values('password', $datos); ?>' />
	<?php echo \core\HTML_Tag::span_error('password', $datos); ?>
	<br />
	<?php
		if (isset($datos['errores']['validacion']))
			echo "<span style='color: red;'>{$datos['errores']['validacion']}</span><br />";
	?>
	<input type='submit' value='enviar'>
	<input type='reset' value='limpiar'>
</form>
<script type="text/javascript" >
	
</script>
