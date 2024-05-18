<?php 
if (!isset($_SESSION)) {
  session_start();
}
require_once('conexion/db.php'); 
$displayfiltro='none';
$tipocompara='';
if(isset($_GET['tipocompara']) && $_GET['tipocompara']!=''){
$tipocompara = $_GET['tipocompara'];
}
$codigo_detalle='';
if(isset($_GET['codDetalle']) && $_GET['codDetalle']!=''){
$codigo_detalle = $_GET['codDetalle'];
$displayfiltro='block';
}
$arraycompdetalle=array();
$query_RsDetalleComparar="SELECT C.COTICODI CODIGO,
                                    C.COTIORDE ORDEN,
                                    C.COTIPROV PROVEEDOR,
									P.PROVNOMB PROVEEDOR_DES,
                                    C.COTIESTA ESTADO,
									C.COTIFOPA FORMA_PAGO,									
									(select M.CRMEDESC 
									 from criterio_medicion M 
									 WHERE M.CRMECONS = CD.CODEPREC LIMIT 1) PRECIO_DESC,									 
									(select M.CRMEDESC 
									 from criterio_medicion M 
									 WHERE M.CRMECONS = C.COTIFOPA LIMIT 1) FORMA_PAGO_DESC,
									 (select M.CRMEDESC 
								      from criterio_medicion M
									WHERE M.CRMECONS = C.COTIGARA LIMIT 1) GARANTIA_DESC,
									(select M.CRMEDESC 
								      from criterio_medicion M
									WHERE M.CRMECONS = C.COTISIEN LIMIT 1) SITIO_DESC,
									(select M.CRMEDESC 
								      from criterio_medicion M
									WHERE M.CRMECONS = C.COTITIEN LIMIT 1) TIEMPO_DESC,									
									(select M.CRMEDESC 
								      from criterio_medicion M
									WHERE M.CRMECONS = CD.CODECAPR LIMIT 1) VALOR_AGREGADO_DESC,
									D.DERECONS CODIGO_DETALLE,
									D.DEREDESC  NOMBRE,
									CD.CODEDESC NOMBRE_COT,
									D.DERECANT CANTIDAD,
								   CD.CODEVALO VALOR,
								    (((CD.CODEVALO*CD.CODEVAIV)/100)+CD.CODEVALO) VALOR_C,
                                   ((((CD.CODEVALO*CD.CODEVAIV)/100)+CD.CODEVALO)*D.DERECANT) TOTAL,
								    CD.CODEPREC PRECIO,
									CD.CODECODI CODIGO_DETALLE_COT,
									(select M.CRMETICR 
								      from criterio_medicion M
									WHERE M.CRMECONS = CD.CODEPREC LIMIT 1) CRITERIO_PRECIO,
								   (select M.CRMETICR 
								      from criterio_medicion M
									WHERE M.CRMECONS = C.COTIFOPA LIMIT 1) CRITERIO,
									C.COTITIEN TIEMPO,
									(select M.CRMETICR 
								      from criterio_medicion M
									WHERE M.CRMECONS = C.COTITIEN LIMIT 1) CRITERIO_TIEMPO,
									C.COTISIEN SITIO,
									(select M.CRMETICR 
								      from criterio_medicion M
									WHERE M.CRMECONS = C.COTISIEN LIMIT 1) CRITERIO_SITIO,
									C.COTIGARA GARANTIA,
									(select M.CRMETICR 
								      from criterio_medicion M
									WHERE M.CRMECONS = C.COTIGARA LIMIT 1) CRITERIO_GARANTIA,
									CD.CODECAPR VALOR_AGREGADO,
									(select M.CRMETICR 
								      from criterio_medicion M
									WHERE M.CRMECONS = CD.CODECAPR LIMIT 1) CRITERIO_VALOR_AGREGADO,
									D.DEREPROV DETALLE_PROVEEDOR,
									D.DEREDCOM DETALLE_COMPARAR,
									D.DEREAPRO ESTADO_DETALLE,
									D.DEREPRRE PROVEEDOR_RECTOR,
									(SELECT P2.PROVNOMB 
									  FROM DETALLE_REQU DR,
									       PROVEEDORES  P2
									 WHERE DR.DERECONS = '".$codigo_detalle."'
									   AND DR.DEREPROV = P2.PROVCODI
									   LIMIT 1
									 ) PROVEEDORDIRECTOR_DES,
									 D.DEREPROV PROVEEDORDIRECTOR,
									(SELECT P2.PROVNOMB 
									  FROM DETALLE_REQU DR,
									       PROVEEDORES  P2
									 WHERE DR.DERECONS = '".$codigo_detalle."'
									   AND DR.DEREPRRE = P2.PROVCODI
									   LIMIT 1
									 ) PROVEEDORRECTOR_DES,
									 D.DEREFFRE FECHA_FIRMADO_RECTOR
						   FROM COTIZACION C,
						       COTIZACION_DETALLE CD,
							   DETALLE_REQU    D,
							   PROVEEDORES     P
						  WHERE CD.CODEDETA = '".$codigo_detalle."'
						    AND C.COTICODI = CD.CODECOTI
							AND CD.CODEDETA = D.DERECONS
							AND C.COTIPROV = P.PROVCODI
						  ORDER BY C.COTIPROV ";
						 //echo $query_RsDetalleComparar;
	$RsDetalleComparar = mysqli_query($conexion,$query_RsDetalleComparar) or die(mysqli_error($conexion));
	$row_RsDetalleComparar = mysqli_fetch_array($RsDetalleComparar);
    $totalRows_RsDetalleComparar = mysqli_num_rows($RsDetalleComparar);
	if($totalRows_RsDetalleComparar>0){
	 do{
	  $arraycompdetalle[]=$row_RsDetalleComparar;
	  }while($row_RsDetalleComparar = mysqli_fetch_array($RsDetalleComparar));
	}
	
