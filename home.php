<?php
require_once('conexion/db.php');
if (!isset($_SESSION)) {
  session_start();
 }
if(!isset($_SESSION['MM_AccesoCorrectoApp']) || $_SESSION['MM_AccesoCorrectoApp'] != 'ACTIVO'){
  header("location: index.php");
}
//echo($_SESSION['MM_IDUsuario']);
$codigo_requerimiento='';
if($codigo_requerimiento!=''){
$query_RsListadoDeta_Requ = "SELECT
							DERECONS CODIGO,
							DEREDESC DESCRIPCION,
							DERECANT CANTIDAD,
							DEREJUST JUSTIFICACION,
							DEREOBSE OBSERVACION,
							DERETISE TIPO
							FROM `DETALLE_REQ` 
						where DEREREQU = '".$codigo_requerimiento."'
							";

				// echo($query_RsListadoDeta_Requ);echo("<br>");
	$RsListadoDeta_Requ = mysqli_query($query_RsListadoDeta_Requ, $conexion) or die(mysqli_error($conexion));
	$row_RsListadoDeta_Requ = mysqli_fetch_array($RsListadoDeta_Requ);
    $totalRows_RsListadoDeta_Requ = mysqli_num_rows($RsListadoDeta_Requ);	
}
    
	/*$query_RsListaProveedores="SELECT P.PROVCODI CODIGO,
                                      P.PROVNOMB NOMBRE,
									  P.PROVTELE TELEFONO
							    FROM PROVEEDORES P";
	$RsListaProveedores = mysqli_query($conexion,$query_RsListaProveedores) or die(mysqli_error($conexion));
	$row_RsListaProveedores = mysqli_fetch_array($RsListaProveedores);
    $totalRows_RsListaProveedores = mysqli_num_rows($RsListaProveedores);*/

	$query_RsPorCotizar="SELECT count(*) POR_COTIZAR FROM `requerimientos` WHERE REQUESTA=5 ";
	$RsPorCotizar = mysqli_query($conexion,$query_RsPorCotizar) or die(mysqli_error($conexion));
	$row_RsPorCotizar = mysqli_fetch_array($RsPorCotizar);
    $totalRows_RsPorCotizar = mysqli_num_rows($RsPorCotizar);

	if($totalRows_RsPorCotizar>0){
	$por_cotizar=$row_RsPorCotizar['POR_COTIZAR'];
	}else{
	$por_cotizar='';
	}
	
?>


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



