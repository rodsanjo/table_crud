<div >
	<h2>Confirmación de baja de usuario</h2>
	
	<form method='post' action="<?php echo \core\URL::generar("usuarios/".\core\Distribuidor::get_metodo_invocado()."_validar"); ?>" onsubmit='return confirm("Esta acción no se puede deshacer. ¿Continuar?");'>

		<input id='id'  name='id' type='hidden' value='<?php echo \core\Array_Datos::values('id', $datos); ?>' />

		Login: <input id='login' name='login' type='text' size='30'  maxlength='30' autocomplete='off' value='<?php echo \core\Array_Datos::values("login", $datos); ?>'/>
		<span class='requerido'>Requerido</span><?php echo \core\HTML_Tag::span_error('login', $datos); ?>
		<br />

		Email: <input id='email' name='email' type='text' size='100' maxlength='100' autocomplete='off' value='<?php echo \core\Array_Datos::values('email', $datos); ?>'/>
		<span class='requerido'>Requerido</span><?php echo \core\HTML_Tag::span_error('email', $datos); ?>
		<br />

		
		<br />
		<?php echo \core\HTML_Tag::span_error('validacion', $datos);?><br />
		<input type='submit' value='Enviar'>
		<?php if (\core\Distribuidor::get_metodo_invocado() != "form_borrar" ): ?>
			<input type='reset' value='Limpiar'>
		<?php endif; ?>
			<input type='button' value='Cancelar' onclick='window.location.assign("<?php echo \core\Datos::contenido("url_cancelar", $datos); ?>");'/>
	</form>
	<script type='text/javascript'>
		window.document.getElementById("login").readOnly='readonly';
		window.document.getElementById("email").readOnly='readonly';
	</script>
</div>