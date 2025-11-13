<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Jesús Temprano - Ej 4, Tema4</title>
    <link rel="stylesheet" href="../webroot/css/stylesForm.css">
</head>
<body>

<?php

    /*  @author Jesús Temprano Gallego
     *  @since 08/11/2025
     */

    echo "<h1>Busqueda departamentos por descripcion.</h1>";
    
    include_once("../core/231018libreriaValidacion.php");

    // Variable para obtener datos de la configuracion de la DB
    $config = parse_ini_file("../config/DB.ini");

    /*  Constantes para la connexion con la DB.
        Se pueden usar tanto `const` como `define()` en la mayoria de casos.
        En esta pagina web explican las diferencias y en que casos se usa uno u otro:
           https://mclibre.org/consultar/php/lecciones/php-constantes.html
    */
    define("HOST", $config["db_host"]);
    define("DBName", $config["db_name_t4"]);
    define("DBUserName", $config["db_user_t4"]);
    define("DBPassword", $config["db_pass_t4"]);
    const DSN = "mysql:host=".HOST.";dbname=".DBName;


    // Variables generales para gestionar los datos del formulario
    $entradaOK = true; // Se pone a false si el cliente no se envia datos o si los datos estan mal
    $aErrores = ["codigo"=>'',"descripcion"=>'',"volumen"=>''];
    $aRespuestas = ["codigo"=>null,"descripcion"=>null,"volumen"=>null];

    if (!isset($_REQUEST["enviar"])) { // Si hemos cargado la pagina por primera vez
        $entradaOK = false;
    } else { // Si hemos pulsado a enviar

        // Recogemos los datos enviados del cliente y los metemos en el array de respuestas
        $aRespuestas["descripcion"] = $_REQUEST['descripcion'];

        // Validamos todos los datos:

        // Comprobamos que si tiene descripcion, que sea alfanumerica
        if ($error = validacionFormularios::comprobarAlfaNumerico($aRespuestas["descripcion"])) {
            $aErrores["descripcion"] = $error; // Si da error se lo pasamos a el array de errores
        }

        // Comprobamos si hay errores
        foreach ($aErrores as $mensaje) {
            if (!empty($mensaje)) $entradaOK = false;
        }
    }

    /*  Salimos del codigo php para escribir el formulario html.
        Podemos meter datos php poniendo en el html: <?= codigoPHP ?>
    */
    ?>
    <form method="post">
        <div id="campos">
            <div>
                <label class="tituloCampo">Descripcion:</label>
                <!-- Ponemos los valores del array respuesta para que el usuario no necesite que escribirlo de nuevo -->
                <input type="text" name="descripcion" autofocus value="<?= $aRespuestas['descripcion'] ?>">
            </div>
            <br>

            <!-- Boton para enviar los datos -->
            <input type="submit" name="enviar" value="Enviar">
        </div>
    </form>
        <?php
            echo '<div class="resultado">';
            try {
                // Iniciamos la conexion con la base de datos
                $miDB = new PDO(DSN, DBUserName, DBPassword);

                if ($entradaOK && !empty($aRespuestas["descripcion"])) { // Si no hubieron errores con los datos
                    // Variable con un query para obtener los datos que cumplan la condicion
                    $query = $miDB->query(<<<EOF
                        SELECT * FROM T02_Departamento
                        WHERE T02_DescDepartamento LIKE '%{$aRespuestas["descripcion"]}%'
                        ORDER BY T02_FechaCreacionDepartamento DESC;
                        EOF
                    );
                } else {
                    // Variable con un query para obtener todos los datos de la tabla
                    $query = $miDB->query("SELECT * FROM T02_Departamento ORDER BY T02_FechaCreacionDepartamento DESC");
                }

                // Esto intenta crear una tabla con los resultados del query
                if ($query -> execute()) { // Si el query se ejecuta correctamente
                    echo "<table>";
                    

                    echo "<thead><tr>";

                    // Contamos cuantas columnas tiene la tabla sacada por el query y la recorremos
                    for ($i = 0; $i < $query->columnCount(); $i++) { // $i representa el índice de la columna actual
                        // Obtenemos el nombre de la columna y lo ponemos en la tabla html
                        $nombreColumna = $query->getColumnMeta($i)["name"];
                        echo "<th>{$nombreColumna}</th>";
                    }
                    echo "</tr></thead>";
                    
                    // Obtiene los registros que ha obtenido el query
                    while ($registro = $query -> fetch(PDO::FETCH_OBJ)) { // Mientras haya mas registros
                        echo "<tr>";
                        // Mete cada registro en la tabla
                        foreach ($registro as $value) {
                            echo "<td>$value</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";

                    // Mostramos cuantos registros tenia la tabla
                    echo "<p>Habia {$query->rowCount()} registros.</p>";
                }
                else { // Ssi da error al hacer el query
                    echo "No se pudo ejecutar la consulta";
                }
            } catch (PDOException $error) { // Esto se ejecuta si da error al iniciar la conexion, insertar los datos, o hacer el query
                echo '<h3 class="error">ERROR SQL:</h3>';
                echo '<p class="error"><strong>Mensaje:</strong> '.$error->getMessage()."</p>";
                echo '<p class="error"><strong>Codigo:</strong> '.$error->getCode()."</p>";
            }
            echo "</div>";
        ?>
</body>
</html>