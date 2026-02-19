<?php
require_once 'php/conexion.php';

// Check connection
if (!$conexion) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create torneo table if not exists (structure from usuarios.sql)
$sql = "CREATE TABLE IF NOT EXISTS `torneo` (
  `torneo_id` int(3) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) NOT NULL,
  `fecha` varchar(25) NOT NULL,
  `usuario_id` int(3) NOT NULL,
  `primer_lugar` int(3) DEFAULT NULL,
  `segundo_lugar` int(3) DEFAULT NULL,
  `estado` varchar(16) NOT NULL DEFAULT 'pendiente',
  PRIMARY KEY (`torneo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

if (mysqli_query($conexion, $sql)) {
    echo "Table 'torneo' created successfully or already exists.\n";
} else {
    echo "Error creating table: " . mysqli_error($conexion) . "\n";
}

// Insert dummy data if no pending tournament exists
$checkSql = "SELECT * FROM torneo WHERE estado = 'pendiente'";
$result = mysqli_query($conexion, $checkSql);

if (mysqli_num_rows($result) == 0) {
    // Determine date 2 days from now
    $futureDate = date('Y-m-d H:i:s', strtotime('+2 days'));
    // fecha field in existing sql seems to be varchar(25), but let's use standard datetime format string
    
    $insertSql = "INSERT INTO `torneo` (`nombre`, `fecha`, `usuario_id`, `estado`) VALUES ('Grand Prix 2026', '$futureDate', 1, 'pendiente')";
    
    if (mysqli_query($conexion, $insertSql)) {
        echo "New pending tournament inserted: $futureDate\n";
    } else {
        echo "Error inserting record: " . mysqli_error($conexion) . "\n";
    }
} else {
    echo "Pending tournament already exists.\n";
    $row = mysqli_fetch_assoc($result);
    echo "Existing tournament date: " . $row['fecha'] . "\n";
}

mysqli_close($conexion);
?>
