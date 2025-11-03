<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Jesús Temprano Gallego</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        header {
            background: #4e9645;
            color: white;
            padding: 15px;
            text-align: center;
        }
        h1, h3, p {
            margin: 0;
        }
        main {
            max-width: 1250px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
            margin-top: 0px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-radius: 8px;
            transition: 0.3s;
        }
        th {
            background-color: #4e9645;
            color: white;
        }
        td {
            background: #ecf0f1;
        }
        tr:hover td {
            background: #d6f8d6;
        }
        ul {
            margin: 0;
            list-style: none;
            padding: 0;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 25px;
        }
        li {
            background: #ecf0f1;
            margin: 10px 0;
            padding: 15px;
            border-left: 5px solid #34db34;
            transition: 0.3s;
            border-radius: 8px;
        }
        li:hover {
            background: #d6f8d6;
            border-left: 5px solid #70bc1a;
            transform: scale(1.03);
        }
        a {
            text-decoration: none;
            color: #0077cc;
        }
        a:hover {
            color: #005fa3;
        }
        footer {
            padding-top: 25px;
            margin: auto;
            background-color: #459650;
            text-align: center;
            height: 150px;
            color: white;
            
            a {
                text-decoration: aquamarine wavy underline;
                color: aquamarine;
                transition: 0.3s;
            }
            a:hover {
                color: blue;
                mix-blend-mode: multiply;
                text-decoration: none;
            }
        }
        tr > td > a {line-height: 14px;}
        table > * > tr > *  {text-align: center;}
        table > * > tr > *:nth-child(2)  {text-align: left;}
    </style>
