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
$codorden='';
if(isset($_GET['codorden']) && $_GET['codorden'] !=''){
 $codorden = $_GET['codorden'];
}

    function criterio($c){
 
    $resultado = false;
	include('conexion/db.php');
    $query_RsCriterio="SELECT CRMEDESC NOMBRE
							FROM criterio_medicion, 
								 criterio, 
								 tipo_criterio 
							WHERE   CRMETICR=CRITCONS 
							AND     TICRID=CRITTICR
							AND     CRMECONS='".$c."'
									  ";
						  //echo($query_RsCriterio);
	 
	
    $RsCriterio = mysqli_query($conexion,$query_RsCriterio) or die(mysqli_error($conexion));
	$row_RsCriterio = mysqli_fetch_array($RsCriterio);
	$totalRows_RCriterio = mysqli_num_rows($RsCriterio);
	
	
		$resultado=$row_RsCriterio['NOMBRE'];
	
	
	
	return $resultado;
	}
$arraytipocompra = array();	
	$query_RsTipoOrdenCompra = "SELECT T.TOCOCODI CODIGO,
									   T.TOCONOMB NOMBRE
							    FROM TIPOORDEN_COMPRA T";
    $RsTipoOrdenCompra = mysqli_query($conexion,$query_RsTipoOrdenCompra) or die(mysqli_error($conexion));
	$row_RsTipoOrdenCompra = mysqli_fetch_array($RsTipoOrdenCompra);
	$totalRows_RTipoOrdenCompra = mysqli_num_rows($RsTipoOrdenCompra);
	
	if($totalRows_RTipoOrdenCompra>0){
	  do{
	    $arraytipocompra[] = $row_RsTipoOrdenCompra;
	    }while($row_RsTipoOrdenCompra = mysqli_fetch_array($RsTipoOrdenCompra));
	}	
	
 $query_RsAsignarCodCotizacion="SELECT P.PARAVALOR+1 VALOR, PARADEFI DEFINICION FROM PARAMETROS P WHERE PARACODI = 5";
	$RsAsignarCodCotizacion = mysqli_query($conexion, $query_RsAsignarCodCotizacion) or die(mysqli_error($conexion));
	$row_RsAsignarCodCotizacion = mysqli_fetch_array($RsAsignarCodCotizacion);
    //$totalRows_RsAsignarCodCotizacion = mysql_num_rows($RsAsignarCodCotizacion);

	//$CODIGOCOTIZACION = $row_RsAsignarCodCotizacion['DEFINICION']."-".$row_RsAsignarCodCotizacion['VALOR'];
	$CODIGOCOTIZACION = $row_RsAsignarCodCotizacion['DEFINICION']."-";

$arrayreqprov=array();

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

    
	$query_RsListaRequerimientos="SELECT O.COORCODI CODIGO_ORDEN,
	                                     DATE_FORMAT(O.COORFECR, '%d/%m/%Y') FECHA_CREACION,
	                                     DATE_FORMAT(O.COORFEEN, '%d/%m/%Y') FECHA_ENVIO,
										 O.COORESTA ESTADO,
										 CASE O.COORESTA 
										  WHEN 0
										   THEN 'CREADO'
										  WHEN 1
										   THEN 'ASIGNANDO PROVEEDOR' 
										  ELSE ''
										 END ESTADO_DES
								   FROM COTIZACION_ORDEN   O
							    where COORCODI='".$codorden."'" ;
									   	  
