<?php 
	if(isset($_GET['c'			]) && $_GET['c'			 ]!=''){ $codigo_proveedor	=$_GET['c'			];} else {$codigo_proveedor	='';}
	if(isset($_GET['tipoGuardar']) && $_GET['tipoGuardar']!=''){ $tipoGuardar		=$_GET['tipoGuardar'];} else {$tipoGuardar		='';}
	if(isset($_GET['convenio']) && $_GET['convenio']!=''){ $convenio = $_GET['convenio']; }else{ $convenio = '';}
	$contrato = '';
	$codigo_asignado_convenio = '';
	$fecha_inicio = '';
	$fecha_fin = '';
	$noeditarconvenio = 0;
	if($codigo_proveedor==''){
?><script>
  alert("sera remitido a proveedores lista");
  location.href = "home.php?page=proveedores_lista";
</script><?php	
exit();
	}
	$arraymedidas = array();
	$query_RsUnidadMedida = "SELECT U.UNMECONS CODIGO,
	                                U.UNMENOMB NOMBRE,
									U.UNMESIGL SIGLA
							from UNIDAD_MEDIDA U";
	$RsUnidadMedida = mysqli_query($conexion,$query_RsUnidadMedida) or die(mysqli_error($conexion));
	$row_RsUnidadMedida = mysqli_fetch_assoc($RsUnidadMedida);
    $totalRows_RsUnidadMedida = mysqli_num_rows($RsUnidadMedida);	
	if($totalRows_RsUnidadMedida>0){
		do{
			$arraymedidas[] = $row_RsUnidadMedida;
		}while($row_RsUnidadMedida = mysqli_fetch_assoc($RsUnidadMedida));
	}
	//var_dump($arraymedidas);
	$query_RsConsulta="SELECT P.PROVCODI CODIGO,
									  P.PROVREGI CODIGO_REG,	
                                      P.PROVNOMB NOMBRE,
									  P.PROVTELE TELEFONO,
									  P.PROVPWEB WEB,
									  P.PROVDIRE DIRECCION,
									  P.PROVCON1 CONTACTO1,
									  P.PROVTEC1 TEL_CONTACTO1,
									  P.PROVCON2 CONTACTO2,
									  P.PROVTEC2 TEL_CONTACTO2,
									  P.PROVCORR CORREO,
									  P.PROVCONV CONVENIO
							    FROM PROVEEDORES P
							 WHERE P.PROVCODI='".$codigo_proveedor."' ";
							 
	$RsConsulta = mysqli_query($conexion,$query_RsConsulta) or die(mysqli_error($conexion));
	$row_RsConsulta = mysqli_fetch_array($RsConsulta);
    $totalRows_RsConsulta = mysqli_num_rows($RsConsulta);

	if($totalRows_RsConsulta > 0)
	{
		$proveedor         = $row_RsConsulta ['CODIGO'];
        $proveedor_reg	   = $row_RsConsulta ['CODIGO_REG'];	
		$proveedor_des     = $row_RsConsulta ['NOMBRE'];	
	}else{
		$proveedor         = '';	
		$proveedor_des     = '';	
?><script>
  alert("este proveedor no existe sera remitido al listado de proveedores");
  location.href = "home.php?page=proveedores_lista";
</script><?php	
exit();	
	}	