function validarCampos(idf){
 
// Validacion No seleccionado 
  if(idf == "1"){
 
 // variables no seleccionado
 var descrip_ns=document.no_selecc.descrip_ns.value;
 var cantidad_ns=document.no_selecc.cantidad_ns.value;
 var justi_ns=document.no_selecc.justi_ns.value;
 var observ_ns=document.no_selecc.observ_ns.value;
 
 // Mensajes de no seleccionado 
 if(descrip_ns == '')
  {
   inlineMsg('descrip_ns','debe digitar la Descripcion.',3);
		return false;   
  }
 if(cantidad_ns == '')
  {
   inlineMsg('cantidad_ns','debe digitar la Cantidad.',3);
		return false;   
  }

  if(justi_ns == '')
  {
   inlineMsg('justi_ns','debe digitar la Justificacion.',3);
		return false;   
  }
  
  if(observ_ns == '')
  {
   inlineMsg('observ_ns','debe digitar la observacion.',3);
		return false;   
  }
   if(document.getElementById('codigodetalle_ns').value!=''){
     if(confirm('esta seguro que desea editar este detalle no seleccionado')){
      document.no_selecc.action = 'solicitud_guardar.php?tipoGuardar=editar_ns&codreq='+document.getElementById('codigo_req').value+'&codigo_detalle='+document.getElementById('codigodetalle_ns').value;
	  }else{
	  return false;
	  }
   }else{
    if(confirm('esta seguro que desea guardar este detalle no seleccionado')){
     document.no_selecc.action = 'solicitud_guardar.php?tipoGuardar=adicionar_ns&codreq='+document.getElementById('codigo_req').value;
     }else{
	 return false;
	 }
   }
}

// Validacion seleccionado
  if(idf == "2"){
  
  //variables seleccionado
 var modalidad_ss=document.si_selecc.modalidad_ss.value;
 var clasificacion_ss=document.si_selecc.clasificacion_ss.value;
 var descripcion_ss=document.si_selecc.descripcion_ss.value;
 var cantidad_ss=document.si_selecc.cantidad_ss.value;
 var justi_ss=document.si_selecc.justi_ss.value;
 var observ_ss=document.si_selecc.observ_ss.value;

  
  // Mensajes seleccionados
  if(modalidad_ss == '')
  {
   inlineMsg('modalidad_ss','debe seleccionar la Modalidad.',3);
		return false;   
  }  
 
 if(clasificacion_ss == '')
  {
   inlineMsg('clasificacion_ss','debe seleccionar la Clasificacion.',3);
		return false;   
  }

   if(descripcion_ss == '')
  {
   inlineMsg('descripcion_ss','debe ingresar la descripción.',3);
		return false;   
  } 
  
  if(cantidad_ss == '')
  {
   inlineMsg('cantidad_ss','debe ingresar la cantidad.',3);
		return false;   
  }  
  
 if(justi_ss == '')
  {
   inlineMsg('justi_ss','debe ingresar la Justificacion.',3);
		return false;   
  }  
 
if(observ_ss == '')
  {
   inlineMsg('observ_ss','debe ingresar la Observacion.',3);
		return false;   
  }
   if(document.getElementById('codigodetalle_ss').value!=''){
     if(confirm('esta seguro que desea editar este detalle no seleccionado')){  
      document.si_selecc.action = 'solicitud_guardar.php?tipoGuardar=editar_ss&codreq='+document.getElementById('codigo_req').value+'&codigo_detalle='+document.getElementById('codigodetalle_ss').value;
	  }else{
	  return false;
	  }
	 }else{
	 if(confirm('esta seguro que desea guardar este detalle no seleccionado')){  
	 document.si_selecc.action = 'solicitud_guardar.php?tipoGuardar=adicionar_ss&codreq='+document.getElementById('codigo_req').value;
	 }else{
	 return false;
	 }
	}	
 }
}

 function f_guardar(){
   if(confirm('seguro que desea generar este requerimientoguardar')){
     document.form_req.action = 'solicitud_guardar.php?tipoGuardar=crear_req';
   
   }
 }
 
function CrearRequerimiento(id){

   if(confirm('seguro que desea generar este requerimiento')){
   if(id=='1'){
     location.href = 'solicitud_guardar.php?tipoGuardar=crear_req';
	 }
	  if(id=='2'){
     location.href = 'solicitud_guardar.php?tipoGuardar=crear_req&x=director';
	 }
   } 
} 

function limpiar(tipo){
}

function FeditarDet(cod,tipo){
      var date = new Date();
	  var timestamp = date.getTime();
	  var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=CargarDatos&time="+timestamp+"&codigo_detalle="+cod+"&tipo="+tipo);
	  if(v_dato!=''){
	  //alert(v_dato);
	   data=v_dato.split('|');
	   if(data[0] !='' && data[0]!='none'){
	     if(tipo==0){
		  $('#codigodetalle_ns').val(data[0]);
		  $('#descrip_ns').val(data[3]);
		  $('#cantidad_ns').val(data[4]);
		  $('#justi_ns').val(data[5]);
		  $('#observ_ns').val(data[6]);
		  $('#btnsub_ns').val('Editar');
		  }	     
		  if(tipo==1){
		  $('#codigodetalle_ss').val(data[0]);
		  $('#modalidad_ss').val(data[1]);
		  //$('#codigodetalle_ss').val(data[1]);
		  $('#descripcion_ss').val(data[3]);
		  $('#cantidad_ss').val(data[4]);
		  $('#justi_ss').val(data[5]);
		  $('#observ_ss').val(data[6]);
		  
		        condObj = document.getElementById('clasificacion_ss');
				optionObj =document.createElement('option');
					optionObj.text  = data[10];
					optionObj.value = data[2];

					try {
						condObj.add(optionObj, null);
					} catch(ex) {
						condObj.add(optionObj); // IE only
					}
					
				$('#clasificacion_ss').val(data[2]);	
           $('#btnsub_ss').val('Editar');				
		  
		  }
	   }
	  }
}

