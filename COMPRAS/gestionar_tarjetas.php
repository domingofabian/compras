<?php

/**
 * Funciones para gestionar las tarjetas de usuario
 * Tabla: usuario_tarjetas
 * NOTA: Este archivo debe ser incluido desde un archivo que ya tenga
 *       la conexión $link establecida (ej: gestionar_tarjetas_ui.php)
 */

/**
 * Obtiene todas las tarjetas de un usuario
 * @param int $usuario_id
 * @param mysqli $link
 * @return array
 */
function obtener_tarjetas_usuario($usuario_id, $link)
{
    $sql = "SELECT ut.id, ut.usuario_id, ut.tarjeta, ut.activa, ut.fecha_agregada, ut.notas, 
                   fp.descripcion, fp.vto, fp.tope, fp.credito
            FROM usuario_tarjetas ut
            JOIN formas_pago fp ON ut.tarjeta = fp.tarjeta
            WHERE ut.usuario_id = ?
            ORDER BY ut.activa DESC, fp.descripcion ASC";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $usuario_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $tarjetas = array();
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $tarjetas[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $tarjetas;
}

/**
 * Agrega una tarjeta a un usuario
 * @param int $usuario_id
 * @param int $tarjeta_id
 * @param string $notas
 * @param mysqli $link
 * @return array
 */
function agregar_tarjeta_usuario($usuario_id, $tarjeta_id, $notas = '', $link)
{
    $sql = "INSERT INTO usuario_tarjetas (usuario_id, tarjeta, activa, notas) 
            VALUES (?, ?, 1, ?)";

    $stmt = mysqli_prepare($link, $sql);

    if (!$stmt) {
        return ['success' => false, 'message' => 'Error en la preparación: ' . mysqli_error($link)];
    }

    mysqli_stmt_bind_param($stmt, "iis", $usuario_id, $tarjeta_id, $notas);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return ['success' => true, 'message' => 'Tarjeta agregada correctamente', 'id' => mysqli_insert_id($link)];
    } else {
        $error = mysqli_error($link);
        mysqli_stmt_close($stmt);
        return ['success' => false, 'message' => 'Error al agregar tarjeta: ' . $error];
    }
}

/**
 * Elimina una tarjeta de un usuario
 * @param int $usuario_tarjeta_id
 * @param mysqli $link
 * @return array
 */
function eliminar_tarjeta_usuario($usuario_tarjeta_id, $link)
{
    $sql = "DELETE FROM usuario_tarjetas WHERE id = ?";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $usuario_tarjeta_id);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return ['success' => true, 'message' => 'Tarjeta eliminada correctamente'];
    } else {
        $error = mysqli_error($link);
        mysqli_stmt_close($stmt);
        return ['success' => false, 'message' => 'Error al eliminar tarjeta: ' . $error];
    }
}

/**
 * Activa/Desactiva una tarjeta de un usuario
 * @param int $usuario_tarjeta_id
 * @param int $activa (0 o 1)
 * @param mysqli $link
 * @return array
 */
function cambiar_estado_tarjeta($usuario_tarjeta_id, $activa, $link)
{
    if ($activa != 0 && $activa != 1) {
        return ['success' => false, 'message' => 'Estado inválido. Debe ser 0 o 1'];
    }

    $sql = "UPDATE usuario_tarjetas SET activa = ? WHERE id = ?";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $activa, $usuario_tarjeta_id);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        $estado = $activa == 1 ? 'activada' : 'desactivada';
        return ['success' => true, 'message' => 'Tarjeta ' . $estado . ' correctamente'];
    } else {
        $error = mysqli_error($link);
        mysqli_stmt_close($stmt);
        return ['success' => false, 'message' => 'Error al cambiar estado: ' . $error];
    }
}

/**
 * Obtiene todas las tarjetas disponibles (de formas_pago)
 * @param mysqli $link
 * @return array
 */
function obtener_todas_tarjetas($link)
{
    $sql = "SELECT tarjeta, descripcion, vto, tope, credito 
            FROM formas_pago 
            ORDER BY descripcion ASC";

    $result = mysqli_query($link, $sql);
    $tarjetas = array();

    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $tarjetas[] = $row;
    }

    return $tarjetas;
}

?>