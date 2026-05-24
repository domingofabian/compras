<?php

include("../conexion_compras.php");

// Establecer header JSON
header('Content-Type: application/json');

$link = mysqli_connect($host, $usuario, $clave, $bd);

if (!$link) {
    echo json_encode(['success' => false, 'message' => 'Error de conectividad']);
    exit();
}

// Validar método POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no válido']);
    exit();
}

// Obtener y validar los datos
$pago = isset($_POST['pago']) ? intval($_POST['pago']) : null;
$fechacierre = isset($_POST['fechacierre']) ? intval($_POST['fechacierre']) : null;
$vto = isset($_POST['vto']) ? intval($_POST['vto']) : null;
$credito = isset($_POST['credito']) ? intval($_POST['credito']) : null;
$tope = isset($_POST['tope']) ? intval($_POST['tope']) : null;

// Validaciones
if (!$pago || $fechacierre === null || $vto === null || $credito === null || $tope === null) {
    echo json_encode(['success' => false, 'message' => 'Faltan parámetros requeridos']);
    exit();
}

// Validar rangos válidos
if ($fechacierre < 1 || $fechacierre > 31) {
    echo json_encode(['success' => false, 'message' => 'El día de cierre debe estar entre 1 y 31']);
    exit();
}

if ($vto < 0 || $vto > 31) {
    echo json_encode(['success' => false, 'message' => 'Los días de vencimiento deben estar entre 0 y 31']);
    exit();
}

if ($credito != 0 && $credito != 1) {
    echo json_encode(['success' => false, 'message' => 'El valor de crédito debe ser 0 o 1']);
    exit();
}

if ($tope < 0) {
    echo json_encode(['success' => false, 'message' => 'El tope no puede ser negativo']);
    exit();
}

// Usar prepared statement para evitar SQL injection
$sql = "UPDATE `formas_pago` SET `fechacierre` = ?, `vto` = ?, `credito` = ?, `tope` = ? WHERE `tarjeta` = ?";
$stmt = mysqli_prepare($link, $sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta: ' . mysqli_error($link)]);
    exit();
}

// Bind parameters
mysqli_stmt_bind_param($stmt, "iiiii", $fechacierre, $vto, $credito, $tope, $pago);

// Ejecutar
if (mysqli_stmt_execute($stmt)) {
    // Verificar si se actualizó alguna fila
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode(['success' => true, 'message' => 'Datos actualizados correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontró el registro a actualizar']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error en la actualización: ' . mysqli_error($link)]);
}

mysqli_stmt_close($stmt);
mysqli_close($link);

?>