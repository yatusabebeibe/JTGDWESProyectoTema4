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

    echo "<h1>Importar departamentos.</h1>";
    
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
            
            $datos = file_get_contents("../tmp/datos.json");
            
            if ($datos) {
                try {
                    // Iniciamos la conexion con la base de datos
                    $miDB = new PDO(DSN, DBUser, DBPass);

                    $aJson = json_decode($datos,true);
                    // var_dump($aJson);
            
                    // String de las columnas que vamos a seleccionar
                    $sColumnas = implode(",", aColumnas);

                    // Esto intenta pasar los datos a JSON
                    if ($entradaOK) { // Si el query se ejecuta correctamente
                        // Variable en formato heredoc con la sentencia SQL con los parametros necesarios
                        $statement = <<<EOT
                        INSERT INTO T02_Departamento VALUES(
                            :codigo,
                            :descripcion,
                            :fechaCreacion,
                            :volumen,
                            :fechaBaja
                        );
                        EOT;
                        $consulta = $miDB->prepare($statement);

                        foreach ($aJson as $departamento) {
                            $aParametros = [
                                ':codigo' => $departamento[aColumnas["Codigo"]] ?? null,
                                ':descripcion' => $departamento[aColumnas["Descripcion"]] ?? null,
                                ':fechaCreacion'=> $departamento[aColumnas["FechaCreacion"]] ?? null,
                                ':volumen' => $departamento[aColumnas["Volumen"]] ?? null,
                                ':fechaBaja' => $departamento[aColumnas["FechaBaja"]] ?? null
                            ];
                            try {
                                $consulta->execute($aParametros);
                            } catch (PDOException $error) { // Esto se ejecuta si da error al importar la DB
                                echo "<p class='error'>El departamento {$aParametros[":codigo"]} tiene datos duplicados o incorrectos</p>";
                                $error ="Datos duplicados o incorrectos";
                            }
                        }
                    }

                    // lo inicializo a null para que el if no de error por no estar definido
                    $aParametros = null;

                    // String de las columnas que vamos a seleccionar
                    $consulta = $miDB->prepare("SELECT $sColumnas FROM T02_Departamento ORDER BY ".aColumnas['Descripcion']." DESC");
                    
                    // Esto intenta crear una tabla con los resultados del query
                    if ($consulta -> execute($aParametros)) { // Si el query se ejecuta correctamente
            
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
            } else {
                echo "<p class='error'>Error al obtener el archivo</p>";
            }
            ?>
            <!-- Boton para exportar los datos -->
            <input type="submit" name="enviar" value="Importar datos">
            <span <?=!empty($error)?"class='error'":null?> ><?= $entradaOK ? (empty($error) ? "Datos Importados correctamente" : "Datos duplicados o incorrectos" ) : null ?></span>
        </form>
    </div>
</body>
</html>