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

    const HOST = "10.199.10.22";
    const DBName = "DBJTGDWESProyectoTema4";

    const DSN = "mysql:host=".HOST.";dbname=".DBName;
    const DBUserName = "userJTGDWESProyectoTema4";
    const DBPassword = "paso";

    try {
        $miDB = new PDO(DSN, DBUserName, DBPassword);
        
        $consulta = $miDB->query("SELECT * FROM T02_Departamento");
        
        if ($consulta -> execute()) {
            echo "<table>";
            
            $numColumnas = $consulta->columnCount();
            
            echo "<thead><tr>";
            for ($nColActual = 0; $nColActual < $numColumnas; $nColActual++) {
                $nombreColumna = $consulta->getColumnMeta($nColActual)["name"];
                echo "<th>{$nombreColumna}</th>";
            }
            echo "</tr></thead>";
            
            $nRegistros=0;
            while ($registro = $consulta -> fetch(PDO::FETCH_OBJ)) {
                $nRegistros++;
                echo "<tr>";
                foreach ($registro as $value) {
                    ?>
                    <td><?= $value ?></td>
                    <?php
                }
                echo "</tr>";
                }
            echo "</table>";

            echo "<p>Habia {$nRegistros} registros.</p>";
        }
        else {
            echo "No se pudo ejecutar la consulta";
        }
    } catch (PDOException $th) {
        echo "Mensaje: ".$th->getMessage()."<br>";
        echo "Codigo: ".$th->getCode()."<br>";
    }
    
?>