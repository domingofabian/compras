<?php

include("../conexion_compras.php");

$hoy = date("Y-m-d");

$link = mysqli_connect($host, $usuario, $clave, $bd);

// Procesar formulario de agregar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_agregar_interes'])) {
    $pagado = mysqli_real_escape_string($link, $_POST['pagado']);
    $tarjeta = mysqli_real_escape_string($link, $_POST['tarjeta']);
    $total = (int) $_POST['total'];
    $interes = (int) $_POST['interes'];
    $entregue = (int) $_POST['entregue'];

    $sql_insert = "INSERT INTO intereses (pagado, tarjeta, total, interes, entregue) VALUES ('$pagado', '$tarjeta', $total, $interes, $entregue)";
    mysqli_query($link, $sql_insert);

    header("Location: intereses.php");
    exit();
}
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <title>Compras/Pagos</title>
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
        max‐width: 100%;
        height: auto;
    }
</style>


<body>
    <div class="container">
        <div class="panel panel-default">

            <div class="panel-heading">

                <div class="text-center" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 5px;">
                    <a href="usuarios_tarjetas.php?user=fabian" class="btn btn-default btn-sm" role="button">Fabian</a>
                    <a href="usuarios_tarjetas.php?user=Pabla" class="btn btn-default btn-sm" role="button">Pabla</a>
                    <a href="usuarios_tarjetas.php?user=Denise" class="btn btn-default btn-sm" role="button">Denise</a>
                    <a href="usuarios_tarjetas.php?user=Lucia" class="btn btn-default btn-sm" role="button">Lucia</a>
                    <a href="todos.php" class="btn btn-primary btn-sm" role="button">$</a>
                    <a class="btn btn-default btn-sm" href="../google-login/Login-google/logout.php"
                        role="button">Salir</a>
                </div>

            </div>

            <div class="panel-body">

                <div id="interes">

                    <div class="clearfix" style="margin-bottom: 15px;">
                        <button type="button" class="btn btn-success pull-right" data-toggle="modal"
                            data-target="#modalAgregar">
                            <i class="glyphicon glyphicon-plus"></i> Agregar
                        </button>
                    </div>

                    <!-- Modal Agregar -->
                    <div class="modal fade" id="modalAgregar" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form action="" method="POST">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="modalLabel">Agregar Nuevo Interés</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Pagado</label>
                                            <input type="date" class="form-control" name="pagado"
                                                value="<?php echo $hoy; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Tarjeta</label>
                                            <select class="form-control" name="tarjeta" required>
                                                <option value="">Seleccione una opción...</option>
                                                <?php
                                                $sql_tarjetas = "SELECT descripcion FROM formas_pago WHERE credito = 1 ORDER BY descripcion ASC";
                                                $res_tarjetas = mysqli_query($link, $sql_tarjetas);
                                                if ($res_tarjetas) {
                                                    while ($t = mysqli_fetch_assoc($res_tarjetas)) {
                                                        echo '<option value="' . htmlspecialchars($t['descripcion']) . '">' . htmlspecialchars($t['descripcion']) . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Total</label>
                                            <input type="number" class="form-control" name="total" required
                                                autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <label>Interés</label>
                                            <input type="number" class="form-control" name="interes" required
                                                autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <label>Entregué</label>
                                            <input type="number" class="form-control" name="entregue" required
                                                autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default"
                                            data-dismiss="modal">Cancelar</button>
                                        <button type="submit" name="btn_agregar_interes"
                                            class="btn btn-primary">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> <?php
                    // Fetch data from intereses ordered by pagado DESC
                    $sql = "SELECT id, pagado, tarjeta, total, interes, entregue FROM intereses ORDER BY pagado DESC";
                    $result = mysqli_query($link, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        echo '<div class="table-responsive">';
                        echo '<table class="table table-hover table-bordered text-center">';
                        echo '<thead>';
                        echo '<tr class="info text-center">';
                        echo '<th class="text-center">Tarjeta</th>';
                        echo '<th class="text-center">Total</th>';
                        echo '<th class="text-center">Interés</th>';
                        echo '<th class="text-center">Entregue</th>';
                        echo '<th class="text-center">Pagado</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        // Colores suaves para diferenciar meses
                        $colores_mes = ['#ffffff', '#f2f7fd', '#fdf8ec', '#f0fbf4', '#fdf3f6', '#fbf4fd'];
                        $color_idx = 0;
                        $mes_actual = '';

                        while ($row = mysqli_fetch_assoc($result)) {
                            $mes_fila = date("Y-m", strtotime($row['pagado']));

                            // Si el mes cambia respecto al anterior, cambiamos de color
                            if ($mes_actual !== '' && $mes_fila !== $mes_actual) {
                                $color_idx = ($color_idx + 1) % count($colores_mes);
                            }
                            $mes_actual = $mes_fila;
                            $bg_color = $colores_mes[$color_idx];

                            $pagado_fmt = date("d/m/Y", strtotime($row['pagado']));
                            $total_fmt = number_format($row['total'], 2, ',', '.');
                            $interes_fmt = number_format($row['interes'], 2, ',', '.');
                            $entregue_fmt = number_format($row['entregue'], 2, ',', '.');

                            echo '<tr style="background-color: ' . $bg_color . ';">';
                            echo '<td><span class="label label-primary">' . htmlspecialchars($row['tarjeta']) . '</span></td>';
                            echo '<td><strong>$ ' . $total_fmt . '</strong></td>';
                            echo '<td style="color: #d9534f;">$ ' . $interes_fmt . '</td>';
                            echo '<td style="color: #5cb85c;">$ ' . $entregue_fmt . '</td>';
                            echo '<td>' . $pagado_fmt . '</td>';
                            echo '</tr>';
                        }

                        echo '</tbody>';
                        echo '</table>';
                        echo '</div>';
                    } else {
                        echo '<div class="alert alert-warning text-center">No hay registros de intereses todavía.</div>';
                    }
                    ?>





                </div>



            </div>


</body>



</html>