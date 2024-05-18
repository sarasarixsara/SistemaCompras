<?php

require_once('conexion/db.php');
	if (!isset($_SESSION)) {
  session_start();
}
if(!isset($_SESSION['MM_AccesoCorrectoApp']) || $_SESSION['MM_AccesoCorrectoApp'] != 'ACTIVO'){
  header("location: index.php");
}

$currentPage = $_SERVER["PHP_SELF"];

$estado_filtro='';
if(isset($_POST['estado_filtro']) && $_POST['estado_filtro'] !=''){
 $estado_filtro = $_POST['estado_filtro'];
}
$codigo_filtro='';
if(isset($_POST['codigo_filtro']) && $_POST['codigo_filtro'] !=''){
 $codigo_filtro = $_POST['codigo_filtro'];
}

$dir_administra ='39550544';

$tamanoPagina = 20;
$maxRows_RsListaRequerimientos = $tamanoPagina;
$pageNum_RsListaRequerimientos = 0;
if (isset($_GET['pageNum_RsListaRequerimientos'])) {
  $pageNum_RsListaRequerimientos = $_GET['pageNum_RsListaRequerimientos'];
}
$startRow_RsListaRequerimientos = $pageNum_RsListaRequerimientos * $maxRows_RsListaRequerimientos;

 
    
	$query_RsEstados="SELECT E.ESTACODI CODIGO,
	                         E.ESTANOMB NOMBRE
					   FROM ESTADOS E";
	$RsEstados = mysqli_query($conexion,$query_RsEstados) or die(mysqli_connect_error());
	$row_RsEstados = mysqli_fetch_array($RsEstados);
    $totalRows_RsEstados = mysqli_num_rows($RsEstados);

	$query_RsListaRequerimientos="SELECT PROVCODI CODIGO_UNI_PROVEEDOR,
										 PROVREGI NIT_CEDULA,
										 PROVNOMB PROVEEDOR_DESC,
										 CONVCONS CODIGO_CONVENIO,
										 CONVFEIN FECHA_INICIO_CONVENIO,
										 CONVFEFI FECHA_FIN_CONVENIO,
										 ORCOCONS CODIGO_ORDEN_CONVENIO,
										 ORCOFEEN ORDEN_FECHA_ENTREGA,
										 ORCOFECH FECHA_ORDEN,
										 ORCOFIRM FIRMA,
										 IFNULL(ORCOTOEN,'0') TODO_RECIBIDO, 
										 IFNULL(ORCOCORR,'0') ENVIADO_CORREO
								FROM PROVEEDORES, 									 
									 ORDEN_COMPRA_CONVENIO, 
									 convenios 
							WHERE PROVCODI=CONVIDPR 
							AND ORCOIDCO=CONVCONS 
					
									
									";

  
  									   
 $query_RsListaRequerimientos = $query_RsListaRequerimientos." order by ORCOCONS DESC";
  // echo($query_RsListaRequerimientos);
   $query_limit_RsListaRequerimientos = sprintf("%s LIMIT %d, %d", $query_RsListaRequerimientos, $startRow_RsListaRequerimientos, $maxRows_RsListaRequerimientos);
	$RsListaRequerimientos = mysqli_query($conexion,$query_limit_RsListaRequerimientos) or die(mysqli_connect_error());
	$row_RsListaRequerimientos = mysqli_fetch_array($RsListaRequerimientos);

$firmado = $row_RsListaRequerimientos['FIRMA'];
    
if (isset($_GET['totalRows_RsListaRequerimientos'])) {
  $totalRows_RsListaRequerimientos = $_GET['totalRows_RsListaRequerimientos'];
} else {
  $all_RsListaRequerimientos = mysqli_query($conexion, $query_RsListaRequerimientos);
  $totalRows_RsListaRequerimientos = mysqli_num_rows($all_RsListaRequerimientos);
}
	

//paginacion
$totalPages_RsListaRequerimientos = ceil($totalRows_RsListaRequerimientos/$maxRows_RsListaRequerimientos)-1;

$queryString_RsListaRequerimientos = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_RsListaRequerimientos") == false &&
        stristr($param, "totalRows_RsListaRequerimientos") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_RsListaRequerimientos = "&" . htmlentities(implode("&", $newParams));
  }
}

$queryString_RsListaRequerimientos = sprintf("&totalRows_RsListaRequerimientos=%d%s", $totalRows_RsListaRequerimientos, $queryString_RsListaRequerimientos);

