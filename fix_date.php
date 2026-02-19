<?php
require_once 'php/conexion.php';

// Update pending tournament to a future date
$futureDate = date('Y-m-d H:i:s', strtotime('+5 days'));
$sql = "UPDATE torneo SET fecha = '$futureDate' WHERE estado = 'pendiente'";

if (mysqli_query($conexion, $sql)) {
    echo "Updated pending tournament to future date: $futureDate";
} else {
    echo "Error updating record: " . mysqli_error($conexion);
}
?>