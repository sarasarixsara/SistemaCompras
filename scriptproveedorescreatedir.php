<?php 
require_once('conexion/db.php');
function VerificarExisteDirectorio($carpeta_prov){
	$existe = 'failed'; /* inicia no existe directorio */
		if (file_exists($carpeta_prov)) {
			$existe = 'nook'; /* directorio existe*/
		} else {
			if(!mkdir($carpeta_prov, 0777, true)) {
				die('');
				$existe = 'nook';
			}else{
				$existe = 'ok';
			}
		}
	return $existe;	
}
function CrearCarpetasProveedores($conexion){
	$query_RsProveedores = "SELECT * FROM PROVEEDORES WHERE 1";
	$RsProveedores = mysqli_query($conexion,$query_RsProveedores) or die(mysqli_error($conexion));
	$row_RsProveedores = mysqli_fetch_array($RsProveedores);
	$totalRows_RsProveedores = mysqli_num_rows($RsProveedores);
	$carpetas_creadas = 0;
	if($totalRows_RsProveedores > 0){
		do{
		$dir_destino = 'archivos_compras/PROVEEDORES/'.$row_RsProveedores['PROVCODI'];
		$dir_origen  = 'proveedores/publico/php/temporalfiles/';
		$carpeta_prov = "archivos_compras/PROVEEDORES/".$row_RsProveedores['PROVCODI'];
		$verificar_directorio = VerificarExisteDirectorio($dir_destino);
		if($verificar_directorio == 'ok'){
			$carpetas_creadas++;			
		}
		}while($row_RsProveedores = mysqli_fetch_array($RsProveedores));
	}
	return $carpetas_creadas;
	
}

	$carpetas_creadas = CrearCarpetasProveedores($conexion);
	echo("resultado de carpetas creadas = ".$carpetas_creadas);	
?>