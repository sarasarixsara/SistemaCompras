<?php
//conexion a base de datos
require_once('conexion/db.php');

// verificacion de session
	if (!isset($_SESSION)) {
  session_start();
}

//Informacion de Prueba 
	//$sUrlDescargas = $_SERVER["DOCUMENT_ROOT"].$sDirectorio;
	//echo($sUrlDescargas);
//$_SESSION['MM_IDUsuario']='93100';


//definicion de variables
$totalRows_RsListadoDeta_Requ=0;

echo($codigo_requerimiento);

$codigo_requerimiento='';
if(isset($_GET['codreq']) && $_GET['codreq']!=''){
$codigo_requerimiento=$_GET['codreq'];
}

$MM_UserID='';
if(isset($_GET['MM_UserID']) && $_GET['MM_UserID']!=''){
$MM_UserID=$_GET['MM_UserID'];
}

$MM_Area='';
if(isset($_GET['MM_Area']) && $_GET['MM_Area']!=''){
$MM_Area=$_GET['MM_Area'];
}
$MM_Areades="";

$estado     = '';
$estado_des = '';
$fecha_creacion ='';
$fecha_envio = '';
$codigo_req = '';
$poa        = '';
$poa_des    = '';
$subpoa     = '';
$subpoa_des     = '';
$solicitante = '';
$tiposubpoa  = '';
$arraymodalides = array();
$arraypoa = array();
$arraysubpoa = array();
$arraydetalles = array();
$arrayundmedida=array();
$MM_RolID='4';	


//consulta index

$query_RsIndex="SELECT * FROM `personas` WHERE `PERSID`='".$MM_UserID."'";
	$RsIndex = mysqli_query($conexion,$query_RsIndex) or die(mysqli_error($conexion));
	$row_RsIndex = mysqli_fetch_array($RsIndex);
    $totalRows_RsIndex = mysqli_num_rows($RsIndex);
		
	
//consulta unidad de medida
$query_RsUmedida="SELECT UNMECONS CODIGO,
					     UNMENOMB NOMBRE,
							   UNMESIGL,
							   UNMECONV 
						FROM   UNIDAD_MEDIDA ";
	$RsUmedida = mysqli_query($conexion,$query_RsUmedida) or die(mysqli_error($conexion));
	$row_RsUmedida = mysqli_fetch_array($RsUmedida);
    $totalRows_RsUmedida = mysqli_num_rows($RsUmedida);
		if($totalRows_RsUmedida>0){
      	  $j=1;
		  do{
		    $arrayundmedida[$row_RsUmedida['CODIGO']] = $row_RsUmedida['NOMBRE']; 
		    $j++;
		    }while($row_RsUmedida = mysqli_fetch_array($RsUmedida));	
		}	
	
// consulta de modalidades
  /*  $query_RsModalidades="";
	$RsModalidades = mysqli_query($conexion,$query_RsModalidades) or die(mysqli_error($conexion));
	$row_RsModalidades = mysqli_fetch_array($RsModalidades);
    $totalRows_RsModalidades = mysqli_num_rows($RsModalidades);
		if($totalRows_RsModalidades>0){
      	  $j=1;
		  do{
		    $arraymodalidades[$row_RsModalidades['CODIGO']] = $row_RsModalidades['NOMBRE']; 
		    $j++;
		    }while($row_RsModalidades = mysqli_fetch_array($RsModalidades));	
		}
*/
// consulta sub poa
    $query_RsSubpoa="SELECT P.PODECODI CODIGO,
	                     P.PODENOMB    NOMBRE
				   FROM POADETA P";
	$RsSubpoa = mysqli_query($conexion,$query_RsSubpoa) or die(mysqli_error($conexion));
	$row_RsSubpoa = mysqli_fetch_array($RsSubpoa);
    $totalRows_RsSubpoa = mysqli_num_rows($RsSubpoa);
		if($totalRows_RsSubpoa>0){
      	  $j=1;
		  do{
		    $arraysubpoa[$row_RsSubpoa['CODIGO']] = $row_RsSubpoa['NOMBRE']; 
		    $j++;
		    }while($row_RsSubpoa = mysqli_fetch_array($RsSubpoa));	
		}	
		
		
//consulta de poa
    $query_RsPoa="SELECT P.POACODI CODIGO,
	                     P.POANOMB NOMBRE,
						 P.POARESP RESPONSABLE
				   FROM POA P
				   /*WHERE POAESTA=1*/
				   ";
	$RsPoa = mysqli_query($conexion,$query_RsPoa) or die(mysqli_error($conexion));
	$row_RsPoa = mysqli_fetch_array($RsPoa);
    $totalRows_RsPoa = mysqli_num_rows($RsPoa);
	
		if($totalRows_RsPoa>0){
      	  $j=1;
		  do{
		    $arraypoa[$row_RsPoa['CODIGO']] = $row_RsPoa['NOMBRE']; 
		    $j++;
		    }while($row_RsPoa = mysqli_fetch_array($RsPoa));			
		}	


if($codigo_requerimiento!=''){

//consulta de archivos
    $query_RsArchivosLista="SELECT R.REARCODI CODIGO,
                                   R.REARREQU REQUERIMIENTO,
                                   R.REARARCH ARCHIVO								   
	                         FROM REQUERIMIENTOSARCH R
							WHERE R.REARREQU = '".$codigo_requerimiento."'";
	$RsArchivosLista = mysqli_query($conexion,$query_RsArchivosLista) or die(mysqli_error($conexion));
	$row_RsArchivosLista = mysqli_fetch_array($RsArchivosLista);
    $totalRows_RsArchivosLista = mysqli_num_rows($RsArchivosLista);

// consulta de detalle de requerimiento 
$query_RsListadoDeta_Requ = "SELECT
							DERECONS CODIGO,
							DEREMODA MODALIDAD,
							DERECLAS CLASIFICACION,
							'' CLASIFICACION_DES,
							DEREDESC DESCRIPCION,
							DERECANT CANTIDAD,
							DEREJUST JUSTIFICACION,
							DEREOBSE OBSERVACION,
							DERETISE TIPO,
							case DERETISE
							 WHEN 0
							  THEN 'NO SELECCIONADO'
							 WHEN 1
							  THEN 'SELECCIONADO'
							 ELSE ''
							END TIPO_DES,
							DEREAPRO APROBADO,
							(SELECT COUNT(D.OBDECODI)
							  FROM OBSERVACIONESDET D
							 WHERE D.OBDECODE = DERECONS) CANTIDAD_OBS,
							 DERECOSU CONSEJO_SUPERIOR,
							 CASE DERECOSU
							   WHEN 0
							    THEN ''
							   WHEN 1
							    THEN 'Consejo Superior'
							   ELSE ''
							   END CONSEJO_SUPERIOR_DES,
							  DERECOTE CONSEJO_TECNOLOGICO, 
							   CASE DERECOTE
							   WHEN 0
							    THEN ''
							   WHEN 1
							    THEN 'Comite Tecnologico'
							   ELSE ''
							   END CONSEJO_TECNOLOGICO_DES,
							   DERECOIN COMITE_INFRAESTRUCTURA, 
							   CASE DERECOIN
							   WHEN 0
							    THEN ''
							   WHEN 1
							    THEN 'Comite Infraestructura'
							   ELSE ''
							   END COMITE_INFRAESTRUCTURA_DES,
							   DEREPOA POA_DETALLE,
							   (SELECT A.POANOMB 
							     FROM POA A
								WHERE A.POACODI = DEREPOA LIMIT 1 ) POA_DETALLE_DES,
								DEREUNME UNIDAD_MEDIDA,
								(SELECT U.UNMESIGL
								FROM UNIDAD_MEDIDA U
								WHERE U.UNMECONS = 	DEREUNME) UNIDAD_MEDIDA_DES,								
							   DERESUPO SUBPOA_DETALLE,
							   (SELECT D.PODENOMB FROM POADETA D
							     WHERE D.PODECODI = DERESUPO LIMIT 1) SUBPOA_DETALLE_DES,
							   DEREOTRO OTRO_DES,
							   DEREREOT REQUIERE_OTRO
							   
							FROM `DETALLE_REQU`
						where DEREREQU = '".$codigo_requerimiento."'
							";

				// echo($query_RsListadoDeta_Requ);echo("<br>");
	$RsListadoDeta_Requ = mysqli_query($conexion,$query_RsListadoDeta_Requ) or die(mysqli_error($conexion));
	$row_RsListadoDeta_Requ = mysqli_fetch_array($RsListadoDeta_Requ);
    $totalRows_RsListadoDeta_Requ = mysqli_num_rows($RsListadoDeta_Requ);
	
	
	
	$RsListadoDeta_Requ2 = mysqli_query($conexion,$query_RsListadoDeta_Requ) or die(mysqli_error($conexion));
	$row_RsListadoDeta_Requ2 = mysqli_fetch_object($RsListadoDeta_Requ2);
	$arr=array();
	$rem[]='"';
	$rem[]="'";
	$rem[]="’";
	$rem[]="‘";
	$rem[]="‚";
	$rem[]="“";
	$rem[]="”";
	$rem[]="„";
	$rem[]="´";
	$rem[]="`";
	$rem[]="{";
	$rem[]="}";
	$rem[]="[";
	$rem[]="]";
	$rem[]="\r\n";
	$rem[]="\n\r";
	$rem[]="\n";
	$rem[]="\r";
	
    if($totalRows_RsListadoDeta_Requ>0){
	  $t=0;
	  do{
	   $arraydetalles[$t] = $row_RsListadoDeta_Requ2 -> CODIGO;
	   $arr[] = array('ID'             => $row_RsListadoDeta_Requ2 -> CODIGO,
	                  'DESCRIPCION'    => str_replace($rem,"",stripslashes($row_RsListadoDeta_Requ2 -> DESCRIPCION)),
	                  'JUSTIFICACION'  => str_replace($rem,"",stripslashes($row_RsListadoDeta_Requ2 -> JUSTIFICACION)),
	                  'OBSERVACION'    => str_replace($rem,"",stripslashes($row_RsListadoDeta_Requ2 -> OBSERVACION)),
					  );
		$t++;
	  //printf ( $row_RsListadoDeta_Requ2 -> JUSTIFICACION , $row_RsListadoDeta_Requ2 -> OBSERVACION ); 
	  }while($row_RsListadoDeta_Requ2 = mysqli_fetch_object($RsListadoDeta_Requ2));
}	

//var_dump($arraydetalles);
// consulta de requerimiento
	$query_RsListadoRequerimiento="SELECT R.REQUESTA ESTADO,
	                                      E.ESTANOMB ESTADO_DES,
										  R.REQUCORE CODIGO_REQ,
										  R.REQUPOA  POA,
										  DATE_FORMAT(R.REQUFESO, '%d/%m/%Y') FECHA_CREACION,
										  DATE_FORMAT(R.REQUFEEN, '%d/%m/%Y') FECHA_ENVIO,
										  (SELECT A.AREANOMB 
										    FROM AREA A
										   WHERE A.AREAID =  R.REQUAREA LIMIT 1) AREA_DES,
										  (SELECT PO.POANOMB 
										    FROM POA PO
										   WHERE PO.POACODI = R.REQUPOA LIMIT 1) POA_DES,
										  R.REQUSUPO SUBPOA,
										  (SELECT PS.PODETIPO
										    FROM POADETA PS
										   WHERE PS.PODECODI = R.REQUSUPO 
										     ) TIPOSUBPOA,
										  (SELECT P.PODENOMB
										    FROM POADETA P
										   WHERE P.PODECODI = R.REQUSUPO LIMIT 1) SUBPOA_DES,
										   (select P.PERSNOMB FROM PERSONAS P
  										     WHERE R.REQUCODI = '".$codigo_requerimiento."'
											AND P.PERSID = R.REQUIDUS
											 LIMIT 1) SOLICITANTE
							  FROM REQUERIMIENTOS R,
							       ESTADOS        E
							 WHERE R.REQUESTA = E.ESTACODI
							   AND R.REQUCODI = '".$codigo_requerimiento."'";
							   //echo($query_RsListadoRequerimiento);
	$RsListadoRequerimiento = mysqli_query($conexion, $query_RsListadoRequerimiento) or die(mysqli_error($conexion));
	$row_RsListadoRequerimiento = mysqli_fetch_assoc($RsListadoRequerimiento);
    $totalRows_RsListadoRequerimiento = mysqli_num_rows($RsListadoRequerimiento);
	$estado         = $row_RsListadoRequerimiento['ESTADO'];
	$estado_des     = $row_RsListadoRequerimiento['ESTADO_DES'];
	$fecha_creacion = $row_RsListadoRequerimiento['FECHA_CREACION'];
	$fecha_envio    = $row_RsListadoRequerimiento['FECHA_ENVIO'];
	$codigo_req 	= $row_RsListadoRequerimiento['CODIGO_REQ'];
	$poa        	= $row_RsListadoRequerimiento['POA'];
	$poa_des    	= $row_RsListadoRequerimiento['POA_DES'];
	$subpoa     	= $row_RsListadoRequerimiento['SUBPOA'];
	$subpoa_des  	= $row_RsListadoRequerimiento['SUBPOA_DES'];
	$solicitante 	= $row_RsListadoRequerimiento['SOLICITANTE'];
	$tiposubpoa 	= $row_RsListadoRequerimiento['TIPOSUBPOA'];
	$area_des       = $row_RsListadoRequerimiento['AREA_DES'];
}


