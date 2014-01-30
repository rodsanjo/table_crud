
<form method='post' name='<?php echo \core\Array_Datos::contenido("form_name", $datos); ?>' action="<?php echo \core\URL::generar($datos['controlador_clase'].'/validar_'.$datos['controlador_metodo']); ?>" >
	
	<?php echo \core\HTML_Tag::form_registrar($datos["form_name"], "post"); ?>
	
	<input id='id' name='id' type='hidden' value='<?php echo \core\Array_Datos::values('id', $datos); ?>' />
	
        Nombre: <input id='nombre' name='nombre' type='text' size='10'  maxlength='30' value='<?php echo \core\Array_Datos::values('nombre', $datos); ?>'/>
	<?php echo \core\HTML_Tag::span_error('nombre', $datos); ?>
	<br />
        
        Símbolo químico: <input id='simbolo_quimico' name='simbolo_quimico' type='text' size='2'  maxlength='3' value='<?php echo \core\Array_Datos::values('simbolo_quimico', $datos); ?>'/>
	<?php echo \core\HTML_Tag::span_error('simbolo_quimico', $datos); ?>
	<br />
        
        Número_atómico: <input id='numero_atomico' name='numero_atomico' type='text' size='10'  maxlength='100' value='<?php echo \core\Array_Datos::values('numero_atomico', $datos); ?>'/>
	<?php echo \core\HTML_Tag::span_error('numero_atomico', $datos); ?>
	<br />
        
        Masa atómica: <input id='masa_atomica' name='masa_atomica' type='text' size='10'  maxlength='15' value='<?php echo \core\Array_Datos::values('masa_atomica', $datos); ?>'/>
	<?php echo \core\HTML_Tag::span_error('masa_atomica', $datos); ?>
	<br />
        
        Fecha de entrada: <input id='fecha_entrada' name='fecha_entrada' type='text' size='15'  maxlength='dd/mm/aaaa' value='<?php echo \core\Array_Datos::values('fecha_entrada', $datos); ?>'/>
	<?php echo \core\HTML_Tag::span_error('fecha_entrada', $datos); ?>
	<br />
        
        Fecha de salida: <input id='fecha_salida' name='fecha_salida' type='date' value='<?php echo \core\Array_Datos::values('fecha_salida', $datos); ?>'/>
	<?php echo \core\HTML_Tag::span_error('fecha_salida', $datos); ?>
	<br />
        
	<br />
	<?php echo \core\HTML_Tag::span_error('errores_validacion', $datos); ?>
	
	<input type='submit' value='Enviar'>
	<input type='reset' value='Limpiar'>
	<button type='button' onclick='location.assign("?menu=<?php echo $datos['controlador_clase']; ?>&submenu=index");'>Cancelar</button>
</form>