if($convenio==''){

		$query_RsVerificarSinConvenio="SELECT P.PROVCODI CODIGO,
                                      P.PROVNOMB NOMBRE,
									  P.PROVTELE TELEFONO,
									  P.PROVPWEB WEB,
									  P.PROVDIRE DIRECCION,
									  P.PROVCON1 CONTACTO1,
									  P.PROVTEC1 TEL_CONTACTO1,
									  P.PROVCON2 CONTACTO2,
									  P.PROVTEC2 TEL_CONTACTO2,
									  P.PROVCORR CORREO,
									  (SELECT C.CONVCONS 
									     FROM convenios C
									   where C.CONVIDPR = P.PROVCODI
									    AND  '".date('Y-m-d')."' >= C.CONVFEIN 
									    AND  '".date('Y-m-d')."' <= C.CONVFEFI
									   LIMIT 1
									  ) CONVENIO,
									  P.PROVCONV TIENE_CONVENIO
									  
							    FROM PROVEEDORES P
							   where P.PROVCODI = '".$codigo_proveedor."'
							  ";
							  //echo($query_RsVerificarSinConvenio);
	$RsVerificarSinConvenio = mysqli_query($conexion,$query_RsVerificarSinConvenio) or die(mysqli_error($conexion));
	$row_RsVerificarSinConvenio = mysqli_fetch_array($RsVerificarSinConvenio);
    $totalRows_RsVerificarSinConvenio = mysqli_num_rows($RsVerificarSinConvenio);
	if($totalRows_RsVerificarSinConvenio>0){	
		if($row_RsVerificarSinConvenio['TIENE_CONVENIO']==1){
			if($row_RsVerificarSinConvenio['CONVENIO'] > 0){
	?><script>
	  alert("este proveedor tiene un convenio vigente verifique");
	  location.href = "home.php?page=proveedores_lista";
	</script><?php	
			}
		}else{
	?><script>
	  alert("debe primero marcar que el proveedor tenga convenio");
	  location.href = "home.php?page=proveedores_lista";
	</script><?php	

		}
	}
}

	
	$query_RsProductoConvenioProveedor = "SELECT CP.COPRID   CONSECUTIVO,
												 CP.COPRIDPC ID_PRODUCTO,
												 CP.COPRIDCO ID_CONVENIO,
												 P.PRODCONS PRODUCTO,
												 P.PRODCODI CODIGO_GENERAL,
												 P.PRODCOSI CODIGO_SIGO,
												 P.PRODDESC PRODUCTO_DES,
												 P.PRODIDCAT CATEGORIA,
												 CP.COPRPREC PRECIO,
												 P.PRODCANT CANTIDAD,
												 (SELECT UNMESIGL FROM unidad_medida UM WHERE UM.UNMECONS=P.PRODIDUM) UNIDAD_MEDIDA,
  											     P.PRODSTOK STOCK,
												 case P.PRODIDCAT 
												   when -1
												    then 'GENERICO'
												  else 'GENERICO'
												  end CATEGORIA_DES,
												 C.CONVCOCO CODIGO_ASIGNADO_CONVENIO,
												 C.CONVCONT CONTRATO,
												 date_format(C.CONVFEIN,'%d/%m/%Y') FECHA_INICIO,
												 date_format(C.CONVFEFI,'%d/%m/%Y') FECHA_FIN
												 
											FROM conve_produc CP,
											     convenios    C,
												 productos    P
										  where C.CONVCONS = CP.COPRIDCO
										   and  CP.COPRIDPC = P.PRODCONS
										   AND  C.CONVCONS  = '".$convenio."'
										   AND  C.CONVIDPR = '".$codigo_proveedor."'
										   ORDER BY P.PRODDESC 
										   ";
										  // echo($query_RsProductoConvenioProveedor);
	$RsProductoConvenioProveedor = mysqli_query($conexion,$query_RsProductoConvenioProveedor) or die(mysqli_error($conexion));
	$row_RsProductoConvenioProveedor = mysqli_fetch_array($RsProductoConvenioProveedor);
    $totalRows_RsProductoConvenioProveedor = mysqli_num_rows($RsProductoConvenioProveedor);	

	
	$query_RsDetallesProveedorConvenio = "SELECT C.CONVCOCO CODIGO_ASIGNADO_CONVENIO,
												 C.CONVCONT CONTRATO,
												 date_format(C.CONVFEIN,'%d/%m/%Y') FECHA_INICIO,
												 date_format(C.CONVFEFI,'%d/%m/%Y') FECHA_FIN
												 
											FROM 
											     convenios    C
										  where C.CONVCONS  = '".$convenio."'
										   AND  C.CONVIDPR = '".$codigo_proveedor."'";
										   //echo($query_RsDetallesProveedorConvenio);
	$RsDetallesProveedorConvenio = mysqli_query($conexion,$query_RsDetallesProveedorConvenio) or die(mysqli_error($conexion));
	$row_RsDetallesProveedorConvenio = mysqli_fetch_array($RsDetallesProveedorConvenio);
    $totalRows_RsDetallesProveedorConvenio = mysqli_num_rows($RsDetallesProveedorConvenio);		
	if($totalRows_RsDetallesProveedorConvenio>0){
		$contrato = $row_RsDetallesProveedorConvenio['CONTRATO'];
		$codigo_asignado_convenio = $row_RsDetallesProveedorConvenio['CODIGO_ASIGNADO_CONVENIO'];
		$fecha_inicio = $row_RsDetallesProveedorConvenio['FECHA_INICIO'];
		$fecha_fin = $row_RsDetallesProveedorConvenio['FECHA_FIN'];	
        $noeditarconvenio = 1;		
	}	
?>

<!DOCTYPE html>

<html>
<!-- inicio del html -->
	<head>
		<title>Convenio-Compras</title>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css">
			.panel-heading{
				padding: 1px 15px;
			}
			.form-control{
				height:24px;
			}
			.panel{
				margin-bottom:10px;
			}
			.panel-body  .SB{
				font-size:14px;
				font-weight:700;
			}
			.btn-info:hover
			{
				background: #47B3D1;
				border: solid 1px #5BC0DE;
				color: white;
				-webkit-transform: rotate(7deg) scale(1.2);
				transform: rotate(7deg) scale(1.2);
				
			}
			
.TB_overlayBGcot {
  height: 100%;
  left: 0;
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 1500;
  background-color: #FFFFFF;
  opacity: 0.55;
}  
.no_visible {
  display: none;
}
.prodconsultado{
	background: #AFF2DB;
}
.prodconsultado textarea{
	background: #AFF2DB;
}
		</style>
		<script type="text/javascript">
		
	function acceptNum(event)
		{
			var key; 
			if(window.event)
			{ 
				key = event.keyCode;
			}
			else if(event.which)
			{ 
				key = event.which;
			} 
			//return (key == 45 || key == 13 || key == 8 || key == 9 || key == 189 || (key >= 48 && key <= 58) )
			//var nav4 = window.Event ? true : false;
			//var key = nav4 ? evt.which : evt.keyCode;
			return (key <= 13 || (key >= 48 && key <= 57));
		}		
		
