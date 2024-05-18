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
$detallesadd       = '';
if(isset($_GET['detallesadd']) && $_GET['detallesadd']!=''){
	$detallesadd = $_GET['detallesadd'];
}

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
	$detallesadd ='';
	if(isset($_POST['detallesadd']) && $_POST['detallesadd']){
		$detallesadd = $_POST['detallesadd'];
	}
	$detalles = explode(",", $detallesadd);


	
	 $query_RsParametroRuta = "SELECT PARAVALOR FROM PARAMETROS WHERE PARANOMB = 'RUTAARCHIVO_NAS'";
	 $RsParametroRuta = mysqli_query($conexion, $query_RsParametroRuta) or die(mysqli_error($conexion));
	 $row_RsParametroRuta = mysqli_fetch_assoc($RsParametroRuta);	
     $carpeta = '/archivos_compras/';	
	
	
	$rutaArchivos = $row_RsParametroRuta['PARAVALOR'].$carpeta;
//echo($rutaArchivos);
    $continua = 0; /*inicializar variable de insert de archivo*/
	if (is_uploaded_file($_FILES['archivo1']['tmp_name']))
	{
		$upload_archivo_dir = $rutaArchivos;
		$nombre_archivo = str_replace("Ñ", "N",$_FILES['archivo1']['name']);
		$nombre_archivo = str_replace("ñ", "n",$nombre_archivo);
		$ext=date("Ymd_His");
		$nombre_archivo = $ext."-".$nombre_archivo;
		$tipo_archivo = $_FILES['archivo1']['type'];

		if ( move_uploaded_file($_FILES['archivo1']['tmp_name'],$upload_archivo_dir.$nombre_archivo) )
		{ /*insertar la primera posicion del array cuando el archivo se haya subido*/
			$query_RsInsertarAnexosDet="INSERT INTO `detalle_anexos` (
															    `DEANCODI`,	
																`DEANDETA`,
																`DEANARCH`,
																`DEANFECH`,
																`DEANDESC`
																) 
																VALUES 
																(NULL,
																'".$detalles[0]."',
																'".$nombre_archivo."',
																sysdate(),
																'".$_POST['descripcion_archivo']."'
																)";

															// exit($query_RsInsertarInsertarAnexosDet);
			$RsInsertarAnexosDet = mysqli_query($conexion,$query_RsInsertarAnexosDet) or die(mysqli_error($conexion));
			$continua = 1; /* se incrementa el valor a 1 por correcta subida de archivo y primer insert*/
		}
	}
	if($continua == 1){
		for($i=1; $i<count($detalles); $i++){ /*subir las siguientes posiciones del array arrancando en uno para omitir la primera que ejecuto subida de archivo*/
			$query_RsInsertarAnexosDet="INSERT INTO `detalle_anexos` (
																`DEANCODI`,	
																`DEANDETA`,
																`DEANARCH`,
																`DEANFECH`,
																`DEANDESC`
																) 
																VALUES 
																(NULL,
																'".$detalles[$i]."',
																'".$nombre_archivo."',
																sysdate(),
																'".$_POST['descripcion_archivo']."'
																)";

															// exit($query_RsInsertarInsertarAnexosDet);
			$RsInsertarAnexosDet = mysqli_query($conexion,$query_RsInsertarAnexosDet) or die(mysqli_error($conexion));
		}
	}

}

	$totalRows_RsListaAnexosDet = 0;
if($detallesadd !='' ){
    $query_RsListaAnexosDet="SELECT `DEANCODI` CODIGO,
									`DEANDETA` DETALLE,
									`DEANARCH` ARCHIVO_RUTA,
									DATE_FORMAT(`DEANFECH`, '%d/%m/%Y') FECHA
								FROM `detalle_anexos`,
								detalle_requ 
							WHERE `DERECONS`=`DEANDETA`
							     and  DEANDETA IN (".$detallesadd.")
							order by DEANDETA";
				 // echo($query_RsListaAnexosDet);
	$RsListaAnexosDet = mysqli_query($conexion,$query_RsListaAnexosDet) or die(mysqli_error($conexion));
	$row_RsListaAnexosDet = mysqli_fetch_array($RsListaAnexosDet);
    $totalRows_RsListaAnexosDet = mysqli_num_rows($RsListaAnexosDet);
}	

?><!DOCTYPE html>
<html>
<head>
    <meta name="tipo_contenido"  content="text/html;" http-equiv="content-type" charset="utf-8">
	
	<link rel="stylesheet" type="text/css" href="css/page.css" />
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
	<script type="text/javascript" src="js/jquery.1.7.2.js"></script>
	<script type="text/javascript" src="js/underscore-min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<style type="text/css">
	  body{
	   background:white;
	  }
	  .destacado{
		  border:solid 1px #c6cfe9;
		  background: #d0e9c6 ;
		  color: #780002;
	  }
	</style>
