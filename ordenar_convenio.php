<?php 
 require_once('conexion/db.php'); 
 
 $provasigdet=''; if(isset($_POST['provasigdet']) && $_POST['provasigdet'] !=''){  $provasigdet = $_POST['provasigdet'];
}

echo($provasigdet);
$total_acumulado = 0;
$datos = array();
/*
   $query_RsDetalleCompra="SELECT `PROVCODI`,
				        `PROVNOMB` NOMBRE_PROVEEDOR,
						 DERECANT  CANTIDAD,
						 (DERECANT*CODEVALO)  VALOR_TOTAL,
						 DEREDESC  DESCRIPCION_DETALLE,
						 CODEDESC  DESCRIPCION_PROVEEDOR,
						 CODEVALO  PRECIO_UNITARIO,
	                     PROVCODI  PROVEEDOR,						 
						 REQUCORE REQURIMIENTO						 
				FROM   `proveedores`,
                        detalle_requ,
						requerimientos,
						cotizacion_detalle,
						cotizacion,
						cotizacion_orden
						
				WHERE `DEREPROV`='".$provasigdet."'
                AND    COTIPROV=DEREPROV				
				AND    DEREPROV=PROVCODI
				AND    REQUCODI=DEREREQU
				AND    CODECOTI=COTICODI
				AND    COTIORDE=COORCODI
				AND    CODEDETA=DERECONS
				";
				//echo ($query_RsDetalleCompra);
	$RsDetalleCompra = mysqli_query($conexion,$query_RsDetalleCompra) or die(mysqli_error($conexion));
	$row_RsDetalleCompra = mysqli_fetch_array($RsDetalleCompra);
    $totalRows_RsDetalleCompra = mysqli_num_rows($RsDetalleCompra);
	
	
    $proveedor 	  = $row_RsDetalleCompra['NOMBRE_PROVEEDOR'];
	*/
	$query_RsTipoOrdenCompra = "SELECT T.TOCOCODI CODIGO, TOCONOMB NOMBRE FROM tipoorden_compra T";
	$RsTipoOrdenCompra = mysqli_query($conexion,$query_RsTipoOrdenCompra) or die(mysqli_error($conexion));
	$row_RsTipoOrdenCompra = mysqli_fetch_array($RsTipoOrdenCompra);
    $totalRows_RsTipoOrdenCompra = mysqli_num_rows($RsTipoOrdenCompra);	
	$Atipoorden_compra = array();
	if($totalRows_RsTipoOrdenCompra>0){
		do{
			$Atipoorden_compra[] = $row_RsTipoOrdenCompra;
		}while($row_RsTipoOrdenCompra = mysqli_fetch_array($RsTipoOrdenCompra));
	}
	//var_dump($Atipoorden_compra);
