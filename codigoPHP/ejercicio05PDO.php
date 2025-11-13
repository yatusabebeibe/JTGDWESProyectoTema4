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

    // Variable para obtener datos de la configuracion de la DB
    $config = parse_ini_file("../config/DB.ini");

    /*  Constantes para la connexion con la DB.
        Existen tanto `define()` como `const` se pueden usar igual en la mayoria de casos.
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
        $aRespuestas["codigo"] = $_REQUEST['codigo'];
        $aRespuestas["descripcion"] = $_REQUEST['descripcion'];
        $aRespuestas["volumen"] = $_REQUEST['volumen'];

        // Dividimos el string por sus comas con el explode() para obtener un array con los codigos
        $aCodigos = explode(",",$aRespuestas["codigo"]);
        foreach ($aCodigos as $key => $value) {
            // Le quitamos los espacios de delante y atras
            $aCodigos[$key] = trim($value);
        }
        // Eliminamos lo duplicados
        $aCodigos = array_unique($aCodigos);

        // Validamos todos los datos:

        // Comprueba que haya 3 codigos
        if (count($aCodigos) != 3) {
            $aErrores["codigo"] = "Tienes que poner 3 codigos diferentes";
        }
        else if (empty($aErrores["codigo"])) {
            // Comprobamos que cada codigo esta bien y se puede poner
            foreach ($aCodigos as $key => $value) {
                // Comprobamos que el codigo no este vacio y tenga exactamente 3 letras
                if ($error = validacionFormularios::comprobarAlfabetico($aRespuestas["codigo"], 3, 3, 1)) {
                    $aErrores["codigo"] = $error; // Si da error se lo pasamos a el array de errores
                }
                // Comprobamos que este en mayusculas
                else if ($aRespuestas["codigo"] !== strtoupper($_REQUEST['codigo'])) {
                    $aErrores["codigo"] = "El codigo tiene estar en mayusculas.";
                }
                // Comprobamos que no este ya en la base de datos
                else {
                    // Se utiliza un try/catch por si diera algun error en la conexion o query
                    try {
                        // Iniciamos la conexion con la base de datos
                        $miDB = new PDO(DSN, DBUserName, DBPassword);

                        // Hacemos una consulta para ver si ya esta usado el codigo
                        $query = $miDB->query("SELECT * FROM T02_Departamento WHERE T02_CodDepartamento = '{$aRespuestas["codigo"]}'");

                        if ($query->rowCount() >= 1) { // Si devuelve algo, es que el codigo ya esta usado
                            $aErrores["codigo"] = "El codigo ya esta siendo usado por otro departamento.";
                        }
                    } catch (PDOException $error) { // Esto se ejecuta si da error al crear la conexion o hacer la consulta
                        $aErrores["codigo"] = "Error de conexion: " . $error->getMessage();
                    }
                }
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
                <label class="tituloCampo">Tres codigos separados por coma (XXX,YYY, ZZZ):</label>
                <!-- Ponemos los valores del array respuesta para que el usuario no tenga que escribirlo de nuevo en caso de error -->
                <input type="text" name="codigo" value="<?= $aRespuestas['codigo'] ?>" obligatorio>
                <!-- Si ha habido un error lo muestra -->
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
                <!-- Ponemos el valor de la fecha para que aunque no la pueda modificar, el usuario sepa que existe -->
                <input type="datetime-local" name="fechaCreacion" value="<?= (new DateTime)->format('Y-m-d\TH:i') ?>" disabled> <!-- El atributo `disabled` hace que no se envie el dato al servidor -->
                <span class="errorCampo"></span>
            </div>
            <br>

            <div>
                <label class="tituloCampo">Volumen:</label>
                <input type="number" step="0.01" name="volumen" value="<?= $aRespuestas['volumen'] ?>" obligatorio>
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
                $miDB = new PDO(DSN, DBUserName, DBPassword);

                if ($entradaOK) { // Si no hubieron errores con los datos
                    // Abrimos otro try/catch para en caso de error con la transaccion poder mostrar igualmente la tabla
                    try {
                        // Empieza una transaccion
                        $miDB->beginTransaction();

                        // Insertamos los departamentos
                        foreach ($aCodigos as $key => $value) {
                            // Ejecuta la sentencia de insercion
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
                        // Si no ha habido ningun error se fijan los cambios en la base de datos
                        $miDB ->commit();
                    } catch (PDOException $error) { // Esto se ejecuta si da error algun exec
                        // Deshacemos los cambios antes de dejarlos fijos
                        $miDB->rollBack();
                        echo "<h3 class=\"error\">ERROR EN LA TRANSACCION SQL:</h3>";
                        echo "<p class=\"error\"><strong>Mensaje:</strong> ".$error->getMessage()."</p>";
                        echo "<p class=\"error\"><strong>Codigo:</strong> ".$error->getCode()."</p>";
                    }
                }
                
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
                echo '<p class="error"><strong>Codigo:</strong> '.$error->getCode()."</p>";
            }
            echo "</div>";
        ?>
</body>
</html>