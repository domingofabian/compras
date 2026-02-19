<?php
require_once 'php/conexion.php';

// Obtener el valor de ronda_id de la URL
$rondaId = $_GET['ronda_id'];

// Consulta SQL para obtener los enfrentamientos de la ronda actual
$obtenerEnfrentamientosMostrarQuery = "SELECT e.*, a1.nombre AS nombre_alumno1, a2.nombre AS nombre_alumno2,
                                       a1.puntos AS puntos_alumno1, a2.puntos AS puntos_alumno2
                                      FROM enfrentamientos e
                                      LEFT JOIN alumnos a1 ON e.alumno1_id = a1.alumno_id
                                      LEFT JOIN alumnos a2 ON e.alumno2_id = a2.alumno_id
                                      WHERE e.ronda_id = $rondaId";
$resultadoEnfrentamientosMostrar = $conexion->query($obtenerEnfrentamientosMostrarQuery);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados</title>
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
                <h1>Resultados</h1>
                <p> 
                    <br>Las rondas se determinaran con un sistema de puntaje, el jugador con mayor puntaje es el ganador de la ronda, los ganadores pelearán por el primer puesto y los perdedores por el tercer puesto.
                    <br>
                </p>
                <br>
                <div id="box_torneo">
                    <?php
                    while ($enfrentamientoMostrar = $resultadoEnfrentamientosMostrar->fetch_assoc()) {
                        // Cálculo de puntos y determinar ganador
                        $alumno1Puntos = $enfrentamientoMostrar['puntos_alumno1'];
                        $alumno2Puntos = $enfrentamientoMostrar['puntos_alumno2'];

                        // Actualizar la columna ganador_id y perdedor_id en la tabla enfrentamientos
                        $alumnoGanadorId = $alumno1Puntos > $alumno2Puntos ? $enfrentamientoMostrar['alumno1_id'] : $enfrentamientoMostrar['alumno2_id'];
                        $alumnoPerdedorId = $alumno1Puntos > $alumno2Puntos ? $enfrentamientoMostrar['alumno2_id'] : $enfrentamientoMostrar['alumno1_id'];

                        $actualizarGanadorPerdedorQuery = "UPDATE enfrentamientos SET ganador_id = $alumnoGanadorId, perdedor_id = $alumnoPerdedorId WHERE enfrentamiento_id = {$enfrentamientoMostrar['enfrentamiento_id']}";
                        $conexion->query($actualizarGanadorPerdedorQuery);

                        echo "<div class='enfrentamiento-caja'>";
                        echo "<h2 class='ronda'>Ronda 2</h2>";
                        echo "<div class='contenido'>";
                        echo "<div class='jugador jugador-1'>";
                        echo "<div class='nombre'>{$enfrentamientoMostrar['nombre_alumno1']}</div>";
                        echo "<div class='puntos'>Puntos: $alumno1Puntos</div>";
                        echo "</div>";
                        echo "<div class='jugador jugador-2'>";
                        echo "<div class='nombre'>{$enfrentamientoMostrar['nombre_alumno2']}</div>";
                        echo "<div class='puntos'>Puntos: $alumno2Puntos</div>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                        echo "<div class='ganador'>Ganador: " . ($alumnoGanadorId == $enfrentamientoMostrar['alumno1_id'] ? $enfrentamientoMostrar['nombre_alumno1'] : $enfrentamientoMostrar['nombre_alumno2']) . "</div>";
                    }
                    // Actualizar el estado de la ronda a "finalizada"
                    $actualizarEstadoRondaQuery = "UPDATE rondas SET estado = 'finalizada' WHERE ronda_id = $rondaId";
                    $conexion->query($actualizarEstadoRondaQuery);

                    // Obtener el valor de ronda_id para la ronda adicional (incrementar en 1)
                    $rondaAdicionalId = $rondaId + 1;
                    ?>

                    <br>
                    
                </div>
                <a id="add_alumnos" href="php/crear_ronda_final.php?ronda_id=<?php echo $rondaAdicionalId; ?>">Siguiente ronda</a>
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