function FdeleteDet(cod,tipo){
      var date = new Date();
	  var timestamp = date.getTime();
	  //var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=CargarDatos&time="+timestamp+"&codigo_detalle="+cod+"&tipo="+tipo);
	  if(confirm('esta seguro de eliminar este detalle del requerimiento?')){
       document.form_req.action='solicitud_guardar.php?tipoGuardar=EliminarDetalle&cod_detalle='+cod+'&codreq='+document.getElementById('codigo_req').value;
	   document.form_req.submit();
	  }

}

function GuardarProveedor(){
 if(confirm('Esta seguro de almacenar estos datos?')){
    document.form1.action="crear_proveedor_guardar.php?tipoGuardar=Guardar";
	document.form1.submit();
 }
}

function callback_error(XMLHttpRequest, textStatus, errorThrown)
{
    alert("Respuesta del servidor "+XMLHttpRequest.responseText);
    alert("Error "+textStatus);
    alert(errorThrown);
}

</script>
<!DOCTYPE html>
<html>
	<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="css/jquery.ui.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
	<script type="text/javascript" src="js/jquery.1.7.2.js"></script>
	<script type="text/javascript" src="js/underscore-min.js"></script>
	<!--<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>-->
	<script type="text/javascript" src="js/jquery.ui.1.8.16.js"></script>
	<link rel="stylesheet" type="text/css" href="css/page.css" />
	<link rel="stylesheet" type="text/css" href="messages.css"/>
	<link rel="stylesheet" href="chosen/chosen.min.css" />	
	<script src="messages.js" type="text/javascript"></script>
	<script src="chosen/chosen.jquery.min.js" type="text/javascript"></script>
	<link rel="stylesheet" href="css/estilo_home3.css" title="style css" type="text/css" media="screen" charset="utf-8">
		<link href="js/toast/toastr.min.css" rel="stylesheet"/>
		<link rel="stylesheet" href="includes/font-awesome/css/font-awesome.min.css">	
		<link href="css/bootstrap-toggle.min.css" rel="stylesheet">
		<script src="js/bootstrap-toggle.min.js"></script>		

		<title>San Bonifacio - Compras</title>
	<!--<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>-->
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/jsplug.js"></script>
	<style type="text/css">
		table{
			font-size:90% !important;
		}
	</style>
	</head>
	<body>
<div class="containergeneral">
    <div id="sidebar">
        <?php
