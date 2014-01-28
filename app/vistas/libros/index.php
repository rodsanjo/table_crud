<div id='libros'>
	<h1>Mis últimos libros leidos</h1>
	<p>Esta aplicación lee líneas de texto de un fichero. Cada línea contiene datos de un libro, excepto la primera que contiene el título de las columnas. Atención, las líneas tienen al final los caracteres de [fin de línea] y [nueva línea]. La función file($file_path,FILE_IGNORE_NEW_LINES) devuelve un array con las líneas del fichero sin los caracteres de final de línea.</p>
	<table border='1px'>
		<thead>
			<tr>
				<th>Título</th>
				<th>Autor</th>
				<th>Comentario</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php
			/*
			 for ($i = 0; $i < count($datos['libros']); $i++) {
			 
				echo "<tr>
						<td>{$datos['libros'][$i]['titulo']}</td>
						<td>{$datos['libros'][$i]['autor']}</td>
						<td>{$datos['libros'][$i]['comentario']}</td>
					</tr>";
			}
			*/
			foreach ($datos['libros'] as $id => $libro) {
				echo "<tr>
						<td>{$libro['titulo']}</td>
						<td>{$libro['autor']}</td>
						<td>{$libro['comentario']}</td>
						<td>
							<a href='".\core\URL::generar(array("libros","form_modificar",$id))."' >Modificar</a>
							<a href='".\core\URL::generar("libros/form_borrar/$id")."' >Borrar</a>
						</td>
					</tr>";
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan='4'><button onclick='window.location.assign("<?php echo \core\URL::generar("libros/form_anexar"); ?>");'>anexar un libro</button></td>
			</tr>
		</tfoot>
		
	</table>
</div>