if($codorden!=''){

  $query_RsListaRequerimientos = $query_RsListaRequerimientos." AND O.COORCODI = '".$codorden."'";	
     $query_RsListaDetalles="SELECT C.COTICODI CODIGO,
                                    C.COTIORDE ORDEN,
                                    C.COTIPROV PROVEEDOR,
									P.PROVNOMB PROVEEDOR_DES,
                                    C.COTIESTA ESTADO,
									D.DERECONS CODIGO_DETALLE,
									D.DEREDESC NOMBRE,
									D.DERECANT CANTIDAD,
									(SELECT UNMESIGL 
									 FROM `unidad_medida` 
									 WHERE UNMECONS=DEREUNME) U_MEDIDA,
									CD.CODEVALO VALOR_UNT,
									CD.CODEVAIV VALOR_POR,
									(((CD.CODEVALO*CD.CODEVAIV)/100)+CD.CODEVALO) VALOR_TOTAL,
									CD.CODEDESC ALIAS,
									 D.DERETIPO TIPOCOMPRA,
							      	R.REQUCORE REQUERIMIENTO,
									R.REQUCODI CODI_REQUERIMIENTO
						   FROM COTIZACION C,
						       COTIZACION_DETALLE CD,
							   detalle_requ    D,
							   PROVEEDORES     P,
							   REQUERIMIENTOS   R
						  WHERE C.COTIORDE = '".$codorden."'
						    AND C.COTICODI = CD.CODECOTI
							AND CD.CODEDETA = D.DERECONS
							AND C.COTIPROV = P.PROVCODI
							AND R.REQUCODI = D.DEREREQU
						  ORDER BY C.COTIPROV 
						  ";
						  //echo('<br>'.$query_RsListaDetalles.'<br>');
	$RsListaDetalles = mysqli_query($conexion,$query_RsListaDetalles) or die(mysqli_error($conexion));
	$row_RsListaDetalles = mysqli_fetch_array($RsListaDetalles);
    $totalRows_RsListaDetalles = mysqli_num_rows($RsListaDetalles); 
	
	if($totalRows_RsListaDetalles>0){
	  do{
	    $arrayreqprov[] = $row_RsListaDetalles;
	    }while($row_RsListaDetalles = mysqli_fetch_array($RsListaDetalles));
	}
	
    
	$query_RsPoblarTabs="SELECT  DISTINCT(C.COTIPROV) PROVEEDOR,
									P.PROVNOMB PROVEEDOR_DES,
									C.COTICODI COTIZACION,
									C.COTIESTA ESTADO_D,
									CASE C.COTIESTA 
										  WHEN 0
										   THEN 'SIN COTIZAR'
										  WHEN 1
										   THEN 'COTIZADO' 
										  ELSE ''
										 END ESTADO_DE,
									C.COTITOTA TOTAL,
									C.COTIFOPA FP,
									C.COTIGARA GR,
									C.COTITIEN TE,
									C.COTISIEN SE,
									C.COTIOBSE OB,
									C.COTIFORE FORMA_ENVIO,
									CASE C.COTIFORE
									 WHEN 1
									  THEN 'CORREO'
									 WHEN 0
									  THEN 'MANUALMENTE'
									 ELSE
										 ''
									 END FORMA_ENVIO_DES
						   FROM COTIZACION C,
						       COTIZACION_DETALLE CD,
							   PROVEEDORES     P,
							   TIPOORDEN_COMPRA T
						  WHERE C.COTIORDE = '".$codorden."'
						    AND C.COTICODI = CD.CODECOTI
							AND C.COTIPROV = P.PROVCODI
						  ORDER BY C.COTIPROV
						  ";
						  //echo($query_RsPoblarTabs);
	$RsPoblarTabs = mysqli_query($conexion,$query_RsPoblarTabs) or die(mysqli_error($conexion));
	$row_RsPoblarTabs = mysqli_fetch_array($RsPoblarTabs);
	$totalRows_RPoblarTabs = mysqli_num_rows($RsPoblarTabs); 
	$arraytabs=array();
	if($totalRows_RPoblarTabs>0){
	  do{
	     $arraytabs[] = $row_RsPoblarTabs;
	    }while($row_RsPoblarTabs = mysqli_fetch_array($RsPoblarTabs));
	}
}  
  if($_SESSION['MM_RolID']==2){	

	      
  }
 
	$RsListaRequerimientos = mysqli_query($conexion,$query_RsListaRequerimientos) or die(mysqli_connect_error());
	$row_RsListaRequerimientos = mysqli_fetch_array($RsListaRequerimientos);
    $totalRows_RsListaRequerimientos = mysqli_num_rows($RsListaRequerimientos); 
	
	

?>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
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



//paginación

 function f_abrir_link(v_link)
	{
	  document.form1.action=v_link;
	  document.form1.submit();

    }