include('menu.php');
?>
    </div>
    <div class="main-content">
    <div class="swipe-area"></div>
        <a href="#" data-toggle=".containergeneral" id="sidebar-toggle">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </a>
        <div class="content">
		<div class="titulo">
			<div class="row" style=" padding-top:10px;">
				<div class="col-md-6 col-lg-6">
					<label  class="SLAB2F">Bienvenido: <?php echo($_SESSION['MM_Usernombre']);?> al portal de compras</label>
				</div>
				<div class="col-md-6 col-lg-6 text-right" style="padding-right:4em;">
							
							<?php 
							if($_SESSION['MM_RolID'] != 4)
							  {
								require_once("scripts/funcionescombo.php");		
								$estados = dameTotalProveedoresUpdate();
								foreach($estados as $indice => $registro)
								{
								if($_SESSION['MM_RolID']== 2){
								?>
								<div class="btn-group">
									<div id="cantidad" class="text-danger; " style="position:absolute; top:-15px; left: 5px;"><?php echo( $registro['TOTAL']);?></div>
								  <a class=" dropdown-toggle badge" data-toggle="dropdown">
									 <span class="fa-2x"><i class="fa fa-bullhorn"></i></span> 
								  </a>
								  <ul class="dropdown-menu" role="menu" style="min-width:104px; left:-90px;">
									<li><a><i class="fa fa-eye"></i> Ver ahora</a></li>
									<li><a><i class="fa fa-eye"></i> Proveedores Actualizados</a></li>
								  </ul>
								</div>	
														
							<?php 
									}
									}
									}
							?>
							
							<?php 

							 if($_SESSION['MM_RolID'] != 4)
							  {
								require_once("scripts/funcionescombo.php");		
								$estados = dameTotalEstadosPorAutorizar();
								foreach($estados as $indice => $registro)
								{
								if($_SESSION['MM_RolID']== 2){
								?>
								<div class="btn-group">
									<div id="cantidad" class="text-danger; " style="position:absolute; top:-15px; left: 5px;"><?php echo( $registro['TOTAL']);?></div>
								  <a class=" dropdown-toggle badge" data-toggle="dropdown">
									 <span class="fa-2x"><i class="fa fa-bell-o"></i></span> 
								  </a>
								  <ul class="dropdown-menu" role="menu" style="min-width:104px; left:-90px;">
									<li><a><i class="fa fa-eye"></i> Ver ahora</a></li>
									<li><a><i class="fa fa-eye"></i> Proveedores Actualizados</a></li>
								  </ul>
								</div>							
							<?php 
									}
							?>
							
								
								<div class="btn-group">
								  <a class=" dropdown-toggle badge" data-toggle="dropdown">
									 <span class="fa fa-cog fa-2x"></span> 
								  </a>
								  <ul class="dropdown-menu" role="menu" style="min-width:104px; left:-90px;">
									<li><a href="#"><i class="fa fa-database"></i> Exportar DB</a></li>
									<li><a href="#"><i class="fa fa-users"></i> Crear Usuarios</a>
								  </ul>
								</div>								
					
								
							<?php	}
							 }
					?>					
				</div>
			</div>
		
												

		
		</div>	
			 
			 <tr>
			  <td>&nbsp;<br></td>
			 </tr>
			<!--</table> -->
            <?php
  if(isset($_GET['page']) && $_GET['page']!=''){
	if($_SESSION['MM_RolID'] != '7'){
    	include($_GET['page'].'.php');
	}else{
		?>
		<script>
		location.href="crear_proveedor.php";
		</script>		
		<?php		
	}
  }
  else
  {
	  if($_SESSION['MM_RolID']== 1)
	  {
	  	include('listar_usuarios.php');
	  }else{
				if($_SESSION['MM_RolID']!= '7'){		  
  					include('requerimientos_lista.php');
				}else{
					?>
					<script>
					location.href="crear_proveedor.php";
					</script>
					<?php
				}
  			}
  }
       ?>
</div>
    </div>
    </div>
</div>
<script type="text/javascript" src="js/toast/toastr.min.js"></script>
	</body>
</html>
<script type="text/javascript">
$(document).ready(function(){

	// obtenemos el valor actual de la burbuja
	var valor = parseInt($('.burbuja').html());
	var $burbuja = $('.burbuja');

	// al presionar algún botón del div "botones"
	$('#botones').on('click',function(event){

		// almacenamos el valor que tenía la burbuja antes del click
		var valorPrevio = valor;

		// obtenemos el nombre del botón presionado
		var boton = $(event.target).attr('id');

		if (boton == 'incrementar') {
			valor++;	
		} else{

			// no permitimos decrementar si ya está el valor en 0
			if (valor > 0) {
				if (boton == 'decrementar') {
					valor--;
				} else{
					valor = 0;
				};
			}
		};
		
		// si hubo un cambio en el valor
		if (valor != valorPrevio) {
			agrandar($burbuja);			
		} 
	});

	// función que pasado un tiempo, quita la clase "agrandar" del elemento
	function removeAnimation(){
		setTimeout(function() {
			$burbuja.removeClass('agrandar')
		}, 1000);
	}

	// función que modifica el valor de la burbuja y la agranda
	function agrandar ($elemento) {
		$elemento.html(valor).addClass('agrandar');
		removeAnimation();
	}
});
</script>