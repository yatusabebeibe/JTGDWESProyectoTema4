<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Jesús Temprano - Ej 5, Tema4</title>
    <link rel="stylesheet" href="../webroot/css/stylesForm.css">
</head>
<body>

<?php

    /*  @author Jesús Temprano Gallego
     *  @since 08/11/2025
     */

    echo "<h1>Insertar 3 elementos a la vez.</h1>";
    
    include_once("../core/231018libreriaValidacion.php");

    // Importamos la configuracion de la DB
    require_once("../config/confDBPDO.php");

    /*  Constantes para la connexion con la DB.
        Existen tanto `define()` como `const` se pueden usar igual en la mayoria de casos.
        En esta pagina web explican las diferencias y en que casos se usa uno u otro:
           https://mclibre.org/consultar/php/lecciones/php-constantes.html
    */
    const DSN = "mysql:host=".DBHost.";dbname=".DBName;


    // Variables generales para gestionar los datos del formulario
    $entradaOK = true; // Se pone a false si el cliente no se envia datos o si los datos estan mal
    $aErrores = ["codigo"=>'',"descripcion"=>'',"volumen"=>''];
    $aRespuestas = ["codigo"=>null,"descripcion"=>null,"volumen"=>null];
    $aDepartamentosNuevos  = [];

    if (!isset($_REQUEST["enviar"])) { // Si hemos cargado la pagina por primera vez
        $entradaOK = false;
    } else { // Si hemos pulsado a enviar

        // Recogemos los datos enviados del cliente y los metemos en el array de respuestas
        $aRespuestas["codigo"] = $_REQUEST['codigo'];
        $aRespuestas["descripcion"] = $_REQUEST['descripcion'];
        $aRespuestas["volumen"] = $_REQUEST['volumen'];

        // Dividimos el string por sus comas con el explode() para obtener un array con los codigos
        $aDepartamentosNuevos = explode(",",$aRespuestas["codigo"]);

        // Validamos todos los datos:

        // Comprueba que haya 3 codigos
        if (count($aDepartamentosNuevos) != 3 || count($aDepartamentosNuevos) != count(array_unique($aDepartamentosNuevos)) ) {
            $aErrores["codigo"] = "Tienes que poner 3 codigos diferentes";
        }
        else if (empty($aErrores["codigo"])) {
            // Se utiliza un try/catch por si diera algun error en la conexion o query
            try {
                // Iniciamos la conexion con la base de datos
                $miDB = new PDO(DSN, DBUser, DBPass);

                // Preparamos la consulta con el parametro `:departamento`
                $consulta = $miDB->prepare("SELECT * FROM T02_Departamento WHERE T02_CodDepartamento = :departamento");

                // Comprobamos que cada codigo esta bien y se puede poner
                foreach ($aDepartamentosNuevos as $key => $value) {
                    // Comprobamos que el codigo no este vacio y tenga exactamente 3 letras
                    if ($error = validacionFormularios::comprobarAlfabetico($aDepartamentosNuevos[$key], 3, 3, 1)) {
                        $aErrores["codigo"] = $error; // Si da error se lo pasamos a el array de errores
                    }
                    // Comprobamos que este en mayusculas
                    else if ($aDepartamentosNuevos[$key] !== strtoupper($aDepartamentosNuevos[$key])) {
                        $aErrores["codigo"] = "El codigo tiene estar en mayusculas.";
                    }
                    else if ($aDepartamentosNuevos[$key] !== trim($aDepartamentosNuevos[$key])) {
                        $aErrores["codigo"] = "El codigo no tiene que tener espacios.";
                    }
                    // Comprobamos que no este ya en la base de datos
                    else {
                        // Asocia un valor a el parametro que pusimos
                        // Usando el bind esta vez solo para probar
                        $consulta->bindParam(":departamento",$aDepartamentosNuevos[$key]);

                        // Hacemos una consulta para ver si ya esta usado el codigo
                        $consulta->execute();

                        if ($consulta->rowCount() >= 1) { // Si devuelve algo, es que el codigo ya esta usado
                            $aErrores["codigo"] = "El codigo ya esta siendo usado por otro departamento.";
                        }
                    }
                }
            } catch (PDOException $error) { // Esto se ejecuta si da error al crear la conexion o hacer la consulta
                $aErrores["codigo"] = "Error de conexion: " . $error->getMessage();
            } finally {
                unset($miDB);
            }
        }

        // Comprobamos que la descripcion no este vacia y sea alfanumerica
        if ($error = validacionFormularios::comprobarAlfaNumerico(cadena:$aRespuestas["descripcion"],obligatorio: 1)) {
            $aErrores["descripcion"] = $error; // Si da error se lo pasamos a el array de errores
        }

        // Comprobamos que no este vacio y si es un numero (puediendo ser decemal)
        if ($error = validacionFormularios::comprobarFloat($aRespuestas["volumen"], min:0, obligatorio:1)) {
            $aErrores["volumen"] = $error; // Si da error se lo pasamos a el array de errores
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
                <label class="tituloCampo">Tres codigos separados por coma (XXX,YYY,ZZZ):</label>
                <!-- Ponemos los valores del array respuesta para que el usuario no tenga que escribirlo de nuevo en caso de error -->
                <input type="text" name="codigo" value="<?= $entradaOK ? "" : $aRespuestas['codigo'] ?>" obligatorio>
                <!-- Si ha habido un error lo muestra -->
                <span class="errorCampo"><?= $aErrores['codigo'] ?></span>
            </div>
            <br>

            <div>
                <label class="tituloCampo">Descripcion:</label>
                <input type="text" name="descripcion" value="<?= $entradaOK ? "" : $aRespuestas['descripcion'] ?>" obligatorio>
                <span class="errorCampo"><?= $aErrores['descripcion'] ?></span>
            </div>
            <br>

            <div>
                <label class="tituloCampo">Fecha Creacion:</label>
                <!-- Ponemos los valores del array respuesta para que el usuario no tenga que escribirlo de nuevo en caso de error, y si ya se ha procesado lo eliminamos -->
                <input type="datetime-local" name="fechaCreacion" value="<?= (new DateTime)->format('Y-m-d\TH:i') ?>" disabled> <!-- El atributo `disabled` hace que no se envie el dato al servidor -->
                <span class="errorCampo"></span>
            </div>
            <br>

            <div>
                <label class="tituloCampo">Volumen:</label>
                <input type="number" step="0.01" name="volumen" value="<?= $entradaOK ? "" : $aRespuestas['volumen'] ?>" obligatorio>
                <span class="errorCampo"><?= $aErrores['volumen'] ?></span>
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
                $miDB = new PDO(DSN, DBUser, DBPass);

                if ($entradaOK) { // Si no hubieron errores con los datos
                    // Abrimos otro try/catch para en caso de error con la transaccion poder mostrar igualmente la tabla
                    try {
                        // Variable en formato heredoc con la sentencia SQL con los parametros necesarios
                        $statement = <<<EOT
                        INSERT INTO T02_Departamento VALUES(
                            :codigo,
                            :descripcion,
                            NOW(),
                            :volumen,
                            NULL
                        );
                        EOT;

                        // Preparamos una sentencia
                        $consulta = $miDB->prepare($statement);
                        
                        // Insertamos los departamentos
                        foreach ($aDepartamentosNuevos as $key => $value) {
                            // Creamos un array con los parametros y los valores que deverian llevar
                            $parametros = [
                                ":codigo" => $aDepartamentosNuevos[$key],
                                ":descripcion" => $aRespuestas["descripcion"],
                                ":volumen" => $aRespuestas["volumen"]
                            ];
                            
                            // Ejecuta la sentencia de insercion insertandole los parametros creados antes
                            $consulta->execute($parametros);
                        }
                    } catch (PDOException $error) { // Esto se ejecuta si da error algun execute
                        echo "<h3 class=\"error\">ERROR EN LA TRANSACCION SQL:</h3>";
                        echo "<p class=\"error\"><strong>Mensaje:</strong> ".$error->getMessage()."</p>";
                        echo "<p class=\"error\"><strong>Codigo:</strong> ".$error->getCode()."</p>";
                    }
                }
                
                // Array con el nombre de las columnas que vamos a seleccionar
                $aColumnas = [
                    "Codigo" => "T02_CodDepartamento",
                    "Descripcion" => "T02_DescDepartamento",
                    "Volumen" => "T02_VolumenDeNegocio",
                    "FechaCreacion" => "T02_FechaCreacionDepartamento",
                    "FechaBaja" => "T02_FechaBajaDepartamento"
                ];

                // Preparamos la consulta
                $consulta = $miDB->prepare("SELECT ".implode(",", $aColumnas)." FROM T02_Departamento ORDER BY T02_FechaCreacionDepartamento DESC");

                // Creamos un array con los parametros y los valores con los que se va a ejecutar
                $parametros = null;
                
                // Esto intenta crear una tabla con los resultados del query
                if ($consulta -> execute($parametros)) { // Si el query se ejecuta correctamente
                    echo "<table>";
                    

                    echo "<thead><tr>";

                    // Contamos cuantas columnas tiene la tabla sacada por el query y la recorremos
                    foreach ($aColumnas as $col) {
                        // Ponemos el nombre de la columna en la tabla html
                        echo "<th>{$col}</th>";
                    }
                    echo "</tr></thead>";
                    
                    // Obtiene los registros que ha obtenido el query
                    while ($registro = $consulta -> fetchObject()) { // Mientras haya mas registros
                        echo "<tr>";
                        // Mete cada registro en la tabla
                        foreach ($aColumnas as $col) {
                            $valor = $registro->$col;

                            if ($col == $aColumnas["Volumen"]) {
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
            } finally {
                unset($miDB);
            }
            echo "</div>";
        ?>
</body>
</html>