?><!DOCTYPE HTML>
<head>
<title>Comparar</title>
<meta charset="utf-8">
	<!--<link rel="stylesheet" type="text/css" href="css/page.css"/>-->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
	<style type="text/css">
	</style>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
	<script type="text/javascript" src="js/jquery.1.7.2.js"></script>	
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript">

$(function() {	
  $(".btnfiltro").click(function(evento){
  $("#filtros").toggle();
  //var options = "";
  //$( "#filtros" ).toggle( 'drop', options, 500 );
  });
  
  $(".asgprov").click(function(evento){
	cod_det_cot=  ($(this).attr("data-deta_cotiz"));
	if(cod_det_cot == 0){ alert('no tiene datos'); return false }
	
    proveedor= ($(this).attr("data-proveedor"));
	descripcion = $("#areaproddescripcion").val();
	asignado = false;	
<?php 
if($_SESSION['MM_RolID'] != 5){
?>	
	
		if(!($("#tdprov_"+proveedor).hasClass("prov_asignado"))){   
			$('.prov_asignado').each(function(index){
		     asignado = true;
		    })
			
			if(asignado == true){
				
				if(confirm("Este detalle se encuentra asignado a un proveedor actualmente, Seguro que quiere cambiarlo?")){
					$('<div id="infoajaxload" style="width:300px; height:30px; background:#b7e06d; margin:0 auto; position:fixed; left:40%; top:0%; z-index:5; border:1px solid #fff500; box-shadow: 0 8px 15px 0 #baf729; padding:5px;">Su informacion se esta procesando...</div>').appendTo('body');						
					$.ajax({
						type: "POST",
						url: "tipo_guardar.php?tipoGuardar=AsignarProveedor&codigo_detalle=<?php echo($codigo_detalle); ?>&proveedor="+proveedor+"&descripcion="+descripcion,
						success : function(r){
							if(r==0 || r==1){
								Success(r,proveedor);
								$("#provasignado_director").text($("#nameproveedor_"+proveedor).text());
							}else{
								$("#infoajaxload").remove();
								alert('Se ha presentado un error de datos');
							}				 
						},
						error   : Error_Ajax
					});
				}
				
			}else{  
					$('<div id="infoajaxload" style="width:300px; height:30px; background:#b7e06d; margin:0 auto; position:fixed; left:40%; top:0%; z-index:5; border:1px solid #fff500; box-shadow: 0 8px 15px 0 #baf729; padding:5px;">Su informacion se esta procesando...</div>').appendTo('body');						
					$.ajax({
						type: "POST",
						url: "tipo_guardar.php?tipoGuardar=AsignarProveedor&codigo_detalle=<?php echo($codigo_detalle); ?>&proveedor="+proveedor+"&descripcion="+descripcion,
						success : function(r){
							alert(r);
							if(r==0 || r==1){
								Success(r,proveedor);
								$("#provasignado_director").text($("#nameproveedor_"+proveedor).text());
							}else{
								$("#infoajaxload").remove();
								alert();
								alert('Se ha presentado un error de datos');
							}				 
						},
						error   : Error_Ajax
					});				
				
			}
			
		}else{
				if(confirm("desea editar esta informacion?")){
					$('<div id="infoajaxload" style="width:300px; height:30px; background:#b7e06d; margin:0 auto; position:fixed; left:40%; top:0%; z-index:5; border:1px solid #fff500; box-shadow: 0 8px 15px 0 #baf729; padding:5px;">Su informacion se esta procesando...</div>').appendTo('body');						
					$.ajax({
						type: "POST",
						url: "tipo_guardar.php?tipoGuardar=AsignarProveedor&codigo_detalle=<?php echo($codigo_detalle); ?>&proveedor="+proveedor+"&descripcion="+descripcion,
						success : function(r){
							if(r==0 || r==1){
								Success(r,proveedor);
								$("#provasignado_director").text($("#nameproveedor_"+proveedor).text());
							}else{
								$("#infoajaxload").remove();
								alert('Se ha presentado un error de datos');
							}				 
						},
						error   : Error_Ajax
					});
				}
		}
		
		AsignarProvRector(proveedor);

  <?php }else{
	  ?>
	  //AsignarProvRector(proveedor);
	  <?php
  } ?>

		
  });	  
  
});

function AsignarProvRector(proveedor){
				if(confirm("desea asignar este proveedor al detalle?")){
					$('<div id="infoajaxload" style="width:300px; height:30px; background:#b7e06d; margin:0 auto; position:fixed; left:40%; top:0%; z-index:5; border:1px solid #fff500; box-shadow: 0 8px 15px 0 #baf729; padding:5px;">Su informacion se esta procesando...</div>').appendTo('body');						
					$.ajax({
						type: "POST",
						url: "tipo_guardar.php?tipoGuardar=AsignarProveedorRector&codigo_detalle=<?php echo($codigo_detalle); ?>&proveedor="+proveedor+"&descripcion="+descripcion,
						success : function(r){
							//nameproveedor_1110514952-3
							 $("#infoajaxload").remove();
							if(r==1 || r==0){
								$("#provasignado_rector").text($("#nameproveedor_"+proveedor).text());
								alert("proveedor almacenado correctamente");
							}else{
								$("#infoajaxload").remove();
								alert('Se ha presentado un error de datos');
							}				 
						},
						error   : Error_Ajax
					});
				}	
}



