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
     *  @since 06/11/2025
     */

    echo "<h1>Mostrar el contenido de la tabla Departamento y el número de registros.</h1>";

    /*  Importamos la configuracion de la DB. Contiene constantes para la connexion con la DB.
        Existen tanto `define()` como `const` se pueden usar igual en la mayoria de casos.
        En esta pagina web explican las diferencias y en que casos se usa uno u otro:
           https://mclibre.org/consultar/php/lecciones/php-constantes.html
    */
    require_once("../config/confDBPDO.php");

    echo '<div class="resultado">';
    try {
        // Iniciamos la conexion con la base de datos
        $miDB = new PDO(DSN, DBUser, DBPass);

        // Preparamos la consulta
        $consulta = $miDB->prepare("SELECT ".implode(",", aColumnas)." FROM T02_Departamento ORDER BY T02_FechaCreacionDepartamento DESC");

        // Creamos un array con los parametros y los valores con los que se va a ejecutar
        $parametros = null;

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

                    if ($col == aColumnas["Volumen"]) {
                        $valor = number_format($valor, decimal_separator:",", thousands_separator:".", decimals:2);
                    }

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
    echo "</div>";
?>
</body>
</html>