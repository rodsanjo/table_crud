<div >
	<h2>Modificar datos del usuario usuario</h2>
	
	<form method='post' action="<?php echo \core\URL::generar("usuarios/".\core\Distribuidor::get_metodo_invocado()."_validar"); ?>" >
		
		<input id='id'  name='id' type='hidden' value='<?php echo \core\Array_Datos::values('id', $datos); ?>' />

		Login: <input id='login' name='login' type='text' size='30'  maxlength='30' autocomplete='off' value='<?php echo \core\Array_Datos::values("login", $datos); ?>'/>
		<span class='requerido'>Requerido</span><?php echo \core\HTML_Tag::span_error('login', $datos); ?>
		<br />

		Email: <input id='email' name='email' type='text' size='100' maxlength='100' autocomplete='off' value='<?php echo \core\Array_Datos::values('email', $datos); ?>'/>
		<span class='requerido'>Requerido</span><?php echo \core\HTML_Tag::span_error('email', $datos); ?>
		<br />


		Fecha alta: <input id='fecha_alta' name='fecha_alta' type='text' size='30'  maxlength='30' autocomplete='off' value='<?php echo \core\Array_Datos::values('fecha_alta', $datos); ?>'/>
		<span class='requerido'>Requerido</span><?php echo \core\HTML_Tag::span_error('fecha_alta', $datos); ?>
		<br />
		
		Fecha confirmación alta: <input id='fecha_confirmacion_alta' name='fecha_confirmacion_alta' type='text' size='30'  maxlength='30' autocomplete='off' value='<?php echo \core\Array_Datos::values('fecha_confirmacion_alta', $datos); ?>'/>
		<?php echo \core\HTML_Tag::span_error('fecha_confirmacion_alta', $datos); ?>
		<br />

		<br />
		<?php echo \core\HTML_Tag::span_error('validacion', $datos);?><br />
		<input type='submit' value='Enviar'>
		<?php if (\core\Distribuidor::get_metodo_invocado() != "form_borrar" ): ?>
			<input type='reset' value='Limpiar'>
		<?php endif; ?>
			<input type='button' value='Cancelar' onclick='window.location.assign("<?php echo \core\Datos::contenido("url_cancelar", $datos); ?>");'/>
	</form>

	
</div>