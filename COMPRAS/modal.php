<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../conexion_compras.php");

$hoy = date("Y-m-d");

// Validar conexión existente
if (!$link) {
    $link = mysqli_connect($host, $usuario, $clave, $bd);
    if (!$link) {
        echo "<div class='alert alert-danger'>Error de conexión: " . mysqli_connect_error() . "</div>";
        exit;
    }
}



function Select_item($usuario, $link)
{

    $instruccion = "SELECT familia, opcion, porcentaje, pagador FROM familias_tipos WHERE select_compra = 1 order by relacion";	// > 9 son ingresos				 

    $consulta = mysqli_query($link, $instruccion) or die("Fallo en la consulta Select_familias_tipos");
    $array = array();
    while ($resultado = mysqli_fetch_array($consulta)) {
        $array[] = $resultado;
    }


    echo "<select  name='seleccion' class='form-control'>
						";

    $cant_de_filas = sizeof($array);
    $ref = 0;
    while ($ref != $cant_de_filas) {
        $i = $array[$ref]['opcion'];

        echo "<option value=\"$i\">$i </option>";
        ++$ref;
    }

    echo "
    </select>";

}
function Recuperar_Tabla_compras($link)
{



    $mes = date("m");
    /*
    $ins = "
            select * from capturas 
            where detalle is null
            and month(fecha)=$mes 
            and cuotas = 1
            order by fecha desc
    ";
*/

    // todas las capturas

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
    mysqli_close($link);
}
function Mostrar_tabla_creditos($listado)
{
    $mes_actual = date("m");
    $total = sizeof($listado);
    $contador = 0;
    $hay_datos = false;

    echo '<div class="list-group" style="margin-bottom:0;">';

    while ($contador != $total) {
        $row = $listado[$contador];
        $fecha = isset($row['fecha']) ? $row['fecha'] : (isset($row[1]) ? $row[1] : null);
        if ($fecha) {
            $mes_registro = date("m", strtotime($fecha));
            if ($mes_registro == $mes_actual) {
                $importe = isset($row['importe']) ? $row['importe'] : (isset($row[2]) ? $row[2] : null);
                $cuotas = isset($row['cuotas']) ? $row['cuotas'] : (isset($row[4]) ? $row[4] : null);
                $subfamilia = isset($row['subfamilia']) ? $row['subfamilia'] : (isset($row[3]) ? $row[3] : null);
                $cuota_numero = isset($row['cuota_numero']) ? $row['cuota_numero'] : (isset($row[7]) ? $row[7] : null);
                $importe_fmt = number_format($importe, 2, ',', '.');
                $hay_datos = true;

                echo '
                <div class="list-group-item" style="border-left: 4px solid #d9534f; padding: 10px 12px; margin-bottom: 2px; border-radius: 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div style="font-size: 15px; font-weight: 600; color: #333;">$ ' . $importe_fmt . '</div>
                            <div style="font-size: 12px; color: #666; margin-top: 2px;">' . htmlspecialchars($subfamilia) . '</div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 11px; color: #999;">' . $fecha . '</div>
                            <span class="label label-danger" style="font-size: 10px;">' . $cuota_numero . '/' . $cuotas . ' cuotas</span>
                        </div>
                    </div>
                </div>';
            }
        }
        ++$contador;
    }

    if (!$hay_datos) {
        echo '<div class="list-group-item text-center" style="color: #aaa; font-style: italic; padding: 15px;">Sin cuotas pendientes</div>';
    }

    echo '</div>';
}

