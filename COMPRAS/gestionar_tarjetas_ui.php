<?php

include("../conexion_compras.php");

$link = mysqli_connect($host, $usuario, $clave, $bd);

if (!$link) {
    die("Error de conectividad");
}

// Incluir funciones
include("gestionar_tarjetas.php");

// Obtener usuario
$usuario_nombre = isset($_GET['user']) ? $_GET['user'] : 'fabian';

// Mapeo de nombres de usuario a IDs (mismos que en usuarios_tarjetas.php)
$usuarios_map = array(
    'fabian' => 8,
    'Fabian-compras' => 8,
    'Denise' => 5,
    'denise' => 5,
    'Pabla' => 6,
    'pabla' => 6,
    'Lucia' => 7,
    'lucia' => 7
);

$usuario_id = isset($usuarios_map[$usuario_nombre]) ? $usuarios_map[$usuario_nombre] : 0;

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    header('Content-Type: application/json');

    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];

        if ($accion == 'agregar') {
            $tarjeta_id = intval($_POST['tarjeta_id']);
            $resultado = agregar_tarjeta_usuario($usuario_id, $tarjeta_id, '', $link);
            echo json_encode($resultado);
            exit();

        } else if ($accion == 'eliminar') {
            $usuario_tarjeta_id = intval($_POST['usuario_tarjeta_id']);
            $resultado = eliminar_tarjeta_usuario($usuario_tarjeta_id, $link);
            echo json_encode($resultado);
            exit();

        } else if ($accion == 'cambiar_estado') {
            $usuario_tarjeta_id = intval($_POST['usuario_tarjeta_id']);
            $activa = intval($_POST['activa']);
            $resultado = cambiar_estado_tarjeta($usuario_tarjeta_id, $activa, $link);
            echo json_encode($resultado);
            exit();
        }
    }
}

// Obtener datos
$tarjetas_usuario = obtener_tarjetas_usuario($usuario_id, $link);
$todas_tarjetas = obtener_todas_tarjetas($link);