function llamarAjax(){
	
}
function Success(r, prov){
	$("#infoajaxload").remove();
	if(r==1){
		 $('.prov_asignado').each(function(index){
		   $(this).removeClass("prov_asignado");
		 })
		$("#tdprov_"+prov).addClass("prov_asignado");
	}
}
function Error_Ajax(XMLHttpRequest, textStatus, errorThrown){
    $("#infoajaxload").remove();
    alert("Respuesta del servidor "+XMLHttpRequest.responseText);
    alert("Error "+textStatus);
    alert(errorThrown);
}


function GuardarCproducto(id){
	criterio_cproduct   = $("#combocartprod_"+id).val();		
	proveedor			= ($("#combocartprod_"+id).attr("data-proveedor2"));
	det_cotizado		= id;
	
	$.ajax({
			type: "POST",
			url: "tipo_guardar.php?tipoGuardar=ActualizarCriterioCaractProducto&detalle_cotizado="+id+"&criterio="+criterio_cproduct,
			success : function(r){
			//console.log(r);
			 location.reload();
							
				},
				error   : Error_Ajax
		});
}
function GuardarPrecio(id){	
	criterio_precio 	= $("#comboprecio_"+id).val();	
	proveedor			= ($("#comboprecio_"+id).attr("data-proveedor2"));
	det_cotizado		= id;
    //console.log(criterio_precio,proveedor,det_cotizado);	
		$.ajax({
			type: "POST",
			url: "tipo_guardar.php?tipoGuardar=ActualizarCriterioPrecio&detalle_cotizado="+id+"&criterio="+criterio_precio,
			success : function(r){
			//console.log(r);
			 location.reload();
							
				},
				error   : Error_Ajax
		});
	
	
	
    
  }
	 

</script>
<style type="text/css">
body{
font-family:verdana;
}
.datacomparar{
/*border: solid 1px #ff0000;*/
min-height:600px;
float:left;
}
#sidebar_left{
 width:250px;
 float:left;
}
#container_comparar{
border:solid 1px #ccc;
min-height:600px;
margin-left:5px;
float:left;
width:700px;
}
.filtros{
 font-size:11px;
 border: 1px solid #ccc;
 margin:4px 3px 5px 3px;
  box-shadow: 1px 1px 3px 1px #ccc;
  padding-bottom:8px;
  border-radius:5px;
}
.btnfiltro{
 margin:5px 0px 2px 5px;
 background:url("images/search.png") no-repeat;
 width:21px;
 height:21px;
 cursor:pointer;
}
.Titulo6{
	FONT-SIZE: 11px;
	FONT-FAMILY: Arial, Helvetica, Verdana;
	BACKGROUND-COLOR: #D4D8EE;
	font-weight: bold;
}
.Titulo5{
	FONT-SIZE: 11px;
	FONT-FAMILY: Arial, Helvetica, Verdana;
	BACKGROUND-COLOR: #CCC;
	font-weight: bold;
}
.SBRA2{
background:#EEEED4;
}
.SBRA3{
background:#FFFFF4;
}
.porc100{
	background:url("images/validated.png") no-repeat;
	width:16px;
	height:16px;
	
}
.porcotro{
	color:#555;
	font-weight:700;
}
.tablaprincipal{
	font-size:10px;
}
.tablaprincipal td{
	border-color:solid 1px #ff0000;
}
.prov_asignado{ <?php $colorprov_asig = '#1EF494'; 
if($_SESSION['MM_RolID'] == 5){
$colorprov_asig = "cyan";	
	?>
<?php 
}
?>
	background:<?php echo($colorprov_asig);?>;	
}

</style>
</head>

<form name="form1" id="form1" method="post" action="">
	    <div id="filtros" class="filtros" style="display:none;">
		  <table>
		   <tr>
		    <td title="codigo detalle">Cod Detalle:</td>
			<td><input type="text" name="codigo_detalle" id="codigo_detalle" value="<?php echo($codigo_detalle);?>" size="15"></td>
		   </tr>
		   <tr>
		    <td title="codigo del requerimiento">Cod Requ:</td>
			<td><input type="text" name="codigo_requerimiento" id="codigo_requerimiento" value="" size="15"></td>
		   </tr>
		   <?php /*
		   <tr>
		   <td title="codigo de cotizacion">Cod Cot:</td>
			<td><input type="text" name="codigo_coti" id="codigo_coti" value="" size="15"></td>
		   </tr>
		   <tr>
		    <td title="persona que solicita">Pers Sol:</td>
			<td><input type="text" name="codigo_requ" id="codigo_requ" value="" size="15"></td>
		   </tr>		   
		   <tr>
		    <td>Inicio:</td>
			<td><input type="text" name="fecha_inicio" id="fecha_inicio" value="" size="15"></td>
		   </tr>
		   <tr>
		    <td>Fin:</td>
			<td><input type="text" name="fecha_fin" id="fecha_fin" value="" size="15"></td>
		   </tr>
		   */ ?>
		   <tr>
		    <td colspan="4" align="center"><input type="button" name="filtro_buscar" id="filtro_buscar" value="Buscar"><input type="button" name="limpiarf" id="limpiarf" value="limpiar"></td>
		   </tr>
		  </table>
		</div>