var arraymedidas = <?php echo(json_encode($arraymedidas)); ?>		
		$(document).ready(function(){
		
<?php 
if($noeditarconvenio==0){
?>			
$("#fecha_inicio, #fecha_fin").datepicker({
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
	   if(compruebafechas($('#fecha_inicio').val(),$('#fecha_fin').val())==0){
	      alert("la fecha inicio no puede ser mayor que la fecha fin");
		  $('#fecha_inicio').val("");
		  //$('#fecha_fin').val("");
		 
	   }
	   
   }
});			
<?php 
		}
?>

			$("#producto_des").autocomplete({
			source: "buscar.php?tipo=cargarproductosconvenio", 				
			minLength: 3,									
			select: function(event, ui){
					 $("#producto_des").val(ui.item.value.nombre);
					 $("#producto").val(ui.item.value.codigo);
					 $("#codigo_generaln").val(ui.item.value.codigo_general);
					 $("#codigo_sigon").val(ui.item.value.codigo_sigo);
					 $("#categorian").val(ui.item.value.categoria);
					 $("#categoria_desn").val(ui.item.value.categoria_des);
					 $("#precion").val(ui.item.value.precio);
					 $("#cantidadn").val(ui.item.value.cantidad);
					 $("#stockn").val(ui.item.value.stock);
					 $("#unidadmedidan").val(ui.item.value.um);
					 $("#unidadmedida_desn").val(ui.item.value.umdes);
					 //subpoa();
					 event.preventDefault();
					},
			focus: function(event, ui){
					 $("#producto_des").val(ui.item.value.nombre);
					 limpiarvarsproducto('1');
					 event.preventDefault();
					}
		});
		$("#productocotizacion_des").autocomplete({
			source: "buscar.php?tipo=cargarproductosconveniocotizacion&proveedor=<?php echo($proveedor);?>", 				
			minLength: 3,									
			select: function(event, ui){
					 $("#productocotizacion_des").val(ui.item.value.nombre);
					 $("#productocotizacion").val(ui.item.value.codigo);
					 $("#preciocotizacionn").val(ui.item.value.precio);
					 $("#cantidadcotizacionn").val(ui.item.value.cantidad);
					 
					 //subpoa();
					 event.preventDefault();
					},
			focus: function(event, ui){
					 $("#productocotizacion_des").val(ui.item.value.nombre);
					 LimpiarFormProd('1');
					 event.preventDefault();
					}
		});
});	
function compruebafechas(fecha_inicio,fecha_final){
    if(fecha_inicio != "" && fecha_final ) {
		fd = fecha_inicio.split("/");
		fh = fecha_final.split("/");
		fd1 = new Date(fd[2], (fd[1] - 1), fd[0]);
		fh1 = new Date(fh[2], (fh[1] - 1), fh[0]);
					if (fh1 < fd1) {
						return 0;
					}
					return 1;
    }
}

function GuardarProducto(){
				if($("#codigo_general").val()==''){
					alert('debe ingresar el codigo general');
					return;
				}
				if($("#codigo_sigo").val()==''){
					alert('debe ingresar el codigo sigo');
					return;
				}
				if($("#cantidad").val()==''){
					alert('debe ingresar la cantidad de producto');
					return;
				}
				if($("#precio").val()==''){
					alert('debe ingresar el precio');
					return;
				}				
				
				if($("#precio").val()==''){
					alert('debe ingresar el precio');
					return;
				}
				if($("#stock").val()==''){
					alert('debe ingresar el stock');
					return;
				}				
				if($("#categoria").val()==''){
					alert('debe ingresar la categoria');
					return;
				}
				if($("#unidad_medida").val()==''){
					alert('debe ingresar la unidad de medida');
					return;
				}
				if(confirm("seguro que desea crear este producto")){
					   	    $.ajax({
								type: "POST",
								url: "tipo_guardar.php?tipoGuardar=GuardarNuevoProducto&codigo_general="+$("#codigo_general").val()+"&codigo_sigo="+$("#codigo_sigo").val()+"&nombre_producto="+$("#nombre_producto").val()+"&categoria="+$("#categoria").val()+"&cantidad="+$("#cantidad").val()+"&precio="+$("#precio").val()+"&stock="+$("#stock").val()+"&unidad_medida="+$("#unidad_medida").val(),
								dataType: 'json',
								success : function(r){
									if(r>0){
										LimpiarFormProd();
										alert("producto creado correctamente");
									} 
								},
								error   : callback_error
							});
				}
			}
			
LimpiarFormProd = function(mod){
	if(mod!='1'){
		$("#productocotizacion").val('');
		$("#productocotizacion_des").val('');
	}
	$("#codigo_general").val('');
	$("#codigo_sigo").val('');
	$("#nombre_producto").val('');
	$("#categoria").val('');
	$("#precio").val('');
	$("#cantidad").val('');
	$("#stock").val('');
	$("#unidad_medida").val('');
}	

function limpiarvarsproductocot(mod){
	if(mod!='1'){
		$("#productocotizacion").val('');
		$("#productocotizacion_des").val('');
	}
	$("#codigocotizacion_generaln").val('');
	$("#codigocotizacion_sigon").val('');
	$("#nombrecotizacio_producton").val('');
	$("#categoriacotizacionnn").val('');
	$("#categoriacotizacion_desn").val('');
	$("#preciocotizacionn").val('');
	$("#cantidadcotizacionn").val('');
	$("#stockcotizacionn").val('');	
}
function limpiarvarsproducto(){
	$("#producto").val('');
	$("#producto_des").val('');
	$("#codigo_generaln").val('');
	$("#codigo_sigon").val('');
	$("#nombre_producton").val('');
	$("#categorian").val('');
	$("#categoria_desn").val('');
	$("#cantidadn").val('');
	$("#precion").val('');
	$("#stockn").val('');	
}		
			