$paginaHasta = 0;
if ($pageNum_RsListaRequerimientos == $totalPages_RsListaRequerimientos)
{
	$paginaHasta = $totalRows_RsListaRequerimientos;
}
else
{
	$paginaHasta = ($pageNum_RsListaRequerimientos+1)*$maxRows_RsListaRequerimientos;
}
	
?>
<style type="text/css">
.contenttable{
 width:100%;
 overflow:hidden;
 min-height:150px;
 border-radius:12px;
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
#tbl_orden {
	font-size:11px;
}
.btncerrarpop{
  float: right;
  margin-right: 5px;
  color: black;
  font-weight: 700;
  font-size: 26px;
  padding: 3px 7px;
  background-color: #ccc;
  border-radius: 12px;
  cursor:pointer;
}
</style>
 
 
 <script type="text/javascript">
  function mostrarfiltros(){
   $( "#divfiltros" ).toggle();
  }
  
  function limpiarfiltros (campo){
  document.getElementById(''+campo).value=""; 
  }
  function Busqueda(){
   document.form1.action="home.php?page=requerimientos_lista";
  }
  function close_popup(){
document.getElementById('TB_overlaycot').className="";
document.getElementById('popup_cot').className="no_visible";
$("#spancerrarpop").html('');
$("#spancerrarpop").removeClass('btncerrarpop');
}
 </script>
</head>

<body>
<div id="TB_overlaycot" class="" onclick="close_popup();">
<span id="spancerrarpop" ></span>
</div>
	   <div id="popup_cot" style="float:left; border: solid 3px #780002; width:70%; position:absolute; z-index:9999; background:#f2f2f2; margin: 0 13%; padding:10px; border-radius:8px;" class="no_visible">
	   
	   </div>
<div id="pagina">
 <form name="form1" id="form1" method="post" action="">
<?php /*<div id="wrapper">*/ ?>

 <div class="contenttable">
 <table width="100%">
 <tr>
<td colspan="8"> 	 <?php
			if ($totalRows_RsListaRequerimientos > 0)
		{
					?>
		Mostrando <b><?php echo($startRow_RsListaRequerimientos+1); ?></b> a <b><?php echo($paginaHasta); ?></b> de <b><?php echo($totalRows_RsListaRequerimientos);
		 ?></b> Registros
		<?php
					}
		else
		{
		?>
		Mostrando <b>0</b> a <b>0</b> de <b>0</b> Registros
		<?php
		}
		?>
</td>
</tr>

