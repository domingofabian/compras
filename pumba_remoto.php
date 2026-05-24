<?php

// ===========================================
// Conexión REMOTA a la base de datos Docker
// ===========================================
// Usar estas credenciales para conectarse 
// desde FUERA del contenedor Docker.
//
// Host: IP de la máquina donde corre Docker
// Puerto: 3307 (mapeado al 3306 interno)
// ===========================================

$host = "db";              // Dentro de Docker usar "db" (nombre del servicio)
// Desde fuera usar la IP del servidor o "localhost"
$usuario = "Denise123++";
$clave = "Qaz456++++";
$bd = "if0_37745141_domingo";

// Puerto externo: 3307 (para conexión remota desde fuera de Docker)
// Puerto interno: 3306 (para conexión entre contenedores)

// environment:
//      - DB_HOST=db
//      - DB_DATABASE=if0_37745141_domingo
//      - DB_USER=Denise123++
//      - DB_PASSWORD=Qaz456++++


error_reporting(0);

$link = mysqli_connect($host, $usuario, $clave, $bd);

if (!$link) {
    die("Error de conexión remota: " . mysqli_connect_error());
}

// URL de la aplicación
define("URL_BASE", "http://" . $_SERVER['SERVER_NAME'] . "/");

$hoy = date("Y-m-d");

?>