function callback_error(XMLHttpRequest, textStatus, errorThrown)
{
    alert("Respuesta del servidor "+XMLHttpRequest.responseText);
    alert("Error "+textStatus);
    alert(errorThrown);
} 
function AgregarProdCotizacion(){
	$("#nombre_producto").val($("#productocotizacion_des").val()) ;
	$("#precio").val($("#preciocotizacionn").val()) ;
	$("#cantidad").val($("#cantidadcotizacionn").val()) ;
}
function AgregarProdConvenio(){
	retorna = 0;
	if($("#producto").val()==''){
		alert("debe ingresar el producto que desea agregar al convenio con este proveedor");
		return;
	}
	
	$("#tabla_producto .trconvprod").each(function(index){
	  data = $(this).attr('id').split('_');
	  id=data[1];
	   if(id == $("#producto").val()){
		   retorna=1;
	   }
	 })
	 if(retorna==1){
		 alert("el producto que desea vincular ya se encuentra dentro del convenio");
		 return;
	 }
	if(confirm("seguro que desea agregar este producto "+$("#producto_des").val()+" al convenio con este proveedor?")){
		 $.ajax({
			type: "POST",
			url: "tipo_guardar.php?tipoGuardar=NuevoProductoConvenio&producto="+$("#producto").val()+"&cantidad="+$("#cantidadn").val()+"&precio="+$("#precion").val()+"&convenio=<?php echo($convenio);?>",
			dataType: 'json',
			success : function(r){
				if(r>0){
					addFila($("#producto").val(),r);
					alert("producto agregado correctamente al convenio con el proveedor <?php echo($proveedor_des);?>");
				} 
			},
			error   : callback_error
		});
	}
}
function addFilaCot(prod, cat, stock, cant, price, um, umdes){
	tr = '<tr>'+
	        '<td>'+prod+'</td>'+
	        '<td>'+cat+'</td>'+
	        '<td>'+stock+'</td>'+
	        '<td>'+umdes+'</td>'+
			'<td>'+cant+'</td>'+
	        '<td>'+price+'</td>'+
	     '</tr>';
	$("#tabla_producto").prepend(tr);
	limpiarvarsproducto();
}
function addFila(prod,r){
	tr = '<tr class="trconvprod" id="trconvprod_'+r+'">'+
	        '<td><input type="button" class="buttonrojodark" onclick="javascript: EditarProducto('+r+')" value="editar">&nbsp;&nbsp;&nbsp;</td>'+
	        '<td>'+$("#producto_des").val()+'</td>'+
	        '<td>'+$("#categoria_desn").val()+'</td>'+
	        '<td>'+$("#stockn").val()+'</td>'+
	        '<td>'+$("#unidadmedida_desn").val()+'</td>'+
			'<td>'+$("#cantidadn").val()+'</td>'+
	        '<td>'+$("#precion").val()+'</td>'+
	     '</tr>';
	$("#tabla_producto").prepend(tr);
	limpiarvarsproducto();
}
function close_popup(){
document.getElementById('TB_overlaycot').className="";
document.getElementById('popup_cot').className="no_visible";
}


function renderprods2(r){
	//var items = r;
	var items = [
	{detalle: "455", detalle_des: "instacrem x 200 sobres ", iva: "0", medida: "12", medida_des: "PACAS", sigla: "Pca", valor: "19100", valor_iva: "0"},
	{detalle: "455", detalle_des: "instacrem x 200 sobres ", iva: "0", medida: "12", medida_des: "PACAS", sigla: "Pca", valor: "19100", valor_iva: "0"}
	];
	 console.log(items);
  var template = $("#cotiprod-template").html();
  $("#target").html(_.template(template,{items:items}));	
  //console.log(items.DETALLE_DES);
	 //var template = $("#cotiprod-template").html();
	 //a = r.toJSON();
	  //compiled_template = $("#tabledataprovcot").html(_.template(template,{r:r}));		
	   //this.el.html(compiled_template);   
		document.getElementById('TB_overlaycot').className="TB_overlayBGcot";	
		document.getElementById('popup_cot').className=""; 
}

function EditarProducto(id){
	var name  = $("#divprod_"+id).text();
	var cat   = $("#divcategoria_"+id).text();
	var stock = $("#divstock_"+id).text();
	var um    = $("#divum_"+id).text();
	var cant  = $("#divcantidad_"+id).text();
	var price = $("#divprecio_"+id).text();	
	var opciones = '<option value="">Seleccione...</option>';
try{	
	for(i=0; i<arraymedidas.length; i++){
	 if(arraymedidas[i].SIGLA == um){
		 opciones = opciones+'<option value="'+arraymedidas[i].CODIGO+'" selected>'+arraymedidas[i].SIGLA+'</option>';
	 }else{
		 opciones = opciones+'<option value="'+arraymedidas[i].CODIGO+'" >'+arraymedidas[i].SIGLA+'</option>';
	 }
	}
	
	$("#trconvprod_"+id).css("visibility","hidden");
	/*$("#divprod_"+id).css("display","none");
	$("#divcategoria_"+id).css("display","none");
	$("#divstock_"+id).css("display","none");
	$("#divum_"+id).css("display","none");
	$("#divcantidad_"+id).css("display","none");
	$("#divprecio_"+id).css("display","none");	
	*/
	tr = "<tr class='prodconsultado' id='trprodconsulta_"+id+"'>"
	       +"<td><input type='button' onclick='javascript:ActualizarNameProducto("+id+")' class='buttonrojo' value='Guardar'><input type='button' class='buttonrojo' onclick='javascript:CancelarNameProducto("+id+")' value='Cancelar'></td>"
	       +"<td><textarea id='textnameprod_"+id+"' style='width:100%' rows='3' required>"+name+"</textarea></td>"
	       +"<td>"+cat+"</td>"
	       +"<td><input type='text' id='textstock_"+id+"' value='"+stock+"' size='2' onKeyPress='return acceptNum(event);'></td>"
	       +"<td><select id='selectunme_"+id+"'>"+opciones+"</select></td>"
	       +"<td><input type='text' id='textcant_"+id+"' value='"+cant+"' size='2' onKeyPress='return acceptNum(event);'></td>"
	       +"<td><input type='text' id='textprice_"+id+"' value='"+price+"' size='10' onKeyPress='return acceptNum(event);'></td>"
		+"</tr>";
		console.log(tr);
$("#trconvprod_"+id).closest("tr").after(tr);		
			
 }catch(ex) {
  
  }		
}
function CancelarNameProducto(id){
	$("#trconvprod_"+id).css("visibility","visible");
	$("#trprodconsulta_"+id).remove();
}

