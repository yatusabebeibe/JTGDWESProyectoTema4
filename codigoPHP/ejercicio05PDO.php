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

    echo "<h1>Insertar 3 elementos a la vez.</h1>";
    
    include_once("../core/231018libreriaValidacion.php");


    const HOST = "10.199.10.22";
    const DBName = "DBJTGDWESProyectoTema4";
    const DSN = "mysql:host=".HOST.";dbname=".DBName;
    const DBUserName = "userJTGDWESProyectoTema4";
    const DBPassword = "paso";


    $entradaOK = true;
    $aErrores = ["codigo"=>'',"descripcion"=>'',"volumen"=>''];
    $aRespuestas = ["codigo"=>null,"descripcion"=>null,"volumen"=>null];

    $aCodigos = [];
    

    if (!isset($_REQUEST["enviar"])) {
        $entradaOK = false;
    } else {
        $aRespuestas["codigo"] = $_REQUEST['codigo'];
        $aRespuestas["descripcion"] = $_REQUEST['descripcion'];
        $aRespuestas["volumen"] = $_REQUEST['volumen'];

        /*  explode() divide el string por sus comas y obtiene
            el array con los codigos.
            Luego le quitamos los espacios de delante y atras
            con un trim(), y eliminamos los duplicados con array_unique()
        */
        $aCodigos = explode(",",$aRespuestas["codigo"]);
        foreach ($aCodigos as $key => $value) {
            $aCodigos[$key] = trim($value);
        }
        $aCodigos = array_unique($aCodigos);

        // Comprueba que haya 3 codigos
        if (count($aCodigos) != 3) {
            $aErrores["codigo"] = "Tienes que poner 3 codigos diferentes";
        }
        else if (empty($aErrores["codigo"])) {
            // Comprobamos que cada codigo esta bien y se puede poner
            foreach ($aCodigos as $key => $value) {
                if ($error = validacionFormularios::comprobarAlfabetico($aCodigos[$key], 3, 3, 1)) {
                    $aErrores["codigo"] = $error;
                }
                else if ($aCodigos[$key] !== strtoupper($aCodigos[$key])) {
                    $aErrores["codigo"] = "Los codigos tienen que estar en mayusculas.";
                }
                else {
                    try {
                        $miDB = new PDO(DSN, DBUserName, DBPassword);
    
                        $query = $miDB->query("SELECT * FROM T02_Departamento WHERE T02_CodDepartamento = '{$aCodigos[$key]}'");
    
                        if ($query->rowCount() >= 1) {
                            $aErrores["codigo"] = "El codigo {$aCodigos[$key]} ya esta siendo usado por otro departamento.";
                        }
                    } catch (PDOException $error) {
                        $aErrores["codigo"] = "Error de conexion: " . $error->getMessage();
                    }
                }
            }
        }

        if ($error = validacionFormularios::comprobarAlfaNumerico(cadena:$aRespuestas["descripcion"],obligatorio: 1)) {
            $aErrores["descripcion"] = $error;
        }

        if (!is_null(validacionFormularios::comprobarFloat($aRespuestas["volumen"], min:0, obligatorio:1))) {
            $aErrores["volumen"] = "La edad debe ser un número mayor a 0.";
        }

        foreach ($aErrores as $mensaje) {
            if (!empty($mensaje)) $entradaOK = false;
        }
    }

    ?>
    <form method="post">
        <div id="campos">
            <div>
                <label class="tituloCampo">Tres codigos separados por coma:</label>
                <!-- <input type="text" name="codigo" value="<?= $entradaOK? null : $aRespuestas['codigo'] ?>" obligatorio> -->
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

                if ( $entradaOK ) {
                    try {
                        $miDB->beginTransaction();
                        foreach ($aCodigos as $key => $value) {
                            $miDB -> exec(<<<EOT
                                INSERT INTO T02_Departamento VALUES(
                                    '{$aCodigos[$key]}',
                                    '{$aRespuestas["descripcion"]}',
                                    NOW(),
                                    {$aRespuestas["volumen"]},
                                    NULL
                                );
                                EOT
                            );
                        }
                        $miDB ->commit();
                    } catch (PDOException $error) {
                        $miDB->rollBack();
                        echo "<h3>ERROR EN LA TRANSACCION SQL:</h3>";
                        echo "<p><strong>Mensaje:</strong> ".$error->getMessage()."</p>";
                        echo "<p><strong>Codigo:</strong> ".$error->getCode()."</p>";
                    }
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