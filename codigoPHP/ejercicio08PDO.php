<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Jesús Temprano - Ej 4, Tema4</title>
    <link rel="stylesheet" href="../webroot/css/stylesForm.css">
    <style>
        form {
            padding: 0;
            max-width: inherit;
            box-shadow: none;
        }
        input[type=submit] {margin: 0;}
    </style>
</head>
<body>

<?php

    /*  @author Jesús Temprano Gallego
     *  @since 17/11/2025
     */

    echo "<h1>Exportar departamentos.</h1>";

    include_once("../core/231018libreriaValidacion.php");

    /*  Importamos la configuracion de la DB. Contiene constantes para la connexion con la DB.
        Existen tanto `define()` como `const` se pueden usar igual en la mayoria de casos.
        En esta pagina web explican las diferencias y en que casos se usa uno u otro:
           https://mclibre.org/consultar/php/lecciones/php-constantes.html
    */
    require_once("../config/confDBPDO.php");


    // Variables generales para gestionar los datos del formulario
    $entradaOK = true; // Se pone a false si el cliente no se envia datos (hace click a exportar)

    if (!isset($_REQUEST["enviar"])) { // Si hemos cargado la pagina por primera vez y no le hemos dado a exportar
        $entradaOK = false;
    }

    /*  Salimos del codigo php para escribir el formulario html.
        Podemos meter datos php poniendo en el html: <?= codigoPHP ?>
    */
    ?>
    <div class="resultado">
        <form method="post">
    <?php
    try {
        // Iniciamos la conexion con la base de datos
        $miDB = new PDO(DSN, DBUser, DBPass);

        // lo inicializo a null para que al hacerlo luego en el if no de error por no estar definido si no pasa el proximo if
        $parametros = null;

        // Array con el nombre de las columnas que vamos a seleccionar
        $aSelecCol = [aColumnas["Codigo"],aColumnas["Descripcion"]];

        // String de las columnas que vamos a seleccionar
        $sColumnas = implode(",", aColumnas);

        // Variable con un query para obtener todos los datos de la tabla
        $consulta = $miDB->prepare("SELECT $sColumnas FROM T02_Departamento ORDER BY ".aColumnas['Descripcion']." DESC");

        // Esto intenta pasar los datos a JSON
        if ($entradaOK && $consulta -> execute($parametros)) { // Si el query se ejecuta correctamente
            try {
                // Obtenemos todos los datos como una lista de objetos
                $aObjResultados = $consulta-> fetchAll(PDO::FETCH_OBJ);

                // Los convertimos a un string JSON
                $sJson = json_encode($aObjResultados, JSON_PRETTY_PRINT);

                echo "<pre>$sJson</pre>";

                file_put_contents("../tmp/datos.json",$sJson);
            } catch (PDOException $error) { // Esto se ejecuta si da error al exportar la DB
                $entradaOK = false;
                echo '<h3 class="error">ERROR EXPORTAR SQL:</h3>';
                echo '<p class="error"><strong>Mensaje:</strong> '.$error->getMessage()."</p>";
                echo '<p class="error"><strong>Codigo:</strong> '.$error->getCode()."</p>";
            }
        }

        // Esto intenta crear una tabla con los resultados del query
        if ($consulta -> execute($parametros)) { // Si el query se ejecuta correctamente

            echo "<table>";
            echo "<thead><tr>";

            // Contamos cuantas columnas tiene la tabla sacada por el query y la recorremos
            foreach (aColumnas as $col) {
                // Ponemos el nombre de la columna en la tabla html
                echo "<th>{$col}</th>";
            }
            echo "</tr></thead>";

            // Obtiene los registros que ha obtenido el query
            while ($registro = $consulta -> fetchObject()) { // Mientras haya mas registros
                echo "<tr>";
                // Mete cada registro en la tabla
                foreach (aColumnas as $col) {
                    $valor = $registro->$col;

                    echo "<td>$valor</td>";
                }
                echo "</tr>";
            }
            echo "</table>";

            // Mostramos cuantos registros tenia la tabla
            echo "<p>Habia {$consulta->rowCount()} registros.</p>";
        }
        else { // Ssi da error al hacer el query
            echo "No se pudo ejecutar la consulta";
        }
    } catch (PDOException $error) { // Esto se ejecuta si da error al iniciar la conexion, insertar los datos, o hacer el query
        echo '<h3 class="error">ERROR SQL:</h3>';
        echo '<p class="error"><strong>Mensaje:</strong> '.$error->getMessage()."</p>";
        echo '<p class="error"><strong>Codigo:</strong> '.$error->getCode()."</p>";
    }
        ?>
            <!-- Boton para exportar los datos -->
            <input type="submit" name="enviar" value="Exportar datos">
            <span <?=!empty($error)?"class='error'":null?> ><?= $entradaOK ? (empty($error) ? "Datos exportados correctamente" : "Error al exportar datos" ) : null ?></span>
        </form>
    </div>
</body>
</html>