function ActualizarNameProducto(id){
var nombre 	 = $("#textnameprod_"+id).val();	
var stock 	 = parseInt($("#textstock_"+id).val());	
var um 	     = $("#selectunme_"+id).val();	
var cant  	 = parseInt($("#textcant_"+id).val());	
var price_un = parseInt($("#textprice_"+id).val());	
if(nombre == ''){ inlineMsg('textnameprod_'+id,'debe digitar el nombre del producto.',3); return false; }
if(stock == ''){ inlineMsg('textstock_'+id,'debe digitar el stock.',3); return false; }
if(um == ''){ inlineMsg('selectunme_'+id,'debe ingresar la unidad de medida.',3); return false; }
if(cant == ''){ inlineMsg('textcant_'+id,'debe ingresar la cantidad.',3); return false; }
if(price_un == ''){ inlineMsg('textprice_'+id,'debe ingresar el precio unitario.',3); return false; }
 
umdes = ($('#selectunme_'+id+' option:selected').html());

	if(confirm("seguro que desea actualizar este registro?")){
		var newArrayD = { 'nombre': nombre, 'stock': stock, 'um':um , 'umdes': umdes, 'cant': cant, 'price_un':price_un};
		$.ajax({
		type: "POST",
		url: "tipo_guardar.php?tipoGuardar=ActualizarNameProducto&producto="+id,
		dataType: 'json',
		success : function(r){
			if(r==0 || r==1){
				$("#trconvprod_"+id).css("visibility","visible");
				$("#trprodconsulta_"+id).remove();	
                actualizarbase(id,newArrayD);				
				alert("registro guardado correctamente");
			}
		},
		error   : callback_error,
		data: { json: JSON.stringify(newArrayD) }
		});
	}
}
function actualizarbase(id,data){
	 $("#divprod_"+id).text(data.nombre);	
	 $("#divstock_"+id).text(data.stock);	
	 $("#divum_"+id).text(data.umdes);	
	 $("#divcantidad_"+id).text(data.cant);	
	 $("#divprecio_"+id).text(data.price_un);
}
</script>
	</head>
	<body>
	<div id="TB_overlaycot" class="" onclick="close_popup();"></div>
	   <div id="popup_cot" style="float:left; border: solid 3px #780002; width:70%; position:absolute; z-index:9999; background:#f2f2f2; margin: 0 13%; padding:10px; border-radius:8px;" class="no_visible">
		<?php 
		for($i=0; $i<15; $i++){
			//echo("jose manuel fuentes cardenas <br>");
		}
		?><?php /*
		<table width="98%" border="0">
		 <tr>
			<td width="35%">Detalle</td>
			<td width="15%">Medida</td>
			<td width="15%">valor</td>
			<td width="7%">iva</td>
			<td width="18%">valor iva</td>
		 </tr>
		</table>
		*/?>
		<table width="98%" border="1" cellpadding="9" id="tabledataprovcot">
		</table>
		
	</div>
		<div class="container">
			<form class="form-horizontal" role="form" id="formulario" name="formulario" method="post" action="" >
				<div class="panel panel-default">
								<div class="panel-heading">Convenio-Compras</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-lg-8"   ><label for="id_prov" class="">Nit			</label></div>								
								<div class="col-lg-2"	><label for="id_prov" class="">Contrato N°	</label></div>
								<div class="col-lg-2"	><label for="id_prov" class="">Fecha Inicio	</label></div>								
							</div> 
							<div class="row">
								<div class="col-lg-8"	>
														<input  type="hidden" name="nit" id="nit"       value="<?php echo($proveedor);?>"/>
														<?php echo($proveedor_reg);?> 		
								</div>								
								<div class="col-lg-2"	><input  class="form-control" type="text" name="contrato" id="contrato" placeholder="Contrato #" value="<?php echo($contrato);?>" />	</div>
								<div class="col-lg-2"	><input  class="" type="text" name="fecha_inicio" id="fecha_inicio" readonly size="10" value="<?php echo($fecha_inicio);?>" />	</div>
							</div>
							
							<div class="row">
								<div class="col-lg-8"	><label for="id_prov" class="">Proveedor	</label></div>
								<div class="col-lg-2"	><label for="id_prov" class="">Codigo Convenio	</label></div>								
								<div class="col-lg-2"	><label for="id_prov" class="">Fecha Fin	</label></div>
							</div> 
							<div class="row">
								<div class="col-lg-8"	>
														<input  type="hidden" name="proveedor" id="proveedor" value="<?php echo($proveedor_des);?>" />
														<?php echo($proveedor_des);?> 	
								</div>
								<div class="col-lg-2"	><input  class="form-control" type="text" name="c_conv_des" id="c_conv_des" placeholder="# Convenio" value="<?php echo($codigo_asignado_convenio);?>" />	</div>
								<div class="col-lg-2"	><input  class="" type="text" name="fecha_fin" id="fecha_fin" readonly size="10"/ value="<?php echo($fecha_fin);?>">	</div>
							</div>
							<?php 
							if($noeditarconvenio == 0){
							?>
							<div class="row">
								<div class="col-xs-2"> 
									<input type="submit" onclick="return fvalidar('guardar');" name="" value="Crear Aqui" class="btn btn-sm btn-info">
								</div>  
							</div>
							<?php 
							}
							?>
						</div>
				</div>
						  
		</div>			
			</form>
			<table>
				<tr>
					<td class="SB">Agregar producto desde una cotización</td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-info" id="limpiarvarsproducto" onclick="ShowCotizaciones()" >Ver Cotizaciones</button></td>
				</tr>	
					<tr>
					<td><br></td>
				</tr>
			</table>			
			<?php if($convenio!='' ){ ?>
			<div class="panel panel-default">
				<div class="panel-heading">Crear Producto</div>
				<div class="panel-body">
				<table style="display:block;">
				<tr>
					<td class="SB" width="440">Buscar producto desde cotizacion</td>
				    <td>
						<input type="text" id="productocotizacion_des" id="productocotizacion_des" value="" size="60">
						<input type="hidden" id="productocotizacion" id="productocotizacion" value="">
						<input type="hidden" id="codigocotizacion_generaln" id="codigocotizacion_generaln" value="">
						<input type="hidden" id="codigocotizacion_sigon" id="codigocotizacion_sigon" value="">
						<input type="hidden" id="categoriacotizacionn" id="categoriacotizacionn" value="">
						<input type="hidden" id="categoriacotizacion_desn" id="categoriacotizacion_desn" value="">
						<input type="hidden" id="preciocotizacionn" id="preciocotizacionn" value="">
						<input type="hidden" id="cantidadcotizacionn" id="cantidadcotizacionn" value="">
						<input type="hidden" id="stockcotizacionn" id="stockcotizacionn" value="">
						<button class="btn btn-sm btn-info" id="limpiarvarsproducto" onclick="limpiarvarsproductocot()" >Limpiar</button>&nbsp;
						<button class="btn btn-sm btn-info" id="limpiarvarsproducto" onclick="AgregarProdCotizacion()" >Agregar</button>
					</td>
				</tr>
				<tr>
					<td><br></td>
				</tr>
			</table>
			<table>
				<tr>
					<td class="SB">C&oacute;digo general</td>
					<td><input class="form-control" type="codigo_general" id="codigo_general" value=""></td>
					<td class="SB">C&oacute;digo exportado de sigo</td>
					<td><input class="form-control" type="codigo_sigo" id="codigo_sigo" value=""></td>
					<td class="SB">Nombre del producto</td>
					<td><input class="form-control" type="nombre_producto" id="nombre_producto" value=""></td>
				</tr>
				<tr>
					<td class="SB">Cantidad</td>
					<td><input class="form-control" type="cantidad" id="cantidad" value="" onKeyPress="return acceptNum(event);"></td>
					<td class="SB">Precio</td>
					<td><input class="form-control" type="precio" id="precio" value="" onKeyPress="return acceptNum(event);"></td>
					<td class="SB">Stock</td>
					<td><input class="form-control" type="stock" id="stock" value="" onKeyPress="return acceptNum(event);"></td>
				</tr>
				<tr>
					<td class="SB">Categoria</td>
					<td>
					 <select type="categoria" id="categoria" >
						<option value="-1">GENERICO</option>
					 </select>
					</td>
				</tr>
				<tr>
					<td class="SB">Unidad Medida</td>
					<td>
					  <select id="unidad_medida" name="unidad_medida">
					    <option value="">Seleccione...</option>
						<?php 
						for($i=0; $i<count($arraymedidas); $i++){
							?>
						<option value="<?php echo($arraymedidas[$i]['CODIGO']);?>"><?php echo($arraymedidas[$i]['NOMBRE']);?></option>
							<?php
						}
						?>
					  </select>
					</td>
				</tr>
				<tr>
					<td height="40" colspan="6" align="center"><input type="button" class="btn btn-sm btn-info" name="GuardarProd" id="GuardarProd" value="Guardar Producto" onclick="GuardarProducto();">
					</td>
				</tr>
			</table>
			</div>
					</div>
					<hr></hr>
		<?php //fin alina ?>
			<table>
				<tr>
					<td class="SB" width="440">buscar un producto existente</td>
				    <td>
						<input type="text" id="producto_des" id="producto_des" value="" size="60">
						<input type="hidden" id="producto" id="producto" value="">
						<input type="hidden" id="codigo_generaln" id="codigo_generaln" value="">
						<input type="hidden" id="codigo_sigon" id="codigo_sigon" value="">
						<input type="hidden" id="categorian" id="categorian" value="">
						<input type="hidden" id="categoria_desn" id="categoria_desn" value="">
						<input type="hidden" id="precion" id="precion" value="">					
						<input type="hidden" id="stockn" id="stockn" value="">
						<input type="hidden" id="unidadmedidan" id="unidadmedidan" value="">
						<input type="hidden" id="unidadmedida_desn" id="unidadmedida_desn" value="">
						<input type="hidden" id="cantidadn" id="cantidadn" value="">
						<button class="btn btn-sm btn-info" id="limpiarvarsproducto" onclick="limpiarvarsproducto()" >Limpiar</button>&nbsp;
						<button class="btn btn-sm btn-info" id="limpiarvarsproducto" onclick="AgregarProdConvenio()" >Agregar</button>
					</td>
				</tr>				
			</table>
			<hr></hr>
			 <table id="tabla_producto" WIDTH="100%" border="1">
			 <thead>
				<tr class="SLAB trtitle" align="center">
					<th></th>
					<th>Nombre</th>
					<th>Categoria</th>
					<th>Stock</th>
					<th>U/Med</th>
					<th>Cantidad</th>
					<th>Precio Un</th>
				</tr>
			 </thead>
			 
			 <?php 
			if($totalRows_RsProductoConvenioProveedor >0){
				$i=0;
				do{
					$i++;
					if($i%2==0){
						$estilo = 'SB2';
					}else{
						$estilo = 'SB';
					}
				?>
				<tr class="<?php echo($estilo);?> trconvprod" id="trconvprod_<?php echo($row_RsProductoConvenioProveedor['PRODUCTO']);?>">
					<td><input type="button" class="buttonrojodark" onclick="javascript: EditarProducto('<?php echo($row_RsProductoConvenioProveedor['PRODUCTO']);?>')" value="editar">&nbsp;&nbsp;&nbsp;<br></td>
					<td><div id="divprod_<?php echo($row_RsProductoConvenioProveedor['PRODUCTO']);?>"><?php echo($row_RsProductoConvenioProveedor['PRODUCTO_DES']);?></div></td>
					<td><div id="divcategoria_<?php echo($row_RsProductoConvenioProveedor['PRODUCTO']);?>"><?php echo($row_RsProductoConvenioProveedor['CATEGORIA_DES']);?></div></td>
					<td align="center"><div id="divstock_<?php echo($row_RsProductoConvenioProveedor['PRODUCTO']);?>"><?php echo($row_RsProductoConvenioProveedor['STOCK']);?></div></td>
					<td><div id="divum_<?php echo($row_RsProductoConvenioProveedor['PRODUCTO']);?>"><?php echo($row_RsProductoConvenioProveedor['UNIDAD_MEDIDA']);?></div></td>
					<td align="center"><div id="divcantidad_<?php echo($row_RsProductoConvenioProveedor['PRODUCTO']);?>"><?php echo($row_RsProductoConvenioProveedor['CANTIDAD']);?></div></td>
					<td><div id="divprecio_<?php echo($row_RsProductoConvenioProveedor['PRODUCTO']);?>"><?php echo($row_RsProductoConvenioProveedor['PRECIO']);?></div></td>
				</tr>
                <?php				
				}while($row_RsProductoConvenioProveedor = mysqli_fetch_array($RsProductoConvenioProveedor));
			}				 
			 ?>
				
			 </table>
	</div>
			<?php } ?>
	</body>