<?php
	
    if($totalRows_RsListaRequerimientos >0){
	 ?>
	 <tr class="SLAB trtitle" align="center">
		   <td width="20%"></td>
		   <td width="5%">Orden</td>		   
		   <td width="5%">Convenio</td>
		   <td width="25%">Proveedor</td>
		   <td width="15%">Fecha de Orden</td>		   
		   <td width="15%">Fecha de Entrega</td>
		   <td width="15%">Firma</td>
		   
	
		</tr>
	 <?php
	  $k=0;
	  do{
	    $k++;
	     if($k%2==0){
		  $estilo="SB";
		 }else{
		  $estilo="SB2";
		 }
	  ?>
	  <tr CLASS="<?php echo($estilo);?>">
	    <td width="20%">
		
		

		<a href="C.php?codprov=<?php echo($row_RsListaRequerimientos['CODIGO_UNI_PROVEEDOR']);?>&codcomp=<?php echo($row_RsListaRequerimientos['CODIGO_ORDEN_CONVENIO']);?>&%=0"  class=""  target="_blank" ><img  width="15"  height="20" src='imagenes/compare.png' border='0'></a>
		
		<?php if($_SESSION['MM_RolID']!= 3){
		
		if($row_RsListaRequerimientos['ENVIADO_CORREO'] == 0){ ?>
		<input type="button" id="btnenvcorr_<?php echo($row_RsListaRequerimientos['CODIGO_ORDEN_CONVENIO']);?>" class="buttonazul" value="enviar" onclick="generar_pdf('<?php echo($row_RsListaRequerimientos['CODIGO_UNI_PROVEEDOR']); ?>','<?php echo($row_RsListaRequerimientos['CODIGO_ORDEN_CONVENIO']);?>','0');"/> 
		<?php 
		  }else{
			  ?>
			  <input type="button" id="btnenvcorr_<?php echo($row_RsListaRequerimientos['CODIGO_ORDEN_CONVENIO']);?>" class="" value="enviar" onclick="generar_pdf('<?php echo($row_RsListaRequerimientos['CODIGO_UNI_PROVEEDOR']); ?>','<?php echo($row_RsListaRequerimientos['CODIGO_ORDEN_CONVENIO']);?>','0');"/> 
		  <?php
		  }
		?>
			  
			  
		<?php if($row_RsListaRequerimientos['TODO_RECIBIDO'] == 0){ ?>
		<input title="falta por recibir" type="button" id="btnreccorr_<?php echo($row_RsListaRequerimientos['CODIGO_ORDEN_CONVENIO']);?>"  
		class="buttonazul" value="recibir" onclick="f_RecibiraProveedor('10','<?php echo($row_RsListaRequerimientos['CODIGO_ORDEN_CONVENIO']);?>');"/>
		<?php 
		  }else{
			  ?>
		<input title="recibido" type="button" id="btnreccorr_<?php echo($row_RsListaRequerimientos['CODIGO_ORDEN_CONVENIO']);?>"  
		class="" value="recibir" onclick="f_RecibiraProveedor('<?php echo($row_RsListaRequerimientos['CODIGO_UNI_PROVEEDOR']); ?>','<?php echo($row_RsListaRequerimientos['CODIGO_ORDEN_CONVENIO']);?>');"/>	  
			  <?php
		}}
		?>
			
		
		</td>
	    <td width="5%"><?php echo($row_RsListaRequerimientos['CODIGO_ORDEN_CONVENIO']);?></td>
		<td width="5%"><?php echo($row_RsListaRequerimientos['CODIGO_CONVENIO']);?></td>
		<td width="25%"><?php echo($row_RsListaRequerimientos['PROVEEDOR_DESC']);?></td>
	    <td bgcolor="" width="15%"><?php echo($row_RsListaRequerimientos['FECHA_ORDEN']);?></td>	    	
		<td align="center" width="15%"><?php echo($row_RsListaRequerimientos['ORDEN_FECHA_ENTREGA']);?></td>
		<td width="15%"><span id="spanfirma_<?php echo($row_RsListaRequerimientos['CODIGO_ORDEN_CONVENIO']);?>"><?php if($row_RsListaRequerimientos['FIRMA'] != '0'){echo ($row_RsListaRequerimientos['FIRMA']);}?></span></td>
		
		
		
		
	  </tr>
	  <?php
	  
	   }while($row_RsListaRequerimientos = mysqli_fetch_array($RsListaRequerimientos));
	}else{
	?>
	<tr>
	  <td colspan="4">No existen registros</td>
	</tr>
	<?php
	}
?>
</table>
<table border="0" align="left" class="datagrid" >
		 <tr>
		  <td colspan="4">&nbsp;</td>
		 </tr>
		  <tr class="texto_gral">
			<td>
			 <ul>
			   <?php if ($pageNum_RsListaRequerimientos > 0) { // Show if not first page ?>
			   <li>
				  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaRequerimientos=%d%s", $currentPage, 0, $queryString_RsListaRequerimientos); ?>')" class="submenus">Primero</a>
               </li>
				  <?php } // Show if not first page ?>
			   <?php if ($pageNum_RsListaRequerimientos > 0) { // Show if not first page ?>
			    <li>
				  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaRequerimientos=%d%s", $currentPage, max(0, $pageNum_RsListaRequerimientos - 1), $queryString_RsListaRequerimientos); ?>')" class="submenus">Anterior</a>
				 </li>
				  <?php } // Show if not first page ?>
			<?php if ($pageNum_RsListaRequerimientos < $totalPages_RsListaRequerimientos) { // Show if not last page ?>
			     <li>
                  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaRequerimientos=%d%s", $currentPage, min($totalPages_RsListaRequerimientos, $pageNum_RsListaRequerimientos + 1), $queryString_RsListaRequerimientos); ?>')" class="submenus">Siguiente</a>
				 </li>
				  <?php } // Show if not last page ?>
			<?php if ($pageNum_RsListaRequerimientos < $totalPages_RsListaRequerimientos) { // Show if not last page ?>
			      <li>
                  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaRequerimientos=%d%s", $currentPage, $totalPages_RsListaRequerimientos, $queryString_RsListaRequerimientos); ?>')" class="submenus">&Uacute;ltimo</a>
				  </li>
				  <?php } // Show if not last page ?>
				</ul>
			</td>
		  </tr>
		</table>
</div>
</form>

