<?php

include("../conexion_compras.php");

$link = mysqli_connect($host, $usuario, $clave, $bd);

if (!$link) {
    die("Error de conectividad");
}

// Obtener el usuario del parámetro GET
$usuario_nombre = isset($_GET['user']) ? $_GET['user'] : 'fabian';

// Mapeo de nombres de usuario a IDs
$usuarios_map = array(
    'fabian' => 8,
    'Denise' => 5,
    'Pabla' => 6,
    'Lucia' => 7,
    'denise' => 5,
    'pabla' => 6,
    'lucia' => 7,
    'Fabian-compras' => 8
);

$usuario_id = isset($usuarios_map[$usuario_nombre]) ? $usuarios_map[$usuario_nombre] : 8;

// Obtener todas las tarjetas del usuario desde la tabla usuario_tarjetas
$sql = "SELECT ut.id as ut_id, ut.usuario_id, ut.tarjeta, ut.activa, fp.descripcion
        FROM usuario_tarjetas ut
        JOIN formas_pago fp ON ut.tarjeta = fp.tarjeta
        WHERE ut.usuario_id = ? AND ut.activa = 1
        ORDER BY ut.tarjeta ASC";

$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $usuario_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$tarjetas = array();
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $tarjetas[] = $row;
}
mysqli_stmt_close($stmt);

// Obtener información del usuario
$sql_user = "SELECT nombre FROM usuarios WHERE id = ?";
$stmt_user = mysqli_prepare($link, $sql_user);
mysqli_stmt_bind_param($stmt_user, "i", $usuario_id);
mysqli_stmt_execute($stmt_user);
$result_user = mysqli_stmt_get_result($stmt_user);
$user_info = mysqli_fetch_array($result_user, MYSQLI_ASSOC);
$nombre_usuario = $user_info ? $user_info['nombre'] : $usuario_nombre;
mysqli_stmt_close($stmt_user);

?>

<!DOCTYPE html>

<html lang="es">

<head>
    <title>APP COMPRAS - <?php echo htmlspecialchars($nombre_usuario); ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link rel="icon" type="image/jpeg" href="2.jpeg" sizes="16x16">
</head>

<style type="text/css">
    html,
    body {
        height: 100%;
        margin: 0;
    }

    #pag {
        min-height: 100%;
    }

    img {
        max-width: 100%;
        height: auto;
        cursor: pointer;
    }

    .panel-heading {
        background-color: #f5f5f5;
        border-color: #ddd;
    }
</style>

<body>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">

                <div class="text-center" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 5px;">
                    <?php
                    // Menú de navegación
                    $usuarios = array(
                        array('nombre' => 'fabian', 'label' => 'Fabian'),
                        array('nombre' => 'Pabla', 'label' => 'Pabla'),
                        array('nombre' => 'Denise', 'label' => 'Denise'),
                        array('nombre' => 'Lucia', 'label' => 'Lucia')
                    );

                    foreach ($usuarios as $u) {
                        $is_active = ($usuario_nombre == $u['nombre'] || strtolower($usuario_nombre) == strtolower($u['nombre'])) ? 'btn-primary' : 'btn-default';
                        echo '<a href="usuarios_tarjetas.php?user=' . urlencode($u['nombre']) . '" class="btn btn-sm ' . $is_active . '" role="button">' . $u['label'] . '</a>' . PHP_EOL;
                    }
                    ?>
                    <a href="https://domingofsepulveda.free.nf/ITEMS/user_cel.php" class="btn btn-default btn-sm"
                        role="button">CASA</a>
                    <a href="intereses.php" class="btn btn-default btn-sm" role="button">INTERES</a>
                    <a href="todos.php" class="btn btn-default btn-sm" role="button">TODOS</a>
                    <a class="btn btn-default btn-sm" href="../LOGIN-AWS-db004/login.php" role="button">Salir</a>
                </div>

            </div>

            <div class="panel-body">

                <div class="container-fluid" id="pag">
                    <div class="text-center">
                        <h3><a
                                href="gestionar_tarjetas_ui.php?user=<?php echo urlencode($usuario_nombre); ?>"><?php echo htmlspecialchars($nombre_usuario); ?></a>
                        </h3>
                        <hr>

                        <?php
                        // Generar dinámicamente los componentes visuales
                        if (count($tarjetas) > 0) {
                            $tarjetas_por_fila = 2;
                            $contador = 0;

                            foreach ($tarjetas as $tarjeta) {
                                // Iniciar nueva fila cada 2 tarjetas
                                if ($contador % $tarjetas_por_fila == 0) {
                                    echo '<div class="row">' . PHP_EOL;
                                }

                                // Mapeo de tarjeta a clase e imagen
                                $logo_map = array(
                                    'Efectivo' => 'pesos.png',
                                    'Adelanto' => 'empleado.jpg',
                                    'Cabal' => 'cabal.png',
                                    'Visa Debito' => 'visa.png',
                                    'Visa Credito' => 'visa.png',
                                    'Visa' => 'visa.png',
                                    'Mercado Pago' => 'pago.png',
                                    'Cuenta DNI' => 'dni.png',
                                    'Vales' => 'vales.png',
                                    'Credito' => 'mastercard.png',
                                    'Mastercard' => 'mastercard.png'
                                );

                                // Determinar qué logo usar
                                $logo = 'pesos.png'; // default
                                foreach ($logo_map as $keyword => $image) {
                                    if (stripos($tarjeta['descripcion'], $keyword) !== false) {
                                        $logo = $image;
                                        break;
                                    }
                                }

                                // Clase única para esta tarjeta
                                $clase_tarjeta = 'tarjeta_' . $tarjeta['tarjeta'];

                                echo '    <div class="col-xs-6">' . PHP_EOL;
                                echo '        <img src="logos/' . htmlspecialchars($logo) . '" class="img-rounded ' . $clase_tarjeta . '" alt="' . htmlspecialchars($tarjeta['descripcion']) . '" title="' . htmlspecialchars($tarjeta['descripcion']) . '">' . PHP_EOL;
                                echo '        <p><small>' . htmlspecialchars($tarjeta['descripcion']) . '</small></p>' . PHP_EOL;
                                echo '    </div>' . PHP_EOL;

                                $contador++;

                                // Cerrar fila
                                if ($contador % $tarjetas_por_fila == 0) {
                                    echo '</div>' . PHP_EOL;
                                    if ($contador < count($tarjetas)) {
                                        echo '<hr>' . PHP_EOL;
                                    }
                                }
                            }

                            // Cerrar última fila si es necesario
                            if ($contador % $tarjetas_por_fila != 0) {
                                echo '</div>' . PHP_EOL;
                            }
                        } else {
                            echo '<p class="text-muted">No hay tarjetas asignadas para este usuario.</p>' . PHP_EOL;
                        }
                        ?>

                    </div>
                </div>

                <br>
                <!-- Modal -->
                <div class="modal fade" id="modal_usuario" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <!-- Contenido dinámico cargado aquí -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Evento para mostrar modal al hacer clic en una tarjeta
            $('.img-rounded').on('click', function () {
                var tarjeta = $(this).attr('class').match(/tarjeta_\d+/);
                var user = '<?php echo urlencode($usuario_nombre); ?>';
                var pago = tarjeta ? tarjeta[0].replace('tarjeta_', '') : '';
                $('.modal-body').load('modal.php?pago=' + pago + '&user=' + user, function () {
                    $('#modal_usuario').modal({
                        show: true
                    });
                });
            });
        });
    </script>

</body>

</html>

<?php

mysqli_close($link);

?>