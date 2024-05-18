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

if($tipoguardar=='eliminar_f'){
	
	if(isset($_GET['codigo_detalle_elim']) && $_GET['codigo_detalle_elim']!=''){
 $codigo_detalle_elim = $_GET['codigo_detalle_elim'];
}
	
	
	
	 $query_RsEliminar_obser="DELETE FROM `detalle_factura` WHERE `DEFAID`= '".$codigo_detalle_elim."'";							
    //echo($query_RsEliminar_obser);
	$RsEliminar_obser = mysqli_query($conexion,$query_RsEliminar_obser) or die(mysqli_error($conexion));
	
}

if($tipoguardar=='guardar'){
     $query_RsCrearRequerimiento="INSERT INTO `detalle_factura` (
																 `DEFAID`,
																 `DEFADESC`,
																 `DEFADETA`
																 ) 
																 VALUES 
																 (
																 NULL,
																 '".$_POST['observacion_add']."',
																 '".$codigo_detalle."'
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

    $query_RsListaComentarios="SELECT 
										`DEFAID` CODIGO,
										`DEFADESC` LINK,
										`DEFADETA` CODI_DETA 
								FROM `detalle_factura`,
								detalle_requ
								WHERE DERECONS=DEFADETA
								AND DEFADETA = '".$codigo_detalle."'";
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
 alert('debe agregar la Factura');
 return false;
}

document.form_detalle.action="factura_detalle.php?codigo_detalle=<?php echo($codigo_detalle);?>&estado=<?php echo($estado);?>&tipoguardar=guardar";

}

function ActualizarObservacion(){
if(document.getElementById('aprobado').value==''){
 alert('debe indicar si aprueba este detalle');
 return false;
}
if(document.getElementById('aprobado').value=='2'){
  if(document.getElementById('observacion').value==''){
   alert('debe agregar la factura');
   return false;
  }
}
document.form_detalle.action="aprobar_detalle.php?codigo_detalle=<?php echo($codigo_detalle);?>&tipoguardar=actualizar";

}

function Felimi_fact(c){
	//alert();
	
	 if(confirm("Seguro desea elimar esta factura?")){
	  document.form_detalle.action="factura_detalle.php?tipoguardar=eliminar_f&codigo_detalle=<?php echo($codigo_detalle);?>&estado=<?php echo($estado);?>&codigo_detalle_elim="+c;
	  }else{
	    return false;
	   }
}
</script>
</head>
 <body>
  <form name="form_detalle" id="form_detalle" method="post" action="">
   <table width="750">
    <tr>
	 <td class="SLAB">Facturas</td>
	 <td>
	  <textarea cols="70" rows="1" id="observacion_add" name="observacion_add"></textarea>
	 </td>
	</tr>
	<tr>
	 <td colspan="2" align="center"><input type="submit" class="button2" id="btnsub_ns" value="Guardar" onclick="return GuardarObservacion(); "></td>
	</tr>
   </table>
   <table width="750" border="0">
     <tr class="SLAB trtitle">
 <?php if( $_SESSION['MM_RolID']!= 4 ){?>	 
	 <td></td>
 <?php } ?>
	   <td>CODIGO DE FACTURA</td>
	 
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
	 <?php if( $_SESSION['MM_RolID']!= 4 ){?>
		 
		 <td><input class="button3" type="submit"  value="Eliminar" onclick="Felimi_fact('<?php echo($row_RsListaComentarios['CODIGO']); ?>');"/></td>
	 <?php } ?>
	 
	 
	  <td> <a href="<?php echo($row_RsListaComentarios['LINK']);?>" target="_blank"><?php echo($row_RsListaComentarios['CODIGO']);?></a> 
	  
	 </td>
	 
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