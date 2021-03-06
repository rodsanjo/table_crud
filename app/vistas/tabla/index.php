<div>
    <h1>Lsita de elementos de la tabla periódica</h1>

    <table border='1'>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Símbolo químico</th>
                <th>Nº atómico</th>
                <th>Masa atómica</th>
                <th>Última modificación</th>
                <th>Fecha de validez</th>
                <th>Acciones
                    <?php echo \core\HTML_Tag::a_boton("boton", array("tabla", "form_insertar"), "insertar"); ?>                 
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($datos['filas'] as $fila)
            {
                echo "
                    <tr>
                        <td>{$fila['nombre']}</td>
                        <td>{$fila['simbolo_quimico']}</td>
                        <td>{$fila['numero_atomico']}</td>
                        <td>{$fila['masa_atomica']}</td>
                        <td>{$fila['fecha_entrada']}</td>
                        <td>{$fila['fecha_salida']}</td>
                        <td>
                    ".\core\HTML_Tag::a_boton_onclick("boton", array("tabla", "form_modificar", $fila['id']), "modificar")
                    //<a class='boton' href='?menu={$datos['controlador_clase']}&submenu=form_modificar&id={$fila['id']}' >modificar</a>
                    .\core\HTML_Tag::a_boton_onclick("boton", array("tabla", "form_borrar", $fila['id']), "borrar").
                    //<a class='boton' href='?menu={$datos['controlador_clase']}&submenu=form_borrar&id={$fila['id']}' >borrar</a>
                        "</td>
                    </tr>
";
            }
            echo "
                <tr>
                    <td colspan='6'></td>
                    <td>"
                        .\core\HTML_Tag::a_boton("boton", array("tabla", "form_insertar"), "insertar").
                    "</td>
                </tr>
            ";
            ?>
        </tbody>
    </table>
</div>