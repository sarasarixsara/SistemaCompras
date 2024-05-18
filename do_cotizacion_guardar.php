<?php 
require_once('conexion/db.php'); 

 $tipoguardar = '';
if(isset($_GET['tipoguardar']) && $_GET['tipoguardar']!=''){
  $tipoguardar = $_GET['tipoguardar'];
}

 $codigo_cotizacion = '';
if(isset($_POST['codigo_cotizacion']) && $_POST['codigo_cotizacion']!=''){
 $codigo_cotizacion = $_POST['codigo_cotizacion'];
}
 $observaciones = '';
if(isset($_POST['observaciones']) && $_POST['observaciones']!=''){
 $observaciones = $_POST['observaciones'];
}
 $forma_pago = '';
if(isset($_POST['forma_pago']) && $_POST['forma_pago']!=''){
 $forma_pago = $_POST['forma_pago'];
}

 $garantia = '';
if(isset($_POST['garantia']) && $_POST['garantia']!=''){
 $garantia = $_POST['garantia'];
}

 $tiempo_entrega = '';
if(isset($_POST['tiempo_entrega']) && $_POST['tiempo_entrega']!=''){
 $tiempo_entrega = $_POST['tiempo_entrega'];
}

 $sitio_entrega = '';
if(isset($_POST['sitio_entrega']) && $_POST['sitio_entrega']!=''){
 $sitio_entrega = $_POST['sitio_entrega'];
}

 $persona_cotizacion = '';
if(isset($_POST['persona_cotizacion']) && $_POST['persona_cotizacion']!=''){
 $persona_cotizacion = $_POST['persona_cotizacion'];
}

 $ced_pers_cotizacion = '';
if(isset($_POST['ced_pers_cotizacion']) && $_POST['ced_pers_cotizacion']!=''){
 $ced_pers_cotizacion = $_POST['ced_pers_cotizacion'];
}

$total = '';
if(isset($_POST['total']) && $_POST['total']!=''){
 $total = $_POST['total'];
}

$flete = '';
if(isset($_POST['flete']) && $_POST['flete']!=''){
 $flete = $_POST['flete'];
}
$v_agregado = '';
if(isset($_POST['v_agregado']) && $_POST['v_agregado']!=''){
 $v_agregado = $_POST['v_agregado'];
}


//exit($v_agregado);
 $link = '';