?>
 
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<style type="text/css">
 .iluminated{background:#FFA749;}
</style>
<form name="form1" id="form1" method="post" action="">
<div id="divfiltros" style="display:block; border:solid 1px #ccc; width:400;">

 <select name="provasigdet" id="provasigdet" class="chzn-select" >				
					<option value="">- Proveedores -</option>
					<?php
					require_once("scripts/funcionescombo.php");		
					$estados = dameProveedorConvenioCompra();
						foreach($estados as $indice => $registro){
						?>
							<option value="<?php echo($registro['PROVCODI'])?>"><?php echo($registro['PROVNOMB']);?></option>
						<?php
						}
					
					?>
				</select>
				
		<input type="button" name="butonfiltro" id="butonfiltro" class="button2" value="Buscar" onclick="F_Busqueda();">	
        <input type="hidden" name="provasigdet_marcado"	id="provasigdet_marcado" value="">	

</div>
<table>
 <tr>
  <td colspan="6">
   <input  type="hidden" class="button2" name="consultar" id="consultar" value="consultar" onclick="mostrarfiltros();">
  </td>
 </tr>
</table>

 <table border="" width="100%" id="table_detalles">
 <thead>
		<tr class="SLAB trtitle">
		  <th colspan="8">PROVEEDOR:</th>
		 </tr>
		<tr class="SLAB trtitle">
		   <th colspan="" width="7%">TIPO ORDEN</th>
		   <th colspan="" width="7%">REQUERIMIENTO</th>
		   <th colspan="" width="">CANT/DET</th>
		   <th colspan="" width="23%">DESCRIPCION DETALLE</th>
		   <th colspan="" width="23%">DESCRIPCION PROVEEDOR</th>
		   <!--<th colspan="" width="10%">PRECIO UNITARIO</th>-->
		   <th colspan="" width="5%">CANTIDAD</th>
		   <th colspan="" width="5%">VALOR UNITARIO</th>
		   <th colspan="" width="5%">UNIDAD MEDIDA</th>
		   <!--<th colspan="" width="5%">CANTIDAD CONVENIO</th>-->
		   <!--<th colspan="" width="12%">VALOR TOTAL</th>-->
		   <!--<th width="10%"></th>-->
		 </tr>
	 </thead>
 </table>
 <table border="0" width="90%" id="tabla_detallesadicionales" style="display:none;">
 <tr>
	<!--<td class="SB" colspan="6" width="100%" align="right">
	 suma servicios = <span id="sumservicios"></span> &nbsp;
	 suma producto = <span id="sumproducto"></span> &nbsp;
	 suma trabajo = <span id="sumtrabajo"></span> &nbsp;
	</td>-->
 </tr>
 <!--<tr>
 <td colspan="5" class="SB">TOTAL</td>
 <td class="SB">
<?php
//$sumatoria_total = (array_sum($datos));
//echo('$'.number_format($sumatoria_total,1,".",","));
//echo('$'.number_format(array_sum($datos),1,".",","));
//var_dump($datos);
//echo($total_acumulado);
?>
 <span id="totalc" style="font-weight:700;"></span>
 </td>
 </tr>-->
 <tr>
 <td class="SB"> Fecha de entrega:
  <input type="text" name="fecha_entrega" id="fecha_entrega" readonly></td>
  <td colspan="4" class="SB">
  Observaciones
 <textarea rows="4" cols="50" name="observacion" id="observacion">

</textarea> </td>
<td class="SB"> Iva:
  <input type="text" name="iva_desc" id="iva_desc"></td>
 </tr>
</table>
<table>
<div id="showtipoorden_compra" style="display:none;">
<p><span class="SB"><b>Tipo de orden Compra</b></span>
<select name="tipoorden_compra" id="tipoorden_compra" onchange="iluminatedtr(this.value);">
	<option value="">Seleccione...</option>
	<?php
	/*
	for($i=0; $i<count($Atipoorden_compra); $i++){
		?>
	<option value="<?php echo($Atipoorden_compra[$i]['CODIGO']);?>"><?php echo($Atipoorden_compra[$i]['NOMBRE']);?></option>
		<?php
	}
	*/
	?>
</select>
<span class="SB"><br><b>Convenio: <span id="convenio_id"></span></b></span>


</p>
</div>
<input  type="button" class="button2" style="display:none;" name="consultar" id="generarordenc" value="quieres ordenar esta compra?" onclick="fordenarcomp('Guardar');">
</table>

</form>

<script type="text/javascript">
$("#fecha_entrega").datepicker({
   showOn: 'both',
   buttonImage: 'images/calendar.png',
   buttonImageOnly: true,
   changeYear: true,
   changeMonth:true,
   showWeek: true,
   dateFormat: 'dd/mm/yy',
   //minDate: 0,

   //regional:'es',
   //numberOfMonths: 2,
   onSelect: function(fech, objDatepicker){
	   
   }
});
var Ctipoorden_compra = <?php echo(json_encode($Atipoorden_compra));?>;


var ordendet;

index          = 0;
cotizacionR     = '';
proveedorR     = '';
precio_unrealR = '';
$("#table_detalles").on("click", ".volverasignar", function(){
 detalle = $(this).attr("data-coddet");	
	 if(ordendet.datos.length>0){
	   for(i=0; i<ordendet.datos.length; i++){
		   if(detalle == ordendet.datos[i].COD_DETALLE){
			   //$("#trd_"+detalle).remove();
			   ordendet.datos[i].DEVOLVER = 1;
			   cotizacionR = ordendet.datos[i].COTIZACION;
			   proveedorR  = ordendet.datos[i].PROVEEDOR;
			   precio_unrealR = ordendet.datos[i].PRECIO_UNREAL;
			   index = 1;
		   }
	   }	 
	 }
 if(confirm("seguro que desea marcar este detalle para volver a asignar proveedor")){
	$.ajax({
			type: "POST",
			url: "tipo_guardar.php?tipoGuardar=DevolverDetalleOrdenCompra&codigo_detalle="+detalle+"&proveedor="+proveedorR+"&cotizacion="+cotizacionR+"&precio_unreal="+precio_unrealR,
			success : function(r){
				console.log(r);
				if(r==1){
				 $("#trd_"+detalle).remove();
				}

			},
			error   : callback_error
		});	 
 }
});	

  function mostrarfiltros(){
   $( "#divfiltros" ).toggle();
  }
  
  function limpiarfiltros (campo){
  document.getElementById(''+campo).value=""; 
  }
  function F_Busqueda(){
	  
	  cleandetallesadicionales();
   //document.form1.action="home.php?page=ordenar_compra";
   if($("#provasigdet").val()==''){
	   $('#table_detalles').html('');
	   $('#provasigdet_marcado').val('');
	   $("#tabla_detallesadicionales").css("display","none");
	   $("#generarordenc").css("display","none");
	   $("#showtipoorden_compra").css("display","none");
	   alert("debe ingresar un proveedor en la busqueda");
	   return;
   }
  if($("#provasigdet_marcado").val() != '' ){
	 if($("#provasigdet").val() == $("#provasigdet_marcado").val()){
		 return;
	 } 
	 	  $("#generarordenc").css("display","none");
	 	  $("#showtipoorden_compra").css("display","none");
  }
  
  //alert($("#provasigdet").val());
   	    $.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=ConsultarDataOrdenConvenio&provasigdet="+$("#provasigdet").val(),
	            dataType: 'json',
				success : function(r){
				      ShowData(r);
					  ordendet = r;
					  console.log(r);
					 //alert('orden de cotizacion creada codigo '+r);
					 //cleaner();
					 
				},
				error   : callback_error
	        });		
  }
  