?><style type="text/css" media="all">
@import "thickbox.css";
</style>

<link rel="stylesheet" type="text/css" href="css/estilo_solicitud.css" />
<?php /*<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>*/?>
<script src="js/thickbox.js" type="text/javascript"></script>

<style type="text/css">
 .seleccionarradio{
     color: #941512;
    font-size: 9px;
    font-weight: bold;
	line-height:0.5;
 }
 .otropoa{
 font-size:13px; color:#800607; font-weight:700
 }
</style>

<script type="text/javascript">
var state="<?php echo($poa_des);?>";
    modalidadcombo = '';
$(document).ready(function(){

			$("#poa_des").autocomplete({
			source: "buscar.php?tipo=cargarpoa", 				
			minLength: 2,									
			select: function(event, ui){
					 $("#poa_des").val(ui.item.value.nombre);
					 $("#poa").val(ui.item.value.nit);
					 //subpoa();
					 event.preventDefault();
					},
			focus: function(event, ui){
					 $("#poa_des").val(ui.item.value.nombre);
					 event.preventDefault();
					}
		});	
		
    $("#li_ns").click(function(evento){
	 $("#fane1").css('display','block');
	 $("#fane2").css('display','none');
	 $("#li_ns").addClass('active');
	 $("#li_ss").removeClass('active');
	});

    $("#li_ss").click(function(evento){
	 $("#fane1").css('display','none');
	 $("#fane2").css('display','block');
	 $("#li_ss").addClass('active');
	 $("#li_ns").removeClass('active');
	});
	cargar_modalidades();
	$("#modalidad_ss").change(function(){dependencia_clasificacion();});
	/*$("#poa").change(function(){subpoa();});*/

	<?php
	if(isset($_GET['env']) && $_GET['env']=='1'){
	?>
     //setTimeout(function() {
		// try{
             //$("#modificar_cantidad").removeClass("visiblemsj");
             //$("#modificar_cantidad").addClass("no_visible");
             //$("#modificar_cantidad").text("");
			 $('#TB_overlaycantidad').addClass("TB_overlayBGcantidad");
			 //}catch(exc){
						 // if(exc.description==null){
						  //alert("excepcion "+exc.message);
						  //}else{
						   //alert("Excepcion: "+exc.description);
						  //}
						//}
			//}, 5000 );
	<?php
	}
	?>
	
	
	$('#menuinfo').click(function(e){
	 if($('#gbsfw').hasClass('gb_wa')){
        $('#gbsfw').removeClass('gb_wa');
		$('#content_gbsfw').css('z-index','-1');
	 }else{
	    $('#gbsfw').addClass('gb_wa');
	    $('#gbsfw').css('z-index','9');
		$('#content_gbsfw').css('z-index','9');
	 }
	});
	
	$('.comentdetalle').click(function(e){
	   id = $(this).attr('id');
	   id = id.split('_');
	   tb_show('Comentarios / Detalle', 'comentarios_detalle.php?codigo_detalle='+id[1]+'&estado='+$('#estado').val()+'&amp;keepThis=true&amp;TB_iframe=true&amp;height=400&amp;width=750');
	});
	
	$("#tabladetalle").on("click", ".agenerada", function(){
	  id = $(this).attr('id').split('_');
	  cod  = id[1];
	  cam  = id[2];
	  $("#trtrcreated_"+cod+"_"+cam).remove();
	});
});

function cargar_modalidades()
{
	$.get("scripts/cargar-modalidades.php", function(resultado){
		if(resultado == false)
		{
			alert("Error");
		}
		else
		{
			//$('#modalidad_ss').append(resultado);
			modalidadcombo = resultado;
			
		}
	});
}

function subpoa()
{
	var code = $("#poa").val();
	$.get("scripts/subpoa.php", { code: code },
		function(resultado)
		{
			if(resultado == false)
			{
				alert("Error");
			}
			else
			{
				$("#subpoa").attr("disabled",false);
				document.getElementById("subpoa").options.length=1;
				$('#subpoa').append(resultado);
				state = $("#poa").val();
			}
		}

	);
}

function dependencia_clasificacion()
{
	var code = $("#modalidad_ss").val();
	$.get("scripts/dependencia-clasificacion.php", { code: code },
		function(resultado)
		{
			if(resultado == false)
			{
				alert("Error");
			}
			else
			{
				$("#clasificacion_ss").attr("disabled",false);
				document.getElementById("clasificacion_ss").options.length=1;
				$('#clasificacion_ss').append(resultado);
			}
		}

	);
}

function loadclasificacion(id){ var code = $("#modalidadcreate_"+id).val();
	$.get("scripts/dependencia-clasificacion.php", { code: code },function(resultado){
	if(resultado == false){ alert("Error");}else{$("#clasificacioncreate_"+id).attr("disabled",false); document.getElementById("clasificacioncreate_"+id).options.length=1;	$('#clasificacioncreate_'+id).append(resultado);
	}});}

