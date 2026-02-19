<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Escuelas y Alumnos</h1>
    <?php
    require_once 'php/conexion.php';
    session_start();
    $usuarioId = $_SESSION['usuario_id'];

    // Consultar las escuelas y sus alumnos relacionados con el usuario
    $sql = "SELECT e.escuela_id AS escuela_id, e.nombreEscuela, a.alumno_id AS alumno_id, a.nombre, a.edad
            FROM escuelas e
            JOIN escuela_alumno ea ON e.escuela_id = ea.escuela_id
            JOIN alumnos a ON ea.alumno_id = a.alumno_id
            WHERE e.usuario_id = $usuarioId";

    $resultado = $conexion->query($sql);

    if ($resultado === false) {
        echo "Error en la consulta: ".$conexion->error;
    }elseif ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $escuelaId = $fila['escuela_id'];
            $escuelaNombre = $fila['nombreEscuela'];
            $alumnoId = $fila['alumno_id'];
            $alumnoNombre = $fila['nombre'];
            $alumnoEdad = $fila['edad'];

            // Mostrar los datos de la escuela y el alumno
            echo "<h2>Escuela: $escuelaNombre (ID: $escuelaId)</h2>";
            echo "<p>Alumno: $alumnoNombre (ID: $alumnoId), Edad: $alumnoEdad</p>";
        }
    } else {
        echo "<p>No se encontraron escuelas ni alumnos para este usuario.</p>";
    }

    // Cerrar la conexión
    mysqli_close($conexion);
    ?>
</body>
</html>