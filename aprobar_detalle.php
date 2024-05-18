<?php
require_once('conexion/db.php');
	if (!isset($_SESSION)) {
  session_start();
}
$codigo_detalle='';
if(isset($_GET['codigo_detalle']) && $_GET['codigo_detalle']!=''){
  $codigo_detalle=$_GET['codigo_detalle'];
}
$estado            = '';
$estado_des        = '';
$codigo_detallereq = '';
$persona           = '';
$persona_des       = '';
$observacion       = '';
$tipoguardar       = '';
$aprobado          = '';
$consecutivo       = '';
$modalidaddeta      = -1;
$clasificaciondeta  = -1;
if(isset($_GET['tipoguardar']) && $_GET['tipoguardar']!=''){
 $tipoguardar = $_GET['tipoguardar'];
}


if($tipoguardar=='actualizar'){
  if(isset($_POST['observacion']) && $_POST['observacion']!=''){

     $query_RsCrearRequerimiento="UPDATE OBSERVACIONESDET SET
															 OBDEOBSE  = '".$_POST['observacion']."' ,
															 OBDEAPROB = '2'
									where OBDECODI = '".$_POST['consecutivo']."'";
															 //exit($query_RsCrearRequerimiento);
  	$RsCrearRequerimiento = mysqli_query($conexion,$query_RsCrearRequerimiento) or die(mysqli_error($conexion));
  }

 if(isset($_GET['modalidad']) && $_GET['modalidad']!=''){
   $modalidaddeta      = $_GET['modalidad'];
 }
 if(isset($_GET['clasificacion']) && $_GET['clasificacion']!=''){
   $clasificaciondeta  = $_GET['clasificacion'];
 }
	$query_RsCrearRequerimiento="UPDATE DETALLE_REQU SET DEREAPRO = '2',
	                                                     DEREMODA = '".$modalidaddeta."',
	                                                     DERECLAS = '".$clasificaciondeta."'
									where DERECONS = '".$codigo_detalle."'";
															 //exit($query_RsCrearRequerimiento);
  	$RsCrearRequerimiento = mysqli_query($conexion,$query_RsCrearRequerimiento) or die(mysqli_error($conexion));
}

if($tipoguardar=='guardar'){
     $query_RsCrearRequerimiento="INSERT INTO OBSERVACIONESDET (
	                                                         OBDECODI,
															 OBDEPERS,
															 OBDEFECH,
															 OBDECODE,
															 OBDEOBSE,
															 OBDEAPROB
															 )
															 VALUES
															 (
															 NULL,
															 '".$_SESSION['MM_UserID']."',
															 sysdate(),
															 '".$codigo_detalle."',
															 '".$_POST['observacion']."',
															 '2'

															 )";
															 //exit($query_RsCrearRequerimiento);
  	$RsCrearRequerimiento = mysqli_query($conexion,$query_RsCrearRequerimiento) or die(mysqli_error($conexion));

	$query_RsCrearRequerimiento="UPDATE DETALLE_REQU SET DEREAPRO = '2',
	                                                     DEREMODA = '".$modalidaddeta."',
	                                                     DERECLAS = '".$clasificaciondeta."'
									where DERECONS = '".$codigo_detalle."'";
															 //exit($query_RsCrearRequerimiento);
  	$RsCrearRequerimiento = mysqli_query($conexion,$query_RsCrearRequerimiento) or die(mysqli_error($conexion));
	
	//envio de mail
	$infomacion_codi=$codigo_detalle;
	include("correo_detdevuelto.php");
}

    $query_RsPoa="SELECT O.OBDECODI CONSECUTIVO,
	                     O.OBDEPERS PERSONA,
						 P.PERSNOMB PERSONA_DES,
						 DATE_FORMAT(O.OBDEFECH,'%d/%m/%Y') FECHA_REGISTRO,
						 O.OBDECODE CODIGO_DETALLEREQU,
						 O.OBDEOBSE OBSERVACION,
						 O.OBDEAPROB APROBADO
				   FROM OBSERVACIONESDET O,
				                PERSONAS P
				  WHERE O.OBDEPERS = P.PERSID
				    AND O.OBDECODE = '".$codigo_detalle."'
					AND O.OBDEAPROB = 2

				  ";
				  //echo($query_RsPoa);
	$RsPoa = mysqli_query($conexion,$query_RsPoa) or die(mysqli_error($conexion));
	$row_RsPoa = mysqli_fetch_array($RsPoa);
    $totalRows_RsPoa = mysqli_num_rows($RsPoa);

if($tipoguardar=='actualizar' || $tipoguardar=='guardar'){	
if($totalRows_RsPoa>0){
  $consecutivo       =  $row_RsPoa['CONSECUTIVO'];
  $codigo_detallereq =  $row_RsPoa['CODIGO_DETALLEREQU'];
  $persona           =  $row_RsPoa['PERSONA'];
  $persona_des       =  $row_RsPoa['PERSONA_DES'];
  $observacion       =  $row_RsPoa['OBSERVACION'];
  $aprobado          =  $row_RsPoa['APROBADO'];
  $tipoguardar       =  'actualizar';
  }
}
?><!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="css/page.css" />
	<style type="text/css">
	  body{
	   background:white;
	  }
	</style>
<script type="text/javascript">
function GuardarObservacion(){

  if(document.getElementById('observacion').value==''){
   alert('Debe justificar el no recibido');
   return false;
  }

document.form_detalle.action="aprobar_detalle.php?codigo_detalle=<?php echo($codigo_detalle);?>&tipoguardar=guardar";

}

function ActualizarObservacion(){

  if(document.getElementById('observacion').value==''){
   alert('debe justificar el no recibido');
   return false;
  }

document.form_detalle.action="aprobar_detalle.php?codigo_detalle=<?php echo($codigo_detalle);?>&tipoguardar=actualizar";

}
</script>
</head>
 <body>
  <form name="form_detalle" id="form_detalle" method="post" action="">
   <table width="270" border="0">
     <tr class="SLAB3" align="center">
	   <td colspan="6">Digite su opini&oacute;n:</td>
	 </tr>
	 <tr>
	  <td colspan="6">
	   <textarea style="width:270px; height:90px; border: 1px solid #c42615;" id="observacion" name="observacion"><?php echo($observacion);?></textarea>
	  </td>
	 </tr>
	 <tr>
	  <td colspan="6" align="center">
	   <input type="hidden" name="consecutivo" id="consecutivo" value="<?php echo($consecutivo);?>">
	   <?php
	   if($tipoguardar==''){
	   ?>
	   <input type="submit" class="button5" id="btnsub_ns" value="Devolver" onclick="return GuardarObservacion(); ">
	   <?php
	   }
	   ?>
	   <?php
	   if($tipoguardar=='actualizar'){
	   ?>
	    <input type="submit" class="button5" id="btnsub_ns" value="Editar Devoluci&oacute;n" onclick="return ActualizarObservacion(); ">
	   <?php
	   }
	   ?>
	  </td>
	 </tr>
   </table>
  </form>
<?php
if(($tipoguardar=="guardar" || $tipoguardar=="actualizar") && isset($_POST['consecutivo']))
{
?>
  <script >
  //parent.accione.value='1';
  parent.actualizaraprobado('<?php echo($codigo_detalle);?>','<?php echo($aprobado);?>');
  alert('registro actualizado');

  </script>
<?php
}
?>
 </body>
</html>