function callback_error(XMLHttpRequest, textStatus, errorThrown)
{
    alert("Respuesta del servidor "+XMLHttpRequest.responseText);
    alert("Error "+textStatus);
    alert(errorThrown);
} 

function ShowData(array){
	tr = "";
	/*
	//console.log(array.datos.length);
	for(i=0; i<array.datos.length; i++){ alert();
		tr = "<tr><td>"+array.datos[i].REQUERIMIENTO+"</td></tr>";
		$("#table_detalles").prepend(tr);
		alert(tr);
	}
*/
proveedor_nombre = '';
try{
proveedor_nombre = 	array.datos[0].NOMBRE_PROVEEDOR;
}catch(e){
}
$("#table_detalles").html('');
head= '<thead>'+
		'<tr class="SLAB trtitle">'+
		  '<th colspan="8" >PROVEEDOR: '+proveedor_nombre+'</th>'+
		  '</tr>'+
		'<tr class="SLAB trtitle ">'+
		   '<th colspan="" width="7%">TIPO ORDEN</th>'+
		   '<th colspan="" width="7%">REQUERIMIENTO</th>'+
		   '<th colspan="" width="">CANT/DET</th>'+
		   '<th colspan="" width="23%">DESCRIPCION DETALLE</th>'+
		   '<th colspan="" width="23%">DESCRIPCION PROVEEDOR</th>'+
		   //'<th colspan="" width="10%">PRECIO UNITARIO</th> '+
		   '<th colspan="" width="5%">CANTIDAD</th>'+
		   '<th colspan="" width="5%">VALOR UNITARIO</th>'+
		   '<th colspan="" width="5%">UNIDAD MEDIDA</th>'+
		   //'<th colspan="" width="12%">VALOR TOTAL</th>'+
		   //'<th colspan="" width="10%"></th>'+
		 '</tr>'+
	 '</thead>';
$("#table_detalles").append(head);	
	_.each( array.datos , function(element,index){
		cantidad_c = ' - ';
		if(element.CANTIDAD_C>0){
			cantidad_c = element.CANTIDAD_C;
		}
		tr = '<tr class="SB tipoorden_'+element.TIPO_ORD+'" id="trd_'+element.COD_DETALLE+'">'+
				'<td>'+element.TIPO_ORD_DES+'</td>'+
				'<td>'+element.REQUERIMIENTO+'</td>'+
				'<td>'+element.CANTIDAD+'</td>'+
				'<td>'+element.DESCR_DET+'</td>'+
				'<td>'+element.DESCR_PROV+'</td>'+
				//'<td>'+element.PRECIO_UN+'</td>'+
				'<td>'+cantidad_c+'</td>'+
				'<td>'+element.PRECIO_UN+'</td>'+
				'<td>'+element.UM_DES+'</td>'+
				//'<td><div id="accionord_'+element.COD_DETALLE+'"><input type="button" class="buttonazul volverasignar" value="Reasignar" data-coddet="'+element.COD_DETALLE+'"></div></td>'+
			'</tr>';
			$("#table_detalles").append(tr);
	});
	
	$("#provasigdet_marcado").val(array.datos[0].CODIGO_PROV);
	$("#totalc").text(array.sum);
	$("#sumservicios").text(array.sumser);
	$("#sumtrabajo").text(array.sumtra);
	$("#sumproducto").text(array.sumpr);
    $("#convenio_id").text(array.convenio2);
	$("#generarordenc").css("display","block");
	$("#showtipoorden_compra").css("display","block");
	$("#tabla_detallesadicionales").css("display","block");
	poblarSelect('tipoorden_compra',array);
	
} 

