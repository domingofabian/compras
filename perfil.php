<?php
require_once 'php/conexion.php';
session_start();

// Función para obtener la URL de la imagen de la medalla
function obtenerMedalla($posicion) {
    $medalla = '';

    switch ($posicion) {
        case 1:
            $medalla = 'img/rihno_gold.png';
            break;
        case 2:
            $medalla = 'img/rihno_silver.png';
            break;
        case 3:
            $medalla = 'img/rhino_bronze.png';
            break;
        default:
            $medalla = 'img/rhino_bronze.png';
            break;
    }

    return $medalla;
}

if (isset($_SESSION['usuario_id'])) {
    // Obtener el ID del usuario que inició sesión
    $usuarioId = $_SESSION['usuario_id'];

    // Obtener el nombre de usuario actual
    $obtenerUsuarioQuery = "SELECT usuario FROM usuarios WHERE usuario_id = $usuarioId";
    $resultadoUsuario = $conexion->query($obtenerUsuarioQuery);

    $nombreUsuario = "";
    if ($resultadoUsuario) {
        $filaUsuario = $resultadoUsuario->fetch_assoc();
        if ($filaUsuario) {
            $nombreUsuario = $filaUsuario['usuario'];
        }
    } else {
        echo "Error al obtener el nombre de usuario: " . $conexion->error;
        exit();
    }

    // Actualizar el nombre de usuario
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_usuario'])) {
        $nuevoNombreUsuario = $_POST['nuevo_usuario'];
        $actualizarUsuarioQuery = "UPDATE usuarios SET usuario = '$nuevoNombreUsuario' WHERE usuario_id = $usuarioId";
        if ($conexion->query($actualizarUsuarioQuery) === false) {
            echo "Error al actualizar el nombre de usuario: " . $conexion->error;
        } else {
            // Actualizar el nombre de usuario en la sesión
            $_SESSION['nombre_usuario'] = $nuevoNombreUsuario;
            header("Location: perfil.php");
            exit();
        }
    }

    // Obtener los resultados de los alumnos creados por el usuario, ordenados por puntos
    $obtenerResultadosAlumnosQuery = "SELECT * FROM alumnos_resultado WHERE usuario_id = $usuarioId ORDER BY puntos DESC";
    $resultadoResultadosAlumnos = $conexion->query($obtenerResultadosAlumnosQuery);
} else {
    echo "Usuario no identificado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="css/crud.css?v=<?php echo time(); ?>">
</head>
<body>
    <div>
        <?php include 'php/header_user_perfil.php'; ?>
        <video muted autoplay loop>
            <source src="img/video.mp4" type="video/mp4">
        </video>
        <div class="capa"></div>
    </div>

    <div class="caja">
    <section id="intro_perfil">
    <!--<div id="cajon_torneo">
            <h1>Perfil</h1>
            <br>
            <div id="box_torneo">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <label for="nuevo_usuario">Nuevo nombre de usuario:</label>
                    <input type="text" name="nuevo_usuario" required>
                    <input type="submit" name="actualizar_usuario" value="Actualizar">
                </form>
            </div>
        </div>-->
        <div id="cajon_torneo">
            <h1>Resultados recientes</h1>
            <br>
            <div id="box_result">
                <table class="tabla-clasificacion">
                        <?php
                        if ($resultadoResultadosAlumnos->num_rows > 0) {
                            echo    
                                    '<tr>
                                    <th><h2>Posición</h2></th>
                                    <th><h2>Medalla</h2></th>
                                    <th><h2>Nombre</h2></th>
                                    <th><h2>Edad</h2></th>
                                    <th><h2>Puntaje</h2></th>
                                    </tr>';
                            $posicion = 1;
                            while ($fila = $resultadoResultadosAlumnos->fetch_assoc()) {
                

                                echo "<tr>";
                                echo "<td><p>$posicion</p></td>";
                                echo "<td><img class='medalla' src='" . obtenerMedalla($posicion) . "' alt='Medalla'></td>";
                                echo "<td><p>" . $fila['nombre'] . "</p></td>";
                                echo "<td><p>" . $fila['edad'] . "</p></td>";
                                echo "<td><p>" . $fila['puntos'] . "</p></td>";
                                echo "</tr>";
                                $posicion++;
                            }
                        } else {
                            echo '<tr><td colspan="5">Aún no hay resultados para mostrar.</td></tr>';
                        }
                        ?>
                </table>
            </div>
        </div>
    </section>
    </div>

    <?php include 'php/footer.php'; ?>
    
    <div id="loader">
        <i class="spinner"></i>
    </div>
    <script src="js/recarga.js"></script>
</body>
</html>
