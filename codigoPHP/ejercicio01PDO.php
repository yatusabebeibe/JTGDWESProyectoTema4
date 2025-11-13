<?php

    /*  @author Jesús Temprano Gallego
     *  @since 03/11/2025
     */

    echo "<h1>Conexión a la base de datos.</h1>";

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

    // Lista con los atributos que vamos a consultar.
    $atributos = array(
        "AUTOCOMMIT",
        "ERRMODE",
        "CASE",
        "CLIENT_VERSION",
        "CONNECTION_STATUS",
        "ORACLE_NULLS",
        "PERSISTENT",
        "PREFETCH",
        "SERVER_INFO",
        "SERVER_VERSION",
        "TIMEOUT",
        "DEFAULT_FETCH_MODE"
    );

    /*  Demostracion de intento de conexión con contraseña incorrecta
        y captura de la excepción para mostrar mensaje y código del error.
        Para ver el manejo de errores al crear el objeto PDO.
    */
    echo "<h2>Ejemplo Error:</h2>";
    try {
        // Hacemos la conexion a la base de datos
        $miDB = new PDO(DSN, DBUserName, "Contraseña Incorrecta");

    } catch (PDOException $error) { // Si la connexion tira un error `PDOException` ejecuta este codigo:
        
        // Mensaje del error mas facil de leer:
        echo "Mensaje de error: ".$error->getMessage()."<br>";
        
        /*  Codigo del error.
            Listado para ver que significa cada error:
               https://dev.mysql.com/doc/mysql-errors/8.0/en/server-error-reference.html
        */
        echo "Codigo de error: ".$error->getCode()."<br>";
    }
    
    // Demostración de conexión con credenciales correctas.
    echo "<h2>Ejemplo Bien:</h2>";
    try {
        $miDB = new PDO(DSN, DBUserName, DBPassword);
        
        // Recorremos la lista de atributos y consultamos su valor.
        foreach ($atributos as $atributo) {
            // Se utiliza un try/catch por cada atributo porque algunos son soportados.
            try {
                // constant("PDO::ATTR_$atributo"): convierte la cadena en la constante real de PDO.
                // $miDB->getAttribute(...): obtiene el valor actual del atributo en la conexión.
                $valor = $miDB->getAttribute(constant("PDO::ATTR_$atributo"));
                echo "<b>Atributo 'ATTR_{$atributo}': </b>".$valor."<br><br>";

            } catch (PDOException $error) { // Esto se ejecuta si da error al hacer `getAttribute()`
                echo "<b>Atributo 'ATTR_{$atributo}': </b>No soportado<br><br>";
            }
        }
    } catch (PDOException $error) { // Esto se ejecuta si da error al hacer `new PDO(...)`
        echo "Mensaje: ".$error->getMessage()."<br>";
        echo "Codigo: ".$error->getCode()."<br>";
    }
    
?>