if(isset($_POST['link']) && $_POST['link']!=''){
 $link = $_POST['link'];
}
if($tipoguardar=='save1'){
	if(!isset($_SESSION['MM_AccesoCorrectoApp']) || $_SESSION['MM_AccesoCorrectoApp'] != 'ACTIVO'){
	  header("location: index.php");
	}
}	
if($tipoguardar=='save'){
      
   if($link != ''){
		$data = explode("_",$link);
		$link = $link;
		if(count($data)==2){
			//echo("hola");
			$query_RsConsultarKey ="SELECT L.PRLICODI CODIGO,
										   L.PRLICOTI CODIGO_COTIZACION,
										   L.PRLILINK LINK,
										   L.PRLIESTA ESTADO								   
									 FROM PROVEEDOR_LINKS L
									WHERE L.PRLICOTI = '".$data[1]."'
									  and L.PRLILINK = '".$link."'
									  and L.PRLIESTA = 1";
									//echo $query_RsConsultarKey;
			$RsConsultarKey = mysqli_query($conexion,$query_RsConsultarKey) or die(mysqli_connect_error());
			$row_RsConsultarKey = mysqli_fetch_array($RsConsultarKey);
			$totalRows_RsConsultarKey = mysqli_num_rows($RsConsultarKey); 
			if($totalRows_RsConsultarKey>0){
			
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
											WHERE C.COTICODI = '".$row_RsConsultarKey['CODIGO_COTIZACION']."'
											  AND C.COTICODI = D.CODECOTI	
											  AND D.CODEDETA = DE.DERECONS
											  
												 ";
										//echo($query_RsDatosCotizacion);
				$RsDatosCotizacion = mysqli_query($conexion,$query_RsDatosCotizacion) or die(mysqli_connect_error());
				$row_RsDatosCotizacion = mysqli_fetch_array($RsDatosCotizacion);
				$totalRows_RsDatosCotizacion = mysqli_num_rows($RsDatosCotizacion);			
				if($totalRows_RsDatosCotizacion>0){
				  do{
				      if(isset($_POST['valor_'.$row_RsDatosCotizacion['CONSECUTIVO']]) && $_POST['valor_'.$row_RsDatosCotizacion['CONSECUTIVO']]!=''){
				        $query_RsInsertDetalle = "UPDATE COTIZACION_DETALLE 
											SET CODEVALO = '".$_POST['valor_'.$row_RsDatosCotizacion['CONSECUTIVO']]."',
												CODEDESC = '".$_POST['areaprov_'.$row_RsDatosCotizacion['CONSECUTIVO']]."',
												CODEVAIV = '".$_POST['valoriva_'.$row_RsDatosCotizacion['CONSECUTIVO']]."',
												CODEVIVA = '".$_POST['valorivapre_'.$row_RsDatosCotizacion['CONSECUTIVO']]."'
											 WHERE  CODECODI = '".$row_RsDatosCotizacion['CONSECUTIVO']."'";
											 
						$RsInsertDetalle = mysqli_query($conexion,$query_RsInsertDetalle) or die(mysqli_connect_error());	    
					  }
				    }while($row_RsDatosCotizacion = mysqli_fetch_array($RsDatosCotizacion));
					
$opt = $tiempo_entrega; /* $opt variable de tipo criterio a calcular en el rango*/
$rango = '';
$query_RsDatosTiempoEntrega = "SELECT CM.CRMECONS CODIGO,
                                      CM.CRMEDESC NOMBRE,
									  CM.CRMERAIN RANGO_INICIO,
									  CM.CRMERAFI RANGO_FIN
									FROM criterio_medicion CM, 
										 criterio           C, 
										 tipo_criterio     TC
									WHERE   CRMETICR=CRITCONS 
									AND     TICRID=CRITTICR
									AND     CRMETICR=4";
		$RsDatosTiempoEntrega = mysqli_query($conexion,$query_RsDatosTiempoEntrega) or die(mysqli_connect_error());
		$row_RsDatosTiempoEntrega = mysqli_fetch_array($RsDatosTiempoEntrega);
		$totalRows_RsDatosTiempoEntrega = mysqli_num_rows($RsDatosTiempoEntrega);				
		if($totalRows_RsDatosTiempoEntrega>0){
			do{
				if($opt >= $row_RsDatosTiempoEntrega['RANGO_INICIO'] && $opt <= $row_RsDatosTiempoEntrega['RANGO_FIN']){
					$rango = $row_RsDatosTiempoEntrega['CODIGO'];
				}
				if($opt >= $row_RsDatosTiempoEntrega['RANGO_INICIO'] && $row_RsDatosTiempoEntrega['RANGO_FIN']=='+'){
					$rango = $row_RsDatosTiempoEntrega['CODIGO'];
				}
			}while($row_RsDatosTiempoEntrega = mysqli_fetch_array($RsDatosTiempoEntrega));
		}					
					
					$query_RsInsertGeneral = "UPDATE cotizacion SET COTIFORE = '1',
																	COTIESTA = '1',
																	COTIOBSE = '".$observaciones."', 
																	COTIFOPA = '".$forma_pago."',
																	COTIGARA = '".$garantia."',
																	COTITIEN = '".$rango."',
																	COTITIEN2 = '".$tiempo_entrega."',
																	COTISIEN = '".$sitio_entrega."',
																	COTIVAAG = '".$v_agregado."',
																	COTITOTA = '".$total."',
																	COTIFLET = '".$flete."'
																	
																	
																																		
																	WHERE COTICODI = '".$codigo_cotizacion."'";
												//echo($query_RsInsertGeneral);
					$RsInsertGeneral = mysqli_query($conexion,$query_RsInsertGeneral) or die(mysqli_connect_error());
                    
					$query_RsInsertGeneral1 = " UPDATE PROVEEDOR_LINKS SET PRLIESTA = 2 WHERE PRLICODI = '".$row_RsConsultarKey['CODIGO']."'";
					
					$RsInsertGeneral1 = mysqli_query($conexion,$query_RsInsertGeneral1) or die(mysqli_connect_error());
                         //echo($RsInsertGeneral);
					   header("location: coti_enviada.php?cot=".$codigo_cotizacion."");				
			
			    
				}

			}else{ ECHO($data[1].$link);
				//header("location: coti_noenviada.php");
			}
		}
    }else{ECHO(2);
				header("location: coti_noenviada.php?cot=".$codigo_cotizacion."");
			}
	//header("location: coti_noenviada.php");
	//exit('el ingreso actual no esta autorizado');
}  