<table width="100%" class="tablaprincipal">
 	  <tr>
		<td class="SBRA2"><b>¿Por que eligio este proveedor?</b></td>
	  </tr>
	  <tr>
	    <td valign="top" colspan="4">
	  <textarea cols="90" rows="2" name="areaproddescripcion" id="areaproddescripcion"><?php 
		if(count($arraycompdetalle)>0){
			echo($arraycompdetalle[0]['DETALLE_COMPARAR']);
		}
	   ?></textarea>
	  </td>

	  </tr>
	  	  <?php
		if(count($arraycompdetalle)>0){
	  ?>
	  <tr>
	  <td class="SBRA2" ><b>Proveedor Asignado por Director Administrativo</b></td>
	  <td colspan="2"><div id="provasignado_director"><?php 		
			echo($arraycompdetalle[0]['PROVEEDORDIRECTOR_DES']);
		?></div></td>
	  </tr>
	  <tr>
		<td  class="SBRA2"><b>Proveedor Asignado por el Rector</b></td>
		<td><div id="provasignado_rector"><?php echo($arraycompdetalle['0']['PROVEEDORRECTOR_DES']);?></div></td>
	  	  </tr>
		<?php 
		} 
		?>

	  <tr>
<tr class="Titulo5">
	<?php 
	for($i=0; $i<count($arraycompdetalle); $i++){
		?>
		<?php		
	 $num=$i+1;
	?>
    <td align="center"colspan="4">PROVEEDOR No: <?php echo($num);?></td>
	<?php
	}
	?>
 </tr>
 <tr class="Titulo5">
  <form>
 <?php
      for($i=0; $i<count($arraycompdetalle); $i++){		  
?>
	 <td align="center" colspan="4" id="tdprov_<?php echo($arraycompdetalle[$i]['PROVEEDOR']);?>" <?php 
	 
	 if($arraycompdetalle[$i]['PROVEEDOR'] == $arraycompdetalle[$i]['DETALLE_PROVEEDOR']){

	 ?>class="prov_asignado"<?php 
	 
	 } ?>>
	 <?php if($arraycompdetalle[$i]['FECHA_FIRMADO_RECTOR'] == NULL){ ?>
	 <span data-deta_cotiz="<?php echo($arraycompdetalle[$i]['VALOR']);?>"
	         data-proveedor="<?php echo($arraycompdetalle[$i]['PROVEEDOR']);?>" 
			 class="btn btn-default asgprov"> Asignar proveedor </span>
			
			 <?php 
			 
			 } ?>
			 
			 <div id="nameproveedor_<?php echo($arraycompdetalle[$i]['PROVEEDOR']);?>">
			 <?php echo($arraycompdetalle[$i]['PROVEEDOR_DES']);?></div><br>
	 <?php /*<textarea cols="50" name="areaproddesc_<?php echo($arraycompdetalle[$i]['PROVEEDOR']);?>" id="areaproddesc_<?php echo($arraycompdetalle[$i]['PROVEEDOR']);?>"><?php echo($arraycompdetalle[$i]['DETALLE_COMPARAR']);?></textarea>*/?>
	 </td>
	 
	 
	  <?php
	  }
	  ?>
	  </form>
</tr>
 <tr class="Titulo5">
	<?php 
	for($i=0; $i<count($arraycompdetalle); $i++){
	 $num=$i+1;
	?>
    <td>Descripcion </td>	
    <td>Cant</td>
    <td>V/U</td>
    <td>Total</td>
	<?php
	}
	?>
 </tr>
   <tr class="SBRA2">
 <?php
  for($i=0; $i<count($arraycompdetalle); $i++){
  ?>
   <td><?php echo($arraycompdetalle[$i]['NOMBRE']);?></td>   
   <td><?php echo($arraycompdetalle[$i]['CANTIDAD']);?></td> 
   <td><?php echo '$'.(number_format($arraycompdetalle[$i]['VALOR_C'],2,',',','));?></td> 
   <td><?php echo '$'.(number_format($arraycompdetalle[$i]['TOTAL'],2,',',','));?></td> 
  <?php
  }
  ?>
</tr>
 <tr class="Titulo5">
	<?php 
	for($i=0; $i<count($arraycompdetalle); $i++){
	 $num=$i+1;
	?>
    <td colspan="4" >Desc Proveedor </td>	    
	<?php
	}
	?>
 </tr>
   <tr  class="SBRA2">
 <?php
  for($i=0; $i<count($arraycompdetalle); $i++){
  ?>
   <td colspan="4" ><?php echo($arraycompdetalle[$i]['NOMBRE_COT']);?></td>
  <?php
  }
  ?>
</tr>
<tr>
 
  <?php
  for($i=0; $i<count($arraycompdetalle); $i++){
  ?>
  <td class="Titulo6" colspan="3">PRECIO</td>
   <td class="SBRA3"> 
       <?php if($_SESSION['MM_RolID'] == 2 || $_SESSION['MM_RolID'] == 3){ ?>
		<span>			 
			 <select data-proveedor2="<?php echo($arraycompdetalle[$i]['PROVEEDOR']);?>"  name="comboprecio" id="comboprecio_<?php echo($arraycompdetalle[$i]['CODIGO_DETALLE_COT']);?>" onchange="GuardarPrecio('<?php echo($arraycompdetalle[$i]['CODIGO_DETALLE_COT']);?>');">			 
			  <option value="">.....</option>
			  <option value="18">Bajo</option>
			  <option value="19">Adecuado</option>
			  <option value="20">Alto</option>			  
			</select>
		</span>
	   <?php }?>
   
   
   <?php echo($arraycompdetalle[$i]['PRECIO_DESC']);?></td>
  <?php
  } 
  ?>
