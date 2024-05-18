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

    $query_RsDatosDetalle="SELECT D.DERECONS CODIGO ,
									   D.DEREDESC NOMBRE,
									   D.DERECANT CANTIDAD,
									   D.DEREAPRO ESTADO,
									   E.ESDENOMB ESTADO_DES,
									   E.ESDECOLO COLOR,
									   D.DEREUNME UM,
									   U.UNMESIGL UM_DES,
									   D.DEREPOA POA,
									   ifnull(D.DERECOOC, ' - ') CODIGO_ORDEN_COMPRA,
									   date_format(D.DEREFPCO, '%d/%m/%Y %r') FECHA_PASA_CONVENIO,
									   date_format(D.DEREFECO, '%d/%m/%Y %r') FECHA_ELABORAR_COTIZACION,
									   date_format(D.DEREFEEC, '%d/%m/%Y %r') FECHA_ENVIAR_COTIZACION,
									   date_format(D.DEREFEAP, '%d/%m/%Y %r') FECHA_PARA_ASIGNAR_PROVEEDOR,
									   date_format(D.DEREFEPA, '%d/%m/%Y %r') FECHA_PROVEEDOR_ASIGNADO,
									   date_format(D.DEREFFDA, '%d/%m/%Y %r') FECHA_FIRMA_DIRECTOR,
									   date_format(D.DEREFFRE, '%d/%m/%Y %r') FECHA_FIRMA_RECTOR,
									   date_format(D.DEREFEEO, '%d/%m/%Y %r') FECHA_ELABORA_ORDEN,
									   date_format(D.DEREFEOC, '%d/%m/%Y %r') FECHA_ORDEN_CONVENIO_ELABORADA,
									   date_format(D.DEREFERE, '%d/%m/%Y %r') FECHA_RECIBIDO_AUXILIAR,
									   date_format(D.DEREFEMU, '%d/%m/%Y %r') FECHA_ENTREGA_MERCANCIA_USUAG,
									   date_format(D.DEREFRSO, '%d/%m/%Y %r') FECHA_RECIBIDO_SOLICITA,
									   date_format(D.DEREFOCR, '%d/%m/%Y %r') FECHA_FIRMA_ORDEN_RECTOR,
									   date_format(D.DEREFOAP, '%d/%m/%Y %r') FECHA_ENVIA_ORDEN_A_PROVEEDOR,
									   R.REQUCORE CODIGO_REQUERIMIENTO,
									   R.REQUIDUS USUARIO_SOLICITA,
									   date_format(R.REQUFESO, '%d/%m/%Y %r') FECHA_SOLICITADO,
									   date_format(R.REQUFEEN, '%d/%m/%Y %r') FECHA_ENVIADO,
									   date_format(R.REQUFREE, '%d/%m/%Y %r') FECHA_REENVIADO,
									   date_format(R.REQUFERE, '%d/%m/%Y %r') FECHA_RECIBIDO,
									   date_format(R.REQUFENR, '%d/%m/%Y %r') FECHA_NORECIBIDO,
									   date_format(R.REQUFEAP, '%d/%m/%Y %r') FECHA_ADMITIDO
									   
									   
								  FROM detalle_requ D left join unidad_medida U ON
								         D.DEREUNME = U.UNMECONS left join estado_detalle E on D.DEREAPRO = E.ESDECODI,
									   requerimientos R
									   
								   WHERE D.DERECONS = '".$codigo_detalle."'
                                     and D.DEREREQU = R.REQUCODI									 
									  "
								      ;
				  //echo($query_RsDatosDetalle);
	$RsDatosDetalle = mysqli_query($conexion,$query_RsDatosDetalle) or die(mysqli_error($conexion));
	$row_RsDatosDetalle = mysqli_fetch_array($RsDatosDetalle);
    $totalRows_RsDatosDetalle = mysqli_num_rows($RsDatosDetalle);
	
	$query_RsDatosCotizacion = "SELECT C.COTIORDE CODIGO_ORDEN_COTIZACION,
									   C.COTIPROV PROVEEDOR,
									   P.PROVNOMB PROVEEDOR_DES,
									   DATE_FORMAT(C.COTIFECH, '%d/%m/%Y') FECHA_CREACION,
									   DATE_FORMAT(C.COTIFEEN, '%d/%m/%Y') FECHA_ENVIO,
									   C.COTIOBSE OBSERVACIONES_COTIZACION,
									   C.COTIFOPA FORMA_DE_PAGO,
									   (SELECT CR.CRMEDESC FROM criterio_medicion CR WHERE CR.CRMECONS = C.COTIFOPA ) FORMA_DE_PAGO_DES, 
									   C.COTIGARA GARANTIA,
									   (SELECT CR.CRMEDESC FROM criterio_medicion CR WHERE CR.CRMECONS = C.COTIGARA ) GARANTIA_DES, 
									   C.COTITIEN TIEMPO,
									   (SELECT CR.CRMEDESC FROM criterio_medicion CR WHERE CR.CRMECONS = C.COTITIEN ) TIEMPO_DES, 
									   C.COTITOTA TOTAL_COTIZACION,
									   C.COTISIEN SITIO_ENTREGA,
									   (SELECT CR.CRMEDESC FROM criterio_medicion CR WHERE CR.CRMECONS = C.COTISIEN ) SITIO_ENTREGA_DES, 
									   C.COTIFORE FORMA_PAGO,
									   C.COTIFLET FLETE,
									   C.COTIVAAG VALOR_AGREGADO,
									   CD.CODEVALO VALOR,
									   CD.CODEDESC DESCRIPCION_PROVEEDOR,
									   CD.CODEVAIV IVA,
									   CD.CODEVIVA VALOR_IVA
									   
										 FROM cotizacion C,
											cotizacion_detalle CD,
											proveedores         P
													   
											
								  where CD.CODEDETA = '".$codigo_detalle."'
									AND C.COTICODI  = CD.CODECOTI
									AND C.COTIPROV  = P.PROVCODI
    
									";
	$RsDatosCotizacion = mysqli_query($conexion,$query_RsDatosCotizacion) or die(mysqli_error($conexion));
	$row_RsDatosCotizacion = mysqli_fetch_array($RsDatosCotizacion);
    $totalRows_RsDatosCotizacion = mysqli_num_rows($RsDatosCotizacion);	
	
    $cotizacionesdet = array();	
	if($totalRows_RsDatosCotizacion>0){
		do{
			$cotizacionesdet[] = $row_RsDatosCotizacion;
		}while($row_RsDatosCotizacion = mysqli_fetch_array($RsDatosCotizacion));
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
	  .detfecha{
		  
	  }
	  .fondobh{
		  background:#F5F5F5 ;
		  font-weight: 500;
		  font-size: 13px;
		  	  }
	  .fondob{
		  background:#def0f4 ;
		  font-weight: 500;
		  font-size: 13px;
		  
	  }
	  .bgblack{
		  background: #780002;
		  color:white;
	  }
	  .bgsecc{
		  background:#f2dede;
	  }
	  .resumenseccion span{
	   font-size:18px;
	   width:45px;
	   cursor:pointer;
	  }
	  .resumenseccion{
		  background:#F4F4F4;
		  border-left:solid 1px #AEADAD;
		  border-right:solid 1px #AEADAD;
		  width:650px;
		  padding:5px 50px;
		  color:#121313;
		  font-weight:700;
		  box-shadow: 0 8px 15px 0 #C7CDC6;
		  margin-bottom:3px;
	  }
	  .resumenseccion span{
		  cursor:pointer;
	  }
	  #tablafechas, #tablapr, #tabladetadi {
		  border:solid 1px #AEADAD;
		  box-shadow: 0 8px 15px 0 #C7CDC6;
	  }
	  .totalcotizacion{
		  color:#ff0000;
		  background:yellow;
		  padding:3px 9px;
		  font-weight: bold;
	  }
	</style>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>	
