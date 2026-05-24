<?php

include ("../conexion_compras.php");

$hoy = date("Y-m-d");

$link=mysqli_connect($host, $usuario, $clave, $bd);

Function Cargar_compra ( $fe, $im, $cu, $se, $pa, $user, $link ){
	

	$sql = "insert into capturas ( fecha, importe, cuotas, subfamilia, forma_pago, usuario ) values ('$fe', $im, $cu,'$se', '$pa', '$user')";
	$result = mysqli_query( $link, $sql );
	$nuevo_id = mysqli_insert_id($link);
	return $nuevo_id;

	//echo "<H2>$sql</H2><P>";
}
Function Si_es_credito ( $fe, $im, $cu, $se, $pa, $user, $id_nuevo, $link ){
	
	$importe_cuota = $im / $cu;
	
	for ($i=1; $i<=$cu; $i++){
		$fe_cuota = date("Y-m-d", strtotime($fe . " +$i month"));
		$sql = "insert into creditos ( fecha, importe, cuotas, captura,  subfamilia, forma_pago, usuario, cuota_numero ) values ('$fe_cuota', $importe_cuota, $cu, $id_nuevo, '$se', '$pa', '$user', $i)";
	    $result = mysqli_query( $link, $sql );

		//echo "<H2>$sql</H2><P>";
	}
	
}

if ($_POST['grabar']=="si"){ 

    $user = $_POST['user'];
	$pago = $_POST['pago'];
	$fe = $_POST['fecha'];
	$im = $_POST['importe'];
	$se = $_POST['seleccion'];
	$cu = $_POST['cuotas']; if ($cu == ""){$cu=1;}


	if ($fe == "")	{$fe = $hoy;}
    
	if ($im == "")
	{
		echo "<H2>Colocar el importe</H2><P>";	
	}	else {

		

		$id_nuevo = Cargar_compra ( $fe, $im, $cu, $se, $pago, $user, $link );
		if ($cu > 1){
		
		    Si_es_credito ( $fe, $im, $cu, $se, $pago, $user, $id_nuevo, $link ); // carga las cuotas en otra tabla
	    }
			
		 $url = "Location: usuarios_tarjetas.php?user=$user";
		 Header($url);
		
	}


}
?>