</tr>
<tr>
 
  <?php
  for($i=0; $i<count($arraycompdetalle); $i++){
  ?>
  <td class="Titulo6" colspan="3">FORMA DE PAGO</td>
   <td class="SBRA3"><?php echo($arraycompdetalle[$i]['FORMA_PAGO_DESC']);?></td>
  <?php
  } 
  ?>
</tr>
<tr>
  <?php
  for($i=0; $i<count($arraycompdetalle); $i++){
  ?>
  <td colspan="3" class="Titulo6">GARANTIA</td>
   <td class="SBRA3"><?php echo($arraycompdetalle[$i]['GARANTIA_DESC']);?></td>
  <?php
  } 
  ?>
</tr>
<tr>
  <?php
  for($i=0; $i<count($arraycompdetalle); $i++){
  ?>
  <td class="Titulo6" colspan="3">SITIO DE ENTREGA</td>
   <td class="SBRA3"><?php echo($arraycompdetalle[$i]['SITIO_DESC']);?></td>
  <?php
  } 
  ?>
</tr>
<tr>
  <?php
  for($i=0; $i<count($arraycompdetalle); $i++){
  ?>
  <td colspan="3" class="Titulo6">TIEMPO DE ENTREGA</td>
   <td class="SBRA3"><?php echo($arraycompdetalle[$i]['TIEMPO_DESC']);?></td>
  <?php
  } 
  ?>
</tr>
<tr>
  <?php
  for($i=0; $i<count($arraycompdetalle); $i++){
  ?>
  <td colspan="3" class="Titulo6">CARACTERISTICAS DE PRODUCTO</td>
   <td class="SBRA3"> 
         <?php if($_SESSION['MM_RolID'] == 2 || $_SESSION['MM_RolID'] == 3){ ?>
           <span>
			 <select data-proveedor2="<?php echo($arraycompdetalle[$i]['PROVEEDOR']);?>"  name="combocartprod" id="combocartprod_<?php echo($arraycompdetalle[$i]['CODIGO_DETALLE_COT']);?>" onchange="GuardarCproducto('<?php echo($arraycompdetalle[$i]['CODIGO_DETALLE_COT']);?>');">			 
			  <option value="">.....</option>
			  <option value="15">Cumple</option>
			  <option value="16">Cumple Parcialmente</option>
			  <option value="17">No Cumple</option>			  
			</select>
			 </span>
		 <?php }?>
   
   
   <?php echo($arraycompdetalle[$i]['VALOR_AGREGADO_DESC']);?></td>
  <?php
  } 
  ?>
</tr>

<tr>
  <?php
  for($i=0; $i<count($arraycompdetalle); $i++){
  ?>
  <td colspan="3" class="Titulo6">VALOR TOTAL</td>
   <td class="SBRA3"><?php echo '$'.(number_format($arraycompdetalle[$i]['TOTAL'],2,',',','));?></td>
  <?php
  } 
  ?>