function validarCampos(idf){
var insert=0;
if(state!=''){
 insert=1;
}
// Validacion No seleccionado
  if(idf == "1"){

 // variables no seleccionado
 var descrip_ns=document.no_selecc.descrip_ns.value;
 var cantidad_ns=document.no_selecc.cantidad_ns.value;
 var unidad_ns=document.no_selecc.unidad_ns.value;
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
  if(unidad_ns == '')
  {
   inlineMsg('unidad_ns','debe ingresar la unidad de medida.',3);
		return false;
  }

  if(justi_ns == '')
  {
   inlineMsg('justi_ns','debe digitar la Justificacion.',3);
		return false;
  }
/*
  if(observ_ns == '')
  {
   inlineMsg('observ_ns','debe digitar la observacion.',3);
		return false;
  }*/
  <?php if($estado!=4){ ?>
   if(document.getElementById('codigodetalle_ns').value!=''){
     if(confirm('esta seguro que desea editar este detalle?')){
      document.no_selecc.action = 'solicitud_guardar.php?tipoGuardar=editar_ns&codreq='+document.getElementById('codigo_req').value+'&codigo_detalle='+document.getElementById('codigodetalle_ns').value;
	  }else{
	  return false;
	  }
   }else{
    if(confirm('esta seguro que desea guardar este detalle?')){
	<?php
	 if($poa=='' || $poa==0){
	 ?>
     document.no_selecc.action = 'solicitud_guardar.php?tipoGuardar=add_new_det&MM_UserID=<?php echo($MM_UserID); ?>&MM_Area=<?php echo($MM_Area); ?>&codreq='+document.getElementById('codigo_req').value+'&poa='+$("#poa").val()+'&subpoa='+$("#subpoa").val()+'&insert='+insert;
	 <?php 
	 }else{
	 ?>
	 document.no_selecc.action = 'solicitud_guardar.php?tipoGuardar=add_new_det&MM_UserID=<?php echo($MM_UserID); ?>&MM_Area=<?php echo($MM_Area); ?>&codreq='+document.getElementById('codigo_req').value;
	 <?php	 
	 }
	 ?>
     }else{
	 return false;
	 }
   }
   <?php
   }else{
   ?>
      if(document.getElementById('codigodetalle_ns').value!=''){
     if(confirm('esta seguro que desea editar este detalle?')){
      document.no_selecc.action = 'solicitud_guardar.php?tipoGuardar=editar_ns&codreq='+document.getElementById('codigo_req').value+'&codigo_detalle='+document.getElementById('codigodetalle_ns').value;
	  }else{
	  return false;
	  }
   }else{
    alert('no se pueden agregar detalles a este requerimiento');
   return false;
   }
   <?php
   }
   ?>
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
/*
if(observ_ss == '')
  {
   inlineMsg('observ_ss','debe ingresar la Observacion.',3);
		return false;
  }*/
   if(document.getElementById('codigodetalle_ss').value!=''){
     if(confirm('esta seguro que desea editar este detalle?')){
      document.si_selecc.action = 'solicitud_guardar.php?tipoGuardar=editar_ss&codreq='+document.getElementById('codigo_req').value+'&codigo_detalle='+document.getElementById('codigodetalle_ss').value;
	  }else{
	  return false;
	  }
	 }else{
	 if(confirm('esta seguro que desea guardar este detalle?')){
	 <?php
	 if($poa=='' || $poa==0){
	 ?>
	 document.si_selecc.action = 'solicitud_guardar.php?tipoGuardar=adicionar_ss&codreq='+document.getElementById('codigo_req').value+'&poa='+$("#poa").val()+'&subpoa='+$("#subpoa").val()+'&insert='+insert;
	 <?php
	 }else{
	 ?>
	 document.si_selecc.action = 'solicitud_guardar.php?tipoGuardar=adicionar_ss&codreq='+document.getElementById('codigo_req').value;
	 <?php
	 }
	 ?>
	 }else{
	 return false;
	 }
	}
 }
}

 function Fvolverenviar(){   
   if($("#poa").val()!=''){
	if(confirm('seguro que desea enviar este requerimiento, recuerde haber hecho todas las modificaciones solicitadas')){
     document.form_req.action = 'solicitud_guardar.php?tipoGuardar=reenviar_req&codreq='+$("#codigo_req").val();
     }else{
      return false;
     }   
	}else{
	alert("debe seleccionar el area");
	try{
		  document.getElementById('poa_des').focus();
		  document.getElementById('poa_des').select();
		}catch(e){}
		return false;
	}
 }

 function Fenviar(){   

   if($("#poa").val()!=''){
	if(confirm('seguro que desea enviar este requerimiento')){
     document.form_req.action = 'solicitud_guardar.php?tipoGuardar=enviar_req&codreq='+$("#codigo_req").val();
     }else{
      return false;
     }   
	}else{
	alert("debe seleccionar el area");
	try{
		  document.getElementById('poa_des').focus();
		  document.getElementById('poa_des').select();
		}catch(e){}
		return false;
	}
 }
 
 function subirarchivo(){
  if($("#archivo1").val()==''){
   alert('debe seleccionar el archivo que desea subir');
   return false;
  }
   if(confirm('seguro que desea subir este archivo')){
     document.form_req.action = 'solicitud_guardar.php?tipoGuardar=ArchivoReq&MM_UserID=<?php echo($MM_UserID); ?>&MM_Area=<?php echo($MM_Area); ?>&codreq='+$("#codigo_req").val();
   }else{
   return false;
   } 
 }

 function volveraListado(){
   document.form_req.action = 'home.php';
 }
 
/*
function CrearRequerimiento(){
   if(confirm('seguro que desea generaras este requerimiento')){
     document.formcrear.action = 'solicitud_guardar.php?tipoGuardar=crear_req';
	 document.formcrear.submit();
   }
}
*/

function limpiar(tipo){
  if(tipo==0){
		  $('#codigodetalle_ns').val('');
		  $('#descrip_ns').val('');
		  $('#cantidad_ns').val('');
		  $('#unidad_ns').val('');
		  $('#justi_ns').val('');
		  $('#observ_ns').val('');
		  $('#btnsub_ns').val('Guardar');
    }
 if(tipo==1){
 		  $('#codigodetalle_ss').val('');
		  $('#modalidad_ss').val('');
		  //$('#codigodetalle_ss').val(data[1]);
		  $('#descripcion_ss').val('');
		  $('#cantidad_ss').val('');
		  $('#justi_ss').val('');
		  $('#observ_ss').val('');
		  $('#clasificacion_ss').val('');
          $('#btnsub_ss').val('Guardar');

 }
}

function FeditarDet(cod,tipo){
      var date = new Date();
	  var timestamp = date.getTime();
	  var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=CargarDatos&time="+timestamp+"&codigo_detalle="+cod+"&tipo="+tipo);
	  if(v_dato!=''){
	  //alert(v_dato);
	   data=v_dato.split('|');
	   if(data[0] !='' && data[0]!='none'){
	      if(tipo== 0 || tipo == -1){
		  //$('#li_ns').addClass('active');
		  //$('#li_ss').removeClass('active');
		  //$('#fane1').css('display','block');
		  //$('#fane2').css('display','none');
		  $('#codigodetalle_ns').val(data[0]);
		  $('#descrip_ns').val(data[3]);
		  $('#cantidad_ns').val(data[4]);
		  $('#justi_ns').val(data[5]);
		  $('#observ_ns').val(data[6]);
		  $('#unidad_ns').val(data[11]);
		  $('#btnsub_ns').val('Editar');
		  	 try{
				  document.getElementById('cantidad_ns').focus();
				  document.getElementById('cantidad_ns').select();
				}catch(e){}
				return false;
		  }
		  if(tipo==1){
			$('#li_ss').addClass('active');
		    $('#li_ns').removeClass('active');
			$('#fane1').css('display','none');
			$('#fane2').css('display','block');
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
		   		  	 try{
				  document.getElementById('cantidad_ss').focus();
				  document.getElementById('cantidad_ss').select();
				}catch(e){}
				return false;
		  }
	   }
	  }
}