function listocotizado(orden,proveedor,cotizacion){
	if(confirm("Seguro que desea pasar esta orden a cotizada?")){
		var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=marcarEstadoCot&orden="+orden+"&proveedor="+proveedor+"&cotizacion="+cotizacion);
		//alert(v_dato);
		if(v_dato=='-1'){
			alert("debe enviar la cotizacion mediante correo o registrarla de forma manual para poder marcar este registro como listo de estado cotizado");
		}
		
		if(v_dato=='2'){
			alert("registro marcado como cotizado exitosamente");
		}
		
	}
}	

function GuardarTipoCompra(orden,proveedor,cotizacion, codigodet){
	//alert("valor "+document.getElementById("tipocompra_"+codigodet+"_"+proveedor).value);
	if($("#tipocompra_"+codigodet+"_"+proveedor).val() !=''){
		$('<div id="infoajaxload" style="width:300px; height:30px; background:#b7e06d; margin:0 auto; position:fixed; left:40%; top:0%; z-index:5; border:1px solid #fff500; box-shadow: 0 8px 15px 0 #baf729; padding:5px;">Su informacion se esta procesando...</div>').appendTo('body');
		var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=GuardarTipoCompra&orden="+orden+"&proveedor="+proveedor+"&cotizacion="+cotizacion+"&codigo_detalle="+codigodet+"&tipocompra="+$("#tipocompra_"+codigodet+"_"+proveedor).val());
		if(v_dato==0 || v_dato==1){
			//alert("registro modificado exitosamente");
			if(v_dato==1){
				proveedoresarray = [];
				<?php 
				  for($k=0; $k<count($arraytabs); $k++){
					  ?>
					  proveedoresarray[<?php echo($k); ?>] = <?php echo($arraytabs[$k]['PROVEEDOR']);?>;
					  <?php
				  }
				?>
				
				for(i=0; i<proveedoresarray.length; i++){
					if(proveedor!=proveedoresarray[i]){
					  $("#tipocompra_"+codigodet+"_"+proveedoresarray[i]).val($("#tipocompra_"+codigodet+"_"+proveedor).val())
					}
				}
			}
			
		}
		$("#infoajaxload").remove();
	}
}

function enviarcorreo(orden,proveedor,cotizacion){
 selectstipocompra = 0;
 	  $(".tipocompra").each(function(index){
	  elid = $(this).attr('id');
	  if($("#"+elid).val()==''){
		  selectstipocompra = 1;
	  }
	 })
 /* falta por arreglar 
 if(selectstipocompra==1){
	 alert("debe ingresar el tipo para cada uno de los detalles");
	 return;
 }*/
 
 $('<div id="infoajaxload" style="width:300px; height:30px; background:#b7e06d; margin:0 auto; position:fixed; left:40%; top:0%; z-index:5; border:1px solid #fff500; box-shadow: 0 8px 15px 0 #baf729; padding:5px;">Su informacion se esta procesando...</div>').appendTo('body');
 
  var v_dato = getDataServer("correo_cotizacion.php","?tipoGuardar=EnviarCorreoCotizacion&orden="+orden+"&proveedor="+proveedor+"&cotizacion="+cotizacion);
  alert(v_dato);
  if(v_dato == 'existe'){
	  alert("ya se ha registrado un correo previo para esta cotizacion");
	  $("#infoajaxload").remove();
	  return false;
  }
  if(v_dato == 'exito!'){
   $("#infoajaxload").remove();
   $("#spanmsgprov_"+proveedor).css("display","block");
   $("#estadocotizacion_"+cotizacion).text("COTIZADO");
   $("#estadocotizacion_"+cotizacion).addClass("label label-warning");
   $("#infoformaenviado_"+cotizacion).text("registro enviado de forma: CORREO");
   $("#infoformaenviado_"+cotizacion).addClass("alert alert-danger");
   
   alert("mensaje enviado satisfactoriamente");
  }else{
	  $("#infoajaxload").remove();
	  alert("se ha presentado un error al enviar el correo, intentalo nuevamente.");
  }
}	

