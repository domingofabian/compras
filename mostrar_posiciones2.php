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

// Obtener el ID del usuario que inició sesión
$usuarioId = $_SESSION['usuario_id'];

// Obtener los resultados de los alumnos creados por el usuario, ordenados por puntos
$obtenerResultadosAlumnosQuery = "SELECT * FROM alumnos_resultado WHERE usuario_id = $usuarioId ORDER BY puntos DESC";
$resultadoResultadosAlumnos = $conexion->query($obtenerResultadosAlumnosQuery);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clasificación de Alumnos</title>
    <link rel="stylesheet" href="css/crud.css?v=<?php echo time(); ?>">
</head>
<body>
    <div>
        <?php include 'php/header_user_round.php'; ?>
        <video muted autoplay loop>
            <source src="img/video.mp4" type="video/mp4">
        </video>
        <div class="capa"></div>
    </div>

    <div class="caja">
    <section id="intro_login">
        <div id="cajon_torneo">
            <h1>Clasificación de Alumnos</h1>
            <p>
                <br>
                ¡El torneo ha finalizado exitosamente! Puedes encontrar este resultado en tu perfil.
                <br>
                <br>
            </p>
            <div id="box_result">
                <table class="tabla-clasificacion">
                        <tr>
                            <th><h2>Posición</h2></th>
                            <th><h2>Medalla</h2></th>
                            <th><h2>Nombre</h2></th>
                            <th><h2>Edad</h2></th>
                            <th><h2>Puntaje</h2></th>
                        </tr>
                        <?php
                        if ($resultadoResultadosAlumnos->num_rows > 0) {
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
                            echo '<tr><td colspan="5">No se han encontrado alumnos.</td></tr>';
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
