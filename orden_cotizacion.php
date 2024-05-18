<?php
require_once('conexion/db.php');
	if (!isset($_SESSION)) {
  session_start();
}
//Variable Crear Usuarios
$tipoGuardar='';
if(isset($_GET['tipoGuardar']) && $_GET['tipoGuardar']!=''){
$tipoGuardar =$_GET['tipoGuardar'];
}

if($tipoGuardar=='CrearOrdenCotizacion'){
	if(isset($_POST['json']) && $_POST['json']!=''){
	$codigo_orden ='';
     $array = json_decode($_POST['json']);
		 if(is_array($array)){
		   $query_RsInsertarOrden ="INSERT INTO COTIZACION_ORDEN (
		                                                          COORCODI,
																  COORFECR,
																  COORPERE
																  )
																  VALUES
																  (
																   NULL,
																   SYSDATE(),
																   '".$_SESSION['MM_UserID']."'
																  )";
           $RsInsertarOrden = mysqli_query($conexion,$query_RsInsertarOrden) or die(mysqli_error($conexion));	

				$query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
				$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
				$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);
				$codigo_orden = $row_RsUltInsert['DATO'];		   
		 //print_r($array);
		 //dos formas primera 
		   /*for($i=0; $i<count($array); $i++){
		       $idproveedor = $array[$i]->val;
		        if(is_array($array[$i]->det)){
			    // echo(count($array[$i]->det));
			    $detalles = $array[$i]->det;
			    for($k=0; $k<count($detalles); $k++){
				 //echo($detalles[$k]->d);
				}
			  }
		   }
		   */
		   //segunda forma 
		   for($i=0; $i<count($array); $i++){
		    $idproveedor = $array[$i]->val;
			
						$query_RsInsertCotizacion = "INSERT INTO COTIZACION (
																			 COTICODI,
																			 COTIORDE,
																			 COTIPROV,
																			 COTIFECH,
																			 COTIFEEN,
																			 COTIESTA,
																			 COTIPERS
																			)
																			VALUES
																			(
																			 NULL,
																			 '".$codigo_orden."',
																			 '".$idproveedor."',
																			 sysdate(),
																			 sysdate(),
																			 0,
																			 '".$_SESSION['MM_UserID']."'
																			)";
					$RsInsertCotizacion = mysqli_query($conexion,$query_RsInsertCotizacion) or die(mysqli_error($conexion)); 

					$query_RsTodosLosDetalles="SELECT LAST_INSERT_ID() DATO";
					$RsTodosLosDetalles = mysqli_query($conexion,$query_RsTodosLosDetalles) or die(mysqli_error($conexion));
					$row_RsTodosLosDetalles = mysqli_fetch_array($RsTodosLosDetalles);
					//$totalRows_RsTodosLosDetalles = mysqli_num_rows($RsTodosLosDetalles);
					$ultima_cotizacion = $row_RsTodosLosDetalles['DATO'];

			
			$updrequ='';
		      if(is_array($array[$i]->det) && count($array[$i]->det)>0){
			 // echo(count($array[$i]->det));
			    $detalles = $array[$i]->det;
			    for($k=0; $k<count($array[$i]->det); $k++){
				 //echo($array[$i]->det[$k]->d.'-');
				 $query_RsInsertDetallesCotizacion ="INSERT INTO COTIZACION_DETALLE ( 
																					 CODECODI,
																					 CODECOTI,
																					 CODEDETA
																					)
																					VALUES
																					(
																					NULL,
																					'".$ultima_cotizacion."',
																					'".$array[$i]->det[$k]->d."'
																					)
																					";
				 $RsInsertDetallesCotizacion = mysqli_query($conexion,$query_RsInsertDetallesCotizacion) or die(mysqli_error($conexion)); 
				 $updrequ=1;
				if($updrequ==1){
				 /*actualizar el estado del detalle elaborando cotizacion*/
				$query_RsUpdDetalles =" UPDATE DETALLE_REQU SET DEREAPRO = '10' WHERE DERECONS = '".$array[$i]->det[$k]->d."'";
				$RsUpdDetalles = mysqli_query($conexion,$query_RsUpdDetalles) or die(mysqli_error($conexion)); 
                 /*actualiza el estado requerimiento*/				
                    $query_RsUpdateReque_Detalle = "SELECT DEREREQU CODIGO_REQUERIMIENTO
											  FROM detalle_requ 
											  WHERE DERECONS='".$array[$i]->det[$k]->d."'";
					$RsUpdateReque_Detalle = mysqli_query($conexion,$query_RsUpdateReque_Detalle) or die(mysqli_error($conexion));
					$row_RsUpdateReque_Detalle = mysqli_fetch_array($RsUpdateReque_Detalle);
					$totalRows_RsUpdateReque_Detalle = mysqli_num_rows($RsUpdateReque_Detalle);
				
				/*$query_RsUpdDetalles =" UPDATE requerimientos SET REQUFECO = sysdate(),
																  REQUPECO = '".$_SESSION['MM_UserID']."'																  
																  WHERE REQUCODI ='".$row_RsUpdateReque_Detalle['CODIGO_REQUERIMIENTO']."' ";
				$RsUpdDetalles = mysqli_query($conexion,$query_RsUpdDetalles) or die(mysqli_error($conexion)); */
				$updrequ='';
					
				}
				}

			  }
			  
			  		$proveedor_cotizar = $idproveedor;
	                //include("correo_cotizacion.php");
		   }
		 }
		 echo($codigo_orden);
	}else{
	echo('none');
	}
}
if($tipoGuardar=='TodosAprobDetalle'){
 	$query_RsTodosLosDetalles = "SELECT COUNT(D.DERECONS) TOTAL
 	                              FROM DETALLE_REQU D
								 WHERE D.DEREREQU = '".$_GET['codreq']."'
								   AND D.DEREAPRO != 1 ";
	$RsTodosLosDetalles = mysqli_query($conexion,$query_RsTodosLosDetalles) or die(mysqli_error($conexion));
	$row_RsTodosLosDetalles = mysqli_fetch_array($RsTodosLosDetalles);
    $totalRows_RsTodosLosDetalles = mysqli_num_rows($RsTodosLosDetalles);
	if($totalRows_RsTodosLosDetalles>0){
	  echo($row_RsTodosLosDetalles['TOTAL']);
	}else{
	 echo('none');
	}
}

?>