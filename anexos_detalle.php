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


if($tipoguardar=='EliminarAnexo'){
 if(isset($_GET['codanexo'])  && $_GET['codanexo']!=''){
	 $query_RsParametroRuta = "SELECT PARAVALOR,
                                      (select d.DEANARCH
									    from detalle_anexos d
									   where d.DEANCODI = '".$_GET['codanexo']."'
									   ) ARCHIVO
	                              FROM PARAMETROS 
							   WHERE PARANOMB = 'RUTAARCHIVO_NAS'";
	 $RsParametroRuta = mysqli_query($conexion, $query_RsParametroRuta) or die(mysqli_error($conexion));
	 $row_RsParametroRuta = mysqli_fetch_assoc($RsParametroRuta);	
	 
	$query_RsEliminarAnexoDet = "DELETE FROM detalle_anexos WHERE DEANCODI = '".$_GET['codanexo']."'";
	$RsEliminarAnexoDet = mysqli_query($conexion,$query_RsEliminarAnexoDet) or die(mysqli_error($conexion));

     $carpeta = '/archivos_compras/';
     $rutaArchivos = $row_RsParametroRuta['PARAVALOR'].$carpeta;	 
     $archivo = $row_RsParametroRuta['ARCHIVO'];	 
    //echo('archivo eliminado '. $rutaArchivos.$archivo);
	unlink($rutaArchivos.$archivo);	
 }
}
if($tipoguardar=='guardar'){
	
	 $query_RsParametroRuta = "SELECT PARAVALOR FROM PARAMETROS WHERE PARANOMB = 'RUTAARCHIVO_NAS'";
	 $RsParametroRuta = mysqli_query($conexion, $query_RsParametroRuta) or die(mysqli_error($conexion));
	 $row_RsParametroRuta = mysqli_fetch_assoc($RsParametroRuta);	
	 
     //$carpeta = '';
	 $carpeta = 'archivos_compras/';	
	//$rutaArchivos = '//nasdell.sanboni.edu.co/compras/archivos_compras/';	
	//$rutaArchivos = 'C:/inetpub/wwwroot/compras/archivos_compras/';	
	//$rutaArchivos = '//192.168.102.26/compras/archivos_compras/';
	$rutaArchivos = $row_RsParametroRuta['PARAVALOR'].$carpeta;

	 	if (is_uploaded_file($_FILES['archivo1']['tmp_name']))
	{
		//echo('INGRESO'.$rutaArchivos);
		$upload_archivo_dir = $rutaArchivos;
		$nombre_archivo = str_replace("Ñ", "N",$_FILES['archivo1']['name']);
		$nombre_archivo = str_replace("ñ", "n",$nombre_archivo);
		$ext=date("Ymd_His");
		$nombre_archivo =$ext."-".$nombre_archivo;
		$tipo_archivo = $_FILES['archivo1']['type'];
      
		if (move_uploaded_file($_FILES['archivo1']['tmp_name'],$upload_archivo_dir.$nombre_archivo))
		{ 
	      
			
			$query_RsInsertarAnexosDet="INSERT INTO `detalle_anexos` (
															    `DEANCODI`,	
																`DEANDETA`,
																`DEANARCH`,
																`DEANFECH`
																) 
																VALUES 
																(NULL,
																'".$codigo_detalle."',
																'".$nombre_archivo."',
																sysdate()
																)";

															// exit($query_RsInsertarInsertarAnexosDet);
			$RsInsertarAnexosDet = mysqli_query($conexion,$query_RsInsertarAnexosDet) or die(mysqli_error($conexion));
		
		}else{echo('Se presento una falla con la ruta de acceso, Favor informar al administrador');}
	}
	

}


    $query_RsListaAnexosDet="SELECT `DEANCODI` CODIGO,
									`DEANDETA` DETALLE,
									`DEANARCH` ARCHIVO_RUTA,
									DATE_FORMAT(`DEANFECH`, '%d/%m/%Y') FECHA
								FROM `detalle_anexos`,
								detalle_requ 
							WHERE `DERECONS`=`DEANDETA`
							AND	   DERECONS=".$codigo_detalle."";
				 // echo($query_RsListaAnexosDet);
	$RsListaAnexosDet = mysqli_query($conexion,$query_RsListaAnexosDet) or die(mysqli_error($conexion));
	$row_RsListaAnexosDet = mysqli_fetch_array($RsListaAnexosDet);
    $totalRows_RsListaAnexosDet = mysqli_num_rows($RsListaAnexosDet);