</html>



<script type="text/javascript">
function fvalidar(TG)
{
 
	 var proveedor				=document.formulario.nit.value;
	 var proveedor_des			=document.formulario.proveedor.value;
	 var contrato				=document.formulario.contrato.value;
	 var fecha_inicio 				=document.formulario.fecha_inicio.value;
	 var fecha_fin				=document.formulario.fecha_fin.value;
 
 // Mensajes 
 
  if(fecha_inicio == '')
  {
   inlineMsg('fecha_inicio','debe digitar la fecha inicio.',5);
		return false;
  }
  
  if(fecha_fin == '')
  {
   inlineMsg('fecha_fin','Olvidaste digitar  fecha fin .',5);
		return false;
  }
  
  
  if(TG=='guardar')
  {  
		if(confirm('Esta seguro que desea convenio con este proveedor?'))
		{
		document.formulario.action="convenio_guardar.php?tipoGuardar=Guardar";
		}else{
			return false;
		}
	}
	
}




</script>
   <script type="text/template" id="cotiprod-templated">
      <%
        // repeat items 
        _.each(items,function(item,key,list){
          
      %>
		 <tr>
			<td width="35%">asdf <%- item.detalle_es %></td>
			<td width="15%"><%- item.medida_des %></td>
			<td width="15%"><%- item.valor %></td>
			<td width="7%"><%- item.iva %></td>
			<td width="18%"><%- item.valor_iva %></td>
		 </tr>
      <%
        });
      %>
	  

    </script>
