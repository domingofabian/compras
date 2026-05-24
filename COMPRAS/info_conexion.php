<?php

include ("conexion.php");

// BASE DE DATOS EN AWS
//$host = "databasedassoluciones.cd8gm4ksairs.us-east-2.rds.amazonaws.com";
//$usuario = "pumba39366";
//$clave = "Zxc123++++";
//$bd ="db001";

// BASE DE DATOSLOCAL
//$host = "127.0.0.1";
//$usuario = "root";
//$clave = "vertrigo";
//$bd ="presupuesto";


header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('content-type: application/json; charset=utf-8');

/*
url:         databasedassoluciones.cd8gm4ksairs.us-east-2.rds.amazonaws.com
usuario:     pumba39366
contraseña:  Zxc123++++
BD: db001



crear apinrest simple
https://code.tutsplus.com/es/how-to-build-a-simple-rest-api-in-php--cms-37000t
*/
function xtabla ($tabla, $enlace){

    $result = mysqli_query($enlace, "SELECT * from $tabla");
    $rows = mysqli_fetch_all($result); // list arrays with values only in rows
    // or
    //$rows = mysqli_fetch_all($result, MYSQLI_ASSOC); // assoc arrays in rows

return json_encode($rows);
}

define("DB_HOST", "localhost");
define("DB_USERNAME", "demo");
define("DB_PASSWORD", "demo");
define("DB_DATABASE_NAME", "rest_api_demo");

$enlace = mysqli_connect( $host , $usuario, $clave, $bd ) ;

if (!$enlace) {
    echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
    echo "errno de depuracion: " . mysqli_connect_errno() . PHP_EOL;
    echo "error de depuracion: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

$info = mysqli_get_host_info($enlace) . PHP_EOL;
//echo "<p>Exito: Se realizo una conexion apropiada a MySQL! <p>La base de datos $bd es genial." . PHP_EOL;
//echo "<p>Informacion del host: " . mysqli_get_host_info($enlace) . PHP_EOL;


// listar todas las tablas
$sql = "SHOW TABLES FROM $bd";
$resultado = mysqli_query ($enlace, $sql);

if (!$resultado) {
    echo "<p>Error de BD, no se pudieron listar las tablas\n";
    exit;
}

while ($fila = mysqli_fetch_row($resultado)) {  $tablas[] = $fila[0];}
mysqli_free_result($resultado);


$Data["Informacion del Host"]= $info;
$Data["Base de Datos"]= $bd;

// recorre arreglo de tablas
foreach ($tablas as $celta => $registro){
   

    $Data["Tablas"][$registro] = $registro;
    //xtabla($registro, $enlace);



}

echo json_encode($Data);
mysqli_close($enlace);
?>