<script type="text/javascript">
$(function(){
	   $("#form_detalle").on("click", ".resumenseccion", function(){
        id = $(this).attr('data-tbl');
        idp = $(this).attr('id');
		if($("#"+id).css("display")=='none'){
			$("#"+id).css("display","block");
			$("#"+idp+" span").text(' - ');
		}else{
			$("#"+id).css("display","none");
			$("#"+idp+" span").text(' + ');
		}
		
		
	   });	
});
</script>
</head>
 <body>
  <form name="form_detalle" id="form_detalle" method="post" action="">
   <table width="750" id="tablapr">
    <tr class="SLAB trtitle">
		<td colspan="6" ALIGN="CENTER">RESUMEN DETALLE</td>
	</tr>
    <tr>
	 <td class="bgblack" colspan="6" align="center"><?php echo($row_RsDatosDetalle['NOMBRE']);?></td>
	</tr>
  </table>
  <table align="center">
	<tr>
	<td class="">Estado</td>
	 <td colspan="5"><span style="background:<?php echo($row_RsDatosDetalle['COLOR']);?>; border-radius:50%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;<?php echo($row_RsDatosDetalle['ESTADO_DES']);?></td>
	 </tr> 
	 <tr class="fondobh">
	 <td class="">Cantidad</td>
	 <td><span class="totalcotizacion"><b><?php echo($row_RsDatosDetalle['CANTIDAD']);?></b></span></td>	 
	 <td class="">Codigo orden compra</td>
	 <td><span class="totalcotizacion"> <b> <?php if($row_RsDatosDetalle['CODIGO_ORDEN_COMPRA']!='0'){ echo($row_RsDatosDetalle['CODIGO_ORDEN_COMPRA']); }else{ echo(' - ');} ?> <b> </span></td>
	 <td class="">Unidad Medida</td>
	 <td><?php echo($row_RsDatosDetalle['UM_DES']);?></td>
	</tr>
	<tr class="fondobh">
	 <td >Fecha firma director</td>
	 <td><?php echo($row_RsDatosDetalle['FECHA_FIRMA_DIRECTOR']);?></td>
	 <td class="">Fecha firma rector</td>
	 <td><?php echo($row_RsDatosDetalle['FECHA_FIRMA_RECTOR']);?></td>
	 <td class="">fecha firma recibido auxliar</td>
	 <td><?php echo($row_RsDatosDetalle['FECHA_RECIBIDO_AUXILIAR']);?></td>
	</tr>	 
  </table>
    <div class="resumenseccion" id="resumen_fechas" data-tbl="tablafechas"> <span>+</span> resumen de fechas</div>
	<table width="750" id="tablafechas" bgcolor="" style="display:none;">
	 <tr class="fondob">
		<td class="" colspan="2">Fecha Solicitado: </td><td class="detfecha"><?php echo($row_RsDatosDetalle['FECHA_SOLICITADO']);?></td>
		<td class="" colspan="2">Fecha Enviado: </td><td class="detfecha"><?php echo($row_RsDatosDetalle['FECHA_ENVIADO']);?></td>
	 </tr>
	 <tr class="fondob">
		<td class="" colspan="2">Fecha Recibido: </td><td class="detfecha"><?php echo($row_RsDatosDetalle['FECHA_RECIBIDO']);?></td>
		<td class="" colspan="2">Fecha Estudio Cotizacion: </td><td class="detfecha"><?php echo($row_RsDatosDetalle['FECHA_ADMITIDO']);?></td>
	 </tr>
	 <tr class="fondob">
		<td class="" colspan="2">Elaborado Cotizacion: </td><td class="detfecha"><?php echo($row_RsDatosDetalle['FECHA_ELABORAR_COTIZACION']);?></td>
		<td class="" colspan="2">Enviador Cotizacion: </td><td class="detfecha"><?php echo($row_RsDatosDetalle['FECHA_ENVIAR_COTIZACION']);?></td>
	 </tr>
	 <tr class="fondob">
		<td class="" colspan="2">Para Asignar Proveedor: </td><td class="detfecha"><?php echo($row_RsDatosDetalle['FECHA_PARA_ASIGNAR_PROVEEDOR']);?></td>
		<td class="" colspan="2">Proveedor Asignado: </td><td class="detfecha"><?php echo($row_RsDatosDetalle['FECHA_PROVEEDOR_ASIGNADO']);?></td>
	 </tr>
	 <tr class="fondob">
		<td class="" colspan="2">Firma Director Administrativo: </td><td class="detfecha"><?php echo($row_RsDatosDetalle['FECHA_FIRMA_DIRECTOR']);?></td>
		<td class="" colspan="2">Firma Rector: </td><td class="detfecha"><?php echo($row_RsDatosDetalle['FECHA_FIRMA_RECTOR']);?></td>
	 </tr>
	 <tr class="fondob">
		<td class="" colspan="2">Elaborar Orden: </td><td class="detfecha"><?php echo($row_RsDatosDetalle['FECHA_ELABORA_ORDEN']);?></td>
		<td class="" colspan="2">Firmar Orden Rector: </td><td class="detfecha"><?php echo($row_RsDatosDetalle['FECHA_FIRMA_ORDEN_RECTOR']);?></td>
	 </tr>
	 <tr class="fondob">
		<td class="" colspan="2">Orden Convenio Elaborada: </td><td class="detfecha"><?php echo($row_RsDatosDetalle['FECHA_ORDEN_CONVENIO_ELABORADA']);?></td>
		<td class="" colspan="2"> </td><td class="detfecha"><?php //echo($row_RsDatosDetalle['FECHA_FIRMA_ORDEN_RECTOR']);?></td>
	 </tr>
	 <tr class="fondob">
		<td class="" colspan="2">Enviar Orden: </td><td class="detfecha"><?php echo($row_RsDatosDetalle['FECHA_FIRMA_ORDEN_RECTOR']);?></td>
		<td class="" colspan="2">Enviar Orden a Proveedor: </td><td class="detfecha"><?php echo($row_RsDatosDetalle['FECHA_ENVIA_ORDEN_A_PROVEEDOR']);?></td>
	 </tr>
	 <tr class="fondob">
		<td class="" colspan="2">Recibe mercancia: </td><td class="detfecha"><?php echo($row_RsDatosDetalle['FECHA_RECIBIDO_AUXILIAR']);?></td>
		<td class="" colspan="2">Entrega mercancia a Usuario solicita: </td><td class="detfecha"><?php echo($row_RsDatosDetalle['FECHA_ENTREGA_MERCANCIA_USUAG']);?></td>
	 </tr>
	 <tr class="fondob">
		<td class="" colspan="2">Recibe mercancia quien solicita: </td><td class="detfecha"><?php echo($row_RsDatosDetalle['FECHA_RECIBIDO_SOLICITA']);?></td>
		<td class="" colspan="2">Fecha marcado convenio: </td><td class="detfecha"><?php echo($row_RsDatosDetalle['FECHA_PASA_CONVENIO']);?></td>
	 </tr>
	 </table>