<script type="text/template" id='cotizacion-template'>
<table cellspacing='0' cellpadding='6' border='1'>
    <thead>
      <tr class="SLAB trtitle">
        <th width="35%">Codigo Cotizacion</th>
        <th width="65%">Fecha Cotizacion</th>
      </tr>
    </thead>
    <tbody>
      <%
        // repeat items 
        _.each(cotizacionesconv,function(item,key,list){
          // create variables
      %>
        <tr class="SB">
            <td width="35%"><a href="javascript: buscarDetalles('<%- item.CODIGO_COTIZACION %>');"><%- item.CODIGO_COTIZACION %></a></td>
            <td width="65%"><%- item.FECHA_COTIZACION %></td>
          </td>
        </tr>
		<tr>
			<td colspan="10">
				<table width="100%" id="tblcot_<%- item.CODIGO_COTIZACION %>">

				</table>
			</td>
		</tr>
      <%
        });
      %>
    </tbody>
  </table>
</script>
<script type="text/template" id='cotiprod-template'>
<table cellspacing='0' cellpadding='6' border='1' >
    <thead>
      <tr class="SLAB trtitle">
        <th width="35%" >Detalle</th>
        <th width="15%">Medida</th>
		<th width="15%">Cantidad</th>
		<th width="15%">Valor</th>
        <th width="7%">Iva</th>
        <th width="18%">Valor Iva</th>
      </tr>
    </thead>
    <tbody>
      <%
        // repeat items 
        _.each(itemsdetalles,function(item,key,list){
      %>
	  <%
	   if(key==0){
	  %>
		<tr>
			<td colspan="10" align="center">
			 <input type="button" class="buttonazul" name="savedetailconv" id="savedetailconv" value="Guardar Productos" onclick="SaveDetailsCot('<%- item.CODIGO_COTIZACION %>')">
			</td>
		</tr>
      <%
		}
	  %>
        <tr class="SB" id="trdetail_<%- item.DETALLE %>" >
            <td width="35%">&nbsp;&nbsp;<input data-um="<%- item.MEDIDA %>" data-umdes="<%- item.SIGLA %>" class="adddetail" type="checkbox" name="cotizaciondetail_<%- item.DETALLE %>" id="cotizaciondetail_<%- item.DETALLE %>" >&nbsp;&nbsp;<span id="tddetaildes_<%- item.DETALLE %>"><%- item.DETALLE_DES %></span></td>
            <td width="15%"><span id="tddsigla_<%- item.DETALLE %>"><%- item.SIGLA %></span></td>
			<td width="15%"><span id="tdcantidad_<%- item.DETALLE %>"><%- item.CANTIDAD %></span></td>
            <td width="15%"><span id="tdvalor_<%- item.DETALLE %>"><%- item.VALOR %></span></td>
            <td width="7%"><span id="tdiva_<%- item.DETALLE %>"><%- item.IVA %></span></td>
            <td width="18%"><span id="tddetaildes_<%- item.DETALLE %>"><%- item.VALOR_IVA %></span></td>
          </td>
        </tr>
      <%
        });
      %>
    </tbody>
  </table>
