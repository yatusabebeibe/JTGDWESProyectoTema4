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


    const HOST = "10.199.10.22";
    const DBName = "DBJTGDWESProyectoTema4";
    const DSN = "mysql:host=".HOST.";dbname=".DBName;
    const DBUserName = "userJTGDWESProyectoTema4";
    const DBPassword = "paso";


    $entradaOK = true;
    $aErrores = ["descripcion"=>''];
    $aRespuestas = ["descripcion"=>null];

    if (!isset($_REQUEST["enviar"])) {
        $entradaOK = false;
    } else {
        $aRespuestas["descripcion"] = $_REQUEST['descripcion'];

        if (!is_null(validacionFormularios::comprobarAlfaNumerico($aRespuestas["descripcion"]))) {
            $aErrores["descripcion"] = "El nombre no puede estar vacío.";
        }

        foreach ($aErrores as $mensaje) {
            if (!empty($mensaje)) $entradaOK = false;
        }
    }

    ?>
    <form method="post">
        <div id="campos">
            <div>
                <label class="tituloCampo">Descripcion:</label>
                <input type="text" name="descripcion" autofocus value="<?= $aRespuestas['descripcion'] ?>">
            </div>
            <br>

            <input type="submit" name="enviar" value="Enviar">
        </div>
    </form>
        <?php
            echo '<div class="resultado">';
            try {
                $miDB = new PDO(DSN, DBUserName, DBPassword);
                
                if ($entradaOK && !empty($aRespuestas["descripcion"])) {
                    $query = $miDB->query(<<<EOF
                        SELECT * FROM T02_Departamento
                        WHERE T02_DescDepartamento LIKE '%{$aRespuestas["descripcion"]}%'
                        ORDER BY T02_FechaCreacionDepartamento DESC;
                        EOF
                    );
                } else {
                    $query = $miDB->query("SELECT * FROM T02_Departamento ORDER BY T02_FechaCreacionDepartamento DESC");
                }
                
                
                if ($query -> execute()) {
                    echo "<table>";
                    
                    $numColumnas = $query->columnCount();
                    
                    echo "<thead><tr>";
                    for ($nColActual = 0; $nColActual < $numColumnas; $nColActual++) {
                        $nombreColumna = $query->getColumnMeta($nColActual)["name"];
                        echo "<th>{$nombreColumna}</th>";
                    }
                    echo "</tr></thead>";
                    
                    $nRegistros=0;
                    while ($registro = $query -> fetch(PDO::FETCH_OBJ)) {
                        $nRegistros++;
                        echo "<tr>";
                        foreach ($registro as $value) {
                            echo "<td>$value</td>";
                        }
                        echo "</tr>";
                        }
                    echo "</table>";

                    echo "<p>Habia {$nRegistros} registros.</p>";
                }
                else {
                    echo "No se pudo ejecutar la consulta";
                }
            } catch (PDOException $error) {
                echo "<h3>ERROR SQL:</h3>";
                echo "<p><strong>Mensaje:</strong> ".$error->getMessage()."</p>";
                echo "<p><strong>Codigo:</strong> ".$error->getCode()."</p>";
            }
            echo "</div>";
        ?>
</body>
</html>