<div >
	<h2>Borrar un elemento químico</h2>
	<?php include "form_and_inputs.php"; ?>
	<script type='text/javascript'>
		window.document.getElementById("nombre").readOnly='readonly';
		window.document.getElementById("simbolo_quimico").readOnly='readonly';
                window.document.getElementById("numero_atomico").readOnly='readonly';
                window.document.getElementById("masa_atomica").readOnly='readonly';
                window.document.getElementById("fecha_entrada").readOnly='readonly';
                window.document.getElementById("fecha_salida").readOnly='readonly';
                formulario.limpiar.style.display = "none";                
	</script>
</div>