<?php 
require_once('conexion/db.php'); 

 $tipoguardar = '';
if(isset($_GET['tipoguardar']) && $_GET['tipoguardar']!=''){
  $tipoguardar = $_GET['tipoguardar'];
}


 $cotizacion_det = '';
if(isset($_POST['cod_cotizacion_det']) && $_POST['cod_cotizacion_det']!=''){
$cotizacion_det = $_POST['cod_cotizacion_det'];
}


if($tipoguardar=='save'){
				$query_RsDatosCotizacion = "SELECT C.COTICODI CODIGO_COTIZACION,
												   C.COTIPROV CODIGO_PROVEEDOR,
												   D.CODECOTI CODIGO_COTIZACIONN,
												   D.CODEDETA CODIGO_DETALLE,
												   D.CODECODI CONSECUTIVO,
												  DE.DEREDESC DESCRIPCION,
												  DE.DERECANT CANTIDAD
												FROM 
												COTIZACION C,
												COTIZACION_DETALLE D,
												DETALLE_REQU      DE
											WHERE D.CODECODI = '".$cotizacion_det."'
											  AND C.COTICODI = D.CODECOTI	
											  AND D.CODEDETA = DE.DERECONS
											  
												 ";
										//echo($query_RsDatosCotizacion);
										//exit();
				$RsDatosCotizacion = mysqli_query($conexion,$query_RsDatosCotizacion) or die(mysqli_connect_error());
				$row_RsDatosCotizacion = mysqli_fetch_array($RsDatosCotizacion);
				$totalRows_RsDatosCotizacion = mysqli_num_rows($RsDatosCotizacion);			
				if($totalRows_RsDatosCotizacion>0){
				  do{  

				      if(isset($_POST['vtotal_'.$row_RsDatosCotizacion['CONSECUTIVO']]) && $_POST['vtotal_'.$row_RsDatosCotizacion['CONSECUTIVO']] !=''){
				      	
				        $query_RsInsertDetalle = "UPDATE COTIZACION_DETALLE 
														SET CODEDESC = '".$_POST['objetivo_'.$row_RsDatosCotizacion['CONSECUTIVO']]."',
															CODEUMED = '".$_POST['unidad_'.$row_RsDatosCotizacion['CONSECUTIVO']]."',
															CODECANT = '".$_POST['cantidad_'.$row_RsDatosCotizacion['CONSECUTIVO']]."',
															CODEVALO = '".$_POST['v_unit_'.$row_RsDatosCotizacion['CONSECUTIVO']]."',
															CODEFEIN = '".$_POST['fechaInicio_'.$row_RsDatosCotizacion['CONSECUTIVO']]."',
															CODEFEFI = '".$_POST['fechaFin_'.$row_RsDatosCotizacion['CONSECUTIVO']]."'					
														 WHERE  CODECODI = '".$row_RsDatosCotizacion['CONSECUTIVO']."'";
											 //echo( $query_RsInsertDetalle);
						$RsInsertDetalle = mysqli_query($conexion,$query_RsInsertDetalle) or die(mysqli_connect_error());	    
					  
                         $query_RsInsertGeneral = "UPDATE cotizacion SET COTITOTA = '".$_POST['vtotal_'.$row_RsDatosCotizacion['CONSECUTIVO']]."'																			
																	WHERE COTICODI = '".$_POST['cod_cotizacion']."'";
												//echo($query_RsInsertGeneral);
						$RsInsertGeneral = mysqli_query($conexion,$query_RsInsertGeneral) or die(mysqli_connect_error());

                        

					  }
				    }while($row_RsDatosCotizacion = mysqli_fetch_array($RsDatosCotizacion));

				   
}
header("location: home.php?page=orden&codorden=".$_POST['cod_orden']);
}

?>
