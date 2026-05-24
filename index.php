<?php
session_start();
include("conexion_compras.php");

// Si no tiene sesión de Google, redirigir al login
if (!isset($_SESSION['access_token']) || !isset($_SESSION['user_email_address'])) {
    header('Location: google-login/Login-google/index.php');
    exit();
}

// Obtener el correo del usuario logueado
$correo = mysqli_real_escape_string($link, $_SESSION['user_email_address']);

// Verificar si el correo está en la tabla permitidos
$sql = "SELECT * FROM permitidos WHERE correo = '$correo'";
$resultado = mysqli_query($link, $sql);

if (mysqli_num_rows($resultado) > 0) {
    // Usuario permitido → redirigir a todos.php
    header('Location: COMPRAS/todos.php');
    exit();
} else {
    // Usuario NO permitido → mostrar mensaje de acceso denegado
    ?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Acceso Denegado</title>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Inter', sans-serif;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
                color: #fff;
            }

            .card {
                background: rgba(255, 255, 255, 0.08);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.15);
                border-radius: 20px;
                padding: 40px 36px;
                max-width: 420px;
                width: 90%;
                text-align: center;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            }

            .icon {
                font-size: 56px;
                margin-bottom: 16px;
            }

            h1 {
                font-size: 22px;
                font-weight: 700;
                margin-bottom: 12px;
                color: #ff6b6b;
            }

            p {
                font-size: 14px;
                line-height: 1.6;
                color: rgba(255, 255, 255, 0.7);
                margin-bottom: 8px;
            }

            .email {
                display: inline-block;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 8px;
                padding: 8px 16px;
                font-size: 13px;
                color: #a0d2ff;
                margin: 12px 0 20px;
                word-break: break-all;
            }

            .btn-logout {
                display: inline-block;
                padding: 12px 32px;
                background: linear-gradient(135deg, #667eea, #764ba2);
                color: #fff;
                text-decoration: none;
                border-radius: 10px;
                font-weight: 600;
                font-size: 14px;
                transition: transform 0.2s, box-shadow 0.2s;
            }

            .btn-logout:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            }
        </style>
    </head>

    <body>
        <div class="card">
            <div class="icon">🔒</div>
            <h1>Acceso Denegado</h1>
            <p>Tu cuenta no tiene permisos para acceder a esta aplicación.</p>
            <div class="email"><?php echo htmlspecialchars($_SESSION['user_email_address']); ?></div>
            <p>Contacta al administrador para solicitar acceso.</p>
            <a href="google-login/Login-google/logout.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </body>

        </html>
        <?php
}
?>