</tr>
<tr>
  <?php
  for($i=0; $i<count($arraycompdetalle); $i++){
  ?>
  <td colspan="4">
  <?php
	    $query_RsTipoCriterio="SELECT TICRID CODIGO,
						      TICRNOMB NOMBRE 
					FROM TIPO_CRITERIO 
					WHERE 1 ";
						 //echo $query_RsTipoCriterio;
	$RsTipoCriterio = mysqli_query($conexion,$query_RsTipoCriterio) or die(mysqli_error($conexion));
	$row_RsTipoCriterio = mysqli_fetch_array($RsTipoCriterio);
    $totalRows_RsTipoCriterio = mysqli_num_rows($RsTipoCriterio);
?>
<table border="1" WIDTH="780">
<tr>
 <td colspan="780">	
  <table width="100%" border="1">
	  <tr ALIGN="CENTER" style="background:#cadeab; color:#3c404e; ">
		 <td width="180">TIPO DE CRITERIO</td>
		 <td width="200">CRITERIO</td>
		 <td width="300">MEDICION DEL CRITERIO</td>
		 <td width="100">VALORACION</td>
	  </tr>
   </table>
 </td>
</tr>
<?php
	if($totalRows_RsTipoCriterio>0){
		$promedio_porcentajes = 0;
		$cantidad_porcentajes = 0;
		do{
		?>
		 <tr>
			<td style="background:#EDEDED; width:180px" align="center"><b><?php echo($row_RsTipoCriterio['NOMBRE']);?></b></td>
			<td width="600">
				<table width="100%" border="0">
				  <?php 
				    		$query_RsCriterio="SELECT C.CRITCONS CONSECUTIVO,
													  C.CRITDESC NOMBRE,
													  C.CRITTICR TIPO_CRITERIO,
													  C.CRITCLID CLAS_ID,
													 CC.CRCLNOMB CLASIFICACION_NOMBRE
												FROM  criterio C,
                                                      criterio_clasificacion CC													  
												WHERE CRITTICR = '".$row_RsTipoCriterio['CODIGO']."'
 												  AND CRITCLID = CC.CRCLCONS
												  ORDER BY CRITCLID DESC
												 
												";
							 //echo $query_RsCriterio."<BR>";
						$RsCriterio = mysqli_query($conexion,$query_RsCriterio) or die(mysqli_error($conexion));
						$row_RsCriterio = mysqli_fetch_array($RsCriterio);
						$totalRows_RsCriterio = mysqli_num_rows($RsCriterio);
						if($totalRows_RsCriterio>0){
							$clasificacion='';
							do{
								?>
								<?php
									if($clasificacion=='' || $row_RsCriterio['CLAS_ID'] != $clasificacion){
								?>
								<tr><td height="30" align="left" colspan="3" style="background:#ccc; border:solid 1px #FCFEF9"><b><?php echo($row_RsCriterio['CLASIFICACION_NOMBRE']);?></b></td></tr>
                                <?php 
								$clasificacion = $row_RsCriterio['CLAS_ID'];
									}
								?>								
								<tr>
								  <td width="200" style="background:#D4D8EE; border:solid 1px #F9F9F9;" ><?php echo($row_RsCriterio['NOMBRE']);?></td>
								  <?php 
											$query_RsCriterioMed="SELECT CRMECONS CODIGO,
																	  CRMEDESC NOMBRE,
																	  CRMETICR CRITERIO,
																	  CRMEPORC PORCENTAJE
															FROM criterio_medicion 
															WHERE  CRMETICR = '".$row_RsCriterio['CONSECUTIVO']."'";
																// echo $query_RsCriterioMed;
											$RsCriterioMed = mysqli_query($conexion,$query_RsCriterioMed) or die(mysqli_error($conexion));
											$row_RsCriterioMed = mysqli_fetch_array($RsCriterioMed);
											$totalRows_RsCriterioMed = mysqli_num_rows($RsCriterioMed);
											?>
										 <td width="300">
											<table border="1">
											<?php
											if($totalRows_RsCriterioMed>0){
												do{
													?>
													<tr style="border:solid 1px #11f10f">
														<td style="background:#EEEED4;" width="270"><?php echo($row_RsCriterioMed['NOMBRE']);?></td>
														<td style="background:#B5EFC8;"  align="center" width="30"><?php echo($row_RsCriterioMed['PORCENTAJE']);?></td>
													</tr>
													<?php
												}while($row_RsCriterioMed = mysqli_fetch_array($RsCriterioMed));
											}
								  ?>
								          </table>
										</td>
										<td align="center">
										<?php if($arraycompdetalle[$i]['CRITERIO_PRECIO'] == $row_RsCriterio['CONSECUTIVO'] ){									 
											  $porcentaje = retornaporcentaje($arraycompdetalle[$i]['CRITERIO_PRECIO'], $arraycompdetalle[$i]['PRECIO']);
											  if($porcentaje==100){
												  echo("<div class='porcotro'>".$porcentaje."</div>");
											  }else{
												  echo("<div class='porcotro'>".$porcentaje."</div>");
											  }
											 
										 } ?>
										 <?php if($arraycompdetalle[$i]['CRITERIO'] == $row_RsCriterio['CONSECUTIVO'] ){									 
											  $porcentaje = retornaporcentaje($arraycompdetalle[$i]['CRITERIO'], $arraycompdetalle[$i]['FORMA_PAGO']);
											  if($porcentaje==100){
												  echo("<div class='porcotro'>".$porcentaje."</div>");
											  }else{
												  echo("<div class='porcotro'>".$porcentaje."</div>");
											  }
										 } ?>
									<?php if($arraycompdetalle[$i]['CRITERIO_TIEMPO'] == $row_RsCriterio['CONSECUTIVO'] ){									 
											 $porcentaje = retornaporcentaje($arraycompdetalle[$i]['CRITERIO_TIEMPO'], $arraycompdetalle[$i]['TIEMPO']);
											 if($porcentaje==100){
												  echo("<div class='porcotro'>".$porcentaje."</div>");
											  }else{
												  echo("<div class='porcotro'>".$porcentaje."</div>");
											  }
										 } ?>
									<?php if($arraycompdetalle[$i]['CRITERIO_SITIO'] == $row_RsCriterio['CONSECUTIVO'] ){									 
											 $porcentaje = retornaporcentaje($arraycompdetalle[$i]['CRITERIO_SITIO'], $arraycompdetalle[$i]['SITIO']);
											 if($porcentaje==100){
												  echo("<div class='porcotro'>".$porcentaje."</div>");
											  }else{
												  echo("<div class='porcotro'>".$porcentaje."</div>");
											  }
										 } ?>
										 <?php if($arraycompdetalle[$i]['CRITERIO_VALOR_AGREGADO'] == $row_RsCriterio['CONSECUTIVO'] ){									 
											 $porcentaje = retornaporcentaje($arraycompdetalle[$i]['CRITERIO_VALOR_AGREGADO'], $arraycompdetalle[$i]['VALOR_AGREGADO']);
											 if($porcentaje==100){
												  echo("<div class='porcotro'>".$porcentaje."</div>");
											  }else{
												  echo("<div class='porcotro'>".$porcentaje."</div>");
											  }
										 } ?>
									<?php if($arraycompdetalle[$i]['CRITERIO_GARANTIA'] == $row_RsCriterio['CONSECUTIVO'] ){									 
											 $porcentaje = retornaporcentaje($arraycompdetalle[$i]['CRITERIO_GARANTIA'], $arraycompdetalle[$i]['GARANTIA']);
											 if($porcentaje==100){
												  echo("<div class='porcotro'>".$porcentaje."</div>");
											  }else{
												  echo("<div class='porcotro'>".$porcentaje."</div>");
											  }
										 } 
										 //$promedio_porcentajes = $promedio_porcentajes + $porcentaje;
										 $cantidad_porcentajes = $cantidad_porcentajes +1;
										 ?>
										</td>
								</tr>
								<?php
							}while($row_RsCriterio = mysqli_fetch_array($RsCriterio));
						}
				  ?>
				</table>
			</td>
		 </tr>
		<?php		
		}while($row_RsTipoCriterio = mysqli_fetch_array($RsTipoCriterio));
	}
?>
	  <tr>
		<td align="right">&nbsp;</td>
		 <td align="right" style="background:#B5EFC8; font-size:16px;"><!-- PROMEDIO : <?php echo(round(($promedio_porcentajes/$cantidad_porcentajes),2));?> %--></td>
	  
	  </tr>
</table>

  </td>
  <?php 
  }
  ?>
