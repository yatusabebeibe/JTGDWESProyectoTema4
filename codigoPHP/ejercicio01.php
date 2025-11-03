<?php

    /*  @author Jesús Temprano Gallego
     *  @since 03/11/2025
     */

    echo "<h1>Conexión a la base de datos.</h1>";

    const HOST = "10.199.10.22";
    const DBName = "DBJTGDWESProyectoTema4";

    const DSN = "mysql:host=".HOST.";dbname=".DBName;
    const DBUserName = "userJTGDWESProyectoTema4";
    const DBPassword = "paso";

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

    // echo "<br><br>";

    echo "<h2>Ejemplo Error:</h2>";
    try {
        $miDB = new PDO(DSN, DBUserName, "Contraseña Incorrecta");
        echo "";
    } catch (PDOException $th) {
        echo "Mensaje de error: ".$th->getMessage()."<br>";
        echo "Codigo de error: ".$th->getCode()."<br>"; // Listado con todos los codigos de error: https://dev.mysql.com/doc/mysql-errors/8.0/en/server-error-reference.html
    }
    
    echo "<h2>Ejemplo Bien:</h2>";
    try {
        $miDB = new PDO(DSN, DBUserName, DBPassword);
        
        foreach ($atributos as $atributo) {
            try {
                $valor = $miDB->getAttribute(constant("PDO::ATTR_$atributo"));
                echo "<b>Atributo 'ATTR_{$atributo}': </b>".$valor."<br><br>";
            } catch (PDOException $e) {
                echo "<b>Atributo 'ATTR_{$atributo}': </b>No soportado<br><br>";
            }
        }
    } catch (PDOException $th) {
        echo "Mensaje: ".$th->getMessage()."<br>";
        echo "Codigo: ".$th->getCode()."<br>";
    }
    
?>