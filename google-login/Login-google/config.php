<?php
error_reporting(E_ALL & ~E_DEPRECATED);
//start session on web page
session_start();

//config.php

//Include Google Client Library for PHP autoload file
require_once 'vendor/autoload.php';

//Make object of Google API Client for call Google API
$google_client = new Google_Client();

// Set credentials dynamically from environment or local gitignored secrets file
$client_id = getenv('GOOGLE_CLIENT_ID') !== false ? getenv('GOOGLE_CLIENT_ID') : '';
$client_secret = getenv('GOOGLE_CLIENT_SECRET') !== false ? getenv('GOOGLE_CLIENT_SECRET') : '';

if (empty($client_id) || empty($client_secret)) {
    if (file_exists(__DIR__ . '/secrets.php')) {
        include(__DIR__ . '/secrets.php');
    }
}

$google_client->setClientId($client_id);
$google_client->setClientSecret($client_secret);

// Asignar la URL de redirección exacta dependiendo de si estamos en Docker o en XAMPP
if ($_SERVER['HTTP_HOST'] === 'localhost:8080') {
    // Entorno Docker
    $redirect_uri = 'http://localhost:8080/google-login/Login-google/index.php';
} else {
    // Entorno local XAMPP (con el nombre de carpeta y %20 para evitar espacios inválidos)
    $redirect_uri = 'http://localhost/compras%20feb26/google-login/Login-google/index.php';
}

$google_client->setRedirectUri($redirect_uri);

// to get the email and profile 
$google_client->addScope('email');
$google_client->addScope('profile');

?>