function FverDet(cod,tipo){
      var date = new Date();
	  var timestamp = date.getTime();
	  var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=CargarDatos&time="+timestamp+"&codigo_detalle="+cod+"&tipo="+tipo);
	  if(v_dato!=''){
	  //alert(v_dato);
	   data=v_dato.split('|');
	   if(data[0] !='' && data[0]!='none'){
	     if(tipo==0){
		  $('#li_ns').addClass('active');
		  $('#li_ss').removeClass('active');
		  $('#fane1').css('display','block');
		  $('#fane2').css('display','none');
		  $('#codigodetalle_ns').val(data[0]);
		  $('#descrip_ns').val(data[3]);
		  $('#cantidad_ns').val(data[4]);
		  $('#justi_ns').val(data[5]);
		  $('#observ_ns').val(data[6]);
		  $('#btnsub_ns').val('Editar');
		  	 try{
				  document.getElementById('cantidad_ns').focus();
				  document.getElementById('cantidad_ns').select();
				}catch(e){}
				return false;
		  }
		  if(tipo==1){
			$('#li_ss').addClass('active');
		    $('#li_ns').removeClass('active');
			$('#fane1').css('display','none');
			$('#fane2').css('display','block');
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
		   		  	 try{
				  document.getElementById('cantidad_ss').focus();
				  document.getElementById('cantidad_ss').select();
				}catch(e){}
				return false;
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
function cerrar_cantidad(){
 document.getElementById('TB_overlaycantidad').className="";
 document.getElementById('modificar_cantidad').className="no_visible";
}
function poa(){
 if($("#poa").val()==''){
  alert('debe ingresar el poa');
  return;
 }
 if($("#subpoa").val()==''){
  alert('debe ingresar el subpoa');
  return;
 }
if(confirm('seguro que quiere almacenar estos datos?')){ 
 var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=GuardarPoa&codreq=<?php echo($codigo_requerimiento);?>&poa="+$("#poa").val()+"&subpoa="+$("#subpoa").val());
 if(v_dato!=''){
    alert('registro almacenado exitosamente');
 }
}
}

function mostrarTickbox(cod){
//tipodetalle = document.getElementById('selecttipo_'+cod).value;
tipodetalle = -1;
 //modalidad = '';
 modalidad = -1;
 //clasificacion = '';
 clasificacion = -1;
 if(tipodetalle==''){
  	alert('debe seleccionar el tipo de detalle');
    infocus('selecttipo_'+cod);
	return false;
 }
 if(tipodetalle==1){
   modalidad     = $("#modalidadcreate_"+cod).val();
   clasificacion = $("#clasificacioncreate_"+cod).val();
   if(modalidad == '' || clasificacion == ''){
    alert('debe ingresar modalidad y clasificacion para este detalle de tipo seleccionado');
	return false;
   }
 }
 tb_show('Justificar / Devolver', 'aprobar_detalle.php?codigo_detalle='+cod+'&modalidad='+modalidad+'&clasificacion='+clasificacion+'&tipodetalle='+tipodetalle+'&amp;keepThis=true&amp;TB_iframe=true&amp;height=200&amp;width=270');
}

function actualizaraprobado(coddet,aprobado){
 if(aprobado==1){
	document.getElementById('tdaprob_'+coddet).className="";
	$("#tdaprob_"+coddet).addClass("verde");
 }
 if(aprobado==2){
	document.getElementById('tdaprob_'+coddet).className="";
	$("#tdaprob_"+coddet).addClass("rojo");
 }
 if(aprobado==3){
	document.getElementById('tdaprob_'+coddet).className="";
	$("#tdaprob_"+coddet).addClass("gris");
 }
 
 if(aprobado==0){
	document.getElementById('tdaprob_'+coddet).className="";
	$("#tdaprob_"+coddet).addClass("amarillo");
 }
}

function actualizarcantComent(coddet,cantidad){
 $("#commentdet_"+coddet+">span").text(cantidad);
}

function volverEditar(){
 $("#postmensajerecibir").html("");
 $("#botonesrecibir").css("display","block");
}

function Recibir(par){
 all=0;
 //cargar mensaje
 if(par==3){
  msj='recibido';
 }
 if(par==4){
  msj='No Recibido';
 }
 
	   //if(all==0){
	  if(par==3){
		   var v_comprobar = getDataServer("tipo_guardar.php","?tipoGuardar=TodosAprobDetalle&parm="+par+"&todosrecibido="+all+"&codreq=<?php echo($codigo_requerimiento);?>");
			 if(v_comprobar!='' && v_comprobar!='none' ){
			  if(v_comprobar==1){
			   alert('No hay detalles aprobados');
			   return;
			  }
			  if(v_comprobar==2){
			   alert('debe marcar algun evento en los detalles');
			   return;
			  }
			 }
	     }
	  

	 if(par==4){
	  
		 var v_comprobar = getDataServer("tipo_guardar.php","?tipoGuardar=MarcarNoRecibido&parm="+par+"&codreq=<?php echo($codigo_requerimiento);?>");
		 if(v_comprobar!=''){
		  if(v_comprobar == 'none'){
		   alert('para ejecutar esta opcion marque todos como devueltos');
		   return;
		  }
		 }
	 }
	 
   if(confirm('hola..¿ estas seguro que quiere pasar este requerimiento a  '+msj+'?')){
     var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=RecibirRequ&parm="+par+"&todosrecibido="+all+"&codreq=<?php echo($codigo_requerimiento);?>");
	 if(v_dato!=''){
	   if(v_dato>0){
	    $("#botonesrecibir").css("display","none");
		$("#postmensajerecibir").html("Se ha actualizado el requerimiento a estado "+msj+" correctamente si desea volver a editar click <a href='javascript:volverEditar();'>aqui</a>");
	    alert('se ha pasado el requerimiento a estado '+msj+' correctamente');
		location.href="home.php?page=requerimientos_lista";
	   }
	 }
    }
}

function AprobarReq(){
	var devueltos =0;
	
	$('.rojo').each(function(index){
	 devueltos =1;
	});
	
	if(devueltos==1){
	 /*alert('El requerimiento no se puede aprobar porque hay detalles devueltos');
	 return false;*/
	}
	arraydetalles = [];	
	<?php 
	if(count($arraydetalles)>0){
	 for($k=0; $k<count($arraydetalles); $k++){
	 
	?>
	 arraydetalles[<?php echo($k);?>] = <?php echo($arraydetalles[$k]);?>;
	<?php
	  }
	}
	?>
	 validapoasubpoa = 0;
	 validaotros     = 0;
	if(arraydetalles.length>0){
	 for(k=0; k<arraydetalles.length; k++){
		if($('#tdaprob_'+arraydetalles[k]).hasClass('verde')){
		 if(document.getElementById('otro_'+arraydetalles[k]).checked==true){
		  if($("#otropoa_"+arraydetalles[k]).val()==''){
		   validaotros= validaotros+1;
		  }
		 }else{
		  if($("#poadetalle_"+arraydetalles[k]).val()=='' || $("#subpoadetalle_"+arraydetalles[k]).val()==''){
		   validapoasubpoa= validapoasubpoa+1;
		  }		 
		 }
	  } 
	}
	if(validaotros>0){
	 alert('ha marcado detalles para ingresar otro poa, por favor ingrese los datos');
	 return false;
	}
	if(validapoasubpoa>0){
	 alert('debe ingresar los valores poa y subpoa de los detalles requeridos');
	 return false;
	}
   	
  var v_comprobar = getDataServer("tipo_guardar.php","?tipoGuardar=ComprobarNotCero&codreq=<?php echo($codigo_requerimiento);?>");
   if(v_comprobar!=''){
      if(v_comprobar=='si'){
	    alert('debe marcar alguna accion de la columna aprobo para continuar');
		return false;
	  }
   /*  
	  if(v_comprobar=='none'){
	   if(confirm("seguro que desea aprobar este requerimiento?")){
	       var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=AprobarReq&codreq=<?php echo($codigo_requerimiento);?>");
		   if(v_dato!=''){
			 if(v_dato>0){
			   alert('el requerimiento se ha aprobado correctamente');
			   location.href="home.php?page=requerimientos_lista";
			  }
           } 
	  }else{
	   return false;
	  }
	 }
    }
	*/
	
	if(v_comprobar=='none'){
	   if(confirm("recuerde marcar las opciones necesarias para cada detalle, seguro que desea aprobar este requerimiento?")){
	    document.form_lista.action="solicitud_guardar.php?codreq=<?php echo($codigo_requerimiento);?>&tipoGuardar=AprobarRequerimiento&aprob_devuelto"+devueltos;
	   }else{
	    return false;
	   }
	}

}
}
}

function archivocarga(a){

}

function ShowTabs(){
 var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=mostrarTab&subpoa="+$("#subpoa").val());
 if(v_dato!=''){
   if(v_dato==1){
    $("#fane2").css("display","block");
    $("#fane1").css("display","none");
    $("#li_ss").css("display","block");
	$("#li_ss").addClass("active");
    $("#li_ns").css("display","none");
	$("#li_ns").removeClass("active");
   }
   if(v_dato==2){
    $("#fane1").css("display","block");
    $("#fane2").css("display","none");
	$("#li_ss").css("display","none");
	$("#li_ns").css("display","block");
	$("#li_ns").addClass("active");
	$("#li_ss").css("display","none");
	$("#li_ss").removeClass("active");
   } 
   if(v_dato==3){
 	$("#li_ss").css("display","block");
	$("#li_ns").css("display","block");  
	$("#fane1").css("display","none");
    $("#fane2").css("display","none");
	$("#li_ss").removeClass("active");
	$("#li_ns").removeClass("active");
   }   
 }
}

function CancelarDetalle(cod){
 /*var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=CancelarDetalle&coddet="+cod);
  if(v_dato>0){
  actualizaraprobado(cod,'2'); 
  alert('detalle cancelado exitosamente');
 }
 */
 //tipodetalle = document.getElementById('selecttipo_'+cod).value;
tipodetalle = -1;
 //modalidad = '';
 modalidad = -1;
 //clasificacion = '';
 clasificacion = -1;
 if(tipodetalle==''){
  	alert('debe seleccionar el tipo de detalle');
    infocus('selecttipo_'+cod);
	return false;
 }
 if(tipodetalle==1){
   modalidad     = $("#modalidadcreate_"+cod).val();
   clasificacion = $("#clasificacioncreate_"+cod).val();
   if(modalidad == '' || clasificacion == ''){
    alert('debe ingresar modalidad y clasificacion para este detalle de tipo seleccionado');
	return false;
   }
 }
 tb_show('Justificar / Cancelar', 'cancelar_detalle.php?estadoreq=<?php echo($estado);?>&codigo_detalle='+cod+'&modalidad='+modalidad+'&clasificacion='+clasificacion+'&tipodetalle='+tipodetalle+'&amp;keepThis=true&amp;TB_iframe=true&amp;height=200&amp;width=270');
}

function infocus(campo){
	try{
		  document.getElementById(campo).focus();
		  document.getElementById(campo).select();
		}catch(e){}
		//return false; 

}

function AprobarDetalle(cod){
 //tipodetalle = document.getElementById('selecttipo_'+cod).value;
 tipodetalle = -1;
 //modalidad = '';
 modalidad = -1;
 //clasificacion = '';
 clasificacion = -1;
 if(tipodetalle==''){
  	alert('debe seleccionar el tipo de detalle');
    infocus('selecttipo_'+cod);
	return false;
 }
 if(tipodetalle==1){
   modalidad     = $("#modalidadcreate_"+cod).val();
   clasificacion = $("#clasificacioncreate_"+cod).val();
   if(modalidad == '' || clasificacion == ''){
    alert('debe ingresar modalidad y clasificacion para este detalle de tipo seleccionado');
	return false;
   }
 }
 
 var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=AprobarDetalle&coddet="+cod+"&modalidad="+modalidad+"&clasificacion="+clasificacion+"&tipodetalle="+tipodetalle);
 //alert(v_dato);
 if(v_dato>0){
  actualizaraprobado(cod,'1'); 
 }
}
<?php echo "var cadenaDatos = '" . json_encode($arr) . "';"; ?>

function crearfila(cod,campo){
 title='';
 tr='';
 desc='';
try{
var info = JSON.parse(cadenaDatos);
 if(campo=='obs'){
   title="Observaciones";
	for(var i = 0; i < info.length; i++ ){
	  if(info[i].ID==cod){
	   desc = info[i].OBSERVACION;
	  }
	}
 }
 if(campo=='just'){
   title="Justificacion";
	for(var i = 0; i < info.length; i++ ){
	  if(info[i].ID==cod){
	   desc = info[i].JUSTIFICACION;
	  }
	}
 }

 if(campo=='desc'){
   title="Descripcion";
	for(var i = 0; i < info.length; i++ ){
	  if(info[i].ID==cod){
	   desc = info[i].DESCRIPCION;
	  }
	}
 }
 
  var SADDREQ= document.getElementById('trtrcreated_'+cod+'_'+campo);
  //alert(SADDREQ);
  if(SADDREQ==null && cod!=''){
     tr = '<tr id="trtrcreated_'+cod+'_'+campo+'" class=""><td class="SLAB trtitle">'+title+'</td><td colspan="12"><p style="text-align:justify;">'+desc+'</p><br><a class="agenerada" id="a_'+cod+'_'+campo+'" onclick="remove(\'a_'+cod+'_'+campo+'\');">Cerrar</a></td></tr>';
     $("#trtr_"+cod).closest("tr").after(tr);
   }
 }catch(ex) {
   cargardata(cod,campo);
  }
}
function remove(cod){
	if(cod!=''){
	 cod = cod.split("a_");
	 //alert(cod[1]);
	 $('#trtrcreated_'+cod[1]).remove();
	 
	}
}

function cargardata(cod,campo){
 var SADDREQ= document.getElementById('trtrcreated_'+cod+'_'+campo);
  //alert(SADDREQ);
  if(SADDREQ==null && cod!=''){
  
  var date = new Date();
  var timestamp = date.getTime();
  var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=CargarCampos&time="+timestamp+"&codigo_detalle="+cod+"&campo="+campo);
  if(v_dato!= '' && v_dato!='none'){
    data = v_dato.split('|');
	descripcion   = data[0];
	justificacion = data[1];
	observacion   = data[2];
	
   if(descripcion!=''){
     tr = '<tr id="trtrcreated_'+cod+'_desc" class=""><td class="SLAB trtitle">Descripcion</td><td colspan="12"><p style="text-align:justify;">'+descripcion+'</p><br><a class="agenerada" id="a_'+cod+'_desc" onclick="remove(\'a_'+cod+'_desc\');">Cerrar</a></td></tr>';
     $("#trtr_"+cod).closest("tr").after(tr);
   }
   if(justificacion!=''){
     tr = '<tr id="trtrcreated_'+cod+'_just" class=""><td class="SLAB trtitle">Justificacion</td><td colspan="12"><p style="text-align:justify;">'+justificacion+'</p><br><a class="agenerada" id="a_'+cod+'_just" onclick="remove(\'a_'+cod+'_just\');">Cerrar</a></td></tr>';
     $("#trtr_"+cod).closest("tr").after(tr);
   }
   if(observacion!=''){
     tr = '<tr id="trtrcreated_'+cod+'_obs" class=""><td class="SLAB trtitle">Observacion</td><td colspan="12"><p style="text-align:justify;">'+observacion+'</p><br><a class="agenerada" id="a_'+cod+'_obs" onclick="remove(\'a_'+cod+'_obs\');">Cerrar</a></td></tr>';
     $("#trtr_"+cod).closest("tr").after(tr);
   } 
  
  }
 }
}

function createcombomodalidad(id){
<?php // 1 seleccionado 0 no seleccionado ?>
sel='';
if(id!=''){
sel = $("#selecttipo_"+id).val();
}
 if(modalidadcombo!=''){
   if(sel==1){
    combocr = '<div id="dinamic_'+id+'"><select name="modalidadcreate_'+id+'" id="modalidadcreate_'+id+'" class="styled-select" onchange="loadclasificacion(\''+id+'\')"><option value="">Seleccione...</option>'+modalidadcombo+'</select><select name="clasificacioncreate_'+id+'" id="clasificacioncreate_'+id+'" class="styled-select"><option value="">Seleccione...</option></select></div>';
	existecombo = document.getElementById('dinamic_'+id);
	if(existecombo==null){
	 $("#tableradio_"+id).append(combocr);
	}else{
	 $("#dinamic_"+id).css("display","block");
	}
   }
   if(sel==0){
	existecombo = document.getElementById('dinamic_'+id);
	if(existecombo==null){
	 //$("#tableradio_"+id).append(combocr);
	}else{
	 $("#dinamic_"+id).css("display","none");
	}
   }
 }
}
function FmodificarCantidad(cod){
      var date = new Date();
	  var timestamp = date.getTime();
	  if(document.getElementById('inpcantidad_'+cod).value != document.getElementById('inpcantidadcopia_'+cod).value){
		  var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=ModificarCantidad&estado_requerimiento=<?php echo($estado);?>&time="+timestamp+"&codigo_detalle="+cod+"&newcantidad="+document.getElementById('inpcantidad_'+cod).value);
		  div = "<div id='ajax_notificacion' class='ajax_notificacion'><div class='ajax_tittle'><span>Registro actualizado exitosamente</span></div></div>";
		  if(v_dato==1){
		   document.getElementById('inpcantidadcopia_'+cod).value = document.getElementById('inpcantidad_'+cod).value
		   $('body').prepend(div);
		   deletediv('ajax_notificacion',3700);
		  }
	  }
}

function Fmodificarpoa(cod){
       var date = new Date();
	  var timestamp = date.getTime();
 poa    = document.getElementById('poadetalle_'+cod).value;
 subpoa = document.getElementById('subpoadetalle_'+cod).value;
	  if(poa!='' && subpoa !=''){
        var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=ModificarPoaSubpoa&estado_requerimiento=<?php echo($estado);?>&time="+timestamp+"&codigo_detalle="+cod+"&poa="+poa+"&subpoa="+subpoa);	 
        //alert(v_dato);	
         if(v_dato==1){
         /*div = "<div id='ajax_notificacion' class='ajax_notificacion'><div class='ajax_tittle'><span>Registro actualizado exitosamente</span></div></div>";
		   $('body').prepend(div);
		   deletediv('ajax_notificacion',3100);		*/ 
		   messageAjax('',3100);
		 }else{
		   messageAjax('No se realizaron cambios',2100);
		 }		
	  }else{
	  alert('debe ingresar el poa y subpoa para guardar');
	  }
 }

function deletediv(div, time){
     setTimeout(function() {
		 try{
             $("#"+div).remove();
			 }catch(exc){
			}
			}, time );
        }

function Otropoa(cod){
	if(document.getElementById('otro_'+cod).checked==true){
	 $("#divpoadeta_"+cod).css("display","none");
	 $("#divotropoa_"+cod).css("display","block");
	}else{
	 $("#divpoadeta_"+cod).css("display","block");
	 $("#divotropoa_"+cod).css("display","none");
	}
}   
function FrequiereOtro(cod){
       var date = new Date();
	  var timestamp = date.getTime();
      otro    = document.getElementById('otropoa_'+cod).value;
	  if(otro!=''){
        var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=ModificarOtro&estado_requerimiento=<?php echo($estado);?>&time="+timestamp+"&codigo_detalle="+cod+"&otro="+otro);	 
		if(v_dato==1){
         messageAjax('',3100);			
		}else{
		 messageAjax('',2500);
		}
	   }else{
	   alert('debe ingresar el campo otros');
	   	try{
		  document.getElementById('otropoa_'+cod).focus();
		  document.getElementById('otropoa_'+cod).select();
		}catch(e){}
		return false;
	   }
        
} 
function messageAjax(msg,timer){
	if(msg==''){
	 msg='Registro actualizado exitosamente';
	}
  div = "<div id='ajax_notificacion' class='ajax_notificacion'><div class='ajax_tittle'><span>"+msg+"</span></div></div>";
		   $('body').prepend(div);
		   deletediv('ajax_notificacion',timer);		 
}


 

function FcorregirDet(cod,tipo){
      var date = new Date();
	  var timestamp = date.getTime();
	  var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=CorregirDet&time="+timestamp+"&codigo_detalle="+cod+"&tipo="+tipo);
	  if(v_dato!=''){
	 alert("Se han guardado sus cambios");
	  location.reload();
	 return false;
}}
  
function AprobarDetalleDirec(cod_det){

poa        = document.getElementById('poadetalle_'+cod_det).value;
subpoa     = document.getElementById('subpoadetalle_'+cod_det).value;
otro   	   = document.getElementById('otropoa_'+cod_det).value;
vcant      = document.getElementById('inpcantidad_'+cod_det).value;

//valida la cantidad
var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=Validar_cantidad&codigo_detalle="+cod_det+"&cantidad="+vcant);
if(v_dato!='' && v_dato=='1'){
	 alert("Se modificaran la cantidad");
	 return false;
}

if(document.getElementById('otro_'+cod_det).checked==true){
if(otro ==''){alert("Debe ingresar algun otro tipo de centro de costo "); return false;}
var v_dato2 = getDataServer("tipo_guardar.php","?tipoGuardar=AprobarDetalle&coddet="+cod_det+"&otropoa="+otro+"&cantidad="+vcant);
}

if(document.getElementById('otro_'+cod_det).checked==false){
if(poa ==''){alert("debe ingresar poa"); return false;}
if(subpoa ==''){alert("debe ingresar subpoa"); return false;}
var v_dato2 = getDataServer("tipo_guardar.php","?tipoGuardar=AprobarDetalle&coddet="+cod_det+"&poa="+poa+"&subpoa="+subpoa+"&cantidad="+vcant);
}

 if(v_dato2>0){
  actualizaraprobado(cod_det,'1'); 
 }



}


function ElimArchivo(cod_arch){

var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=eliminar_archivo&codigo_arch="+cod_arch);
if(v_dato!='' && v_dato=='1'){
	 alert("Se a eliminado el archivo");
	 location.reload();
	 return false;
}
}
 
</script>
<div id="modificar_cantidad" style="margin-top:8%;" class="<?php
	if(isset($_GET['env']) && $_GET['env']=='1'){
	 echo("visiblemsj");
	}else{ echo('no_visible');} ?>">
    Su Requerimiento fue Creado correctamente <br>codigo: <b><?php echo($codigo_req);?></b>
	<br><br><br>
	<input type="button" name="nbt1" id="nbt1" class="button3" value="Nuevo Requerimiento" onclick="CrearRequerimiento();">
	<input type="button" name="nbt2" id="nbt2" class="button3" style="width:140px;" value="Salir" onclick="location.href='home.php'">
</div>
<div id="pagina" style="background:#F3F2F2; margin-top: -15px; min-width:1001px; width:100%; padding-bottom:30px;">
<input type="hidden" name="codigo_req" id="codigo_req" value="<?php echo($codigo_requerimiento);?>">
<input type="hidden" name="estado" id="estado" value="<?php echo($estado);?>">
<div id="titulo">
 <table width="1000" border="0" class="menuoptions" style="background:#F5F5F5; min-width: 1000px; width: 100%;">
  <tr class="">
    <td align="left" width="110" style="color:#CB5100;"><span id="menuinfo">Informaci&oacute;n&nbsp;&nbsp;&nbsp;</span></td>  
    
	<td></td>
    <td></td>	
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
 </table>
<div id="content_gbsfw" style="width:500px; overflow:auto; position:absolute; height:300px; left:80px; z-index:-1">
   <div id="gbsfw" class="gb_z" guidedhelpid="gbifp" style="width: 480px; height: 265px; min-width: 480px;">
    <div class="fc">
 <table width="440" border="0" class="caja2">
  <tr class="SLABCAJA">
    <td>Fecha Creaci&oacute;n:</td>
	<td><?php echo($fecha_creacion);?></td>
   </tr> 
   <?php 
   if($estado>=2){
   ?>
    <tr class="SLABCAJA">
    <td>Fecha Envio:</td>
	<td><?php echo($fecha_envio);?></td>
   </tr>    
   <?php 
   }
   ?>
      <?php 
   if($estado>=3){
   ?>
    <tr class="SLABCAJA">
    <td>Fecha No Recibido:</td>
	<td><?php echo($fecha_envio);?></td>
   </tr>    
   <?php 
   }
   ?>
      <?php 
   if($estado>=4){
   ?>
    <tr class="SLABCAJA">
    <td>Fecha Recibido:</td>
	<td><?php echo($fecha_envio);?></td>
   </tr>    
   <?php 
   }
   ?>
   <tr>
     <td>C&oacute;digo Requerimiento:</td>
     <td><b><?php echo($codigo_req);?></b></td>
   </tr>
	<tr>
	<td>Solicitante:</td>
	
	<td><?php echo($solicitante);?></td>
	</tr>
	<tr>
    <td>Area:</td>
	<td><?php echo($area_des);?></td>
	</tr>
	<tr>
    <td>Estado:</td>
	<td><?php echo($estado_des);?></td>
	</tr>
  </tr>
 </table>      
    </div>
</div>
   </div>
</div>
<div id="wrapper">
<?php
if($codigo_requerimiento!=''){
?>
	<div id="header">
	<?php /*SOLICITUD DE REQUERIMIENTO-FECHA:  */?>
	</div>
	<div id="container">
		<?php /*<div id="side-a">*/?>

<div id="sidebar_left">
   <table id="tbpoa" border="0" width="450" style="margin-left:20px;">
     <tr>
	  <td><br></td>
	 </tr>
	 <?php
	 if($codigo_req!=''){
	 ?>
	 <tr>
	   <td class="SLAB2">Requerimiento: </td>
	   <td><b><?php echo($codigo_req);?></b></td>
	 </tr>
	 <?php
	 }
	 ?>
	 <?php
	 if($poa=='0' && ($estado==1 || $estado == '')){
	 ?>
     <tr>
	   <td width="80" class="SLAB2"></td>
	   <td>
        <input type="hidden" name="poa_des" readonly id="poa_des" value="<?php echo($MM_Areades);?>" size="34">
        <input type="hidden" name="poa" id="poa" readonly value="<?php echo($MM_Area);?>" readonly size="3">
	   </td>
	  </tr>
	  <tr>
	  <?php 
	   /*
	  ?>
	   <td class="SLAB2">SUBPOA</td>
	   <td>
	    <select id="subpoa" name="subpoa" class="styled-select" onchange="ShowTabs();">
						<option value="">Selecciona Uno...</option>
						<?php
						  if($subpoa!=''){
						  ?>
						  <option value="<?php echo($subpoa);?>" selected><?php echo($subpoa_des);?></option>
						  <?php
						  }
						 ?>
					</select>
	   </td>
	   <?php
	    */
	   ?>
	 </tr>
	 <?php
	 }
	 ?>
	 <tr>
	   <td><br></td>
	 </tr>
   </table>
   <?php 
	if($estado=='' || $estado == 1 || $estado==3 && $MM_RolID == 4 || $estado==4 || $estado==5 && $MM_RolID == 4 ){
	?>
	 <ul id="tabify_menu" class="menu_tab" style="margin: 0;">
      <?php
	   $showns='none';
	   $showss="none";
	   $showns1="none";
	   $showss1="none";
	   $activens='';
	   $activess='';
	   if($tiposubpoa==2){
	   $showns='block';
	   $activens='active';
	   $showns1="block";
	   }
	   if($tiposubpoa==1){
	   $showss="block";
	   $activess='active';
	   $showss1="block";
	   }
	   if($tiposubpoa == 3){
	   $showns='block';
	   $showss='block';
	   $activens='';
	   $activess='';
	   $showns1="none";
	   $showss1="none";
	   }
	  ?>
    </ul>
	
	<div id="fane1" class="tab_content">
		    <form action="" id="no_selecc" name="no_selecc" method="post" >
			<table id="tabla_noseleccionados" width="400" >
			<tr>
			<td colspan="2" align="center" class="Titulo1">
			 DETALLE
			</td>
			</tr>
			<tr>
			<td class="SLAB2">Cantidad:
			</td>
			<td>
			<input type="text" name="cantidad_ns" size="8" id="cantidad_ns" value="" class="">
			</td>
			</tr>
			<tr>
			<td class="SLAB2">U/Medida:
			</td>
			<td>
			    <select name="unidad_ns" id="unidad_ns" class="styled-select"  ><option value="">Seleccione...</option>
		  <?php
               if(count($arrayundmedida) >0){
            for($p=1; $p<=count($arrayundmedida); $p++){
			     
			?>
			<option value="<?php echo($p);?>" <?php if($p==$row_RsListadoDeta_Requ['UNIDAD_MEDIDA']){ echo('selected');} ?> ><?php echo($arrayundmedida[$p]);?></option>
			<?php
			  }
		  }
		  ?>
		  </select>
			</td>
			</tr>
			<tr>
			<td class="SLAB2"> Descripcion:
			</td>
			<td>
            <textarea type="text" name="descrip_ns" id="descrip_ns" value="" rows="1" cols="40"class=""></textarea>
			</td>
			</tr>
			<tr>
			<td class="SLAB2">Justificacion:
			</td>
			<td>
			<textarea type="text" name="justi_ns" id="justi_ns" rows="2" cols="40" value="" class=""></textarea>
			</td>
			</tr>
			<tr>
			<td class="SLAB2">Observaciones:
			</td>
			<td>
			<textarea type="text" name="observ_ns" id="observ_ns"rows="2" cols="40" value="" class=""></textarea>
			</td>
			</tr>
			<tr>
			<td colspan="6" align="center">
			 <div id="botonessub">
				 <input type="hidden" name="codigodetalle_ns" id="codigodetalle_ns" value="">
				 <?php if (($estado==1 || $estado ==3|| $estado ==4 ||$estado ==5) && $MM_RolID!= 2){ 
				    $infoboton = "A&ntilde;adir";
					if($estado ==3 ||$estado ==4 ||$estado ==5 ){
					 $infoboton = "Editar";
					}
				 ?>
				 <input type="submit" class="button2" id="btnsub_ns" value="<?php echo($infoboton);?>"  onclick="return validarCampos('1'); "/>
				 <input type="button" class="button2" name="limpiar_ns" value="Limpiar" onclick="limpiar('0');">
				 <?php
				 }
				 ?>
			 </div>
			</td>
			</tr>
			</table>
			</form>
	</div>
    <div id="fane2" class="tab_content" style="display:none<?php //echo($showss1);?>;">
      	<form action="" id="si_selecc" name="si_selecc" method="post" style="background:#F2F2F7">
			<table class="" id="tabla_seleccionados">
            <tr>
			<td class="Titulo1" colspan="4" align="center">SELECCIONADOS</td>
			</tr>
				<tr><td class="SLAB">Modalidad:</td>
					<td><select id="modalidad_ss" name="modalidad_ss" class="styled-select">
						<option value="">Selecciona Uno...</option>
					</select></td>
				</tr>

				<tr><td class="SLAB">clasificacion:</td>
				<td><select id="clasificacion_ss" name="clasificacion_ss" class="styled-select">
						<option value="">Selecciona Uno...</option>
					</select></td>
				</tr>
            <tr>
            <tr>
				<td class="SLAB">Cantidad:
				</td>
				<td>
				<input type="text" name="cantidad_ss" size="8" id="cantidad_ss" value="" class="">
				</td>
			</tr>
			<tr>
			 <td class="SLAB">Descripci&oacute;n:</td>
			 <td><textarea type="text" name="descripcion_ss" id="descripcion_ss" rows="2" cols="40"  class=""></textarea></td>
			</tr>
			<tr>
			<td class="SLAB">Justificacion:
			</td>
			<td>
			<textarea type="text" name="justi_ss" id="justi_ss" rows="2" cols="40"  class=""></textarea>
			</td>
			</tr>
			<tr>
			<td class="SLAB">Observaciones:
			</td>
			<td>
			<textarea type="text" name="observ_ss" id="observ_ss"rows="2" cols="40"  class=""></textarea>
			</td>
			</tr>
			<tr>
			<td colspan="6" align="center">
			 <input type="hidden" name="codigodetalle_ss" id="codigodetalle_ss" value="">
			 <?php if ($estado==1 && $_SESSION['MM_RolID'] ==2){ ?>
			 <input class="button2" type="submit" value="Añadir" id="btnsub_ss"  onclick="return validarCampos('2'); "/>
			 <input class="button2" type="button" name="limpiar_ss" value="Limpiar" onclick="limpiar('1');">
			 <?php
			 }
			 ?>
			</td>
			</tr>
			</table>
			</form>
    </div>
	<?php
	}
	?>
</div>
	 <?php /*</div>*/?>



	</div>

</body>
</div>
<form name="form_lista" id="form_lista" action="" method="post">
  <table class="bordered" id="tabladetalle"  style="clear:both; padding-bottom:20px; margin-top:30px; min-width:950px; width:100%">

   <thead>
    <tr>
	  <th colspan="20">	<div id="footer">
		<span>Listado detalle</span>
	</div></th>
	</tr>
    <tr class="TituloDetalles">
        <th></th>
        <th>#</th>
        <th>Descripci&oacute;n</th>
        <th width="15">Cant</th>
		<th width="15">Und</th>
		<?php
		  if($estado!='' && $estado!='1' && $_SESSION['MM_RolID'] != 4){
		?>
        <th width="110" style="font-size:11px;">Aprobo <?php /*if($estado==2 && $_SESSION['MM_RolID']== 2 && $totalRows_RsListadoDeta_Requ>1){ ?>&nbsp;<br>recibir Todos<input type="checkbox" name="check_todos" id="check_todos"><?php } */ ?></th>
		<?php
		  }
		?>
		<?php if($estado== -2 && $_SESSION['MM_RolID']== 2){
		?><th>Tipo</th>
		<?php 
		}
		?>
		<?php if($estado>=3){
		?><th>Poa</th>
		<?php 
		}
		?>
        <th>Justificacion</th>
		<th>Observaci&oacute;n</th>
		<?php
		if($_SESSION['MM_RolID']== 3){
		?>
		<th>Presupuesto</th>
		<?php 
		}
		?>
		<th>&nbsp;</th>
    </tr>
    </thead>
	<?php
$i = 0;
	if ($totalRows_RsListadoDeta_Requ > 0) { // recorrer si no esta vacia
     do {
	 $i++;
	  $estilo="SB";
	  if($i%2==0){
	  $estilo="SB";
	  }
	  $claseaprobado='amarillo';
	  if($row_RsListadoDeta_Requ['APROBADO']==1){
	  $claseaprobado='verde';  
	  }
	  if($row_RsListadoDeta_Requ['APROBADO']==2){
	  $claseaprobado='rojo';	  
	  }
	  if($row_RsListadoDeta_Requ['APROBADO']==3){
	  $claseaprobado='gris';  
	  }
	  if($estado=='' || $estado=='1' ){
	   $claseaprobado = '';
	  }
	 ?>

    <tr class="<?php echo($estilo);?>" id="trtr_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>">
        <td width="<?php if($estado==3){ echo('150');}else{ echo('70'); } ?>">
		  <div class="actiondetalle">
			<?php if ( $estado==1 || $estado==4 || $estado==3 && $_SESSION['MM_RolID'] == 4 || $estado==5 && $_SESSION['MM_RolID'] == 4  ){ 
            
			if($row_RsListadoDeta_Requ['APROBADO']==2)
			{
			?>
			<div onclick="FeditarDet('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>','<?php echo($row_RsListadoDeta_Requ['TIPO']); ?>');"><span><img src="imagenes/b_edit.png" width="16">&nbsp;Editar</span></div>
			
			<div onclick="FcorregirDet('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>','<?php echo($row_RsListadoDeta_Requ['TIPO']); ?>');"><span><img src="imagenes/save.png" width="16">&nbsp;Corregir</span></div>
			
			<?php
			}else if($estado==1){ ?>
			<div onclick="FeditarDet('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>','<?php echo($row_RsListadoDeta_Requ['TIPO']); ?>');"><span><img src="imagenes/b_edit.png" width="16">&nbsp;Editar</span></div>
			<?php }
			  if($estado==1){
			?>
			 <div onclick="FdeleteDet('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>','<?php echo($row_RsListadoDeta_Requ['TIPO']); ?>');"><span><img src="imagenes/delete.jpg" width="16" >&nbsp;Eliminar</span></div>
			 <?php 
			 }
			 ?>
		     <?php } ?>
			 <?php /*
			 <div onclick="FverDet('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>','<?php echo($row_RsListadoDeta_Requ['TIPO']); ?>');"><span><img src="img/add.jpg" width="16" >&nbsp;Ver</span></div>
			 */?>
		<?php if($estado== 3 && $_SESSION['MM_RolID']== 3){	 ?>
			 <input type="checkbox" id="consejosuperior_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" name="consejosuperior_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" title="Consejo Superior" value="1" <?php if($row_RsListadoDeta_Requ['CONSEJO_SUPERIOR']==1){ echo('checked');} ?> >Consejo Superior<br>
			 <input type="checkbox" id="consejotecnologico_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" name="consejotecnologico_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" title="Comite Tecnologico" value="1" <?php if($row_RsListadoDeta_Requ['CONSEJO_TECNOLOGICO']==1){ echo('checked');} ?>>Comite Tecnologico<br>
			 <input type="checkbox" id="comiteinfra_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" name="comiteinfra_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" title="Comite Infraestructura" value="1" <?php if($row_RsListadoDeta_Requ['COMITE_INFRAESTRUCTURA']==1){ echo('checked');} ?> >Comite Infraestructura<br>
			 <?php 
			 }else{
		
			  if($row_RsListadoDeta_Requ['CONSEJO_SUPERIOR']==1){ echo('*Consejo Superior<br>');} ?>
			 <?php if($row_RsListadoDeta_Requ['CONSEJO_TECNOLOGICO']==1){ echo('*Consejor Tecnologico<br>');} ?>
			 <?php if($row_RsListadoDeta_Requ['COMITE_INFRAESTRUCTURA']==1){ echo('*Comite Infraestructura<br>');} 
			 }
			 ?>
		  </div>
		</td>
		<?php if($estado == 2 && $_SESSION['MM_RolID']== 4){	 ?>
        <td id="tdaprob_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>"> 
		<?php
		 echo($i);
		/*
		if($estado==3 && $_SESSION['MM_RolID']== 3){
		?> onclick="mostrarTickbox('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>');" <?php } ?> ><?php echo($i); */ ?>
		</td>
		
		<?php }else{ ?>
		<td id="tdaprob_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" class="<?php echo($claseaprobado);?>"> 
		<?php
		 echo($i);
		/*
		if($estado==3 && $_SESSION['MM_RolID']== 3){
		?> onclick="mostrarTickbox('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>');" <?php } ?> ><?php echo($i); */ ?>
		</td>
		<?php
		}?>
        <td>
		   <p><?php if(strlen($row_RsListadoDeta_Requ['DESCRIPCION'])<100){ echo($row_RsListadoDeta_Requ['DESCRIPCION']); }else{ echo(substr($row_RsListadoDeta_Requ['DESCRIPCION'],0,100));?>&nbsp;&nbsp;<a href="javascript:crearfila('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>','desc');">...</a><?php } ?><br></p>
	    </td>
        <td width="70"><?php if($estado==3 && $_SESSION['MM_RolID']== 3 && $claseaprobado!= "rojo"){ ?><input type="text" name="inpcantidad_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" id="inpcantidad_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" value="<?php echo($row_RsListadoDeta_Requ['CANTIDAD']); ?>" size="4"><input type="hidden" name="inpcantidadcopia_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" id="inpcantidadcopia_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" value="<?php echo($row_RsListadoDeta_Requ['CANTIDAD']); ?>" size="4">&nbsp;<!--<img onclick="FmodificarCantidad('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>');" src="imagenes/guardar.png" width="15" height="15">--> <?php }else{  echo($row_RsListadoDeta_Requ['CANTIDAD']); } ?></td>
		
		<td width="6"><?php echo($row_RsListadoDeta_Requ['UNIDAD_MEDIDA_DES']); ?></td>
		<?php
		  if($estado!='' && $estado!='1' && $_SESSION['MM_RolID'] != 4){
		?>
        <td class="imgacciones">&nbsp;
		 <?php if($estado==2 && $_SESSION['MM_RolID']== 2){ ?>
		 <?php /*<input type="button" value="Si" onclick="AprobarDetalle('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>');">*/?>
		 <img src="imagenes/aceptar.png" onclick="AprobarDetalle('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>');" title="Aprobar Detalle" >&nbsp;
		 <img src="imagenes/delete.jpg" width="16" onclick="mostrarTickbox('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>');" title="Devolver Detalle" >
		 <?php /* <input type="button" value="No" onclick="mostrarTickbox('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>');"> */?> <?php 
		} ?>
		<?php if($estado==3  && $_SESSION['MM_RolID']== 3 && $claseaprobado!= "rojo"){ ?>
		
		 <img src="imagenes/cancelar.png" onclick="CancelarDetalle('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>');" title="Cancelar Detalle" >&nbsp;
		 <img src="imagenes/aceptar.png" onclick="AprobarDetalleDirec('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>');" title="Aprobar Detalle" >&nbsp;
		 <img src="imagenes/delete.jpg" width="16" onclick="mostrarTickbox('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>');" title="Devolver Detalle" >
		 <?php /* <input type="button" value="No" onclick="mostrarTickbox('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>');"> */?> <?php 
		} ?>
        &nbsp;
		</td>
		<?php
		}
		?>        
		<?php if($estado== -2 && $_SESSION['MM_RolID']== 2){
		?>
		<td>
		<table class="seleccionarradio" id="tableradio_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" border="0" width="100%">
		 <tr>
		  <td>
		     <select onchange="createcombomodalidad('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>');" name="selecttipo_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" id="selecttipo_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>">
			  <option value="">Eliga tipo...</option>
			  <option value="0" <?php if($row_RsListadoDeta_Requ['TIPO']== 0){ echo('selected'); } ?> >Detalle no Seleccionado</option>
			  <option value="1" <?php if($row_RsListadoDeta_Requ['TIPO']== 1){ echo('selected'); } ?>>Detalle Seleccionado</option>
			 </select>
		 </td>
		 </tr>
		 <?php /*Finput
		 
		 <tr>
		  <td>no selecc.</td>
		  <td><input onclick="createcombomodalidad('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>','0');" title="marcar como no seleccionado" type="radio" name="detallerad_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" id="detallerad_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" value="0"></td>
		 </tr>
		 <tr>
		  <td>seleccionado</td>
		  <td><input onclick="createcombomodalidad('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>','1');" title="marcar como seleccionado" type="radio" name="detallerad_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" id="detallerad_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" value="1"></td>
		 </tr>
         */
		 if($row_RsListadoDeta_Requ['TIPO']==1){
		 ?>
		  <tr>
		    <td>
		 <select name="modalidadcreate_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" id="modalidadcreate_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" class="styled-select" onchange="loadclasificacion('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>')"><option value="">Seleccione...</option>
		  <?php
		  //print_r($arraymodalidades);
          if(count($arraymodalidades) >0){
            for($p=1; $p<=count($arraymodalidades); $p++){
			     
			?>
			<option value="<?php echo($p);?>" <?php if($p==$row_RsListadoDeta_Requ['MODALIDAD']){ echo('selected');} ?> ><?php echo($arraymodalidades[$p]);?></option>
			<?php
			  }
		  }
		  ?>
		 </select><select name="clasificacioncreate_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" id="clasificacioncreate_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" class="styled-select"><option value="">Seleccione...</option>
		  <?php if($row_RsListadoDeta_Requ['CLASIFICACION'] != -1 ){
		   ?>
		   <option value="<?php echo($row_RsListadoDeta_Requ['CLASIFICACION']);?>" selected ><?php echo($row_RsListadoDeta_Requ['CLASIFICACION_DES']);?></option>
		   <?php
		  }
		  ?>
		 </select></div>
		    </td>
		  </tr>
		 <?php
		 }
		 ?> 		 
		</table>
		
		</td>
		<?php
		}
		?>
		<?php if($estado>=3){
		 // ($row_RsListadoDeta_Requ['REQUIERE_OTRO']=='' || $row_RsListadoDeta_Requ['REQUIERE_OTRO']==0 )
		 $sololectura='';
		 if($_SESSION['MM_RolID']!=3){
		 $sololectura='readonly';
		 }
		?><td>
		   <div id="divpoadeta_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" style="<?php if($row_RsListadoDeta_Requ['REQUIERE_OTRO']=='1'){ echo('display:none;');} ?>">
			<?php if($_SESSION['MM_RolID']==3 && $estado==3){ ?>
			<select name="poadetalle_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" id="poadetalle_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" class="styled-select" <?php echo($sololectura);?> ><option value="">Seleccione...</option>
		<?php
		  //print_r($arraymodalidades);
          if(count($arraypoa) >0){
            for($p=1; $p<=count($arraypoa); $p++){
			     
			?>
			<option value="<?php echo($p);?>" <?php if($p==$row_RsListadoDeta_Requ['POA_DETALLE']){ echo('selected');} ?> ><?php echo($arraypoa[$p]);?></option>
			<?php
			  }
		  }
		  ?>
		  </select>
		  <select name="subpoadetalle_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" id="subpoadetalle_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" class="styled-select" <?php echo($sololectura);?> ><option value="">Seleccione...</option>
		  <?php
               if(count($arraysubpoa) >0){
            for($p=1; $p<=count($arraysubpoa); $p++){
			     
			?>
			<option value="<?php echo($p);?>" <?php if($p==$row_RsListadoDeta_Requ['SUBPOA_DETALLE']){ echo('selected');} ?> ><?php echo($arraysubpoa[$p]);?></option>
			<?php
			  }
		  }
		  ?>
		  </select>&nbsp;
		  <?php if($_SESSION['MM_RolID']==3 && $estado==3 && $claseaprobado!= "rojo"){ ?>
		  <!--<img onclick="Fmodificarpoa('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>');" src="imagenes/guardar.png" width="15" height="15">-->&nbsp;
		  <?php 
		  }
		  ?>
		  <?php 
		  }
		  ?>
		   

		  </div>
		  <?php 
		  if($estado!=3){
			  if($row_RsListadoDeta_Requ['REQUIERE_OTRO']=='1'){
			   echo('<span style="color:#555"><b>Otro:</b> '.$row_RsListadoDeta_Requ['OTRO_DES'].'</span>');
			   }else{
			   echo('<span style="color:#555"><b>Poa:</b>'.$row_RsListadoDeta_Requ['POA_DETALLE_DES'].'<br><b>Subpoa:</b> '.$row_RsListadoDeta_Requ['SUBPOA_DETALLE_DES'].'</span>');
			  }
		  }
		  ?>
		  <?php if($_SESSION['MM_RolID']==3 && $estado==3){ ?>
		  <span class="otropoa">ingresar otro poa y subpoa</span>
		  <input type="checkbox" onclick="Otropoa('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>')" name="otro_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" value="1" id="otro_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" <?php if($row_RsListadoDeta_Requ['REQUIERE_OTRO']=='1'){ echo('checked');} ?>>
		  <div>
		  <div id="divotropoa_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" style="<?php if($row_RsListadoDeta_Requ['REQUIERE_OTRO']=='1'){ echo('display:block;'); }else{ echo('display:none');} ?>">
		  <span class="otropoa">otro</span>&nbsp;<input type="text" name="otropoa_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" id="otropoa_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>"
		  value="<?php echo($row_RsListadoDeta_Requ['OTRO_DES']);?>" maxlength="100">&nbsp;
		  <?php 
		  }
		  ?>
		  <?php if($_SESSION['MM_RolID']==3 && $estado==3){ ?>
		  <!--<img onclick="FrequiereOtro('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>');" src="imagenes/guardar.png" width="15" height="15">-->
		  </div>
		  <?php 
		  }
		  ?>
		  
		</td>
		<?php 
		}
		?>
        <td><p><?php if(strlen($row_RsListadoDeta_Requ['JUSTIFICACION'])<100){ echo($row_RsListadoDeta_Requ['JUSTIFICACION']); }else{ echo(substr($row_RsListadoDeta_Requ['JUSTIFICACION'],0,100));?>&nbsp;&nbsp;&nbsp;<a href="javascript:crearfila('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>','just');" >... </a><?php } ?><br></p></td>
        <td><p><?php if(strlen($row_RsListadoDeta_Requ['OBSERVACION'])<100){ echo($row_RsListadoDeta_Requ['OBSERVACION']); }else{ echo(substr($row_RsListadoDeta_Requ['OBSERVACION'],0,100)); ?>&nbsp;&nbsp;&nbsp;<a href="javascript:crearfila('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>','obs');">... </a><?php } ?><br></p></td>
		<?php if($_SESSION['MM_RolID']==3 ){
		?>
		 <td>$15.200.000</td> 
		<?php 
		}
		?>
		
		<td>
		  <div class="comentdetalle" id="commentdet_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>"><span class="numcoment"><?php echo($row_RsListadoDeta_Requ['CANTIDAD_OBS']);?></span></div>
		  <div style="margin:0 auto;">..</div>
		</td>
    </tr>
 <?php } while ($row_RsListadoDeta_Requ = mysqli_fetch_array($RsListadoDeta_Requ));
     } //  ?>
	 <div id="botonesrecibir">
        <?php 
		 if($estado==3 && $_SESSION['MM_RolID']==3){ 
		 ?>
	 <tr>
	  <td colspan="12">	   
         <input type="submit" class="button2" id="btnsub_ns" value="Aprobar" onclick="return AprobarReq(); ">
		 <?php
          }
		  ?>
        <?php 
		 if($estado==5 && $_SESSION['MM_RolID']==2){ 
		 ?>
         <input type="submit" class="button2" id="btnsub_ns" value="Cotizar" onclick="return Acotizar(); ">
		 <?php
          }
		  ?>		  
	    <?php 
		 if($estado==2 && $_SESSION['MM_RolID']==2){ 
		 ?>
	    <input type="button" class="button2" id="btnsub_ns" value="Recibir" onclick="return Recibir('3'); ">
		<!-- visto el requerimiento para casos de requerimientos con todos los detalles en devuelto-->
		<input type="button" class="button2" id="btnsub_ns" value="No Recibir" onclick="return Recibir('4'); ">
		<?php
		 }
		 ?>
		</div>
		<div id="postmensajerecibir">
		
	  </td>
	 </tr>
	 </div>
</table>
	    <?php
            if($totalRows_RsArchivosLista){
			?>
			<table style="margin-left:10px;" width="600" BORDER="0">
			 <tr>
			  <td>&nbsp;</td>
			 </tr>
			 <tr class="SLAB trtitle">
			  <td></td>
			  <td align="center" colspan="3">Archivos</td>
			 </tr>
			<?php
			 do{
			?>
			 <tr>
			  <td height="35"><a href="descargar.php?doc=<?php echo($row_RsArchivosLista['ARCHIVO']);?>" target="_blank" class="buttonrojo">descargar</a></td>
			  <td height="35"><a class="buttonrojo" onclick="ElimArchivo('<?php echo($row_RsArchivosLista['CODIGO'])?>');">eliminar</a></td>
			  <td class="SLAB2"><?php echo($row_RsArchivosLista['ARCHIVO']);?></td>
			  
			 </tr>
			<?php
			   }while($row_RsArchivosLista = mysqli_fetch_array($RsArchivosLista));
			?>
			</table>
			<?php
			}else{
			
			echo('<p style="margin-left:20px;" class="SLAB2">Este requerimiento no tiene archivos adjuntos</p>');
			}
	    ?>
</form>		
 <form name="form_req" id="form_req" method="post" action="" enctype="multipart/form-data">
 <table border="0" width="800">
 <?php /*
 <tr bgcolor="#F4F3F1">
<td >
<a href="index.html?doc=prueba">Saldo</a>
</td>
<td colspan="2">
$8.435.234
</td>
</tr>
*/ ?>
<?php
if($estado == 1 && $totalRows_RsListadoDeta_Requ>0){
?>
 <tr bgcolor="#F4F3F1">
<td class="SLAB3">
Anexo:
</td>
<td>

 <input type="file" name="archivo1" id="archivo1" />
 <input class="button2" type="submit"  value="Subir Archivo" onclick="return subirarchivo();"/>
</td>
</tr>
<?php
}
?>
<tr>
 <td>&nbsp;</td>
</tr>
<?php
if($totalRows_RsListadoDeta_Requ>0 && $MM_RolID == 4){
?>
<tr bgcolor="#F4F3F1">
<td colspan="6" align="center">
<input type="hidden" value="1" name="area" id="area">

<?php 

switch($estado)
{
            case 1:
			?>
            <input class="button2" type="submit"  value="Enviar" onclick="return Fenviar();"/>
			<input class="button2" type="submit"  value="Posponer" onclick="return volveraListado();"/>
			<?php
            break;
            case 4:
			?>
            <input class="button3" type="submit"  value="Volver a Enviar" onclick="return Fvolverenviar();"/>
           <?php
            break;
         
			
}			
?>
</td>
</tr>
<?php
}
?>
<?php
//if($estado==2 && $_SESSION['MM_RolID'] == 4){
if($estado>=2){
?>
 <tr>
   <td colspan="6" align="center">
     <input class="button2" type="submit"  value="Volver a Listado Requerimientos" onclick="return volveraListado();"/>
   </td>
 </tr>
<?php
}

?>
 </table>
 </form>
<?php
}
else
{
?>
<form name="formcrear" id="formcrear" action="" method="post">
<table style="margin-left:10px; text-align:center;" width="300">
 <tr>
   <td class="preg_requerimiento"><br></td>
 </tr>
 <tr>
  <td>
    <div class="button3" style="width:350px; height:200px; margin:1px 0px 350px 300px; border-radius:50%;" id="crearreq" onclick="return CrearRequerimiento();">
      <div class="sol_here">Solicite su requerimiento<br>Ahora </div>
    </div>
  </td>
 </tr>
</table>
</form>
<?php
}
?></div>
<div id="TB_overlaycantidad" class="" onclick="cerrar_cantidad();"></div>