</head>
<body>
    <!-- 😼 -->
    <header>
        <h1>CFGS - Desarrollo de Aplicaciones Web</h1>
        <h3>Jesús Temprano Gallego</h3>
        <p>Curso 2025/2026 - Grupo DAW2</p>
    </header>
    <!-- 😼 -->
    <main>
        <ul>
            <li><a href="">Borrado</a></li>
            <li><a href="">Creacion</a></li>
            <li><a href="">Carga Inicial</a></li>
        </ul>
        <table>
            <thead>
                <tr>
                    <th style="width: 50px; max-width: 50px;">Nº Ej</th>
                    <th style="width: calc(100% - 50px - 145px*2);">Ejercicio</th>
                    <th style="width: 145px; max-width: 145px;">PDO</th>
                    <th style="width: 145px; max-width: 145px;">MySQLi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Conexión a la base de datos con la cuenta usuario y tratamiento de errores. <i>Utilizar excepciones automáticas siempre que sea posible en todos los ejercicios.</i></td>
                    <td>
                        <a href="./codigoPHP/ejercicio01.php" target="_self"><!-- Ejecutar --></a>
                        <a href="./mostrarcodigo/muestraEjercicio01.php" target="_self"><!-- Ver código --></a>
                    </td>
                    <td>
                        <a href="./codigoPHP/ejercicio01.php" target="_self"><!-- Ejecutar --></a>
                        <a href="./mostrarcodigo/muestraEjercicio01.php" target="_self"><!-- Ver código --></a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Mostrar el contenido de la tabla Departamento y el número de registros.</td>
                    <td>
                        <a href="./codigoPHP/ejercicio02.php" target="_self"><!-- Ejecutar --></a>
                        <a href="./mostrarcodigo/muestraEjercicio02.php" target="_self"><!-- Ver código --></a>
                    </td>
                    <td>
                        <a href="./codigoPHP/ejercicio02.php" target="_self"><!-- Ejecutar --></a>
                        <a href="./mostrarcodigo/muestraEjercicio02.php" target="_self"><!-- Ver código --></a>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Formulario para añadir un departamento a la tabla Departamento con validación de entrada y control de errores.</td>
                    <td>
                        <a href="./codigoPHP/ejercicio03.php" target="_self"><!-- Ejecutar --></a>
                        <a href="./mostrarcodigo/muestraEjercicio03.php" target="_self"><!-- Ver código --></a>
                    </td>
                    <td>
                        <a href="./codigoPHP/ejercicio03.php" target="_self"><!-- Ejecutar --></a>
                        <a href="./mostrarcodigo/muestraEjercicio03.php" target="_self"><!-- Ver código --></a>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Formulario de búsqueda de departamentos por descripción (por una parte del campo DescDepartamento, si el usuario no pone nada deben aparecer todos los departamentos).</td>
                    <td>
                        <a href="./codigoPHP/ejercicio04.php" target="_self"><!-- Ejecutar --></a>
                        <a href="./mostrarcodigo/muestraEjercicio04.php" target="_self"><!-- Ver código --></a>
                    </td>
                    <td>
                        <a href="./codigoPHP/ejercicio04.php" target="_self"><!-- Ejecutar --></a>
                        <a href="./mostrarcodigo/muestraEjercicio04.php" target="_self"><!-- Ver código --></a>
                    </td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Pagina web que añade tres registros a nuestra tabla Departamento utilizando tres instrucciones insert y una transacción, de tal forma que se añadan los tres registros o no se añada ninguno.</td>
                    <td>
                        <a href="./codigoPHP/ejercicio05.php" target="_self"><!-- Ejecutar --></a>
                        <a href="./mostrarcodigo/muestraEjercicio05.php" target="_self"><!-- Ver código --></a>
                    </td>
                    <td>
                        <a href="./codigoPHP/ejercicio05.php" target="_self"><!-- Ejecutar --></a>
                        <a href="./mostrarcodigo/muestraEjercicio05.php" target="_self"><!-- Ver código --></a>
                    </td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>Pagina web que cargue registros en la tabla Departamento desde un array departamentosnuevos utilizando una consulta preparada. <i>(Después de programar y entender este ejercicio, modificar los ejercicios anteriores para que utilicen consultas preparadas). Probar consultas preparadas sin bind, pasando los parámetros en un array a execute.</i></td>
                    <td>
                        <a href="./codigoPHP/ejercicio06.php" target="_self"><!-- Ejecutar --></a>
                        <a href="./mostrarcodigo/muestraEjercicio06.php" target="_self"><!-- Ver código --></a>
                    </td>
                    <td>
                        <a href="./codigoPHP/ejercicio06.php" target="_self"><!-- Ejecutar --></a>
                        <a href="./mostrarcodigo/muestraEjercicio06.php" target="_self"><!-- Ver código --></a>
                    </td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>Página web que toma datos (código y descripción) de un fichero xml y los añade a la tabla Departamento de nuestra base de datos. (IMPORTAR). El fichero importado se encuentra en el directorio .../tmp/ del servidor.</td>
                    <td>
                        <a href="./codigoPHP/ejercicio07.php" target="_self"><!-- Ejecutar --></a>
                        <a href="./mostrarcodigo/muestraEjercicio07.php" target="_self"><!-- Ver código --></a>
                    </td>
                    <td>
                        <a href="./codigoPHP/ejercicio07.php" target="_self"><!-- Ejecutar --></a>
                        <a href="./mostrarcodigo/muestraEjercicio07.php" target="_self"><!-- Ver código --></a>
                    </td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>Página web que toma datos (código y descripción) de la tabla Departamento y guarda en un fichero departamento.xml. (COPIA DE SEGURIDAD / EXPORTAR). El fichero exportado se encuentra en el directorio .../tmp/ del servidor.</td>
                    <td>
                        <a href="./codigoPHP/ejercicio08.php" target="_self"><!-- Ejecutar --></a>
                        <a href="./mostrarcodigo/muestraEjercicio08.php" target="_self"><!-- Ver código --></a>
                    </td>
                    <td>
                        <a href="./codigoPHP/ejercicio08.php" target="_self"><!-- Ejecutar --></a>
                        <a href="./mostrarcodigo/muestraEjercicio08.php" target="_self"><!-- Ver código --></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </main>
    <!-- 😼 -->
    <footer><a href="../../" target="_self">Jesús Temprano Gallego</a> | 30/10/2025</footer>
    <!-- 😼 -->
    <!-- muxixima glasia alvelto pol el marivilliosiximo achetemeele que te paxo chatgepete -->
</body>
</html>
