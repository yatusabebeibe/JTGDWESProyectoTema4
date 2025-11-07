<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Jesús Temprano - Ej 3, Tema4</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 30px;
        }
        h1 {
            text-align: center;
        }
        form {
            background: #fff;
            max-width: 450px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,.1);
        }
        #campos div {
            display: flex;
            flex-direction: column;
        }
        input {
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            /* width: min-content; */
        }
        input[type="submit"] {
            margin-top: 15px;
            padding: 10px;
            border: none;
            background: #4e9645;
            color: #fff;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: .2s;
        }
        input[type="submit"]:hover {
            background: #4e9645;
        }
        input[obligatorio]{
            background-color: #fbff0042;
        }
        input[disabled]{
            background-color: #46464641;
        }
        .errorCampo {
            font-size: 13px;
            margin-top: 3px;
        }
        .resultado {
            max-width: 1200px;
            width: max-content;
            margin: 20px auto;
            background: #fff;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 15px;
        }

        table th, table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background: #e6e6e6;
            font-weight: bold;
            
        }

        table tr:nth-child(even) {
            background: #f7f7f7;
        }

        table tr:hover {
            background: #e2f3e2;
        }
    </style>
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
            
        if (!is_null(validacionFormularios::comprobarAlfabetico($aRespuestas["codigo"], 3, 3, 1))) {
            $aErrores["codigo"] = "El codigo tiene ser de tener 3 letras.";
        }
        else if ($aRespuestas["codigo"] !== strtoupper($_REQUEST['codigo'])) {
            $aErrores["codigo"] = "El codigo tiene estar en mayusculas.";
        }
        else {
            try {
                $miDB = new PDO(DSN, DBUserName, DBPassword);

                $statement = $miDB->query("SELECT * FROM T02_Departamento WHERE T02_CodDepartamento = '{$aRespuestas["codigo"]}'");

                if ($statement->rowCount() >= 1) {
                    $aErrores["codigo"] = "El codigo ya esta siendo usado por otro departamento.";
                }
            } catch (PDOException $error) {
                $aErrores["codigo"] = "Error de conexion: " . $error->getMessage();
            }
        }

        if (!is_null(validacionFormularios::comprobarAlfabetico(cadena:$aRespuestas["descripcion"],obligatorio: 1))) {
            $aErrores["descripcion"] = "El nombre no puede estar vacío.";
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
                <label class="tituloCampo">Codigo:</label>
                <input type="text" name="codigo" value="<?= $aRespuestas['codigo'] ?>" obligatorio>
                <span class="errorCampo" style="color:red;"><?= $aErrores['codigo'] ?></span>
            </div>
            <br>
            
            <div>
                <label class="tituloCampo">Descripcion:</label>
                <input type="text" name="descripcion" value="<?= $aRespuestas['descripcion'] ?>" obligatorio>
                <span class="errorCampo" style="color:red;"><?= $aErrores['descripcion'] ?></span>
            </div>
            <br>

            <div>
                <label class="tituloCampo">Descripcion:</label>
                <input type="datetime-local" name="descripcion" value="<?= (new DateTime)->format('Y-m-d\TH:i') ?>" disabled>
                <span class="errorCampo" style="color:red;"><?= $aErrores['descripcion'] ?></span>
            </div>
            <br>

            <div>
                <label class="tituloCampo">Volumen:</label>
                <input type="number" step="0.01" name="volumen" value="<?= $aRespuestas['volumen'] ?>" obligatorio>
                <span class="errorCampo" style="color:red;"><?= $aErrores['volumen'] ?></span>
            </div>
            <br>

            <input type="submit" name="enviar" value="Enviar">
        </div>
    </form>
        <?php
            echo '<div class="resultado">';
            try {
                $miDB = new PDO(DSN, DBUserName, DBPassword);
                
                $query = $miDB->query("SELECT * FROM T02_Departamento");
                
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
            } catch (PDOException $th) {
                echo "<h3>ERROR SQL:</h3>";
                echo "<p><strong>Mensaje:</strong> ".$error->getMessage()."</p>";
                echo "<p><strong>Codigo:</strong> ".$error->getCode()."</p>";
            }
            echo "</div>";
        ?>
</body>
</html>