function Mostrar_tabla_compras_cel($listado, $pago, $user)
{
    $total = sizeof($listado);
    $contador = 0;
    $hay_datos = false;

    echo '<div class="list-group" style="margin-bottom:0;">';

    while ($contador != $total) {
        $fecha = $listado[$contador]['fecha'];
        $importe = $listado[$contador]['importe'];
        $cuotas = $listado[$contador]['cuotas'];
        $subfamilia = $listado[$contador]['subfamilia'];
        $forma_pago = $listado[$contador]['forma_pago'];
        $usuario = $listado[$contador]['usuario'];

        if (($forma_pago == $pago) and ($usuario == $user)) {
            $importe_fmt = number_format($importe, 2, ',', '.');
            $hay_datos = true;

            echo '
            <div class="list-group-item" style="border-left: 4px solid #337ab7; padding: 10px 12px; margin-bottom: 2px; border-radius: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 15px; font-weight: 600; color: #333;">$ ' . $importe_fmt . '</div>
                        <div style="font-size: 12px; color: #666; margin-top: 2px;">' . htmlspecialchars($subfamilia) . '</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 11px; color: #999;">' . $fecha . '</div>
                        ' . ($cuotas > 1 ? '<span class="label label-warning" style="font-size: 10px;">' . $cuotas . ' cuotas</span>' : '') . '
                    </div>
                </div>
            </div>';
        }
        ++$contador;
    }

    if (!$hay_datos) {
        echo '<div class="list-group-item text-center" style="color: #aaa; font-style: italic; padding: 15px;">Sin compras este mes</div>';
    }

    echo '</div>';
}
function Recuperar_tarjeta($forma_pago, $link)
{

    $instruccion = "
                    SELECT *
                    FROM formas_pago
                    WHERE tarjeta = $forma_pago
                    ";


    $error_reporting = error_reporting(0); // Suprimir warnings temporalmente
    $consulta = mysqli_query($link, $instruccion) or die("Fallo en la consulta $instruccion");
    $l = array();  // Inicializar array vacío

    while ($resultado = mysqli_fetch_array($consulta)) {
        $l[0] = $resultado['fechacierre'];
        $l[1] = $resultado['vto'];
        $l[2] = $resultado['credito'];
        $l[3] = $resultado['tope'];
        $l[4] = $resultado['descripcion'];
        $l[5] = $resultado['cuotas'];
    }
    error_reporting($error_reporting); // Restaurar error reporting

    // Asegurar que todos los índices existan
    if (!isset($l[0]))
        $l[0] = 28;
    if (!isset($l[1]))
        $l[1] = 0;
    if (!isset($l[2]))
        $l[2] = 0;
    if (!isset($l[3]))
        $l[3] = 0;
    if (!isset($l[4]))
        $l[4] = '';
    if (!isset($l[5]))
        $l[5] = 0;

    return $l;
    mysqli_close($link);
}
function obtenerPorcentaje($cantidad, $total)
{
    $porcentaje = ((float) $cantidad * 100) / $total; // Regla de tres
    $porcentaje = round($porcentaje, 0);  // Quitar los decimales
    return $porcentaje;
}
function suma_medio_pago($forma_pago, $link)
{

    $mes = date("m");
    $instruccion = "
                    SELECT SUM(importe) AS total
                    FROM capturas
                    WHERE forma_pago = $forma_pago
                    and month(fecha)=$mes
                    ";

    //echo "<h1>$instruccion</h1>";       

    $consulta = mysqli_query($link, $instruccion) or die("Fallo en la consulta $instruccion");
    $cant = mysqli_affected_rows($link);

    if ($cant == 0) {
        $l = 0;
    } else {
        while ($resultado = mysqli_fetch_array($consulta)) {
            $l = $resultado['total'];
        }
    }
    return $l;
    mysqli_close($link);

}
function suma_medio_pago_credito($forma_pago, $link)
{

    // Obtener el día de cierre de la forma de pago
    $sql_cierre = "SELECT fechacierre FROM formas_pago WHERE tarjeta = $forma_pago";
    $result_cierre = mysqli_query($link, $sql_cierre);

    if (!$result_cierre) {
        return 0;
    }

    $row_cierre = mysqli_fetch_array($result_cierre);
    $fecha_cierre = $row_cierre ? intval($row_cierre['fechacierre']) : 28;

    // Obtener fecha actual
    $hoy = date("Y-m-d");
    $mes_actual = intval(date("m"));
    $año_actual = intval(date("Y"));
    $dia_actual = intval(date("d"));

    // Calcular rango de fechas desde el últmo fechacierre hasta hoy
    if ($dia_actual < $fecha_cierre) {
        // Estamos antes del cierre, así que el cierre fue el mes pasado
        $mes_cierre = $mes_actual - 1;
        $año_cierre = $año_actual;
        if ($mes_cierre <= 0) {
            $mes_cierre = 12;
            $año_cierre--;
        }
    } else {
        // Estamos en o después del cierre, así que el cierre fue este mes
        $mes_cierre = $mes_actual;
        $año_cierre = $año_actual;
    }

    $fecha_inicio = sprintf("%04d-%02d-%02d", $año_cierre, $mes_cierre, min($fecha_cierre, 28));
    $fecha_fin = $hoy;

    // Sumar importes de la tabla creditos desde fecha_cierre hasta hoy
    $instruccion = "
                    SELECT SUM(importe) AS total
                    FROM creditos
                    WHERE forma_pago = $forma_pago
                    AND fecha >= '$fecha_inicio'
                    AND fecha <= '$fecha_fin'
                    ";
    //echo "<h1>$instruccion</h1>";

    //echo "<h1>Rango: $fecha_inicio a $fecha_fin</h1>";

    $consulta = mysqli_query($link, $instruccion);

    if (!$consulta) {
        return 0;
    }

    $resultado = mysqli_fetch_array($consulta);
    $total = $resultado['total'] ? floatval($resultado['total']) : 0;

    return $total;
}