</script>

<!-- Create your target -->

<div id="target"></div>

<!-- Write some code to fetch the data and apply template -->

<script type="text/javascript">
var items = [];
 renderprods = function(r){
  /*items = [
    {DETALLE: "455", DETALLE_DES: "instacrem x 200 sobres ", IVA: "0", MEDIDA: "12", MEDIDA_DES: "PACAS", SIGLA: "Pca", VALOR: "19100", VALOR_IVA: "0" },
    {DETALLE: "455", DETALLE_DES: "instacrem x 200 sobres ", IVA: "0", MEDIDA: "12", MEDIDA_DES: "PACAS", SIGLA: "Pca", VALOR: "19100", VALOR_IVA: "0" }
  ];
  */
  items = r;
  var template = $("#cotiprod-template").html();
  //console.log(items);
  $("#tabledataprovcot").html(_.template(template,{items:items}));
}

var itemsdetalles = [];
 renderdetalles = function(r,cot){
  itemsdetalles = r;
  var template = $("#cotiprod-template").html();
  //console.log(items);
  $("#tblcot_"+cot).html(_.template(template,{itemsdetalles:itemsdetalles}));
}
var cotizacionesconv = [];
rendercotizaciones = function(r){
	cotizacionesconv = r;
  var template = $("#cotizacion-template").html();
  //console.log(items);
  $("#tabledataprovcot").html(_.template(template,{cotizacionesconv:cotizacionesconv}));
	
}
function ShowCotizaciones(){
$.ajax({
	type: "POST",
	url: "tipo_guardar.php?tipoGuardar=CargarCotizaciones&proveedor=<?php echo($codigo_proveedor);?>",
	dataType: 'json',
	success : function(r){
		rendercotizaciones(r);
				document.getElementById('TB_overlaycot').className="TB_overlayBGcot";	
		        document.getElementById('popup_cot').className="";
				$('html, body').animate({ scrollTop: $('#tabledataprovcot').offset().top }, 'slow');
	},
	error   : callback_error
});	

}

function ShowProductosCotizacion(){
$.ajax({
	type: "POST",
	url: "tipo_guardar.php?tipoGuardar=CargarProductosConvCotizacion&proveedor="+$("#stock").val(),
	dataType: 'json',
	success : function(r){
		renderprods(r);
				document.getElementById('TB_overlaycot').className="TB_overlayBGcot";	
		        document.getElementById('popup_cot').className="";
	},
	error   : callback_error
});	

}
function buscarDetalles(cot){
$.ajax({
	type: "POST",
	url: "tipo_guardar.php?tipoGuardar=CargarProductosConvCotizacion&proveedor=<?php echo($codigo_proveedor);?>&cotizacion="+cot,
	dataType: 'json',
	success : function(r){
		renderdetalles(r, cot);
				document.getElementById('TB_overlaycot').className="TB_overlayBGcot";	
		        document.getElementById('popup_cot').className="";
	},
	error   : callback_error
});		
}

function SaveDetailsCot(cot){
var newArrayD = new Array();	
                                   //each significa por cada es un bucle
	$("#tblcot_"+cot+" .adddetail").each(function(index){
		  if($(this).is(':checked')){
		  var ideta       = $(this).attr('id');
		  var valdeta     = ideta.split("_");
		  var detalle     = valdeta[1];
		  var detalle_des = $("#tddetaildes_"+detalle).text();
		  var cantidad    = $("#tdcantidad_"+detalle).text();
		  var precio      = $("#tdvalor_"+detalle).text();
		  var um          = $("#cotizaciondetail_"+detalle).attr("data-um");
		  var umdes       = $("#cotizaciondetail_"+detalle).attr("data-umdes");
		  var odeta = { 'det': detalle, 'des': detalle_des, 'cant':cantidad ,'price':precio, 'um':um, 'umdes':umdes};
			//alert(ideta);
			newArrayD.push(odeta);	 
		  }			
	});	
	//console.log(newArrayD);
	if(newArrayD.length>0){
		
		if(confirm("esta seguro de agregar estos productos al convenio")){
				$.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=GuardarProdConv&convenio=<?php echo($convenio);?>&cotizacion="+cot,
	            dataType: 'json',
				success : function(r){
					if(r>0){
					 //trdetail_
					 _.each(newArrayD,function(item,key,list){
					    //console.log(newArrayD[key].det);
						$("#trdetail_"+newArrayD[key].det).remove();
						addFilaCot(newArrayD[key].des, 'GENERICO', '0', newArrayD[key].cant ,newArrayD[key].price, newArrayD[key].um, newArrayD[key].umdes);
					 });						
					 alert("productos agregados exitosamente cantidad de productos agregados al convenio = "+r);
					}
				},
				error   : callback_error,
	    
	            data: { json: JSON.stringify(newArrayD) }
	        });
		}
	}else{
		alert("debe marcar productos para agregar al convenio");
	}
}
</script>