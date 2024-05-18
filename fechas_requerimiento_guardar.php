<?php
require_once('conexion/db.php');

	if (!isset($_SESSION)) {
  session_start();
}
//no seleccionados

$fecha_desde='';
if(isset($_POST['fecha_desde'])&&$_POST['fecha_desde']!='')
{
$fecha_desde=$_POST['fecha_desde'];
}

$fecha_hasta='';
if(isset($_POST['fecha_hasta'])&&$_POST['fecha_hasta']!='')
{
$fecha_hasta=$_POST['fecha_hasta'];
}

$tipoGuardar='';
if(isset($_GET['tipoGuardar'])&&$_GET['tipoGuardar']!='')
{
$tipoGuardar=$_GET['tipoGuardar'];
}



if ($tipoGuardar == 'Guardar')
{
 $query_RsDeleteDet="INSERT INTO FECHAS_REQ (
											 FERECODI,
											 FEREFEDE,
											 FEREFEHA
											)
											VALUES
											(
											 NULL,
											 STR_TO_DATE('".$fecha_desde."','%d/%m/%Y'),
											 STR_TO_DATE('".$fecha_hasta."','%d/%m/%Y')
											)";
 $RsDeleteDet = mysqli_query($conexion,$query_RsDeleteDet) or die(mysqli_error($conexion));
     $redireccionar = "location: home.php?page=fechas_requerimiento";
    header($redireccionar);
}
if ($tipoGuardar == 'Editar')
{
 $query_RsDeleteDet="UPDATE FECHAS_REQ set FEREFEDE = STR_TO_DATE('".$fecha_desde."','%d/%m/%Y'),
                                           FEREFEHA = STR_TO_DATE('".$fecha_hasta."','%d/%m/%Y')
					 where FERECODI = '".$_POST['codigo_fecha']."'
										   ";
 $RsDeleteDet = mysqli_query($conexion,$query_RsDeleteDet) or die(mysqli_error($conexion));
$redireccionar = "location: home.php?page=fechas_requerimiento";
    header($redireccionar);
}


?>