//------------------------------------------------------------------------------------
/**
 * Retorna lista de registros en el rango de fechas desde el último fechacierre hasta hoy para un forma_pago.
 * @param int $forma_pago
 * @param mysqli $link
 * @return array
 */
function obtener_registros_credito_rango($forma_pago, $link)
{
    // Obtener el día de cierre de la forma de pago
    $sql_cierre = "SELECT fechacierre FROM formas_pago WHERE tarjeta = $forma_pago";
    $result_cierre = mysqli_query($link, $sql_cierre);
    if (!$result_cierre) {
        return [];
    }
    $row_cierre = mysqli_fetch_array($result_cierre);
    $fecha_cierre = $row_cierre ? intval($row_cierre['fechacierre']) : 28;

    // Obtener fecha actual
    $hoy = date("Y-m-d");
    $mes_actual = intval(date("m"));
    $año_actual = intval(date("Y"));
    $dia_actual = intval(date("d"));

    // Calcular rango de fechas desde el último fechacierre hasta hoy
    if ($dia_actual < $fecha_cierre) {
        $mes_cierre = $mes_actual - 1;
        $año_cierre = $año_actual;
        if ($mes_cierre <= 0) {
            $mes_cierre = 12;
            $año_cierre--;
        }
    } else {
        $mes_cierre = $mes_actual;
        $año_cierre = $año_actual;
    }
    $fecha_inicio = sprintf("%04d-%02d-%02d", $año_cierre, $mes_cierre, min($fecha_cierre, 28));
    $fecha_fin = $hoy;

    // Consultar registros de la tabla creditos en el rango
    $instruccion = "SELECT * FROM creditos WHERE forma_pago = $forma_pago AND fecha >= '$fecha_inicio' AND fecha <= '$fecha_fin'";

    //echo "<small>$instruccion</small>";

    $consulta = mysqli_query($link, $instruccion);
    if (!$consulta) {
        return [];
    }
    $registros = [];
    while ($row = mysqli_fetch_array($consulta)) {
        $registros[] = $row;
    }
    return $registros;
}

// DEBUG: Verificar parámetros
if (!isset($_GET['user']) || !isset($_GET['pago'])) {
    echo "<div class='alert alert-danger'>Error: Faltan parámetros GET</div>";
    echo "user=" . (isset($_GET['user']) ? $_GET['user'] : 'NO') . ", pago=" . (isset($_GET['pago']) ? $_GET['pago'] : 'NO');
    exit;
}

$user = $_GET['user'];
$pago = $_GET['pago'];



$info = Recuperar_tarjeta($pago, $link);

//echo "<div class='alert alert-info'>Parámetros obtenidos: user=$user, pago=$pago</div>";
//echo "<div class='alert alert-info'>Info recuperada: " . json_encode($info) . "</div>";


if ($info[1] == 0) {
    // $sum = suma_medio_pago ( $pago, $link );


} else {
    // se divide total de compre por cant. cuotas
    //
    // falta sumar cuotas adeudadas anteriores - tabla debe
    //
    //$sum = suma_medio_pago_credito ( $pago, $link );
}

$sin_decimales = number_format($sum, 2, ',', ' ');


$sum = suma_medio_pago_credito(28, $link);
//echo "<div class='alert alert-info'> suma_medio_pago_credito " . $ii . "</div>";


$max = $info[3];


$p = obtenerPorcentaje($sum, $max);

$color_class = "";
if ($p > 90) {
    $color_class = " progress-bar-danger";
} elseif ($p > 75) {
    $color_class = " progress-bar-warning";
}

$barra = "<div class='progress-bar$color_class' role='progressbar' style='width: $p%;' aria-valuenow='25' aria-valuemin='0' aria-valuemax='100'>$ $sum</div>";
$info_targeta = "<span class='text-muted small editable-info' data-pago='$pago' data-fechacierre='$info[0]' data-vto='$info[1]' data-credito='$info[2]' data-tope='$info[3]' style='cursor: pointer;'> Cierra el $info[0] - Vence el $info[1] - Maximo  </span>$ $info[3]  ";

?>