<script type="text/javascript">
$(document).ready(function(){
	details = parent.multiple_detalle_seleccionados;
	detalles = _.sortBy(details, 'detail');
	//console.log(detalles);	
	datacolec = [];
	for(i=0; i < detalles.length; i++){
		datacolec[i] = detalles[i].detail;
	}
	console.log(datacolec.toString());
	datacolec = _.uniq(datacolec);
	for(j = 0; j < datacolec.length; j++){
		for(i=0; i < detalles.length; i++){
			if(datacolec[j] == detalles[i].detail){
				tr = "<tr class='SB'><td>"+detalles[i].detail+"</td><td>"+detalles[i].texto+"</td></tr>";
				$("#detallesmultiples").prepend(tr);
				break;
			}
		}
	}	
	$("#detallesadd").val(datacolec.toString());
	//alert(datacolec.toString());

});


 function subirarchivo(){
 if(document.getElementById('archivo1').value==''){
 alert('¡Te falto agregar el archivo!');
 return false;
}
  
   if(confirm('seguro que desea subir este archivo')){
     document.form_detalle.action = 'multiple_detalles_archivo.php?tipoguardar=guardar';
   }else{
   return false;
   } 
 }
function DeleteAnexo(cod){
	if(cod != ''){
	   if(confirm('seguro que desea eliminar este anexo?')){
			 $.ajax({
				type: "POST",
				url: "tipo_guardar.php?tipoGuardar=deleteanexomultiple&codigo_anexo="+cod,
				success : function(r){
					if(r==1){
						$("#trnewcreated_"+cod).remove();
						alert("Anexo Eliminado Correctamente");
					}
				},
				error   : callback_error
			});		   
	   }else{
	   return;
	   }	
	}
} 
function cargardetalles(){
	if($("#searchbyrequ").val()=='' && $("#searchbydeta").val()==''){
		alert("para realizar la busqueda debe ingresar un codigo de requerimiento o un detalle");
		return;
	}
	$("#busquedaboton").css("display","none");
	$("#realizandobusqueda").css("display","block");
	
	 	 $.ajax({
			type: "POST",
			url: "tipo_guardar.php?tipoGuardar=buscardetallesmultiple&codigo_detalle="+$("#searchbydeta").val()+"&codigo_requerimiento="+$("#searchbyrequ").val(),
			dataType: 'json',
			success : function(r){
				$("#busquedaboton").css("display","block");
				$("#realizandobusqueda").css("display","none");
				if(r.length>0){
					add_detalles(r);
				}else{
					alert("no se encontraron datos");
				}
			},
			error   : callback_error
		});	
	
}
function add_detalles(r){
	$("#detalles_encontrados").html(""); console.log(r[0])
	head='<tr class="SB">'+
			'<td colspan="12"><b>Archivos Encontrados detalle buscado</b></td>'+
		'</tr>'+
		'<tr class="SLAB trtitle">'+
			'<td></td>'+
			'<td>Cod Det</td>'+
			'<td>Descripcion Detalle</td>'+
		'</tr>';
		$("#detalles_encontrados").append(head);
		tr='<tr class="SB"><td><b>'+r[0].DETALLE+'</b></td><td>'+r[0].DESCR+'</td><td></td></tr>';
			$("#detalles_encontrados").append(tr);
		for(i=0; i < r.length; i++){		
			tr='<tr class="destacado"><td width="70"><button type="button" id="btnadd_'+r[i].CODIGO+'" class="btn btn-xs btn-success" onclick="addarchivoanterior(\''+r[i].CODIGO+'\',\''+r[i].ARCHIVO_RUTA+'\')">Agregar</button></td><td >Archivo</td><td>'+r[i].ARCHIVO_RUTA+'</td></tr>';
			$("#detalles_encontrados").append(tr);
		}
}
function addarchivoanterior(codigo, archivo){
	if(confirm("Seguro desea Vincular este archivo a los detalles seleccionados?")){
	 	 $.ajax({
			type: "POST",
			url: "tipo_guardar.php?tipoGuardar=addarchivodetalleseleccionados&codigo_archivo="+codigo+"&detallesadd="+$("#detallesadd").val(),
			dataType: 'json',
			success : function(r){
				if(r.length > 0){
					$("#btnadd_"+codigo).remove();
					addpaintnewarchive(archivo, r);
				}
			},
			error   : callback_error
		});			
	}
}
function addpaintnewarchive(archivo, details){
	//campo = $("#detallesadd").val();
	//details =campo.split(",");
	for(i=0; i < details.length; i++ ){
		tr='<tr class="SB" id="trnewcreated_'+details[i].codigo_archivo+'">'+
		      '<td>'+
		  		  '<input type="button" class="buttonrojo" value="Eliminar" onclick="DeleteAnexo(\''+details[i].codigo_archivo+'\');"></td>'+
				'<td>'+details[i].codigo_detalle+'</td>'+
		  	   '<td><a href="downloadfile.php?doc='+archivo+'&amp;tipopath=ac" target="_back">'+archivo+'</a></td>'+
			   '<td>Hace un momento</td>'+
	         '</tr>';
		$("#tablacargadosarchivos").prepend(tr);
	}
}
function callback_error(XMLHttpRequest, textStatus, errorThrown)
{
    alert("Respuesta del servidor "+XMLHttpRequest.responseText);
    alert("Error "+textStatus);
    alert(errorThrown);
} 
</script>
</head>
 <body>
  <form name="form_detalle" id="form_detalle" method="post" action="" accept-charset="UTF-8"  enctype="multipart/form-data">
  <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Detalles Seleccionados</h3>
            </div>
            <div class="panel-body">
			<div class="well">
			  <table id="detallesmultiples" width="700">
				<thead>
					<tr >
						<th width="70">Detalle</th>
						<th>Descripci&oacute;n</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			  </table> 
            </div>
            </div>
          </div>
 
  <hr></hr>
  <ul class="nav nav-tabs nav-justified">
	  <li><a href="#nuevoarchivodiv" data-toggle="tab"><b>Nuevo Archivo</b></a></li>
	  <li><a href="#desdeanteriordiv" data-toggle="tab"><b>Cargar Desde detalle anterior</b></a></li>
	</ul>
	<div class="tab-content">
		<div id="nuevoarchivodiv" class="tab-pane">
		<table style="margin-top:1em">
			<tr>
				<td class="SB">Descripcion del archivo</td>
				<td><textarea maxlength="200" name="descripcion_archivo" id="descripcion_archivo" class="form-control"></textarea></td>
			</tr>
			<tr>
			    <td class="SB">Archivo</td>
				<td><input type="file" name="archivo1" id="archivo1" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
		<tr>
			<td colspan="2" align="center">
			 <input class="button2" type="submit"  value="Subir Archivo" onclick="return subirarchivo();"/>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		</table>
		</div>
		<div id="desdeanteriordiv" class="tab-pane">
		  <table style="margin-top:1em">	
			<tr>
				<td colspan="2"><div id="realizandobusqueda" style="color:#780002; font-weight:bold; display:none">Realizando Busqueda...</div></td>
			</tr>
			<tr>
			<td class="SB">Busqueda por Codigo Detalle</td>
			<td>
			<input type="hidden" name="searchbyrequ" id="searchbyrequ" value="" placeholder="codigo requerimiento" class="form-control">
			<input type="text" name="searchbydeta" id="searchbydeta" value="" placeholder="codigo detalle" class="form-control"></td>
					<td colspan="2" align="center">
					<button id="busquedaboton" type="button" class="btn btn-sm btn-default" onclick="cargardetalles();">Buscar</button>
				</td>			
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			</table>		
		
		</div>
	</div>

	<table id="detalles_encontrados" width="750">

	</table>
	<hr></hr>

   <table width="750">
    <tr>
	 <td class="SLAB">Anexos detalle</td>
	 
	 <td>
		<input type="hidden" name="detallesadd" id="detallesadd" value="">
		 </td>
	</tr>
	<tr>
	 <!--<td colspan="2" align="center"><input type="submit" class="button2" id="btnsub_ns" value="Guardar" onclick="return GuardarAnexo(); ">--></td>
	</tr>
   </table>

   <table width="750" border="0" id="tablacargadosarchivos">
   <thead>
     <tr>
		<td>&nbsp;</td>
	 </tr>
	 <tr>
		<td colspan="8"><b>Archivos de los detalles cargados</b></td>
	 </tr>
     <tr class="SLAB trtitle">
	   <td></td>
	   <td>Detalle</td>
	   <td>Archivo</td>
	   <td>Fecha</td>
	   <td></td>
	 </tr>
	 <tr>
		<td>&nbsp;</td>
	 </tr>
	 </thead>
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
	 <tr class="<?php echo($estilo);?>" id="trnewcreated_<?php echo($row_RsListaAnexosDet['CODIGO']);?>">
		  <td>
		  <?php 
		  if($_SESSION['MM_RolID'] == 2){
		  ?>
		  <input type="button" class="buttonrojo" value="Eliminar" onclick="DeleteAnexo('<?php echo($row_RsListaAnexosDet['CODIGO']);?>');"></td>
		  <?php /*<td>  <a href="http://190.107.23.165/compras/archivos_compras/<?php echo($row_RsListaAnexosDet['ARCHIVO_RUTA']);?>" target="_back"><?php echo($row_RsListaAnexosDet['ARCHIVO_RUTA']);?></a></td>*/?>
		  <?php 
		  }
		  ?>
		  <td><?php echo($row_RsListaAnexosDet['DETALLE']);?></td>
		  <td>  <a href="downloadfile.php?doc=<?php echo($row_RsListaAnexosDet['ARCHIVO_RUTA']);?>&tipopath=ac" target="_back"><?php echo($row_RsListaAnexosDet['ARCHIVO_RUTA']);?></a></td>
		  <td><?php echo($row_RsListaAnexosDet['FECHA']);?></td>
	 </tr>
			   <?php
	    }while($row_RsListaAnexosDet = mysqli_fetch_array($RsListaAnexosDet));
	}
?>
	 <tr>
		<td>&nbsp;</td>
	 </tr>
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