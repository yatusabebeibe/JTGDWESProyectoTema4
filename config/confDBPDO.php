<?php

/*  Constantes para la conexión con la DB.
    Existen tanto `define()` como `const` y se pueden usar igual en la mayoría de casos.
    En esta pagina web explican las diferencias y en que casos se usa uno u otro:
        https://mclibre.org/consultar/php/lecciones/php-constantes.html
*/

const DBHost = "10.199.11.252"; // desarrollo
// const DBHost = "localhost"; // explotación

// admin
const DBUserRoot = "adminsql";
const DBPassRoot = "paso"; // desarrollo
// const DBPassRoot = "!Paso1234x"; // explotación
const DSNRoot = "mysql:host=".DBHost;

// usuario
const DBName = "DBJTGDWESProyectoTema5";
const DBUser = "userJTGDWESProyectoTema5";
const DBPass = "paso"; // desarrollo
// const DBPass = "!Paso1234x"; // explotación
const DSN = "mysql:host=".DBHost.";dbname=".DBName;

// Array con el nombre de las columnas que vamos a seleccionar
const aColumnas = [
    "Codigo" => "T02_CodDepartamento",
    "Descripcion" => "T02_DescDepartamento",
    "Volumen" => "T02_VolumenDeNegocio",
    "FechaCreacion" => "T02_FechaCreacionDepartamento",
    "FechaBaja" => "T02_FechaBajaDepartamento"
];