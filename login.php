<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="css/login.css?v=<?php echo time(); ?>">
</head>
<body>
    <div>
        <?php include 'php/header_login.php'; ?>
        <video muted autoplay loop>
            <source src="img/video.mp4" type="video/mp4">
        </video>
        <div class="capa"></div>
    </div>  
    <div class="caja">
        <section id="intro_login">
            <div id="cajon_login">
                <div id="box_datos">
                    <h1>Iniciar Sesión</h1>
                    <form action="login.php" method="POST" id="form_login">
                        <h3>Usuario:</h3>
                        <input type="text" name="usuario"><br>
                        <h3>Contraseña:</h3>
                        <input type="password" name="contraseña"><br>
                        <input type="submit" value="Iniciar Sesión" id="iniciar">
                    </form>
                    <?php
                    session_start();
                    require_once 'ORM/conexion.php';
                    require_once 'ORM/usuariosORM.php';

                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $usuario = $_POST['usuario'];
                        $contraseña = $_POST['contraseña'];
                
                        // Crear una instancia del modelo de Usuario
                        try {
                            $conexion = new PDO("mysql:host={$host};dbname={$dbname}", $username, $password);
                            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                            $usuarioModel = new Usuario($conexion); // Asegúrate de que $conexion sea un objeto PDO
                
                            // Realizar una consulta para obtener el usuario por nombre
                            $resultado = $usuarioModel->getUserByName($usuario);
                
                            if (!empty($resultado)) {
                                if (password_verify($contraseña, $resultado['contraseña'])) {
                                    $_SESSION['usuario'] = $usuario;
                                    $_SESSION['usuario_id'] = $resultado['usuario_id'];
                
                                    header("Location: crud.php");
                                } else {
                                    echo "Contraseña incorrecta.";
                                }
                            } else {
                                echo "Usuario no encontrado.";
                            }
                        } catch (PDOException $e) {
                            echo "Error de conexión: " . $e->getMessage();
                        } finally {
                            $conexion = null; // Cerrar la conexión al finalizar
                        }
                    }

                    header("Location: crud.php");
                ?>
                    <p>¿No tenes cuenta? <a href="registro.php">Registrate aquí</a></p>
                </div>
                <div id="box_info">
                    <p>Inicia sesión para comenzar a crear tu torneo. Registrate si no tienes una cuenta.</p>
                    <img src="img/logo.png" alt="Jorgito">
                </div>
            </div>
        </section>
        <section id="centrar_orden_box1">
            <div id="orden_box1">
            </div>
        </section>
        
        <div id="sec1">
        </div>
        
    </div>
    <footer id="pie">
        <div>
            <p>Texto ejemplo</p>
            <p>Texto ejemplo</p>
            <p>Texto ejemplo</p>
        </div>
        <div>
            <p>Texto ejemplo</p>
            <p>Texto ejemplo</p>
            <p>Texto ejemplo</p>
        </div>
    </footer>
</body>
</html>