</tr>

</table>
<?php
function retornaporcentaje($criterio, $formapago){
	include('conexion/db.php'); 
	$query_RsTipoCriterio="SELECT M.CRMEPORC
				    FROM  criterio_medicion M
                    WHERE M.CRMETICR = '".$criterio."'
                      and M.CRMECONS = '".$formapago."'
                     limit 1					  
					 ";
						 //echo $query_RsTipoCriterio;
	$RsTipoCriterio = mysqli_query($conexion,$query_RsTipoCriterio) or die(mysqli_error($conexion));
	$row_RsTipoCriterio = mysqli_fetch_array($RsTipoCriterio);
    $totalRows_RsTipoCriterio = mysqli_num_rows($RsTipoCriterio);
	if($totalRows_RsTipoCriterio>0){
		return $row_RsTipoCriterio['CRMEPORC'];
	}
}
//var_dump($arraycompdetalle);
    //TIPO CRITERIO 
	$query_RsTipoCriterio="SELECT TICRID   CODIGO,
						  TICRNOMB NOMBRE 
				    FROM  tipo_criterio 
					 ";
						 //echo $query_RsTipoCriterio;
	$RsTipoCriterio = mysqli_query($conexion,$query_RsTipoCriterio) or die(mysqli_error($conexion));
	$row_RsTipoCriterio = mysqli_fetch_array($RsTipoCriterio);
    $totalRows_RsTipoCriterio = mysqli_num_rows($RsTipoCriterio);
	
	if($totalRows_RsTipoCriterio>0){
	 do{
	  $arrayTipoCriterio[]=$row_RsTipoCriterio;
	  }while($row_RsTipoCriterio = mysqli_fetch_array($RsTipoCriterio));
	}
	
	//CRITERIO
	$query_RsCriterio="SELECT TICRID CODIGO, 
							   CRITDESC DESCR_CRITERIO
						  FROM tipo_criterio, criterio
						 WHERE CRITTICR = TICRID
											 ";
								 //echo $query_RsCriterio;
	$RsCriterio = mysqli_query($conexion,$query_RsCriterio) or die(mysqli_error($conexion));
	$row_RsCriterio = mysqli_fetch_array($RsCriterio);
    $totalRows_RsCriterio = mysqli_num_rows($RsCriterio);
	
	if($totalRows_RsCriterio>0){
	 do{
	  $arrayCriterio[]=$row_RsCriterio;
	  }while($row_RsCriterio = mysqli_fetch_array($RsCriterio));
	}
	
	//MEDICION DE CRITERIO
	$query_RsMedCriterio="SELECT TICRID   CODIGO_TIPO, 
								   CRITCONS CODIGO_CRITERIO,
								   CRITDESC DESCR_CRITERIO,
								   CRMEDESC DESC_MEDI,
								   CRMEPORC PORCENTAJE
						  FROM tipo_Criterio, criterio,
                               criterio_medicion
						 WHERE CRITTICR = TICRID
                         AND   CRMETICR = CRITCONS
                         
											 ";
								 //echo $query_RsMedCriterio;
	$RsMedCriterio = mysqli_query($conexion,$query_RsMedCriterio) or die(mysqli_error($conexion));
	$row_RsMedCriterio = mysqli_fetch_array($RsMedCriterio);
    $totalRows_RsMedCriterio = mysqli_num_rows($RsMedCriterio);
	
	if($totalRows_RsMedCriterio>0){
	 do{
	  $arrayMedCriterio[]=$row_RsMedCriterio;
	  }while($row_RsMedCriterio = mysqli_fetch_array($RsMedCriterio));
	}

?>


</form>
</html>
<?php 
/*$datas = array();
$arrdata[0] = array('TIPOCRITERIO'       => array(),
					'PREG'         		 => $row_RsPreguntasRespData['PREGUNTA'],
					'RESPUESTA'    		 => $_GET['respuesta'],
					'ACIERTO'      		 => $row_RsPreguntasRespData['VALOR_ACIERTO'],
					'PROMEDIO'     		 => $promedio,
					'DIFICULTAD'   		 => $dificultad,
					'LOGIT'        		 => $logit,
		   
					'LOGIT_0'      	 	 => $logit0,
					'FITEST_0'     	   	 => $fitest_0,
					'ESPERADO_0'   		 => $esperado,
					'VARIANZAOF_O' 	     => $varianzaof_0,
					'RESIDUAL_0'     	 => $residual_0,
					'SUMARESIDUALES_0'    => $sumaresiduales_0,
		   
  );
  */