<script type="text/template" id='ordenprod-template'>
<table cellspacing='0' cellpadding='6' border='1' id='tbl_orden' >
    <thead>
      <tr class="SLAB trtitle">
        <th width="35%" >Detalle</th>
		<th width="4%">Cantidad</th>
		<th width="4%">Medida</th>
		<th width="15%">Valor Unitario</th>
		<th width="15%">Iva Unitario</th>
		<th width="15%">Valor Total</th>
		<th width="10%">Cod Requ</th>
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
			 <input type="button" class="buttonazul" name="savedetailconv" id="savedetailconv" value="Guardar Productos" onclick="SaveDetailsCot('<%- item.ORDEN %>')">
			 <input type="hidden" name="sinrecibir" id="sinrecibir" value="">
			</td>
		</tr>
      <%
		}
	  %>
        <tr class="SB" id="trdetail_<%- item.DETALLE %>" >
            <td width="35%">&nbsp;&nbsp;<%
	   if(item.FECHA_REC_PROV == -1){
	  %><input class="adddetail" type="checkbox" name="cotizaciondetail_<%- item.DETALLE %>" id="cotizaciondetail_<%- item.DETALLE %>" ><%
		}
	  %>&nbsp;&nbsp;<span id="tddetaildes_<%- item.DETALLE %>"><%- item.DETALLE_DES %></span></td>
			<td width=""><span id="tdcantidad_<%- item.DETALLE %>"><%- item.CANTIDAD %></span><input type="hidden" name="tdcodorden_<%- item.DETALLE %>" id="tdcodorden_<%- item.DETALLE %>" value="<%- item.COD_DETORD %>"></td>
			<td width=""><span id="tdmedida_<%- item.DETALLE %>"><%- item.MEDIDA %></span></td>
			<td width=""><span id="tdvalorunitario_<%- item.DETALLE %>"><%- item.VALOR_UNITARIO %></span></td>
			<td width=""><span id="tdiva_<%- item.DETALLE %>"><%- item.IVA %>&nbsp;&nbsp;&nbsp; <%- item.PORC_IVA %>%</span></td>
			<td width=""><span id="tdtotal_<%- item.DETALLE %>"><%- item.VALOR_TOTAL %></span></td>
			<td width=""><span id="tdcodrequ_<%- item.DETALLE %>"><a target="_blank" href="home.php?page=solicitud&codreq=<%- item.CONS_REQU %>"><%- item.COD_REQU %></a></span></td>
          </td>
        </tr>
      <%
        });
      %>
    </tbody>
  </table>
</script>


<script type="text/javascript">
	function getDataServer(url, vars){
		 var xml = null;
		 try{
			 xml = new ActiveXObject("Microsoft.XMLHTTP");
		 }catch(expeption){
			 xml = new XMLHttpRequest();
		 }
		 xml.open("GET",url + vars, false);
		 xml.send(null);
		 if(xml.status == 404) alert("Url no valida");
		 return xml.responseText;
	}

function generar_pdf(prov,cod, f){
$.ajax({
	            type: "POST",
	            url: "C.php?codprov="+prov+"&codcomp="+cod+"&f="+f+"&%=1",
	            dataType: 'json',
				success : function(r){		
			
							var nombre_archivo="CONV-2015-"+cod;
							//alert('se esta firmado su documento');
						  modificar_archivo(nombre_archivo,cod,prov,f);
						//alert("se ha registrado correctamente esta orden de compra");
					 
				},
				error   : callback_error
	            //data: { json: ordendet }
	        });
}	

function modificar_archivo(a,c,p,f){
	
		$.ajax({
						type: "POST",
						url: "tipo_guardar.php?tipoGuardar=descargar_archivo_orden_convenio&nomb_arch="+a+"&prov="+p+"&f="+f+"&cod_orden="+c,
						dataType: 'json',
						success : function(r){
						 //console.log('aqui va el mensaje'+r.msj);	
					
						if(r.msj == "si")
						{  
					          f_Enviar_correo(a,c,p,f);
							  //$("#spanfirma_"+c).text(r.codigo);	
							  //$("#btnfirma_"+c).remove(); 
						}else if( r.msj == "no")
								{
									alert('El archivo no se a encontrado. Por favor informar a los administradores del aplicativo. GRACIAS');
								}
													
						
					  },	
						error   : callback_error,
				  
					
		});
}		
	
function callback_error(XMLHttpRequest, textStatus, errorThrown)
{
    alert("Respuesta del servidor "+XMLHttpRequest.responseText);
    alert("Error "+textStatus);
    alert(errorThrown);
} 
	
function Firma_director_admin(prov,cod,pers){
  if(confirm('Esta seguro de firmar esta orden de compra?')){	
		    $.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=firmarOrden&cod_prov="+prov+"&cod_orden="+cod+"&persona="+pers,
	            dataType: 'json',
				success : function(r){					
					if(r>0){
						//console.log(r);
						generar_pdf(prov,cod,r);
						
						// alert(r);
					
					} 
				},
				error   : callback_error
	            //data: { json: ordendet }
	        });
	}
}
//error   : callback_error