function poblarSelect(campo, data){

	options = "<option value=''>Seleccione...</option>";
	$("#"+campo).html(options);
	//_.uniq([data.datos]);
	tipoordenes = _.map(data.datos, function(element){ return {"CODIGO": element.TIPO_ORD ,"NOMBRE" : element.TIPO_ORD_DES } });
	//console.log(tipoordenes);
	//_.intersection([1, 2, 3], [101, 2, 1, 10], [2, 1]);
	//tipoordenes = _.uniq([tipoordenes]);
	_.each( tipoordenes , function(element,index){
	    options = "<option value='"+element.CODIGO+"'>"+element.NOMBRE+"</option>";
		 existeoption = $("#tipoorden_compra option[value='"+element.CODIGO+"']");
		 if(existeoption.length == 0){
	       $("#"+campo).append(options);
		 }
		
	});
	
	//console.log(Ctipoorden_compra);
	
}
  
  function fordenarcomp(TG,P){
if($('#fecha_entrega').val() == ''){
	
	alert('Le falto ingresar la fecha');
	return;
}
if($('#tipoorden_compra').val() == ''){
	
	alert('debe ingresar el tipo de orden Compra');
	return;
}
tipordn = $('#tipoorden_compra').val();
//convenio = $('#tipoorden_compra').val();
  /*convenio = parseInt($('#convenio_id').text());
  alert(convenio);
  */
	if(confirm('Esta seguro de ordenar esta compra?')){	
	console.log(ordendet);
		    $.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=OrdenarcompraConvenio&provasigdet="+$("#convenio_id").val()+"&fecha_entrega="+$("#fecha_entrega").val()+"&observacion="+$("#observacion").val()+"&iva_desc="+$("#iva_desc").val()+"&tipo_orden="+tipordn,
	            dataType: 'json',
				success : function(r){
					console.log();
					if(r>0){
					 $("#provasigdet option[value='"+$("#provasigdet").val()+"']").remove();
					 $('#table_detalles').html('');
					 $("#generarordenc").css("display","none");
					 $("#tabla_detallesadicionales").css("display","none");
					 $("#showtipoorden_compra").css("display","none");
					 $("#provasigdet_marcado").val('');
					 $(".tipoorden_"+tipordn).remove();
					 cleandetallesadicionales();
						alert("se ha registrado correctamente esta orden de compra "+r);
					} 
				},
				error   : callback_error,
	    
	            data: { json: JSON.stringify(ordendet) }
	            //data: { json: ordendet }
	        });
	}			
			
  }
  
  function cleandetallesadicionales(){
	  $("#fecha_entrega").val('');
	  $("#observacion").val('');
	  $("#iva_desc").val('');
	  $("#totalc").text('');
	  $("#tipoorden_compra").val('');
	  $("#tipoorden_compra").html('');
  }
 
 function iluminatedtr(tipoord){
	 cleanIluminate();
	 if(tipoord!=''){
		 $(".tipoorden_"+tipoord).addClass("iluminated");
	 }
 }
 function cleanIluminate(){
	for(i=0; i<Ctipoorden_compra.length; i++){
		//console.log('qie '+Ctipoorden_compra[i].CODIGO);
		$(".tipoorden_"+Ctipoorden_compra[i].CODIGO).removeClass("iluminated");
	} 
 }
 </script>
<!--<script type="text/javascript"> $(".chzn-select").chosen(); $(".chzn-select-deselect").chosen({allow_single_deselect:true}); </script>-->