<?php
require_once 'php/conexion.php';
session_start();

// Obtener el ID del usuario que inició sesión
$usuarioId = $_SESSION['usuario_id'];

// Obtener los alumnos creados por el usuario, ordenados por puntos
$obtenerAlumnosCreadosQuery = "SELECT * FROM alumnos WHERE usuario_id = $usuarioId ORDER BY puntos DESC";
$resultadoAlumnosCreados = $conexion->query($obtenerAlumnosCreadosQuery);

// Mostrar los alumnos en una tabla con su posición según los puntos
?>

<!DOCTYPE html>
<html>
<head>
    <title>Clasificación de Alumnos</title>
</head>
<body>
    <h1>Clasificación de Alumnos</h1>
    <table>
        <tr>
            <th>Posición</th>
            <th>Nombre</th>
            <th>Edad</th>
            <th>Puntos</th>
        </tr>
        <?php
        $posicion = 1;
        while ($fila = $resultadoAlumnosCreados->fetch_assoc()) {
            echo "<tr>";
            echo "<td>$posicion</td>";
            echo "<td>" . $fila['nombre'] . "</td>";
            echo "<td>" . $fila['edad'] . "</td>";
            echo "<td>" . $fila['puntos'] . "</td>";
            echo "</tr>";
            $posicion++;
        }
        ?>
    </table>
</body>
</html>
