<?php

/** Cargamos la configuración de conexión con DB
 *  Tenemos que usar dirname(__FILE__) para empezar desde la ruta del archivo actual.
 *  Si no, al llamar a este archivo desde otro archivo utilizaría la ruta del otro
 *  archivo y podría no funcionar.
 *  IMPORTANTE: poner '/' al principio del string con la ruta.
 */ 
require_once(dirname(__FILE__) . "/../config/confDBPDO.php");

try {
    // Iniciamos la conexión
    $conexionPDO = new PDO(DSNRoot, DBUserRoot, DBPass);

    /** Cargamos el archivo SQL que queremos ejecutar.
     *  Tenemos que usar dirname(__FILE__) para empezar desde la ruta del archivo actual.
     *  Si no, al llamar a este archivo desde otro archivo utilizaría la ruta del otro
     *  archivo y podría no funcionar.
     *  IMPORTANTE: poner '/' al principio del string con la ruta.
     */ 
    $sql = file_get_contents(dirname(__FILE__) . "/../scriptDB/CreaDBJTGDWESProyectoTema4.sql");

    $consulta = $conexionPDO->prepare($sql);

    // Ejecutamos el script SQL del archivo
    $consulta->execute(null);

    // Mensaje de funcionamiento correcto
    echo "Creacion correcta. ";

} catch (PDOException $error) { // Esto es lo que ocurre si salta un error
    $conexionPDO=null;
    echo '<p class="error"><strong>Mensaje:</strong> '.$error->getMessage()."</p>";
    echo '<p class="error"><strong>Codigo:</strong> '.$error->getCode()."</p>";
}