?><!DOCTYPE html>
<html>
<head>
    <meta name="tipo_contenido"  content="text/html;" http-equiv="content-type" charset="utf-8">
	
	<link rel="stylesheet" type="text/css" href="css/page.css" />
	<style type="text/css">
	  body{
	   background:white;
	  }
	</style>
<script type="text/javascript">

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

 function subirarchivo(){
 if(document.getElementById('archivo1').value==''){
 alert('¡Te falto agregar el archivo!');
 return false;
}
  
   if(confirm('seguro que desea subir este archivo')){
     document.form_detalle.action = 'anexos_detalle.php?codigo_detalle=<?php echo($codigo_detalle);?>&tipoguardar=guardar';
   }else{
   return false;
   } 
 }
function DeleteAnexo(cod){
   if(confirm('seguro que desea eliminar este anexo')){
     document.form_detalle.action = 'anexos_detalle.php?codigo_detalle=<?php echo($codigo_detalle);?>&tipoguardar=EliminarAnexo&codanexo='+cod;
   }else{
   return false;
   }	
} 

</script>
</head>
 <body>
  <form name="form_detalle" id="form_detalle" method="post" action="" accept-charset="UTF-8"  enctype="multipart/form-data">
   <table width="750">
    <tr>
	 <td class="SLAB">Anexos detalle</td>
	 
	 <td><input type="file" name="archivo1" id="archivo1" />
		 <input class="button2" type="submit"  value="Subir Archivo" onclick="return subirarchivo();"/></td>
	</tr>
	<tr>
	 <!--<td colspan="2" align="center"><input type="submit" class="button2" id="btnsub_ns" value="Guardar" onclick="return GuardarAnexo(); ">--></td>
	</tr>
   </table>

   <table width="750" border="0">
     <tr class="SLAB trtitle">
	   <td></td>
	   <td>Archivo</td>
	   <td>Fecha</td>
	   <td></td>
	 </tr>
<?php
    if($totalRows_RsListaAnexosDet >0){
	  $k=0;
	  do{
	    $k++;
		$estilo="SB";
		if($k%2==0){
		  $estilo="SB2";
		}
	 ?>
	 <tr class="<?php echo($estilo);?>">
	  <td>
	  <?php 
	  if($_SESSION['MM_RolID'] == 2){
	  ?>
	  <input type="submit" class="buttonrojo" value="Eliminar" onclick="return DeleteAnexo('<?php echo($row_RsListaAnexosDet['CODIGO']);?>');"></td>
	  <?php /*<td>  <a href="http://190.107.23.165/compras/archivos_compras/<?php echo($row_RsListaAnexosDet['ARCHIVO_RUTA']);?>" target="_back"><?php echo($row_RsListaAnexosDet['ARCHIVO_RUTA']);?></a></td>*/?>
	  <?php 
	  }
	  ?>
	  <td>  <a href="downloadfile.php?doc=<?php echo($row_RsListaAnexosDet['ARCHIVO_RUTA']);?>&tipopath=ac" target="_back"><?php echo($row_RsListaAnexosDet['ARCHIVO_RUTA']);?></a></td>
	  <td><?php echo($row_RsListaAnexosDet['FECHA']);?></td>
	 
			   <?php
	    }while($row_RsListaAnexosDet = mysqli_fetch_array($RsListaAnexosDet));
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
  parent.actualizarcantComent('<?php echo($codigo_detalle);?>','<?php echo($totalRows_RsListaAnexosDet);?>');

  </script>
<?php
}
?>
 </body>
</html>