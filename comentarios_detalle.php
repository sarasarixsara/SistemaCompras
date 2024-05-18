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
if(isset($_GET['estado']) && $_GET['estado']!=''){
  $estado=$_GET['estado'];
}


$estado_des        = '';
$codigo_detallereq = '';
$persona           = '';
$persona_des       = '';
$observacion       = '';
$tipoguardar       = '';
$aprobado          = '';
$consecutivo       = '';
if(isset($_GET['tipoguardar']) && $_GET['tipoguardar']!=''){
 $tipoguardar = $_GET['tipoguardar'];
}
/*
if($codigo_detalle!=''){
$codigo=explode('commentdet',$codigo_detalle);
$codigo_detalle = $codigo[1];
}
*/

if($tipoguardar=='guardar'){
     $query_RsCrearRequerimiento="INSERT INTO OBSERVACIONESDET (
	                                                         OBDECODI,
															 OBDEPERS,
															 OBDEFECH,
															 OBDECODE,
															 OBDEOBSE,
															 OBDEAPROB,
															 OBDEESTA
															 )
															 VALUES
															 (
															 NULL,
															 '".$_SESSION['MM_UserID']."',
															 sysdate(),
															 '".$codigo_detalle."',
															 '".$_POST['observacion_add']."',
															 '0',
                                                             '".$estado."'
															 )";

															// exit($query_RsCrearRequerimiento);
  	$RsCrearRequerimiento = mysqli_query($conexion,$query_RsCrearRequerimiento) or die(mysqli_error($conexion));

}

/*
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

				  ";
				  //echo($query_RsPoa);
	$RsPoa = mysqli_query($conexion,$query_RsPoa) or die(mysqli_error($conexion));
	$row_RsPoa = mysqli_fetch_array($RsPoa);
    $totalRows_RsPoa = mysqli_num_rows($RsPoa);
if($totalRows_RsPoa>0){
  $consecutivo       =  $row_RsPoa['CONSECUTIVO'];
  $codigo_detallereq =  $row_RsPoa['CODIGO_DETALLEREQU'];
  $persona           =  $row_RsPoa['PERSONA'];
  $persona_des       =  $row_RsPoa['PERSONA_DES'];
  $observacion       =  $row_RsPoa['OBSERVACION'];
  $aprobado          =  $row_RsPoa['APROBADO'];
  $tipoguardar       =  'actualizar';
}
*/

    $query_RsListaComentarios="SELECT D.OBDECODI CODIGO,
	                     D.OBDEPERS PERSONA,
						 (select P.PERSNOMB
						   FROM PERSONAS P
						  WHERE P.PERSID = D.OBDEPERS) PERSONA_DES,
						 date_format(D.OBDEFECH, '%d/%m/%Y') FECHA,
						 D.OBDECODE CODIGO_DETALLE,
						 D.OBDEOBSE OBSERVACION,
						 D.OBDEAPROB APROBADO,
						 E.ESTANOMB  ESTADO_DES
					FROM OBSERVACIONESDET D left join
					 ESTADOS E ON D.OBDEESTA = E.ESTACODI
				  WHERE D.OBDECODE = '".$codigo_detalle."'";
				 // echo($query_RsListaComentarios);
	$RsListaComentarios = mysqli_query($conexion,$query_RsListaComentarios) or die(mysqli_error($conexion));
	$row_RsListaComentarios = mysqli_fetch_array($RsListaComentarios);
    $totalRows_RsListaComentarios = mysqli_num_rows($RsListaComentarios);
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
if(document.getElementById('observacion_add').value==''){
 alert('debe agregar la observacion');
 return false;
}

document.form_detalle.action="comentarios_detalle.php?codigo_detalle=<?php echo($codigo_detalle);?>&estado=<?php echo($estado);?>&tipoguardar=guardar";

}

function ActualizarObservacion(){
if(document.getElementById('aprobado').value==''){
 alert('debe indicar si aprueba este detalle');
 return false;
}
if(document.getElementById('aprobado').value=='2'){
  if(document.getElementById('observacion').value==''){
   alert('debe agregar la observacion');
   return false;
  }
}
document.form_detalle.action="aprobar_detalle.php?codigo_detalle=<?php echo($codigo_detalle);?>&tipoguardar=actualizar";

}
</script>
</head>
 <body>
  <form name="form_detalle" id="form_detalle" method="post" action="">
   <table width="750">
    <tr>
	 <td class="SLAB">Comentario</td>
	 <td>
	  <textarea cols="70" rows="5" id="observacion_add" name="observacion_add"></textarea>
	 </td>
	</tr>
	<tr>
	 <td colspan="2" align="center"><input type="submit" class="button2" id="btnsub_ns" value="Guardar" onclick="return GuardarObservacion(); "></td>
	</tr>
   </table>
   <table width="750" border="0">
     <tr class="SLAB trtitle">
	   <td>Persona que hace el comentario</td>
	   <td>Comentario</td>
	   <td>Fecha</td>
	 </tr>
<?php
    if($totalRows_RsListaComentarios >0){
	  $k=0;
	  do{
	    $k++;
		$estilo="SB";
		if($k%2==0){
		  $estilo="SB2";
		}
	 ?>
	 <tr class="<?php echo($estilo);?>">
	  <td><?php echo($row_RsListaComentarios['PERSONA_DES']);?></td>
	  <td><?php echo($row_RsListaComentarios['OBSERVACION']);?></td>
	  <td><?php echo($row_RsListaComentarios['FECHA']);?></td>
	 
	 </tr>
	 <?php
	    }while($row_RsListaComentarios = mysqli_fetch_array($RsListaComentarios));
	}
?>
	 <tr>
	  <td colspan="6" align="center">
	   <input type="hidden" name="consecutivo" id="consecutivo" value="<?php echo($consecutivo);?>">
	   <?php
	   if($tipoguardar==''){
	   /*
	   ?>
	   <input type="submit" class="button2" id="btnsub_ns" value="Guardar" onclick="return GuardarObservacion(); ">
	   <?php
	   */
	   }
	   ?>
	   <?php
	   if($tipoguardar=='actualizar'){
	   /*
	   ?>
	   <input type="submit" class="button2" id="btnsub_ns" value="Actualizar" onclick="return ActualizarObservacion(); ">
	   <?php
	   */
	   }
	   ?>
	  </td>
	 </tr>
   </table>
  </form>
<?php
if($tipoguardar=="guardar" || $tipoguardar=="actualizar")
{
?>
  <script >
  //parent.accione.value='1';
  parent.actualizarcantComent('<?php echo($codigo_detalle);?>','<?php echo($totalRows_RsListaComentarios);?>');

  </script>
<?php
}
?>
 </body>
</html>