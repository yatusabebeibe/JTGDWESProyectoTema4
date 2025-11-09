<style>
    table, tr, td, th {
        font-family: sans-serif;
        border: 3px black solid;
        border-collapse: collapse;
    }
    th {text-align: center; font-weight: bold;}
    td, th {
        padding: 5px;
        width: max-content;
    }
    tr:nth-of-type(2n) {background: lightgray;}
    thead {
        background: lightskyblue;
    }
</style>

<?php

    /*  @author Jesús Temprano Gallego
     *  @since 06/11/2025
     */

    echo "<h1>Mostrar el contenido de la tabla Departamento y el número de registros.</h1>";

    /*  Constantes para la connexion con la DB.
        Se pueden usar tanto `const` como `define()` en la mayoria de casos.
        En esta pagina web explican las diferencias y en que casos se usa uno u otro:
           https://mclibre.org/consultar/php/lecciones/php-constantes.html
    */
    const HOST = "10.199.10.22";
    const DBName = "DBJTGDWESProyectoTema4";
    const DSN = "mysql:host=".HOST.";dbname=".DBName;
    const DBUserName = "userJTGDWESProyectoTema4";
    const DBPassword = "paso";

    try {
        // Iniciamos la conexion con la base de datos
        $miDB = new PDO(DSN, DBUserName, DBPassword);
        
        // Variable con un query para obtener todos los datos de la tabla
        $query = $miDB->query("SELECT * FROM T02_Departamento ORDER BY T02_FechaCreacionDepartamento DESC");
        
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
    }
?>