<form action='cargar_modal.php' method='post'>

    <input type='hidden' name='grabar' value='si'>
    <input type='hidden' name='user' value='<?php echo $user; ?>'>
    <input type='hidden' name='pago' value='<?php echo $pago; ?>'>


    <div class="panel-body">

        <?php
        $tope_fmt = number_format($info[3], 0, ',', '.');
        echo "
        <div class='editable-info' data-pago='$pago' data-fechacierre='$info[0]' data-vto='$info[1]' data-credito='$info[2]' data-tope='$info[3]' title='Click para editar'
             style='cursor: pointer; background-color: #f9f9f9; border: 1px solid #ddd; border-left: 4px solid #f0ad4e; border-radius: 4px; padding: 6px 8px; margin-bottom: 12px; transition: background-color 0.2s;'
             onmouseover=\"this.style.backgroundColor='#f1f1f1'\" onmouseout=\"this.style.backgroundColor='#f9f9f9'\">
            <div style='display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2px; flex-wrap: wrap; gap: 4px;'>
                <span style='font-size: 13px; font-weight: bold; color: #333; flex: 1; min-width: 120px;'>$info[4]</span>
                <span class='label label-success' style='font-size: 11px; margin-top:2px;'>Máx $ $tope_fmt</span>
            </div>
            <div style='display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #777; flex-wrap: wrap;'>
                <span style='margin-right: 5px;'>Cierra: <strong style='color:#555;'>$info[0]</strong></span>
                <span>Vence: <strong style='color:#555;'>$info[1]</strong></span>
            </div>
        </div>";
        ?>

        <div class="progress">
            <?php echo $barra; ?>
        </div>



        <div class="row">

            <div class="col-xs-7">
                <?php

                Select_item($user, $link);

                ?>

            </div>
            <div class="col-xs-5">
                <input class="form-control" type="number" name="importe" placeholder="Importe" required>
            </div>


        </div>
        <br>



        <div class="row">

            <div class="col-xs-7">
                <input class="form-control" type="date" name="fecha" type="date" value="<?php echo $hoy; ?>">
            </div>

            <div class="col-xs-5">


                <?php

                if ($info[2] == 1) {
                    echo "<input class='form-control' type='number' name='cuotas' value=1>";
                } else {

                    echo "<button type='submit' class='btn btn-danger btn-block'>Pagar</button>";
                }

                ?>

            </div>

        </div>

            </div>

        </div>

        <br>

        <div class="row">


            <div class="col-xs-8">
                <!-- Se movió la información de la tarjeta arriba de la barra de progreso -->
            </div>

            <div class="col-xs-4">
                <?php

                if ($info[2] == 1) {
                    echo "<button type='submit' class='btn btn-danger btn-block'> Pagar</button>";
                }

                ?>
            </div>

        </div>

        <br>


</form>

<div style="margin-top:10px;">
    <?php
    $lis = Recuperar_Tabla_compras($link);
    Mostrar_tabla_compras_cel($lis, $pago, $user);
    ?>

    <div class="text-center" style="margin:10px 0;"><span class="label label-default">Cuotas pendientes</span></div>

    <?php
    $lis2 = obtener_registros_credito_rango($pago, $link);
    Mostrar_tabla_creditos($lis2);
    ?>
</div>
</div>

<!-- Modal para editar información de tarjeta -->
<div id="editModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Editar Información de Tarjeta</h4>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="form-group">
                        <label for="fechacierre">Día de Cierre:</label>
                        <input type="number" class="form-control" id="fechacierre" name="fechacierre" min="1" max="31"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="vto">Días de Vencimiento:</label>
                        <input type="number" class="form-control" id="vto" name="vto" required>
                    </div>
                    <div class="form-group">
                        <label for="credito">¿Es Crédito?</label>
                        <select class="form-control" id="credito" name="credito">
                            <option value="0">No (Débito)</option>
                            <option value="1">Sí (Crédito)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tope">Límite de Crédito ($):</label>
                        <input type="number" class="form-control" id="tope" name="tope" required>
                    </div>
                    <input type="hidden" id="pago" name="pago">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveBtn">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Al hacer click en el span editable
        $('.editable-info').click(function () {
            var pago = $(this).data('pago');
            var fechacierre = $(this).data('fechacierre');
            var vto = $(this).data('vto');
            var credito = $(this).data('credito');
            var tope = $(this).data('tope');

            // Llenar el formulario del modal con los valores actuales
            $('#pago').val(pago);
            $('#fechacierre').val(fechacierre);
            $('#vto').val(vto);
            $('#credito').val(credito);
            $('#tope').val(tope);

            // Mostrar el modal
            $('#editModal').modal('show');
        });

        // Al hacer click en guardar
        $('#saveBtn').click(function () {
            var formData = {
                pago: $('#pago').val(),
                fechacierre: $('#fechacierre').val(),
                vto: $('#vto').val(),
                credito: $('#credito').val(),
                tope: $('#tope').val()
            };

            console.log('Enviando datos:', formData);

            // Enviar los datos al servidor
            $.ajax({
                type: 'POST',
                url: 'update_formas_pago.php',
                data: formData,
                success: function (response) {
                    console.log('Respuesta:', response);
                    if (response.success) {
                        alert('Datos actualizados correctamente');
                        location.reload(); // Recargar la página para ver los cambios
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                dataType: 'json',
                error: function (xhr, status, error) {
                    console.log('Error:', error);
                    alert('Error al procesar la solicitud');
                }
            });
        });
    });
</script>

</body>

</html>