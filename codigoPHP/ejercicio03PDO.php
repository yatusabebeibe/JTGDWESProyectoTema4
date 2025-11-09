<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Jesús Temprano - Ej 3, Tema4</title>
    <link rel="stylesheet" href="../webroot/css/stylesForm.css">
</head>
<body>

<?php

    /*  @author Jesús Temprano Gallego
     *  @since 07/11/2025
     */

    echo "<h1>Formulario añadir departamento.</h1>";
    
    include_once("../core/231018libreriaValidacion.php");


    const HOST = "10.199.10.22";
    const DBName = "DBJTGDWESProyectoTema4";
    const DSN = "mysql:host=".HOST.";dbname=".DBName;
    const DBUserName = "userJTGDWESProyectoTema4";
    const DBPassword = "paso";


    $entradaOK = true;
    $aErrores = ["codigo"=>'',"descripcion"=>'',"volumen"=>''];
    $aRespuestas = ["codigo"=>null,"descripcion"=>null,"volumen"=>null];

    if (!isset($_REQUEST["enviar"])) {
        $entradaOK = false;
    } else {
        $aRespuestas["codigo"] = $_REQUEST['codigo'];
        $aRespuestas["descripcion"] = $_REQUEST['descripcion'];
        $aRespuestas["volumen"] = $_REQUEST['volumen'];
            
        if ($error = validacionFormularios::comprobarAlfabetico($aRespuestas["codigo"], 3, 3, 1)) {
                $aErrores["codigo"] = $error;
            }
        else if ($aRespuestas["codigo"] !== strtoupper($_REQUEST['codigo'])) {
            $aErrores["codigo"] = "El codigo tiene estar en mayusculas.";
        }
        else {
            try {
                $miDB = new PDO(DSN, DBUserName, DBPassword);

                $query = $miDB->query("SELECT * FROM T02_Departamento WHERE T02_CodDepartamento = '{$aRespuestas["codigo"]}'");

                if ($query->rowCount() >= 1) {
                    $aErrores["codigo"] = "El codigo ya esta siendo usado por otro departamento.";
                }
            } catch (PDOException $error) {
                $aErrores["codigo"] = "Error de conexion: " . $error->getMessage();
            }
        }

        if ($error = validacionFormularios::comprobarAlfaNumerico(cadena:$aRespuestas["descripcion"],obligatorio: 1)) {
            $aErrores["descripcion"] = $error;
        }

        if ($error = validacionFormularios::comprobarFloat($aRespuestas["volumen"], min:0, obligatorio:1)) {
            $aErrores["volumen"] = $error;
        }

        foreach ($aErrores as $mensaje) {
            if (!empty($mensaje)) $entradaOK = false;
        }
    }

    ?>
    <form method="post">
        <div id="campos">
            <div>
                <label class="tituloCampo">Codigo:</label>
                <input type="text" name="codigo" value="<?= $aRespuestas['codigo'] ?>" obligatorio>
                <span class="errorCampo"><?= $aErrores['codigo'] ?></span>
            </div>
            <br>
            
            <div>
                <label class="tituloCampo">Descripcion:</label>
                <input type="text" name="descripcion" value="<?= $aRespuestas['descripcion'] ?>" obligatorio>
                <span class="errorCampo"><?= $aErrores['descripcion'] ?></span>
            </div>
            <br>

            <div>
                <label class="tituloCampo">Fecha Creacion:</label>
                <input type="datetime-local" name="fechaCreacion" value="<?= (new DateTime)->format('Y-m-d\TH:i') ?>" disabled>
                <span class="errorCampo"></span>
            </div>
            <br>

            <div>
                <label class="tituloCampo">Volumen:</label>
                <input type="number" step="0.01" name="volumen" value="<?= $aRespuestas['volumen'] ?>" obligatorio>
                <span class="errorCampo"><?= $aErrores['volumen'] ?></span>
            </div>
            <br>

            <input type="submit" name="enviar" value="Enviar">
        </div>
    </form>
        <?php
            echo '<div class="resultado">';
            try {
                $miDB = new PDO(DSN, DBUserName, DBPassword);

                if ($entradaOK) {
                    $statement = <<<EOT
                    INSERT INTO T02_Departamento VALUES(
                        '{$aRespuestas["codigo"]}',
                        '{$aRespuestas["descripcion"]}',
                        NOW(),
                        {$aRespuestas["volumen"]},
                        NULL
                    );
                    EOT;
                    $miDB -> exec($statement);
                }
                
                $query = $miDB->query("SELECT * FROM T02_Departamento ORDER BY T02_FechaCreacionDepartamento DESC");
                
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
                echo "<h3 class=\"error\">ERROR SQL:</h3>";
                echo "<p class=\"error\"><strong>Mensaje:</strong> ".$error->getMessage()."</p>";
                echo "<p class=\"error\"><strong>Codigo:</strong> ".$error->getCode()."</p>";
            }
            echo "</div>";
        ?>
</body>
</html>