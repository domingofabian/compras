<?php

include("../conexion_compras.php");

$hoy = date("Y-m-d");

$link = mysqli_connect($host, $usuario, $clave, $bd);

function Recuperar_Tabla_compras($link)
{

    $mes = date("m");

    $ins = "
                select * from capturas 
                where detalle is null
                and month(fecha)=$mes 
                order by fecha desc
        ";


    $con = mysqli_query($link, $ins) or die("Fallo en la consulta Recuperar_Tabla_compras liena 186");
    $lis = array();
    while ($res = mysqli_fetch_array($con)) {
        $lis[] = $res;
    }
    return $lis;


}

function Mostrar_tabla_compras_cel($listado, $link)
{

    $total = sizeof($listado);
    $contador = 0;

    // Colores por usuario
    $colores_usuario = array(
        'fabian' => '#337ab7', // azul
        'Pabla' => '#5cb85c',  // verde
        'Denise' => '#f0ad4e', // naranja
        'Lucia' => '#d9534f',  // rojo
        'pabla' => '#5cb85c',
        'denise' => '#f0ad4e',
        'lucia' => '#d9534f'
    );

    echo '<div class="list-group" style="margin-bottom:0;">';

    while ($contador != $total) {
        $fecha = $listado[$contador]['fecha'];
        $importe = $listado[$contador]['importe'];
        $cuotas = $listado[$contador]['cuotas'];
        $subfamilia = $listado[$contador]['subfamilia'];
        $forma_pago = $listado[$contador]['forma_pago'];
        $usuario = $listado[$contador]['usuario'];

        // Obtener descripción de la forma de pago
        $sql_descripcion = "SELECT descripcion FROM formas_pago WHERE tarjeta = $forma_pago";
        $result_descripcion = mysqli_query($link, $sql_descripcion);
        $row_descripcion = mysqli_fetch_array($result_descripcion);
        $descripcion = $row_descripcion ? $row_descripcion['descripcion'] : 'N/A';

        $color = isset($colores_usuario[$usuario]) ? $colores_usuario[$usuario] : '#777';
        $importe_fmt = number_format($importe, 2, ',', '.');

        echo '
        <div class="list-group-item" style="border-left: 4px solid ' . $color . '; padding: 10px 12px; margin-bottom: 2px; border-radius: 0;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 15px; font-weight: 600; color: #333;">$ ' . $importe_fmt . '</div>
                    <div style="font-size: 12px; color: #666; margin-top: 2px;">' . htmlspecialchars($subfamilia) . '</div>
                    <div style="margin-top: 3px;">
                        <span class="label label-info" style="font-size: 10px;">' . htmlspecialchars($descripcion) . '</span>
                        <span class="label" style="background-color:' . $color . '; font-size: 10px;">' . htmlspecialchars($usuario) . '</span>
                    </div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 11px; color: #999;">' . $fecha . '</div>
                    ' . ($cuotas > 1 ? '<span class="label label-warning" style="font-size: 10px;">' . $cuotas . ' cuotas</span>' : '') . '
                </div>
            </div>
        </div>';

        ++$contador;
    }

    if ($total == 0) {
        echo '<div class="list-group-item text-center" style="color: #aaa; font-style: italic; padding: 15px;">Sin compras este mes</div>';
    }

    echo '</div>';
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
                    <a href="intereses.php" class="btn btn-primary btn-sm" role="button">in</a>
                    <a class="btn btn-default btn-sm" href="../google-login/Login-google/logout.php"
                        role="button">Salir</a>
                </div>

            </div>

            <div class="panel-body">



                <div>
                    <?php
                    $lis = Recuperar_Tabla_compras($link);
                    Mostrar_tabla_compras_cel($lis, $link);
                    ?>
                </div>





            </div>



        </div>


</body>



</html>