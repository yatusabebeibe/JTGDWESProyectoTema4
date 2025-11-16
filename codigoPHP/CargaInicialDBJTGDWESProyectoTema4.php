<?php

/** Cargamos la configuración de conexión con DB
 *  Tenemos que usar dirname(__FILE__) para empezar desde la ruta del archivo actual.
 *  Si no, al llamar a este archivo desde otro archivo utilizaría la ruta del otro
 *  archivo y podría no funcionar.
 *  IMPORTANTE: poner '/' al principio del string con la ruta.
 */ 
$config = parse_ini_file(dirname(__FILE__) . "/../config/DB.ini");

if ($config) { // Comprueba que la configuración a sido cargada correctamente
    try {
        // Iniciamos la conexión
        $conexionPDO = new PDO(
            "mysql:host={$config['db_host']};dbname={$config['db_name_t4']}",
            $config["db_user_root"],
            $config["db_pass_root"]
        );

        $query = $conexionPDO->query("SELECT * FROM T02_Departamento");

        if ($query->rowCount() == 0) {
            /** Cargamos el archivo SQL que queremos ejecutar.
             *  Tenemos que usar dirname(__FILE__) para empezar desde la ruta del archivo actual.
             *  Si no, al llamar a este archivo desde otro archivo utilizaría la ruta del otro
             *  archivo y podría no funcionar.
             *  IMPORTANTE: poner '/' al principio del string con la ruta.
             */ 
            $sql = file_get_contents(dirname(__FILE__) . "/../scriptDB/CargaInicialDBJTGDWESProyectoTema4.sql");

            // Ejecutamos el script SQL del archivo
            $conexionPDO->exec($sql);

            // Mensaje de funcionamiento correcto
            echo "Carga inicial correcta. ";

        } else { // Si devuelve algo, es que ya se ha cargado
            echo "Error Carga: ya existen datos. ";
        }
    } catch (PDOException $error) { // Esto es lo que ocurre si salta un error
        echo '<p class="error"><strong>Mensaje:</strong> '.$error->getMessage()."</p>";
        echo '<p class="error"><strong>Codigo:</strong> '.$error->getCode()."</p>";
    }
} else {
    echo "Error: no se pudo cargar la configuracion";
}