//paginación

 function f_abrir_link(v_link)
	{
	  document.form1.action=v_link;
	  document.form1.submit();

    }
	
function f_Enviar_correo(a,c,p,f)
{
	if(confirm('Esta seguro de enviar este correo?')){	
		    $.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=Enviar_correo_Orden_conv&prov="+p+"&nomb_archivo="+a+"&cod_orden_conv="+c,
	            dataType: 'json',
				success : function(r){					
					if(r>0){
						
						 alert('Su correo se a Enviado correctamente');
					
					} else
						{
									alert('El correo no se a enviado. Por favor informar a los administradores del aplicativo. GRACIAS');
								}
				},
				//error   : callback_error
	            //data: { json: ordendet }
	        });
	}
	
}	

function f_RecibiraProveedor( prov, orden ){
	document.getElementById('TB_overlaycot').className="TB_overlayBGcot";	
	$('#spancerrarpop').text(" X ");	
	document.getElementById('spancerrarpop').className="btncerrarpop";	
	document.getElementById('popup_cot').className="";
	$('html, body').animate({ scrollTop: $('#popup_cot').offset().top }, 'slow');
			    $.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=cargar_orden_convenio&orden="+orden,
	            dataType: 'json',
				success : function(r){	
				console.log("antes");
                console.log(r);	
				console.log("despues");
					if(r.length > 0){
						renderdetalles(r, orden);
						//alert('Su correo se a Enviado correctamente');					
					} 
				},
				error   : callback_error
	            //data: { json: ordendet }
	        });
}
var itemsdetalles = [];
 renderdetalles = function(r,orden){
  itemsdetalles = r;
  var template = $("#ordenprod-template").html();
  //console.log(items);
  $("#popup_cot").html(_.template(template,{itemsdetalles:itemsdetalles}));
  $("#sinrecibir").val("0");
  if(r.length>0){
	  _.each(r,function(item,key,list){
		  if(r[key].FECHA_REC_PROV == -1){
			  valor = (parseInt($("#sinrecibir").val())+1);
			  $("#sinrecibir").val(valor);
		  }
		  /*console.log(r[key]);
		  console.log(key);*/
	  });
	  if($("#sinrecibir").val()==0){
		  $('#savedetailconv').remove();
	  }
	  tiene_clase = ($("#btnreccorr_"+orden).attr("class"));
	  if(tiene_clase == 'buttonazul' && $("#sinrecibir").val()==0){
			$.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=TodosRecibidoProv&orden="+orden,
	            dataType: 'json',
				success : function(r){
					//console.log(r);
					if(r>0){
					 $("#btnreccorr_"+orden).removeClass("buttonazul");
					}
					
				},
				error   : callback_error
	        });		  
	  }
  }  
}
function SaveDetailsCot(orden){
var newArrayD = new Array();	
                                   //each significa por cada es un bucle
	$("#popup_cot .adddetail").each(function(index){
		  if($(this).is(':checked')){
		  var ideta = $(this).attr('id');
		  var valdeta = ideta.split("_");
		  var detalle = valdeta[1];
		  var detalle_des = $("#tddetaildes_"+detalle).text();
		  var cantidad = $("#tdcantidad_"+detalle).text();
		  var precio      = $("#tdvalor_"+detalle).text();
		  var coddetorden = $("#tdcodorden_"+detalle).val();
		  	var odeta = { 'det': detalle, 'des': detalle_des, 'cant':cantidad , 'price':precio, 'detorden':coddetorden };
			//alert(ideta);
			newArrayD.push(odeta);	 
		  }			
	});	
	console.log(newArrayD);
	if(newArrayD.length>0){
		if(confirm("esta seguro de agregar estos productos al proveedor")){
			$.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=RecibirProductosConvenio&orden="+orden+"&sinrecibir="+$("#sinrecibir").val(),
	            dataType: 'json',
				success : function(r){
					//console.log(r);
					if(r.length>0){
						if(r[0].change == 1){
							$("#btnreccorr_"+orden).removeClass("buttonazul");
							$('#savedetailconv').remove();
						}
					 alert("se ha marcado como recibido "+r[0].afectado+" detalles");
					}
				},
				error   : callback_error,
	    
	            data: { json: JSON.stringify(newArrayD) }
	        });
		}
	}else{
		alert("debe marcar productos para agregar como recibidos");
	}
}


</script>

		