if($tipoguardar=='save1'){
	
      
   //if($link != ''){
	//	$data = explode("_",$link);
		/*$link = $link;
		if(count($data)==2){
			//echo("hola");
			$query_RsConsultarKey ="SELECT L.PRLICODI CODIGO,
										   L.PRLICOTI CODIGO_COTIZACION,
										   L.PRLILINK LINK,
										   L.PRLIESTA ESTADO								   
									 FROM PROVEEDOR_LINKS L
									WHERE L.PRLICOTI = '".$data[1]."'
									  and L.PRLILINK = '".$link."'
									  and L.PRLIESTA = 1";
									//echo $query_RsConsultarKey;
			$RsConsultarKey = mysqli_query($conexion,$query_RsConsultarKey) or die(mysqli_connect_error());
			$row_RsConsultarKey = mysqli_fetch_array($RsConsultarKey);
			$totalRows_RsConsultarKey = mysqli_num_rows($RsConsultarKey); 
			if($totalRows_RsConsultarKey>0){
			*/
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
											WHERE C.COTICODI = '".$codigo_cotizacion."'
											  AND C.COTICODI = D.CODECOTI	
											  AND D.CODEDETA = DE.DERECONS
											  
												 ";
										//echo($query_RsDatosCotizacion);
				$RsDatosCotizacion = mysqli_query($conexion,$query_RsDatosCotizacion) or die(mysqli_connect_error());
				$row_RsDatosCotizacion = mysqli_fetch_array($RsDatosCotizacion);
				$totalRows_RsDatosCotizacion = mysqli_num_rows($RsDatosCotizacion);			
				if($totalRows_RsDatosCotizacion>0){
				  do{
				      if(isset($_POST['valor_'.$row_RsDatosCotizacion['CONSECUTIVO']]) && $_POST['valor_'.$row_RsDatosCotizacion['CONSECUTIVO']]!=''){
				        $query_RsInsertDetalle = "UPDATE COTIZACION_DETALLE 
						                        SET CODEVALO = '".$_POST['valor_'.$row_RsDatosCotizacion['CONSECUTIVO']]."',
						                            CODEDESC = '".$_POST['areaprov_'.$row_RsDatosCotizacion['CONSECUTIVO']]."',
						                            CODEVAIV = '".$_POST['valoriva_'.$row_RsDatosCotizacion['CONSECUTIVO']]."',
						                            CODEVIVA = '".$_POST['valorivapre_'.$row_RsDatosCotizacion['CONSECUTIVO']]."'
											 WHERE  CODECODI = '".$row_RsDatosCotizacion['CONSECUTIVO']."'";
											 
						$RsInsertDetalle = mysqli_query($conexion,$query_RsInsertDetalle) or die(mysqli_connect_error());
						
						$query_RsupdateEstaDet = "UPDATE DETALLE_REQU SET DEREAPRO = '15' WHERE DERECONS = ".$row_RsDatosCotizacion['CODIGO_DETALLE'].";";
					    $RsupdateEstaDet = mysqli_query($conexion,$query_RsupdateEstaDet) or die(mysqli_error($conexion)); 
						
					  }
				    }while($row_RsDatosCotizacion = mysqli_fetch_array($RsDatosCotizacion));
					
$opt = $tiempo_entrega; /* $opt variable de tipo criterio a calcular en el rango*/
$rango = '';
$query_RsDatosTiempoEntrega = "SELECT CM.CRMECONS CODIGO,
                                      CM.CRMEDESC NOMBRE,
									  CM.CRMERAIN RANGO_INICIO,
									  CM.CRMERAFI RANGO_FIN
									FROM criterio_medicion CM, 
										 criterio           C, 
										 tipo_criterio     TC
									WHERE   CRMETICR=CRITCONS 
									AND     TICRID=CRITTICR
									AND     CRMETICR=4";
		$RsDatosTiempoEntrega = mysqli_query($conexion,$query_RsDatosTiempoEntrega) or die(mysqli_connect_error());
		$row_RsDatosTiempoEntrega = mysqli_fetch_array($RsDatosTiempoEntrega);
		$totalRows_RsDatosTiempoEntrega = mysqli_num_rows($RsDatosTiempoEntrega);				
		if($totalRows_RsDatosTiempoEntrega>0){
			do{
				if($opt >= $row_RsDatosTiempoEntrega['RANGO_INICIO'] && $opt <= $row_RsDatosTiempoEntrega['RANGO_FIN']){
					$rango = $row_RsDatosTiempoEntrega['CODIGO'];
				}
				if($opt >= $row_RsDatosTiempoEntrega['RANGO_INICIO'] && $row_RsDatosTiempoEntrega['RANGO_FIN']=='+'){
					$rango = $row_RsDatosTiempoEntrega['CODIGO'];
				}
			}while($row_RsDatosTiempoEntrega = mysqli_fetch_array($RsDatosTiempoEntrega));
		}
					
					
					$query_RsInsertGeneral = "UPDATE cotizacion SET COTIFORE = '0',
																	COTIESTA = '1',
																	COTIOBSE = '".$observaciones."', 
																	COTIFOPA = '".$forma_pago."',
																	COTIGARA = '".$garantia."',
																	COTITIEN = '".$rango."',
																	COTITIEN2 = '".$tiempo_entrega."',
																	COTISIEN = '".$sitio_entrega."',
																	COTIVAAG = '".$v_agregado."',
																	COTITOTA = '".$total."',
																	COTIFLET = '".$flete."'
																	
																	
																																		
																	WHERE COTICODI = '".$codigo_cotizacion."'";
												//echo($query_RsInsertGeneral);
					$RsInsertGeneral = mysqli_query($conexion,$query_RsInsertGeneral) or die(mysqli_connect_error());
					/*
					$query_RsupdateCot = "UPDATE COTIZACION SET COTIFORE = '0' WHERE `COTIZACION`.`COTICODI` = ".$codigo_cotizacion.";";
							 $RsupdateCot = mysqli_query($conexion,$query_RsupdateCot) or die(mysqli_error($conexion)); 
							 */
                    
					//$query_RsInsertGeneral1 = " UPDATE PROVEEDOR_LINKS SET PRLIESTA = 2 WHERE PRLICODI = '".$row_RsConsultarKey['CODIGO']."'";
					
					//$RsInsertGeneral1 = mysqli_query($conexion,$query_RsInsertGeneral1) or die(mysqli_connect_error());
                         //echo($RsInsertGeneral);
					  header("location: coti_enviada.php?cot=".$codigo_cotizacion."");					
			
			    
				}

			//}else{
				//header("location: coti_noenviada.php");
			//}
		//}
    //}else{
		//		header("location: coti_noenviada.php");
		//	}
	//header("location: coti_noenviada.php");
	//exit('el ingreso actual no esta autorizado');
	
}

?>