// Obtener IDs de tarjetas ya asignadas
$tarjetas_asignadas = array_column($tarjetas_usuario, 'tarjeta');

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestionar Tarjetas - <?php echo $usuario_nombre; ?></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style>
        body {
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
        }

        .tarjeta-item {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            margin: 5px 0;
            background: white;
            border-radius: 4px;
        }

        .tarjeta-nombre {
            flex-grow: 1;
            min-width: 150px;
        }

        .tarjeta-activa {
            color: green;
            font-weight: bold;
        }

        .tarjeta-inactiva {
            color: red;
            font-weight: bold;
        }

        .btn-accion {
            margin: 0 5px;
        }

        .alert-info {
            margin: 10px 0;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Gestionar Tarjetas de <?php echo ucfirst($usuario_nombre); ?></h2>

        <div id="mensaje" class="alert alert-info" style="display:none;"></div>

        <!-- Tarjetas Asignadas -->
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4>Tarjetas Asignadas (<?php echo count($tarjetas_usuario); ?>)</h4>
            </div>
            <div class="panel-body">
                <?php if (count($tarjetas_usuario) > 0): ?>
                    <?php foreach ($tarjetas_usuario as $t): ?>
                        <div class="tarjeta-item">
                            <div class="tarjeta-nombre">
                                <strong><?php echo $t['descripcion']; ?></strong>
                                <br>
                                <small>Vencimiento: <?php echo $t['vto']; ?> días</small>
                                <br>
                                <small class="<?php echo ($t['activa'] == 1) ? 'tarjeta-activa' : 'tarjeta-inactiva'; ?>">
                                    <?php echo ($t['activa'] == 1) ? '✓ Activa' : '✗ Inactiva'; ?>
                                </small>
                            </div>
                            <div>
                                <button class="btn btn-xs btn-warning btn-accion"
                                    onclick="cambiarEstado(<?php echo $t['id']; ?>, <?php echo (1 - $t['activa']); ?>)">
                                    <?php echo ($t['activa'] == 1) ? 'Desactivar' : 'Activar'; ?>
                                </button>
                                <button class="btn btn-xs btn-danger btn-accion"
                                    onclick="eliminarTarjeta(<?php echo $t['id']; ?>)">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">Ninguna tarjeta asignada aún</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Agregar Nueva Tarjeta -->
        <div class="panel panel-success">
            <div class="panel-heading">
                <h4>Agregar Nueva Tarjeta</h4>
            </div>
            <div class="panel-body">
                <form id="formAgregar">
                    <div class="form-group">
                        <label for="tarjeta_id">Seleccionar Tarjeta:</label>
                        <select id="tarjeta_id" name="tarjeta_id" class="form-control" required>
                            <option value="">-- Seleccionar --</option>
                            <?php foreach ($todas_tarjetas as $t): ?>
                                <?php if (!in_array($t['tarjeta'], $tarjetas_asignadas)): ?>
                                    <option value="<?php echo $t['tarjeta']; ?>">
                                        <?php echo $t['descripcion']; ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="button" class="btn btn-success" onclick="agregarTarjeta()">
                        Agregar Tarjeta
                    </button>
                </form>
            </div>
        </div>

        <hr>
        <a href="javascript:history.back()" class="btn btn-default">← Volver</a>
    </div>

    <script>
        function agregarTarjeta() {
            var tarjeta_id = $('#tarjeta_id').val();

            if (!tarjeta_id) {
                mostrarMensaje('Por favor, selecciona una tarjeta', 'warning');
                return;
            }

            $.ajax({
                type: 'POST',
                url: 'gestionar_tarjetas_ui.php?user=<?php echo $usuario_nombre; ?>',
                data: {
                    accion: 'agregar',
                    tarjeta_id: tarjeta_id
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        mostrarMensaje(response.message, 'success');
                        setTimeout(function () { location.reload(); }, 1500);
                    } else {
                        mostrarMensaje(response.message, 'danger');
                    }
                },
                error: function () {
                    mostrarMensaje('Error al procesar la solicitud', 'danger');
                }
            });
        }

        function eliminarTarjeta(usuario_tarjeta_id) {
            if (confirm('¿Estás seguro de que deseas eliminar esta tarjeta?')) {
                $.ajax({
                    type: 'POST',
                    url: 'gestionar_tarjetas_ui.php?user=<?php echo $usuario_nombre; ?>',
                    data: {
                        accion: 'eliminar',
                        usuario_tarjeta_id: usuario_tarjeta_id
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            mostrarMensaje(response.message, 'success');
                            setTimeout(function () { location.reload(); }, 1500);
                        } else {
                            mostrarMensaje(response.message, 'danger');
                        }
                    },
                    error: function () {
                        mostrarMensaje('Error al procesar la solicitud', 'danger');
                    }
                });
            }
        }

        function cambiarEstado(usuario_tarjeta_id, activa) {
            $.ajax({
                type: 'POST',
                url: 'gestionar_tarjetas_ui.php?user=<?php echo $usuario_nombre; ?>',
                data: {
                    accion: 'cambiar_estado',
                    usuario_tarjeta_id: usuario_tarjeta_id,
                    activa: activa
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        mostrarMensaje(response.message, 'success');
                        setTimeout(function () { location.reload(); }, 1000);
                    } else {
                        mostrarMensaje(response.message, 'danger');
                    }
                },
                error: function () {
                    mostrarMensaje('Error al procesar la solicitud', 'danger');
                }
            });
        }

        function mostrarMensaje(mensaje, tipo) {
            $('#mensaje').removeClass('alert-success alert-danger alert-warning alert-info');
            $('#mensaje').addClass('alert-' + tipo);
            $('#mensaje').text(mensaje);
            $('#mensaje').show();
        }
    </script>

</body>

</html>

<?php
mysqli_close($link);
?>