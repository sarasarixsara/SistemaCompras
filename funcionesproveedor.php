<?php
//inicio del php

require_once('conexion/db.php');



    //consulta rol
	/*Actualizar el valor de provcodi a los detalles*/
	/*
   $query_RsListaDetalles="SELECT D.DERECONS CODIGO,
                                  D.DEREPROV PROVEEDOR
						     FROM detalle_requ D
							where D.DEREPROV != ''
								   ";
	$RsListaDetalles = mysqli_query($conexion,$query_RsListaDetalles) or die(mysqli_error());
	$row_RsListaDetalles = mysqli_fetch_array($RsListaDetalles);
    $totalRows_RsListaDetalles = mysqli_num_rows($RsListaDetalles);
	if($totalRows_RsListaDetalles>0){
		do{
			$query_RsDatosProv = "SELECT P.PROVCODI CODIGO FROM proveedores P where P.PROVREGI = '".$row_RsListaDetalles['PROVEEDOR']."'";
			$RsDatosProv = mysqli_query($conexion,$query_RsDatosProv) or die(mysqli_error());
			$row_RsDatosProv = mysqli_fetch_array($RsDatosProv);
			$totalRows_RsDatosProv = mysqli_num_rows($RsDatosProv);			
			if($totalRows_RsDatosProv>0){
				do{
				  $query_RsUpdateProv = "UPDATE detalle_requ set DEREPROV = '".$row_RsDatosProv['CODIGO']."' where DERECONS =  '".$row_RsListaDetalles['CODIGO']."';";
				  //echo($query_RsUpdateProv.'<br>');
				  $RsUpdateProv = mysqli_query($conexion,$query_RsUpdateProv) or die(mysqli_error());		
				}while($row_RsDatosProv = mysqli_fetch_array($RsDatosProv));
			}
		}while($row_RsListaDetalles = mysqli_fetch_array($RsListaDetalles));
	}
	*/
	/**ACTUALIZAR COTIZACIONES LO DE PROVEEDOR*/
   /*$query_RsListaDetalles="SELECT C.COTICODI CODIGO,
                                  C.COTIPROV PROVEEDOR
						     FROM cotizacion C
								   ";
	$RsListaDetalles = mysqli_query($conexion,$query_RsListaDetalles) or die(mysqli_error());
	$row_RsListaDetalles = mysqli_fetch_array($RsListaDetalles);
    $totalRows_RsListaDetalles = mysqli_num_rows($RsListaDetalles);
	if($totalRows_RsListaDetalles>0){
		do{
			$query_RsDatosProv = "SELECT P.PROVCODI CODIGO FROM proveedores P where P.PROVREGI = '".$row_RsListaDetalles['PROVEEDOR']."'";
			$RsDatosProv = mysqli_query($conexion,$query_RsDatosProv) or die(mysqli_error());
			$row_RsDatosProv = mysqli_fetch_array($RsDatosProv);
			$totalRows_RsDatosProv = mysqli_num_rows($RsDatosProv);			
			if($totalRows_RsDatosProv>0){
				do{
				  $query_RsUpdateProv = "UPDATE cotizacion set COTIPROV = '".$row_RsDatosProv['CODIGO']."' where COTICODI =  '".$row_RsListaDetalles['CODIGO']."';";
				  //echo($query_RsUpdateProv.'<br>');
				  $RsUpdateProv = mysqli_query($conexion,$query_RsUpdateProv) or die(mysqli_error());		
				}while($row_RsDatosProv = mysqli_fetch_array($RsDatosProv));
			}
		}while($row_RsListaDetalles = mysqli_fetch_array($RsListaDetalles));
	}	
	*/
	/* actualizar convenios solo APLICAR LOCAL*/
	/*
	$query_RsListaDetalles="SELECT C.CONVCONS CODIGO,
                                  C.CONVIDPR PROVEEDOR
						     FROM convenios C
								   ";
	$RsListaDetalles = mysqli_query($conexion,$query_RsListaDetalles) or die(mysqli_error());
	$row_RsListaDetalles = mysqli_fetch_array($RsListaDetalles);
    $totalRows_RsListaDetalles = mysqli_num_rows($RsListaDetalles);
	if($totalRows_RsListaDetalles>0){
		do{
			$query_RsDatosProv = "SELECT P.PROVCODI CODIGO FROM proveedores P where P.PROVREGI = '".$row_RsListaDetalles['PROVEEDOR']."'";
			$RsDatosProv = mysqli_query($conexion,$query_RsDatosProv) or die(mysqli_error());
			$row_RsDatosProv = mysqli_fetch_array($RsDatosProv);
			$totalRows_RsDatosProv = mysqli_num_rows($RsDatosProv);			
			if($totalRows_RsDatosProv>0){
				do{
				  $query_RsUpdateProv = "UPDATE convenios set CONVIDPR = '".$row_RsDatosProv['CODIGO']."' where CONVCONS =  '".$row_RsListaDetalles['CODIGO']."';";
				  //echo($query_RsUpdateProv.'<br>');
				  $RsUpdateProv = mysqli_query($conexion,$query_RsUpdateProv) or die(mysqli_error());		
				}while($row_RsDatosProv = mysqli_fetch_array($RsDatosProv));
			}
		}while($row_RsListaDetalles = mysqli_fetch_array($RsListaDetalles));
	}
	*/
	
	//corregir prov en la tabla orden_compra
	/*$query_RsListaDetalles="SELECT C.ORCOCONS CODIGO,
                                  C.ORCOIDPR PROVEEDOR
						     FROM orden_compra C
								   ";
	$RsListaDetalles = mysqli_query($conexion,$query_RsListaDetalles) or die(mysqli_error());
	$row_RsListaDetalles = mysqli_fetch_array($RsListaDetalles);
    $totalRows_RsListaDetalles = mysqli_num_rows($RsListaDetalles);
	if($totalRows_RsListaDetalles>0){
		do{
			$query_RsDatosProv = "SELECT P.PROVCODI CODIGO FROM proveedores P where P.PROVREGI = '".$row_RsListaDetalles['PROVEEDOR']."'";
			$RsDatosProv = mysqli_query($conexion,$query_RsDatosProv) or die(mysqli_error());
			$row_RsDatosProv = mysqli_fetch_array($RsDatosProv);
			$totalRows_RsDatosProv = mysqli_num_rows($RsDatosProv);			
			if($totalRows_RsDatosProv>0){
				do{
				  $query_RsUpdateProv = "UPDATE orden_compra set ORCOIDPR = '".$row_RsDatosProv['CODIGO']."' where ORCOCONS =  '".$row_RsListaDetalles['CODIGO']."';";
				  //echo($query_RsUpdateProv.'<br>');
				  $RsUpdateProv = mysqli_query($conexion,$query_RsUpdateProv) or die(mysqli_error());		
				}while($row_RsDatosProv = mysqli_fetch_array($RsDatosProv));
			}
		}while($row_RsListaDetalles = mysqli_fetch_array($RsListaDetalles));
	}	

	*/
	//tabla actualiza proveedor clasificacion
	$query_RsListaDetalles="SELECT D.PRCLCODI CODIGO,
                                  D.PRCLPROV PROVEEDOR
						     FROM proveedor_clasificacion D
							where D.PRCLPROV != ''
								   ";
	$RsListaDetalles = mysqli_query($conexion,$query_RsListaDetalles) or die(mysqli_error());
	$row_RsListaDetalles = mysqli_fetch_array($RsListaDetalles);
    $totalRows_RsListaDetalles = mysqli_num_rows($RsListaDetalles);
	if($totalRows_RsListaDetalles>0){
		do{
			$query_RsDatosProv = "SELECT P.PROVCODI CODIGO FROM proveedores P where P.PROVREGI = '".$row_RsListaDetalles['PROVEEDOR']."'";
			$RsDatosProv = mysqli_query($conexion,$query_RsDatosProv) or die(mysqli_error());
			$row_RsDatosProv = mysqli_fetch_array($RsDatosProv);
			$totalRows_RsDatosProv = mysqli_num_rows($RsDatosProv);			
			if($totalRows_RsDatosProv>0){
				do{
				  $query_RsUpdateProv = "UPDATE proveedor_clasificacion set PRCLPROV = '".$row_RsDatosProv['CODIGO']."' where PRCLCODI =  '".$row_RsListaDetalles['CODIGO']."';";
				  //echo($query_RsUpdateProv.'<br>');
				  $RsUpdateProv = mysqli_query($conexion,$query_RsUpdateProv) or die(mysqli_error());		
				}while($row_RsDatosProv = mysqli_fetch_array($RsDatosProv));
			}
		}while($row_RsListaDetalles = mysqli_fetch_array($RsListaDetalles));
	}
?>	
    