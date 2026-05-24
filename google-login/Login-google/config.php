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

// Asignar la URL de redirección de forma dinámica
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];

if ($host === 'localhost:8080') {
    // Entorno Docker local
    $redirect_uri = 'http://localhost:8080/google-login/Login-google/index.php';
} elseif (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
    // Entorno local XAMPP
    $redirect_uri = 'http://localhost/compras%20feb26/google-login/Login-google/index.php';
} else {
    // Entorno de producción (Railway, VPS, etc.)
    $redirect_uri = $protocol . $host . '/google-login/Login-google/index.php';
}

$google_client->setRedirectUri($redirect_uri);

// to get the email and profile 
$google_client->addScope('email');
$google_client->addScope('profile');

?>