$arrdata = array();
$arrdata = tipo_criterio();
$segnivel = criterio($arrdata);
$arrdata  = medicion($segnivel);
//var_dump($arrdata[0]['CRITERIO']);
//echo("<p></p>");
//var_dump($arrdata[0]['MEDICION']);
//echo(json_encode($arrdata));

function medicion($array){
	include('conexion/db.php'); 
	$arrayreturn = array();
    $arrayreturn = $array;
	for($i=0; $i<count($array); $i++){	
		$query_RsConsulta="SELECT CRMECONS CODIGO,
								  CRMEDESC NOMBRE,
								  CRMETICR CRITERIO,
								  CRMEPORC PORCENTAJE
						FROM criterio_medicion 
						WHERE  CRMETICR = '".$array[$i]['CODIGO']."'";
							 //echo $query_RsConsulta;
		$RsConsulta = mysqli_query($conexion,$query_RsConsulta) or die(mysqli_error($conexion));
		$row_RsConsulta = mysqli_fetch_array($RsConsulta);
		$arraycriteriomed = array();
		do{
			$arraycriteriomed[] = $row_RsConsulta;
		}while($row_RsConsulta = mysqli_fetch_array($RsConsulta));
		//$arrayreturn[$i]['CRITERIO']['MEDICION'] = $arraycriteriomed;
		$arrayreturn[$i]['MEDICION'] = $arraycriteriomed;
		$arraycriteriomed = array();
	}
	return $arrayreturn;
}

function tipo_criterio(){
	include('conexion/db.php'); 
	$arrayreturn = array();
    $query_RsConsulta="SELECT TICRID CODIGO,
						      TICRNOMB NOMBRE 
					FROM TIPO_CRITERIO 
					WHERE 1 ";
						 //echo $query_RsConsulta;
	$RsConsulta = mysqli_query($conexion,$query_RsConsulta) or die(mysqli_error($conexion));
	$row_RsConsulta = mysqli_fetch_array($RsConsulta);
    //$totalRows_RsConsulta = mysqli_num_rows($RsConsulta);
	do{
		$arrayreturn[] =  $row_RsConsulta;
	}while($row_RsConsulta = mysqli_fetch_array($RsConsulta));
	return $arrayreturn;
}

function criterio($array){
	include('conexion/db.php'); 
	$arrayreturn = array();
    $arrayreturn = $array;
	for($i=0; $i<count($array); $i++){
		$query_RsConsulta="SELECT CRITCONS CONSECUTIVO,
							  CRITDESC NOMBRE,
							  CRITTICR TIPO_CRITERIO,
							  CRITCLID CLAS_ID 
						FROM  criterio C 
						WHERE CRITTICR='".$array[$i]['CODIGO']."' ";
							 //echo $query_RsConsulta;
		$RsConsulta = mysqli_query($conexion,$query_RsConsulta) or die(mysqli_error($conexion));
		$row_RsConsulta = mysqli_fetch_array($RsConsulta);
		$totalRows_RsConsulta = mysqli_num_rows($RsConsulta);
			if($totalRows_RsConsulta>0){
				$arraycriterio = array();
				do{
					$arraycriterio[] = $row_RsConsulta;
					
				}while($row_RsConsulta = mysqli_fetch_array($RsConsulta));
			}
			$arrayreturn[$i]['CRITERIO'] = $arraycriterio;
			$arraycriterio = array();
	}
	return $arrayreturn;

}

function pintar_criterio(){
include('conexion/db.php'); 
$parametro='1';
$query_RsConsulta="SELECT CRITCONS CONSECUTIVO,
						  CRITDESC NOMBRE,
						  CRITTICR TIPO_CRITERIO,
						  CRITCLID CLAS_ID 
					FROM  criterio C 
					WHERE CRITCONS='".$parametro."' ";
						 //echo $query_RsConsulta;
	$RsConsulta = mysqli_query($conexion,$query_RsConsulta) or die(mysqli_error($conexion));
	$row_RsConsulta = mysqli_fetch_array($RsConsulta);
    $totalRows_RsConsulta = mysqli_num_rows($RsConsulta);
	
	if($totalRows_RsConsulta>0){
	 do{
	  $arrayConsulta[]=$row_RsConsulta;
	  }while($row_RsConsulta = mysqli_fetch_array($RsConsulta));
	}
	

	$resutado=$arrayConsulta;
  return ($resutado);

}


function pintar_clas_criterio(){
include('conexion/db.php'); 
$parametro='1';
$query_RsConsulta="SELECT CRCLCONS CONS,
						  CRCLNOMB NOMBRE 
					FROM criterio_clasificacion 
					WHERE 1 ";
						 //echo $query_RsConsulta;
	$RsConsulta = mysqli_query($conexion,$query_RsConsulta) or die(mysqli_error($conexion));
	$row_RsConsulta = mysqli_fetch_array($RsConsulta);
    $totalRows_RsConsulta = mysqli_num_rows($RsConsulta);
	
	if($totalRows_RsConsulta>0){
	 do{
	  $arrayConsulta[]=$row_RsConsulta;
	  }while($row_RsConsulta = mysqli_fetch_array($RsConsulta));
	}
	

	$resutado=$arrayConsulta;
  return ($resutado);

}

?>
<?php 
 
 $arraycrit=pintar_criterio();

 $array_clas_crit=pintar_clas_criterio();
 
?>


	