<div class="resumenseccion" id="resumen_adicional" data-tbl="tabladetadi"> <span>+</span> resumen Cotizacion</div>	 
<table width="750" id="tabladetadi" style="display:none;">
<tr class="fondob">
	<td colspan="4" width="750">Cotizaciones en la que aparece el detalle</td>
</tr>
<?php 
    if($totalRows_RsDatosCotizacion >0){
	?>
<tr class="fondob">
	<td>Orden de cotizacion : <?php echo($cotizacionesdet[0]['CODIGO_ORDEN_COTIZACION']);?></td>
</tr>	
<?php 
 for($i=0; $i<count($cotizacionesdet); $i++){
	?>
<tr class="fondobh">
	<td colspan="4" align="center">Proveedor:  <b><?php echo($cotizacionesdet[$i]['PROVEEDOR_DES']);?></b></td>
</tr>
<tr> 
   <td COLSPAN="4" >
	<table ALIGN="CENTER" width="700">
	   <tr class="fondob">
		<td>Fecha creacion: <?php echo($cotizacionesdet[$i]['FECHA_CREACION']);?></td>
		<td>Fecha envio: <?php echo($cotizacionesdet[$i]['FECHA_ENVIO']);?></td>
	   </tr>
	   <tr class="fondob">	
		<td>Valor: <b><?php echo("$".number_format($cotizacionesdet[$i]['VALOR'],1,'.',','));?></b></td>
		<td>Iva: <b><?php echo($cotizacionesdet[$i]['IVA']);?> %</b></td>
		<td>Valor iva: <b><?php echo($cotizacionesdet[$i]['VALOR_IVA']);?></b></td>
		<td>Flete: <b><?php echo($cotizacionesdet[$i]['FLETE']);?>    </b> </td>
	   </tr>
	   <tr class="fondob">
		<td>forma de pago : <?php echo($cotizacionesdet[$i]['FORMA_DE_PAGO_DES']);?> </td>
		<td>Garantia : <?php echo($cotizacionesdet[$i]['GARANTIA_DES']);?> </td>
		<td>Tiempo : <?php echo($cotizacionesdet[$i]['TIEMPO_DES']);?> </td>
	   </tr>
	   <tr class="fondob">
		<td>Sitio de Entrega : <?php echo($cotizacionesdet[$i]['SITIO_ENTREGA_DES']);?> </td>
		<td></td>
		<td></td>
	   </tr>
	</table>
   </td>
</tr>
<tr class="fondob">
	<td colspan="4">Observaciones Cotizacion: <?php echo($cotizacionesdet[$i]['OBSERVACIONES_COTIZACION']);?> </td>
</tr><tr class="fondob">
	<td colspan="4">Descripcion Proveedor: <?php echo($cotizacionesdet[$i]['DESCRIPCION_PROVEEDOR']);?> </td>
</tr>
<tr class="fondob">
	<td>Total Cotizacion : <span class="totalcotizacion"><?php echo("$".number_format($cotizacionesdet[$i]['TOTAL_COTIZACION'],1,'.',','));?></span></td>
</tr>
	<?php
 }
?>
<?php	
	}
?>
   </table>
  </form>
 </body>
</html>