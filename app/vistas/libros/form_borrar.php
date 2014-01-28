<h2>Confirma el borrado del libro</h2>
<form id='form_borrar' method='post' action='<?php echo URL_ROOT; ?>?menu=libros&submenu=form_borrar_validar'>
	
	<input type='hidden' id='id' name='id' value='<?php echo \core\Datos::values('id', $datos); ?>'/>
	
Título: <input type='text' id='titulo' name='titulo' maxsize='50' value='<?php echo \core\Datos::values('titulo', $datos); ?>' readonly='readonly' /><span class='requerio'>Requerido</span><?php echo \core\HTML_Tag::span_error('autor', $datos); ?><br />
	Autor: <input type='text' id='autor' name='autor' maxsize='50'  readonly='readonly' value='<?php echo \core\Datos::values('autor', $datos) ?>'/><span class='requerio'>Requerido</span><?php echo \core\HTML_Tag::span_error('comentario', $datos); ?><br />
	Comentario: <input type='text' id='comentario' name='comentario' maxsize='50'  readonly='readonly' value='<?php echo \core\Datos::values('comentario', $datos) ?>'/><?php echo \core\HTML_Tag::span_error('comentario', $datos); ?><br />

	<?php echo \core\HTML_Tag::span_error('validacion', $datos);?><br />

	<input type='submit' value='confirmar borrado' />
	<input type='button' value='cancelar' onclick='window.location.assign("<?php echo URL_ROOT; ?>?menu=libros");'/>
</form>