function Vmanual(orden,prov,cot){
	
	var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=ValidarEnvioManualCot&cot="+cot);
  // pilas con esto  alert(v_dato);
  /* 
  if(v_dato == '1'){
	  alert("ya se ha registrado un correo previo para esta cotizacion");
	 
	 // return false;
  } */
  
  if(v_dato == '0' || v_dato == '1'){
	 window.open('do_cotizacionmanual.php?orden='+orden+'&proveedor='+prov+'&cotizacion='+cot);
	
	  //alert();
  }
	
	
}

function Vmanualservi(orden,prov,cot){


	
	var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=ValidarEnvioManualCot&cot="+cot);
  // pilas con esto  alert(v_dato);
  /* 
  if(v_dato == '1'){
	  alert("ya se ha registrado un correo previo para esta cotizacion");
	 
	 // return false;
  } */
  
  if(v_dato == '0' || v_dato == '1'){
	 window.open('do_cotizacionmanualservi.php?orden='+orden+'&proveedor='+prov+'&cotizacion='+cot);
	
	  //alert();
  }
	
}

function Vmanualtrab(orden,prov,cot){


	
	var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=ValidarEnvioManualCot&cot="+cot);
  // pilas con esto  alert(v_dato);
  /* 
  if(v_dato == '1'){
	  alert("ya se ha registrado un correo previo para esta cotizacion");
	 
	 // return false;
  } */
  
  if(v_dato == '0' || v_dato == '1'){
	 window.open('do_cotizacionmanualtrab.php?orden='+orden+'&proveedor='+prov+'&cotizacion='+cot);
	
	  //alert();
  }
	
}

	
</script>
<style type="text/css">
.contenttable{
 width:750px;
 overflow:hidden;
 min-height:150px;
 border-radius:12px;
}
.tituloprov{
background: none repeat scroll 0 0 #32323D;
    line-height: 2.1;
}
 .tituloprovtit{
background: none repeat scroll 0 0 #706D6D;
    line-height: 1.5;
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
 </script>
 

    <script type="text/javascript">

    $(document).ready(function(){

        $(".previsualizar").click(function(){
            codigo = $(this).attr("data-prov");
            $("#myModal_"+codigo).modal('show');

        });

    });

    </script>


 <head>
 <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
</head>

<body>
 <form name="form1" id="form1" method="post" action="">
   <div class="containerordenfd">         
<?php
	
    if($totalRows_RsListaRequerimientos >0){
	 ?>
	   <div class="jumbotron" style="width:450px;"> 
			<div class="row">
				<div class="col-md-2">Codigo Orden</div>
				<div class="col-md-2"><?php echo($row_RsListaRequerimientos['CODIGO_ORDEN']);?></div>
			</div>
			<div class="row">
				<div class="col-md-2">Estado</div>
				<div class="col-md-2"><?php echo($row_RsListaRequerimientos['ESTADO_DES']);?></div>
			</div>
			<div class="row">
				<div class="col-md-2">Fecha Creacion</div>
				<div class="col-md-2"><?php echo($row_RsListaRequerimientos['FECHA_CREACION']);?></div>
			</div>
      </div>
		
	  <ul class="nav nav-tabs">
		<?php 
		 $t=0;
		  for($k=0; $k<count($arraytabs); $k++){
		    $t++;
		  ?>
		  <li><a href="#provcodord_<?php echo($arraytabs[$k]['PROVEEDOR']);?>" data-toggle="tab"><?php echo($arraytabs[$k]['PROVEEDOR_DES']);?></a></li>
		  <?php
		  }
		?>
	  </ul>
		

	 <?php
	  $k=0;
	  do{
	    $k++;
	     if($k%2==0){
		  $estilo="SB";
		 }else{
		  $estilo="SB2";
		 }
	  
	   }while($row_RsListaRequerimientos = mysqli_fetch_array($RsListaRequerimientos));
	}else{
	?>
	<tr>
	  <td colspan="4">No existen registros</td>
	</tr>
	<?php
	}
?>

<?php
if($codorden!=''){
    if($totalRows_RsListaDetalles>0){
?>
<div class="tab-content">
 <?php 
  $k=0;
  $proveedorcodi='';
// do{
  $k++;
  if($k%2==0){
   $estiloordn ="";
  }else{
  $estiloordn="";
  }
?>
<?php 
if(count($arraytabs)>0){
for($k=0; $k<count($arraytabs); $k++){
?>
<div id="provcodord_<?php echo($arraytabs[$k]['PROVEEDOR']);?>" class="tab-pane" style="width:950px;">

   <div class="form-group">
    <label  class="col-md-2 control-label">Codigo:</label>
    <div class="col-lg-10">
   <label  class="col-md-4 control-label"><?php echo($CODIGOCOTIZACION.$arraytabs[$k]['COTIZACION']);?></label>
    </div>
  </div>
  
    <div class="form-group">
    <label  class="col-md-2 control-label">Estado:</label>
    <div class="col-lg-10">
   <label  class="col-md-4 control-label"><span id="estadocotizacion_<?php echo($arraytabs[$k]['COTIZACION']);?>"><?php echo($arraytabs[$k]['ESTADO_DE']);?></span></label>
    </div>
  </div>
	   <br>
 <table class="table table-bordered table-hover" width="800">
 <thead>
  <tr class="">
    <td>Cantidad</td>
	<td>U/med</td>
    <td>Articulo</td>
	<td>Descripcion Proveedor</td>
    <td>Valor Unit</td>
	<td>% Iva</td>
	<td>Valor Total</td>
	<td>Tipo</td>
	<td>Requerimiento</td>
	
  </tr>
 </thead>
<?php
  for($j=0; $j<count($arrayreqprov); $j++){ 
   if($arraytabs[$k]['PROVEEDOR']==$arrayreqprov[$j]['PROVEEDOR']){
 ?>
  <tr>
    <td><?php echo($arrayreqprov[$j]['CANTIDAD']);?></td>
	<td><?php echo($arrayreqprov[$j]['U_MEDIDA']);?></td>
    <td><?php echo($arrayreqprov[$j]['NOMBRE']);?></td>
    <td><?php echo($arrayreqprov[$j]['ALIAS']);?></td>
	<td>$<?php echo($arrayreqprov[$j]['VALOR_UNT']);?></td>
	<td>%<?php echo($arrayreqprov[$j]['VALOR_POR']);?></td>
	<td>$<?php echo($arrayreqprov[$j]['VALOR_TOTAL']);?></td>
	<td>
	<?php 
		// if($arraytabs[$k]['ESTADO_D']!=''){
	?>
		<select class="tipocompra" name="tipocompra_<?php echo($arrayreqprov[$j]['CODIGO_DETALLE']);?>_<?php echo($arraytabs[$k]['PROVEEDOR']);?>" id="tipocompra_<?php echo($arrayreqprov[$j]['CODIGO_DETALLE']);?>_<?php echo($arraytabs[$k]['PROVEEDOR']);?>" Onchange="GuardarTipoCompra('<?php echo($codorden);?>','<?php echo($arraytabs[$k]['PROVEEDOR']);?>','<?php echo($arraytabs[$k]['COTIZACION']);?>','<?php echo($arrayreqprov[$j]['CODIGO_DETALLE']);?>');">
		 <option value="">Seleccione...</option>
		 <?php 
		    if(count($arraytipocompra)>0){
				for($n=0; $n<count($arraytipocompra); $n++){
					?>
					<option value="<?php echo($arraytipocompra[$n]['CODIGO']);?>" <?php if($arrayreqprov[$j]['TIPOCOMPRA']==$arraytipocompra[$n]['CODIGO']){ echo('selected');} ?> ><?php echo($arraytipocompra[$n]['NOMBRE']);?></option>
					<?php
				}
			}
		 ?>
		</select>
		<?php 
		// }
		?>
	</td>
	<td><a href="home.php?page=solicitud&codreq=<?php echo($arrayreqprov[$j]['CODI_REQUERIMIENTO']);?>" class="btn btn-link" role="button"><?php echo($arrayreqprov[$j]['REQUERIMIENTO']);?></a></td>
	</tr>
  
 <?php
   }
}
  ?>
  <tr>
  <td colspan="6">Valor Total</td>
  <td >$<?php echo($arraytabs[$k]['TOTAL']);?></td>
 </tr>
 </table>
 
 <?php 
 $fp=$arraytabs[$k]['FP'];
 $gr=$arraytabs[$k]['GR'];
 $te=$arraytabs[$k]['TE'];
 $se=$arraytabs[$k]['SE'];
 $ob=$arraytabs[$k]['OB'];
 $forma_pago=criterio($fp);
 $garantia=criterio($gr);
 $tiempo_entrega=criterio($te);
 $sitio_entrega=criterio($se);
 

 ?>
  <div class="row">
	   <div class="form-group">
		<label  class="col-md-3 control-label">Forma De Pago:</label>
		<div class="col-lg-9">
	   <label  class="col-md-9 control-label"><?php  echo($forma_pago);?></label>
		</div>
	  </div>
  </div>
   <div class="row">
   <div class="form-group">
    <label  class="col-md-3 control-label">Garantia:</label>
    <div class="col-lg-9">
   <label  class="col-md-9 control-label"><?php  echo($garantia);?></label>
    </div>
  </div>
  </div>
   <div class="row">
    <div class="form-group">
    <label  class="col-md-4 control-label">Sitio de Entrega:</label>
    <div class="col-lg-8">
   <label  class="col-md-8 control-label"><?php echo($sitio_entrega);?></label>
    </div>
  </div>
  </div>
   <div class="row">
  <div class="form-group">
    <label  class="col-md-4 control-label">Tiempo de Entrega:</label>
    <div class="col-lg-8">
   <label  class="col-md-8 control-label"><?php echo($tiempo_entrega);?></label>
    </div>
  </div>
  </div>
  <div class="row">
   <div class="form-group">
    <label  class="col-md-3 control-label">Observaciones:</label>
    <div class="col-lg-9">
	<textarea class="form-control" rows="3"><?php  echo($ob);?></textarea>
  
    </div>
  </div>
  </div>
  <br>
  <div class="row">
  	<div class="col-md-5">
	 <span id="infoformaenviado_<?php echo($arraytabs[$k]['COTIZACION']);?>" class="<?php if($arraytabs[$k]['FORMA_ENVIO']!=''){ ?>alert alert-danger<?php } ?>">
	 <?php 
	 if($arraytabs[$k]['FORMA_ENVIO']!=''){
		 echo('registro enviado de forma: <b>'.$arraytabs[$k]['FORMA_ENVIO_DES'].'</b>');
	 }
	 ?>
	</span>
	</div>
   </div>
   <br>
   
  <div class="row">
   <div class="col-md-2" style="width:10%;">
	<input type="button" class="btn btn-sm btn-primary" value="Enviar correo" onclick="enviarcorreo('<?php echo($codorden);?>','<?php echo($arraytabs[$k]['PROVEEDOR']);?>','<?php echo($arraytabs[$k]['COTIZACION']);?>');">
  </div>
   <div class="col-md-2" style="width:11%;">
	<input type="button" class="btn btn-sm btn-primary" value="Reenviar correo" onclick="enviarcorreo('<?php echo($codorden);?>','<?php echo($arraytabs[$k]['PROVEEDOR']);?>','<?php echo($arraytabs[$k]['COTIZACION']);?>');">
  </div>
   <div class="col-md-2" style="width:50%;">	   
   <input type="button" class="btn btn-sm btn-primary" value="Llenar Manual Productos" onclick="Vmanual('<?php echo($codorden);?>','<?php echo($arraytabs[$k]['PROVEEDOR']);?>','<?php echo($arraytabs[$k]['COTIZACION']);?>');">
  </div>
    <div class="col-md-2" style="width:10%;">	   
   <input type="button" class="btn btn-sm btn-primary" value="Llenar Manual servicios" onclick="Vmanualservi('<?php echo($codorden);?>','<?php echo($arraytabs[$k]['PROVEEDOR']);?>','<?php echo($arraytabs[$k]['COTIZACION']);?>');">
  </div>
   <div class="col-md-2" style="width:10%;">	   
   <input type="button" class="btn btn-sm btn-primary" value="Llenar Manual Trabajos" onclick="Vmanualtrab('<?php echo($codorden);?>','<?php echo($arraytabs[$k]['PROVEEDOR']);?>','<?php echo($arraytabs[$k]['COTIZACION']);?>');">
  </div>  
  <div class="col-md-1" style="width:10%;">
	<input type="button" class="btn btn-sm btn-primary" value="  Listo  " onclick="listocotizado('<?php echo($codorden);?>','<?php echo($arraytabs[$k]['PROVEEDOR']);?>','<?php echo($arraytabs[$k]['COTIZACION']);?>');">
  </div>
  
    <div class="col-md-2"style="width:10%;">
   <input type="button" class="btn btn-sm btn-primary previsualizar" value="Previsualizar" data-prov="<?php echo($arraytabs[$k]['PROVEEDOR']);?>">
     </div>
	<span id="spanmsgprov_<?php echo($arraytabs[$k]['PROVEEDOR']);?>" style="color:#4d9911; display:none; background:#fffdc1; padding:5px;">correo enviado</span>

     

    <!-- Modal HTML -->

    <div id="myModal_<?php echo($arraytabs[$k]['PROVEEDOR']);?>" class="modal fade">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                    <h4 class="modal-title">Visualización Correo</h4>

                </div>

                <div class="modal-body">

<table width='100%'>
	<tr>
		<td width='613'>
		<table width='100%' border='0'>
		  <tr>
			<td width='41%'></td>
			<td width='59%'>&nbsp;</td>
		  </tr>
		  <tr>
			<td colspan='2'><h2>Reciba un cordial saludo de parte de la Corporaci&oacute;n  Colegio San Bonifacio de las lanzas:</h2></td>
		  </tr>
		  <tr>
		    <td colspan=''><h2><small>Se&ntilde;or(a)</small></h2></td>
		  </tr>
		   <tr>
		    <td colspan=''><h2><small>NOMBRE DEL PROVEEDOR</small></h2></td>
		  </tr>
		</table>
		<br>
		<br>
		<p class="text-justify"><strong>Por Favor, se solicita muy amablemente cotizar los siguientes items atravez del PORTAL WEB DE COMPRAS de la  Corporaci&oacute;n  Colegio San Bonifacio de las lanzas: </strong></p>
		 <br>
		</td>
	</tr>
</table>			                   
								  
								   <table class="table table-bordered table-hover" width="500">
 <thead>
 <h2><small>Articulos A Cotizar</small></h2>
  <tr class="">
    <td>Cantidad</td>
    <td>Articulo</td>    
  </tr>
 </thead>
<?php
  for($j=0; $j<count($arrayreqprov); $j++){ 
   if($arraytabs[$k]['PROVEEDOR']==$arrayreqprov[$j]['PROVEEDOR']){
 ?>
  <tr>
    <td><?php echo($arrayreqprov[$j]['CANTIDAD']);?></td>
    <td><?php echo($arrayreqprov[$j]['NOMBRE']);?></td>	
  </tr>
 <?php
   }
}
  ?>
 </table>
				
			  <br>
			  <p><strong>Favor cotizar ingresando  al siguiente link:</strong></p>
		<p><a href='#'>INGRESAR A COTIZAR</a></p>
			 
  <p class="text-justify  text-warning"><small>Ante cualquier inquietud, puede comunicarse con la encargada del Proceso de 
Contrataci&oacute;n - EDNA CONSUELO CARVAJAL OYUELA, en el teléfono 2670226 Ext.116 ó 311 538 3277, 
o email edna.carvajal@sanboni.edu.co, quien le brindará su acompañamiento y asesoría 
necesaria.
<br> <br>
 
Cordialmente,  
 <br><br>
 
 EDNA CONSUELO CARVAJAL OYUELA<br>
Asistente Administrativa y de Compras<br>
CORPORACION COLEGIO SAN BONIFACIO DE LAS LANZAS<br>
Avenida Ambalá-Hacienda El Vergel
 </small></p>

                 

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>     

                </div>

            </div>

        </div>

    </div>


    


  
 

   </div>
</div>
  <?php
}
 }
}
}
?>
</div>
</div>
</form>