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
<html>
<head>
    <title>Resultados de la ronda</title>
</head>
<body>
    <h1>Resultados de la ronda</h1>
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
        echo "Ronda 1:<br><br>";
        echo "Enfrentamiento: {$enfrentamientoMostrar['nombre_alumno1']} vs {$enfrentamientoMostrar['nombre_alumno2']}<br>";
        echo "Puntos {$enfrentamientoMostrar['nombre_alumno1']}: $alumno1Puntos<br>";
        echo "Puntos {$enfrentamientoMostrar['nombre_alumno2']}: $alumno2Puntos<br>";
        echo "Ganador: " . ($alumnoGanadorId == $enfrentamientoMostrar['alumno1_id'] ? $enfrentamientoMostrar['nombre_alumno1'] : $enfrentamientoMostrar['nombre_alumno2']) . "<br><br>";
    }
    // Actualizar el estado de la ronda a "finalizada"
    $actualizarEstadoRondaQuery = "UPDATE rondas SET estado = 'finalizada' WHERE ronda_id = $rondaId";
    $conexion->query($actualizarEstadoRondaQuery);

    // Obtener el valor de ronda_id para la ronda adicional (incrementar en 1)
    $rondaAdicionalId = $rondaId + 1;
    ?>

    <br>
    <a href="php/crear_ronda_adicional.php?ronda_id=<?php echo $rondaAdicionalId; ?>">Finalizar torneo:</a>

</body>
</html>