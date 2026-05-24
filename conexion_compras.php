<?php


//$host = "sql202.infinityfree.com";
//$usuario = "if0_37745141";
//$clave = "RKCBhRN2oBcE88";


$host = getenv('DB_HOST') !== false ? getenv('DB_HOST') : "127.0.0.1";
$usuario = getenv('DB_USER') !== false ? getenv('DB_USER') : "root";
$clave = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : "";
$bd = getenv('DB_DATABASE') !== false ? getenv('DB_DATABASE') : "if0_37745141_domingo";


error_reporting(0);

// datos de conexión de la base de datos
$link = mysqli_connect($host, $usuario, $clave, $bd);


// URL de la aplicación
define("URL_BASE", "http://" . $_SERVER['SERVER_NAME'] . "/");

// fechas y horas

$hoy = date("Y-m-d");

?>