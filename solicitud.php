<?php
//conexion a base de datos
	require_once('conexion/db.php');

// verificacion de session
	if (!isset($_SESSION)) 
	{
  		session_start();
	}

//definicion de variables
	$totalRows_RsListadoDeta_Requ=0;

	$director_admini_ced='39550544';

	$codigo_requerimiento='';
	if(isset($_GET['codreq']) && $_GET['codreq']!='')
	{
		$codigo_requerimiento=$_GET['codreq'];
	}

	$estado     			= '';
	$estado_des 			= '';
	$fecha_creacion 		='';
	$fecha_envio 			= '';
	$fecha_recibido			= '';
	$fecha_aprobado			='';
	$fecha_cotizando        = '';
	$codigo_req 			= '';
	$poa        			= '';
	$poa_des    			= '';
	$subpoa     			= '';
	$subpoa_des     		= '';
	$solicitante 			= '';
	$tiposubpoa  			= '';
	$arraymodalides 		= array();
	$arraypoa 				= array();
	$arraysubpoa 			= array();
	$arraydetalles 			= array();
	$arrayundmedida			= array();
	$codigo_evento = '';


//consultar estados para pasar requerimiento director administrativo 
    $query_RsEstadosDiradm = "SELECT E.ESTACODI CODIGO,
	                           E.ESTANOMB NOMBRE,
							   E.ESTACOLO COLOR
							from ESTADOS E
						 WHERE E.ESTACODI IN(19);
							   ";
	$RsEstadosDiradm = mysqli_query($conexion,$query_RsEstadosDiradm) or die(mysqli_error($conexion));
	$row_RsEstadosDiradm = mysqli_fetch_assoc($RsEstadosDiradm);
    $totalRows_RsEstadosDiradm = mysqli_num_rows($RsEstadosDiradm);
	if($totalRows_RsEstadosDiradm>0){
		do{
		$arrayestadosDiradm[] = $row_RsEstadosDiradm;
		}while($row_RsEstadosDiradm = mysqli_fetch_array($RsEstadosDiradm));
	}	

//consultar estados para pasar requerimiento auxiliar administrativo
    $query_RsEstados = "SELECT E.ESTACODI CODIGO,
	                           E.ESTANOMB NOMBRE,
							   E.ESTACOLO COLOR
							from ESTADOS E
						 WHERE E.ESTACODI IN(5,19,11,17,7)
						 ORDER BY E.ESTACODI ASC
							   ";
	$RsEstados = mysqli_query($conexion,$query_RsEstados) or die(mysqli_error($conexion));
	$row_RsEstados = mysqli_fetch_assoc($RsEstados);
    $totalRows_RsEstados = mysqli_num_rows($RsEstados);
	if($totalRows_RsEstados>0){
		do{
		$arrayestados[] = $row_RsEstados;
		}while($row_RsEstados = mysqli_fetch_array($RsEstados));
	}


	
	if($codigo_requerimiento!='')
	{
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
									(SELECT TIPONOMB
									  FROM tipo_compra TC
									 WHERE TC.TICOCODI = DERECLAS) CLASIFICACION_DES,									
									DEREPRES PRESUPUESTO,
									DEREDESC DESCRIPCION,
									DERECANT CANTIDAD,
									DEREJUST JUSTIFICACION,
									DEREOBSE OBSERVACION,
									DERECONV CONVENI,							
									DERETISE TIPO,
									DEREVIAT CAMPO_VIATICOS,
									DEREELEC CAMPO_ELECTRONICA,
									(SELECT TOCONOMB
									  FROM tipoorden_compra TOC
									 WHERE TOC.TOCOCODI = DERETIPO) TIPOORDENCOMPRA_DES,
									case DERETISE
									 WHEN 0
									  THEN 'NO SELECCIONADO'
									 WHEN 1
									  THEN 'SELECCIONADO'
									 ELSE ''
									END TIPO_DES,
									DEREAPRO APROBADO,
									(SELECT ESDENOMB 
									FROM `estado_detalle` 
									WHERE `ESDECODI`= DEREAPRO
									AND `ESDEFLAG`=1) APROBADO_DES,
									(SELECT COUNT(D.OBDECODI)
									  FROM OBSERVACIONESDET D
									 WHERE D.OBDECODE = DERECONS) CANTIDAD_OBS,
									 (SELECT `PROVNOMB` 
										FROM `proveedores` 
										WHERE `PROVCODI`= DEREPROV) PROVEEDOR_DESC_DET,
										 (SELECT `PROVNOMB` 
										FROM `proveedores` 
										WHERE `PROVCODI`= DEREPROV2) PROVEEDOR_RECOT_DESC,
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
									   DERENCOT DETA_NO_COTIZA,
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
									   DERECOOC ORDEN_COTIZADA,
									   DEREIOCC ORDEN_CONVENIO,
									   DERECOMC ORDEN_MENOR_CUANTIA,
									   DEREREOT REQUIERE_OTRO,
									   DEREDCOM OBSERVACION_PROV,
									   IFNULL(DEREFERE, '-1') FECH_RECIB_USUGENE,
									   (SELECT R.REQUENCU FROM requerimientos R where R.REQUCODI = '".$codigo_requerimiento."') ENCUESTA
									   
									FROM `DETALLE_REQU`
								where DEREREQU = '".$codigo_requerimiento."'
									";

						 //echo($query_RsListadoDeta_Requ);echo("<br>");
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
	
			$detalles_aprobados = array();
			if($totalRows_RsListadoDeta_Requ>0)
			{
			  $t=0;
			  do{
				   $arraydetalles[$t] = $row_RsListadoDeta_Requ2 -> CODIGO;
				   $arr[] = array('ID'             => $row_RsListadoDeta_Requ2 -> CODIGO,
								  'DESCRIPCION'    => str_replace($rem,"",stripslashes($row_RsListadoDeta_Requ2 -> DESCRIPCION)),
								  'JUSTIFICACION'  => str_replace($rem,"",stripslashes($row_RsListadoDeta_Requ2 -> JUSTIFICACION)),
								  'OBSERVACION'    => str_replace($rem,"",stripslashes($row_RsListadoDeta_Requ2 -> OBSERVACION)),
								  'OBSERVACION_PROV'    => str_replace($rem,"",stripslashes($row_RsListadoDeta_Requ2 -> OBSERVACION_PROV)),
								  );
					$t++;
					if($row_RsListadoDeta_Requ2 -> APROBADO == '16'){
						$detalles_aprobados[] = $row_RsListadoDeta_Requ2 -> CODIGO;
					}
	 
				}while($row_RsListadoDeta_Requ2 = mysqli_fetch_object($RsListadoDeta_Requ2));
			}	


			// consulta de requerimiento
			$query_RsListadoRequerimiento="SELECT R.REQUESTA ESTADO,
	                                      E.ESTANOMB ESTADO_DES,
										  R.REQUCORE CODIGO_REQ,
										  R.REQUPOA  POA,
										  DATE_FORMAT(R.REQUFESO, '%d %b %Y  %H:%i:%s') FECHA_CREACION,
										  DATE_FORMAT(R.REQUFEEN, '%d %b %Y  %H:%i:%s') FECHA_ENVIO,
										  DATE_FORMAT(R.REQUFERE, '%d %b %Y  %H:%i:%s') FECHA_RECIBIDO,
										  DATE_FORMAT(R.REQUFEAP, '%d %b %Y  %H:%i:%s') FECHA_APROBADO,
										  DATE_FORMAT(R.REQUFECO, '%d %b %Y  %H:%i:%s') FECHA_COTIZANDO,
										  (SELECT A.AREANOMB 
										    FROM AREA A
										   WHERE A.AREAID =  R.REQUAREA LIMIT 1) AREA_DES,
										  (SELECT PO.POANOMB 
										    FROM POA PO
										   WHERE PO.POACODI = R.REQUPOA LIMIT 1) POA_DES,
										  R.REQUSUPO SUBPOA,
										  R.REQUFLED BANDERA,
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
											 LIMIT 1) SOLICITANTE,
											 (select  P.PERSID FROM PERSONAS P
  										     WHERE R.REQUCODI = '".$codigo_requerimiento."'
											AND P.PERSID = R.REQUIDUS
											 LIMIT 1) CEDULA_PERSONA,
											 REQUEVEN CODIGO_EVENTO
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
			$fecha_recibido = $row_RsListadoRequerimiento['FECHA_RECIBIDO'];
			$fecha_aprobado = $row_RsListadoRequerimiento['FECHA_APROBADO'];
			$fecha_cotizando= $row_RsListadoRequerimiento['FECHA_COTIZANDO'];
			$codigo_req 	= $row_RsListadoRequerimiento['CODIGO_REQ'];
			$poa        	= $row_RsListadoRequerimiento['POA'];
			$poa_des    	= $row_RsListadoRequerimiento['POA_DES'];
			$subpoa     	= $row_RsListadoRequerimiento['SUBPOA'];
			$subpoa_des  	= $row_RsListadoRequerimiento['SUBPOA_DES'];
			$solicitante 	= $row_RsListadoRequerimiento['SOLICITANTE'];
			$tiposubpoa 	= $row_RsListadoRequerimiento['TIPOSUBPOA'];
			$area_des       = $row_RsListadoRequerimiento['AREA_DES'];
			$cedula_sol     = $row_RsListadoRequerimiento['CEDULA_PERSONA'];
			$bandera 		= $row_RsListadoRequerimiento['BANDERA'];
			$codigo_evento  = $row_RsListadoRequerimiento['CODIGO_EVENTO'];
}


?>

<style type="text/css" media="all">
@import "thickbox.css";
.chkdetails{
	list-style-type:none;
	padding:0;
}
.chkdetails li{
	display:inline;
}
.claseseleccionado{
	background:#ffcccc;
}
</style>

<link rel="stylesheet" type="text/css" href="css/estilo_solicitud.css" />
<link rel="stylesheet" href="chosen/chosen.min.css" />
<?php /*<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>*/?>
<script src="js/thickbox.js" type="text/javascript"></script>
<script src="chosen/chosen.jquery.min.js" type="text/javascript"></script>

<style type="text/css">
.infodetest{
	color: #151313 !important;
	font-size: 9px;
  background: #F4EBEA;
  padding: 2px 3px;
  border: solid 1px #B8A1A1;
  border-radius: 5px;
  box-shadow: 1px 2px 3px 1px #D5D5D5;
}
 .seleccionarradio{
     color: #941512;
    font-size: 9px;
    font-weight: bold;
	line-height:0.5;
 }
 .loading_img{
	 display:none;
 }
 
 .otropoa{
 font-size:13px; color:#800607; font-weight:700
 }
 .buttongris{
  background: #ECEAEA;
  padding: 3px 12px;
  margin-bottom: 2px;
  border-radius: 5px;
  border: solid 1px #90E886;
 }
 .selectgris{
	   padding: 10px;
  color: #A64A4A;
  background-color: #F4F4F4;
  border-radius: 5px;
  border: solid 1px #BBB1B1;
  font-weight:bold;
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
	

	<?php
	if(isset($_GET['env']) && $_GET['env']=='1'){
	?>
    $('#TB_overlaycantidad').addClass("TB_overlayBGcantidad");
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
	
	$('.AnexosDetalles').click(function(e){
	   id = $(this).attr('id');
	   id = id.split('_');
	   tb_show('Anexos / Detalle', 'anexos_detalle.php?codigo_detalle='+id[1]+'&estado='+$('#estado').val()+'&amp;keepThis=true&amp;TB_iframe=true&amp;height=400&amp;width=750');
	});
	$("#multiplesdetalles").click(function(){
			detalles = multiple_detalle_seleccionados;
			details = [];
			for(i=0; i < detalles.length; i++){
				details[i] = detalles[i].detail;
			}
			details = _.uniq(details);
	   tb_show('Multiple Detalles / Archivo', 'multiple_detalles_archivo.php?estado='+$('#estado').val()+'&detallesadd='+details.toString()+'&amp;keepThis=true&amp;TB_iframe=true&amp;height=400&amp;width=750');		
	});	
	$('.FactDetalles').click(function(e){
	   id = $(this).attr('id');
	   id = id.split('_');
	   tb_show('Factura / Detalle', 'factura_detalle.php?codigo_detalle='+id[1]+'&estado='+$('#estado').val()+'&amp;keepThis=true&amp;TB_iframe=true&amp;height=400&amp;width=750');
	});
	
	$("#tabladetalle").on("click", ".agenerada", function(){
	  id = $(this).attr('id').split('_');
	  cod  = id[1];
	  cam  = id[2];
	  $("#trtrcreated_"+cod+"_"+cam).remove();
	});
	
	multiple_detalle_seleccionados = [];
	$(".multiplefilechk").click(function(){
		console.log($(this).attr("value"))
		if($(this).is(':checked')){
			add = {detail:$(this).attr("value"), texto: $("#parrafodescr_"+$(this).attr("value")).text()}
			multiple_detalle_seleccionados.push(add);
			$("#trtr_"+add.detail).addClass('claseseleccionado');
			}else{
				
              if(multiple_detalle_seleccionados.length>0){
				  for(var i=0; i<multiple_detalle_seleccionados.length; i++){
					 if(multiple_detalle_seleccionados[i].detail == $(this).attr("value")) {
						 multiple_detalle_seleccionados.splice(i,1);
					 }
				  }
			  }
			$("#chkall").attr('checked', false);
			$("#trtr_"+$(this).attr('value')).removeClass('claseseleccionado');
			}
		console.log(multiple_detalle_seleccionados);	
		if(multiple_detalle_seleccionados.length > 0 ){
			$("#multiplesdetalles").css("display","block");
		}else{
			$("#multiplesdetalles").css("display","none");
		}
		
	});
	
	$("#chkall").click(function(index){
		if($(this).is(':checked')){
			$(".multiplefilechk").each(function(index){
				console.log($(this).val());
				//$("input:checkbox").prop('checked', true);
				$(this).attr('checked', true);
				add = {detail:$(this).attr("value"), texto: $("#parrafodescr_"+$(this).attr("value")).text()}
				multiple_detalle_seleccionados.push(add);
				$("#trtr_"+add.detail).addClass('claseseleccionado');
			});
		}else{
			//$("input:checkbox").prop('checked', true);
			$(this).attr('checked', false);
			multiple_detalle_seleccionados = [];
			$(".multiplefilechk").each(function(index){
				$(this).attr('checked', false);
				$("#trtr_"+$(this).attr('value')).removeClass('claseseleccionado');
			});
		}
		if(multiple_detalle_seleccionados.length > 0 ){
			$("#multiplesdetalles").css("display","block");
		}else{
			$("#multiplesdetalles").css("display","none");
		}		
		
	})	

});

$(document).ready(function(){
    $("#markAllReceived").click(function(){
        fentregaMultiple();
    });
});

function todo(){
	$("#chkall").attr("checked",true);
	$(".multiplefilechk").each(function(index){
		$(this).attr('checked', true);
		add = {detail:$(this).attr("value"), texto: $("#parrafodescr_"+$(this).attr("value")).text()}
		multiple_detalle_seleccionados.push(add);
		$("#trtr_"+add.detail).addClass('claseseleccionado');
	});	
	$("#multiplesdetalles").css("display","block");
}
function nada(){
	$("#chkall").attr("checked",false);
	multiple_detalle_seleccionados = [];
	$("#multiplesdetalles").css("display","none");
	$(".multiplefilechk").each(function(index){
		$(this).attr('checked', false);
		$("#trtr_"+$(this).attr('value')).removeClass('claseseleccionado');
	});	
}
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

 if(document.getElementById('is_evento').checked==true){
	  if($("#codigo_evento").val()==''){
		  toastr.info("debe ingresar un codigo de evento para continuar");
		  inlineMsg('codigo_evento','debe ingresar un codigo de evento.',3);
		  return false;
	  }
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
     document.no_selecc.action = 'solicitud_guardar.php?tipoGuardar=adicionar_ns&codreq='+document.getElementById('codigo_req').value+'&poa='+$("#poa").val()+'&subpoa='+$("#subpoa").val()+'&insert='+insert;
	 <?php 
	 }else{
	 ?>
	 document.no_selecc.action = 'solicitud_guardar.php?tipoGuardar=adicionar_ns&codreq='+document.getElementById('codigo_req').value;
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
     document.form_lista.action = 'solicitud_guardar.php?tipoGuardar=enviar_req&codreq='+$("#codigo_req").val();
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
     document.form_req.action = 'solicitud_guardar.php?tipoGuardar=ArchivoReq&codreq='+$("#codigo_req").val();
   }else{
   return false;
   } 
 }

 function volveraListado(){
   location.href='home.php?page=requerimientos_lista ';

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
function FirmaDirAdministrativo(det){
      var date = new Date();
	  var timestamp = date.getTime();
	  //var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=CargarDatos&time="+timestamp+"&codigo_detalle="+det);
	 	 $.ajax({
			type: "POST",
			url: "tipo_guardar.php?tipoGuardar=FirmarDirectorioAdm&codigo_detalle="+det,
			success : function(r){
				if(r != ''){
					data = r.split("|");
					if(data.length==4){
					  $("#tdaprob_"+det).prop("title",data[3]);
					  $("#tdaprob_"+det).css("background",data[2]);
					  $("#afirmarDirAdm_"+det).remove();
					}
					
				}
			},
			error   : callback_error
		});
}

function FirmaRector(det){
      var date = new Date();
	  var timestamp = date.getTime();
	 	 $.ajax({
			type: "POST",
			url: "tipo_guardar.php?tipoGuardar=FirmarRector&codigo_detalle="+det,
			success : function(r){
				if(r != ''){
					data = r.split("|");
					if(data.length==5){
					  if(data[4] == 1 || data[4] == 2){
                        if(data[4] == 1){ 						  
						  $("#tdaprob_"+det).prop("title",data[3]);
						  $("#tdaprob_"+det).css("background",data[2]);
						  $("#afirmarRect_"+det).remove();
						  alert("proveedor asignado Correctamente");
						}else{
							alert("el proveedor asignado por el director administrativo es diferente al asignado por rectoria");
						}
						
					  }else{
						  alert("debe asignar el proveedor al detalle");
					  }
					}
					
				}
			},
			error   : callback_error
		});
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
  alert('debe ingresar el centro de costo');
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
  if(aprobado==7){
	document.getElementById('tdaprob_'+coddet).className="";
	$("#tdaprob_"+coddet).addClass("violeta");
 }
}

function actualizarcantComent(coddet,cantidad){
 $("#commentdet_"+coddet+">span").text(cantidad);
}

function volverEditar(){
 $("#postmensajerecibir").html("");
 $("#botonesrecibir").css("display","block");
}


function AprobarReq(){
	var devueltos =0;
	
	$('.rojo').each(function(index){
	 devueltos =1;
	});
	
	if(devueltos==1){
	 alert('El requerimiento no se puede aprobar porque hay detalles devueltos');
	 return false;
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
	 alert('debe ingresar los valores poa y centro de costo  de los detalles requeridos');
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

function AprobarCoordinador(cod){
	var date = new Date();
	  var timestamp = date.getTime();
	  //var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=CargarDatos&time="+timestamp+"&codigo_detalle="+det);
	  if(confirm("Seguro que desea Aprobar este detalle")){
		$.ajax({
			type: "POST",
			url: "tipo_guardar.php?tipoGuardar=AprobarCoordinador&codigo_detalle="+cod,
			success : function(r){
				if(r != ''){
					
					toastr.success('<strong>Registro Aprobado Correctamente </strong>');
				}
			},
			error   : callback_error
		});
	  }

}

function infocus(campo){
	try{
		  document.getElementById(campo).focus();
		  document.getElementById(campo).select();
		}catch(e){}
		//return false; 

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
 
 if(campo=='obs_prov'){
   title="Observaciones_prov";
	for(var i = 0; i < info.length; i++ ){
	  if(info[i].ID==cod){
	   desc = info[i].OBSERVACION_PROV;
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
	  alert('debe ingresar el poa y centro de costo para guardar');
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


  
	function fvolvercotiz()
		{	
			alert('12');
			var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=det_vol_cot&time="+timestamp+"&codigo_detalle="+cod+"&tipo="+tipo);
			  if(v_dato!='')
			  {
				 alert("Se han guardado sus cambios");
				  location.reload();
				 return false;
			  } 
		}
 
	function AprobarDetalleDirec(cod_det)
	{

		poa        = document.getElementById('poadetalle_'+cod_det).value;
		subpoa     = document.getElementById('subpoadetalle_'+cod_det).value;
		otro   	   = document.getElementById('otropoa_'+cod_det).value;
		vcant      = document.getElementById('inpcantidad_'+cod_det).value;
		presup     = document.getElementById('presup_'+cod_det).value;


		//valida la cantidad
		var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=Validar_cantidad&codigo_detalle="+cod_det+"&cantidad="+vcant);
		if(v_dato!='' && v_dato=='1')
		{
			 alert("Se modificaran la cantidad");	 
		}

		if(document.getElementById('otro_'+cod_det).checked==true)
		{
			if(otro ==''){alert("Debe ingresar algun otro tipo de centro de costo "); return false;}
			var v_dato2 = getDataServer("tipo_guardar.php","?tipoGuardar=AprobarDetalle&coddet="+cod_det+"&otropoa="+otro+"&cantidad="+vcant+"&presup="+presup);
		}

		if(document.getElementById('otro_'+cod_det).checked==false)
		{
			if(poa ==''){alert("debe ingresar poa"); return false;}
			if(subpoa ==''){alert("debe ingresar centro de costo"); return false;}
			var v_dato2 = getDataServer("tipo_guardar.php","?tipoGuardar=AprobarDetalle&coddet="+cod_det+"&poa="+poa+"&subpoa="+subpoa+"&cantidad="+vcant+"&presup="+presup);
		}

//alert(v_dato2);
		if(v_dato2>0)
		{
			actualizaraprobado(cod_det,'1'); 
		}
	}
	
	function ElimArchivo(cod_arch)
		{

			var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=eliminar_archivo&codigo_arch="+cod_arch);
				if(v_dato!='' && v_dato=='1')
				{
					alert("Se a eliminado el archivo");
					location.reload();
					return false;
				}
		}


  function fentrega(id,Ncot){
    
		$("#loading_img_"+id).css("display","block");
			$.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=marcarentregado&ncot=="+Ncot+"&codigo_e="+id,
	            dataType: 'json',
				success : function(r){
					if(r.length>0){
						if(r[0].afectado !='' && r[0].afectado == '1'){
						 location.reload();
						 //return false;
						   if(r[0].background!=''){
							   $("#tdaprob_"+id).css("background", ""+r[0].background);
							}
						  $("#trtr_"+id+" .imgacciones").each(function(index){
							$(this).html('');
						  });
						  $("#loading_img_"+id).css("display","none");
						}
					}
				},
				error   : callback_error
	        });					
	
	  
  } 
  function fentregaMultiple(){
    let detallesSeleccionados = $(".multiplefilechk:checked");
    if(detallesSeleccionados.length === 0){
        alert("No hay detalles seleccionados.");
        return;
    }

    if(confirm("¿Seguro que desea marcar como recibidos todos los detalles seleccionados?")){
        detallesSeleccionados.each(function(){
            let id = $(this).attr("value");
            let Ncot = $(this).data("ncot");  // Asumiendo que el número de cotización está almacenado como data-attribute
            fentrega(id, Ncot);
        });
    }
}


function CompletaSelect(campo,obj,campo2){
	console.log(obj);
	if(obj.length>0){
		$("#"+campo).html('');
		$("#"+campo).append('<option value="">- Seleccionar un producto del convenio proveedor -</option>');
		_.each( obj , function(element,index){
			$("#"+campo).append('<option value="'+element.CODI_CONVE_PRODUC+'">'+element.NOMBRE+' ( '+element.PRECIO+') '+element.UM+' CANTIDAD :'+element.CANT+'</option>');
			
		});	
	 //$("#"+campo).css('display','');
     //$("#"+campo).chosen();	
	 $("#"+campo).trigger("chosen:updated");
	 
	 
	}else{
	 $("#"+campo).html('');
	 $("#"+campo).append('<option value="">- sin producto en convenio proveedor -</option>');		
	 $("#"+campo).css('display','none');
     //$("#"+campo).chosen();	
	 $("#"+campo).trigger("chosen:updated");
	}
}  
  
  function ShowProductosConvenio(det,conv){
  		$("#productoconvenio_"+det).html('');
		
		//$("#productoconvenio_"+det).chosen();
		//productoconvenio_1034_chosen
		$("#"+det).append('<option value="">Seleccionar producto...</option>');

		 $.ajax({
			type: "POST",
			url: "tipo_guardar.php?tipoGuardar=CargarProductosConvenido&convenio="+conv,
			dataType: 'json',
			success : function(r){
				CompletaSelect('productoconvenio_'+det,r);
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

function Fpendiente(p){

		if(confirm('esta seguro que desea Pasar  este Requerimiento?')){
		var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=req_est_pendiente&req="+p);
				//alert(v_dato);
				if(v_dato!='' && v_dato=='1')
				{
					alert("Se a marcado como Pendiente");
					location.reload();
					return false;
				} 
				 }else{
	  return false;
	  }
	  
}

function Fdevol_admitido(da){

		if(confirm('esta seguro que desea Pasar  este Requerimiento?')){
		var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=devol_admitido&req="+da);
				//alert(v_dato);
				if(v_dato!='' && v_dato=='1')
				{
					alert("Se a pasado a estado  Admitido");
					location.reload();
					return false;
				} 
				 }else{
	  return false;
	  }
	  
}

function Ffirm_dir_adm(p){

if(confirm('esta seguro que desea Pasar  este Requerimiento?')){
     
	 var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=req_est_firmaAdm&req="+p);
				//alert(v_dato);
				if(v_dato!='' && v_dato=='1')
				{
					alert("el requerimiento fue pasado a FIRMA DA");
					location.reload();
					return false;
				} 
	 }else{
	  return false;
	  }
	  
	
		
}

function Fpasa_rector(p){

if(confirm('esta seguro que desea Pasar  este Requerimiento?')){
     
	 var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=req_est_firmaRect&req="+p);
				//alert(v_dato);
				if(v_dato!='' && v_dato=='1')
				{
					alert("el requerimiento fue pasado a FIRMA RECTOR");
					location.reload();
					return false;
				} 
	 }else{
	  return false;
	  }
	  
	
		
}


function FcancelarReque(c){

if(confirm('esta seguro que desea Pasar  este Requerimiento?')){
     
	 var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=req_est_Cancelado&req="+c);
				//alert(v_dato);
				if(v_dato!='' && v_dato=='1')
				{
					alert("el requerimiento fue pasado a CANCELADO");
					location.reload();
					return false;
				}

				if(v_dato!='' && v_dato=='0')
				{
					alert("Su procedimiento sera anulado, NO A CANCELA TODOS LOS DETALLES DEL REQUERIMIENTO");
					//location.reload();
					return false;
				}
				
	 }else{
	  return false;
	  }
	  
	
		
}

function generarEncuesta(codrequ){
	if(confirm("Seguro que desea generar esta encuesta?")){
		       $.ajax({
					type: "POST",
					url: "tipo_guardar.php?tipoGuardar=GenerarEncuesta&requerimiento="+codrequ,
					dataType: 'json',
					success : function(r){
						if(r >0){
						$("#btngenerarencuesta").remove();
						 alert("encuesta generada");	
						}	
					},
					error   : callback_error
				});	
}}

function ver_historial(cod){

   tb_show('Historial / Detalle', 'historial_detalle.php?codigo_detalle='+cod+'&estado='+$('#estado').val()+'&amp;keepThis=true&amp;TB_iframe=true&amp;height=400&amp;width=750');
}
function pasaraestado(){
	if($("#pasar_estado").val()==''){
		alert("debe ingresar el estado ");
		return false;
	}
	if(confirm("seguro que desea cambiar de estado?")){	
	 document.form_lista.action = 'solicitud_guardar.php?tipoGuardar=pasarDeestado&codreq=<?php echo($codigo_requerimiento);?>';
	}
}

function pasarestadodetalle(){
	if($("#pasar_estado").val()==''){
		alert("debe ingresar el estado ");
		return false;
	}
	if(confirm("seguro que desea cambiar de estado?")){	
	 //document.form_lista.action = 'solicitud_guardar.php?tipoGuardar=pasarDeestadodetalle&coddetalle=';
	}
}

function frecibo_auxiliarA(id){
	
	if(confirm("Seguro de marcar como recibido este item?")){
		       $.ajax({
					type: "POST",
					url: "tipo_guardar.php?tipoGuardar=RecibeAuxiliarAdmin&det_req="+id,
					dataType: 'json',
					success : function(r){
							location.reload();
					},
					error   : callback_error
				});	
}
}

function NoAprobarReq()
{
var aceptar=0;

	$.ajax({
			type: "POST",
			url: "tipo_guardar.php?tipoGuardar=ValidarDetalles&codreq="+<?php echo($codigo_requerimiento);?>,
			dataType: 'json',
			success : function(r)
			{ 
			  if(r == '1')
			   {	    
                 aceptar=r; 				 
				 if(aceptar ==  1){
			      Dd(aceptar);
		          };
		        };	
			},	
			error   : callback_error,
		});				
}

function Dd(x){
	
	if(confirm("¿Quieres Pasara Este Requerimiento a Estado Pendiente ?"))
	                {					
					 $.ajax({
							type: "POST",
							url: "solicitud_guardar.php?tipoGuardar=pasarDeestado2&estado_req=19&codreq="+<?php echo($codigo_requerimiento);?>,
							dataType: 'json',
							success : function(t){
								alert('Su Actividad Se Ejecuto Exitosamente');
								ventana=window.self; 
								ventana.opener=window.self;
								ventana.close();					
							},	
							error   : callback_error,
						});
					};
}

function PasaEstadoDetalle (det,est){
		if(confirm("¿Quieres Pasara de estado este Detalle?"))
	                {					
					 $.ajax({
							type: "POST",
							url: "solicitud_guardar.php?tipoGuardar=pasarDeestadoDetalle&estado_det="+est+"&cod_det="+det,
							dataType: 'json',
							success : function(t){
								alert('Su Actividad Se Ejecuto Exitosamente');
								location.reload();					
							},	
							error   : callback_error,
						});
					};
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
			
				<td align="left" width="110" style="color:#CB5100;"><span id="menuinfo">Informaci&oacute;n&nbsp;&nbsp;&nbsp;</span></td>  
		</table>
		<div id="content_gbsfw" style="width:500px;  position:absolute; min-height:300px; left:80px;  z-index:-1">
			<div id="gbsfw" class="gb_z fc" guidedhelpid="gbifp" style="width: 480px; min-height: 265px;  min-width: 480px; ">
				<div class="fc">
					<table width="440" border="0" class="caja2">
						<tr class="SLABCAJA">
							<td>Fecha Creaci&oacute;n:</td>
							<td><?php echo($fecha_creacion);?></td>
						</tr>   
						<tr class="SLABCAJA">
							<td>Fecha Envio:</td>
							<td><?php echo($fecha_envio);?></td>
					   </tr>   
						<tr class="SLABCAJA">
							<td>Fecha No Recibido:</td>
							<td><?php echo($fecha_envio);?></td>
					   </tr>    
						<tr class="SLABCAJA">
							<td>Fecha Recibido:</td>
							<td><?php echo($fecha_recibido);?></td>
						</tr>    
						<tr class="SLABCAJA">
							<td>Fecha Admitido:</td>
							<td><?php echo($fecha_aprobado);?></td>
						</tr>    
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
			
					</table>      
				</div>
			</div>
		</div>
	</div>
	
	<?php if($cedula_sol == $_SESSION['MM_UserID'] and $bandera == 1){?>
<div class="row" style="margin-top:1.5em;">
	<div class="col-md-4 col-xs-5" style="padding-left: 3.2em;">
		<div class="marcar_evento">Evento <input type="checkbox" name="is_evento" id="is_evento" value="1" onclick="add_codevento(this)" <?php if($codigo_evento != ''){ echo('checked');} ?>> <span id="msgaddevento" class="msgaddevento"><?php echo($codigo_evento);?></span></div>
		<div class="panel panel-danger" style=" display:<?php if($codigo_evento != ''){ echo('block');}else{ echo('none');} ?>" id="add_codevento" >
			<div class="panel-heading">
			  Ingresa el codigo
			</div>
			<div class="panel-body">
			  <div style="display:flex"><input type="text" name="codigo_evento" id="codigo_evento" value="<?php echo($codigo_evento);?>" class="form-control" style="width:70%">
			  <input type="button" class="btn btn-danger" id="" value=" Guardar " style="margin-left:3px" onclick="return SaveCodeEvento(); "></div>
			</div>
		  </div>		
	</div>
	<div class="col-md-4">

	</div>
</div>

	<div id="fane1" class="tab_content">
		<form action="" id="no_selecc" name="no_selecc" method="post" >
			<table id="tabla_noseleccionados" width="400" >
				<tr>
					<td colspan="2" align="center" class="Titulo1">DETALLE</td>
				</tr>
				<tr>
					<td class="SLAB2">Cantidad:</td>
					<td><input type="text" name="cantidad_ns" size="8" id="cantidad_ns" value="" class=""></td>
				</tr>
				<tr>
					<td class="SLAB2">U/Medida:</td>
					<td>
						<select name="unidad_ns" id="unidad_ns" class="styled-select"  ><option value="">Seleccione...</option>
							<?php
													require_once("scripts/funcionescombo.php");		
													$estados = dameUnidadMedida();
													foreach($estados as $indice => $registro)
													{
														?>
														<option value="<?php echo($registro['CODIGO'])?>"><?php echo($registro['DESCRIPCION']);?></option>
														<?php
													}
											   ?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="SLAB2"> Descripcion:</td>
					<td><textarea type="text" name="descrip_ns" id="descrip_ns" value="" rows="1" cols="40"class=""></textarea></td>
				</tr>
				<tr>
					<td class="SLAB2">Justificacion:</td>
					<td><textarea type="text" name="justi_ns" id="justi_ns" rows="2" cols="40" value="" class=""></textarea></td>
				</tr>
				<tr>
					<td class="SLAB2">Observaciones:</td>
					<td><textarea type="text" name="observ_ns" id="observ_ns"rows="2" cols="40" value="" class=""></textarea></td>
				</tr>
				<tr>
					<td colspan="6" align="center">
					 <div id="botonessub">
						 <input type="hidden" name="codigodetalle_ns" id="codigodetalle_ns" value="">
						 <?php
							$infoboton = "A&ntilde;adir";
							
							 //$infoboton = "Editar";
							
						 ?>
						 <input type="submit" class="button2" id="btnsub_ns" value="<?php echo($infoboton);?>"  onclick="return validarCampos('1'); "/>
						 <input type="button" class="button2" name="limpiar_ns" value="Limpiar" onclick="limpiar('0');">
						 <?php
						 
						 ?>
					 </div>
					</td>
				</tr>
			</table>
		</form>
	</div>
	<?php } ?>
	<form name="form_lista" id="form_lista" action="" method="post" enctype="multipart/form-data">
		<!-- Inicio combo de estados de requerimiento para director administrativo-->
			<?php if($_SESSION['MM_RolID']== 3 && ($estado > 2))
				{
			?>
					<span><input class="button3" type="submit" value="pasar a estado:" onclick="return pasaraestado()"></span>
	 				<select name="pasar_estado" id="pasar_estado" class="selectgris">
						<option value="">Seleccione...</option>
							<?php 
								for($i=0; $i<count($arrayestadosDiradm); $i++)
								{
							?>
									<option value="<?php echo($arrayestadosDiradm[$i]['CODIGO']);?>"><?php echo($arrayestadosDiradm[$i]['NOMBRE']);?></option>	
							<?php
								}
							?>
	 				</select>
			<?php 
				}
			?>
		<!--Fin-->	
		<!-- Inicio combo de estados de requerimiento para auxiliar administrativo-->
			<?php 	
				if($_SESSION['MM_RolID'] == 2 && ($estado > 2))
				{
			?>
					<span><input class="button3" type="submit" value="pasar a estado:" onclick="return pasaraestado()"></span>
	 				<select name="pasar_estado" id="pasar_estado" class="selectgris">
						<option value="">Seleccione...</option>
						<?php 
							for($i=0; $i<count($arrayestados); $i++)
							{
						?>
								<option value="<?php echo($arrayestados[$i]['CODIGO']);?>"><?php echo($arrayestados[$i]['NOMBRE']);?></option>	
						<?php
							}
						?>
	 				</select>
			<?php 
				}
			?>
		<!--Fin -->	
		<table class="bordered" id="tabladetalle"  style="clear:both; padding-bottom:20px; margin-top:30px; min-width:950px; width:100%">
		 <thead>
					 <tr>
							<th colspan="3"><div id="footer"><span>Listado detalle</span> 
							</div></th>
					<th colspan="16">
					 <div class="btn btn-xs btn-default" style="width:50px; float:left;" title="Seleccionar" >
						<ul class="chkdetails">
						<li><input type="checkbox" id="chkall"></li>
						<li class="dropdown">
						  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">&nbsp;<span class="caret"></span></a>
						  <ul class="dropdown-menu">
							<li><a href="javascript:todo()">Todo</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="javascript:nada()">Nada</a></li>
						  </ul>
						</li>						
						</ul>
					</div>
					<div id="containerinfoeventolista" style="background:#ffffff; margin-left:3px; float:right; padding:3px; border-radius:5px; <?php if($codigo_evento != ''){ echo('display:block');}else{ echo('display:none;');}?>">Tipo de Requerimiento Evento:<br>
					codigo: <span id="msgaddeventolistado" class="msgaddevento"><?php echo($codigo_evento);?></span>						
					</div>
						<div id="multiplesdetalles" style="display: none;margin-left: 10px;float: left;"><button type="button" class="btn btn-default">Cargar un archivo a multiples detalles</button></div>
						<button id="markAllReceived" style="padding: 8px;margin-left:10px;"> <img src="imagenes/entregado.png" width="16"  title="Entregado" >Recibido</button>

					</th>
					<?php if($_SESSION['MM_RolID']== 2){
                        $colorbandera=0;
                        if($bandera == 1){
                            $colorbandera = '#40FF00';
                        }else{
                        	 $colorbandera = '#FE2E2E';
						   }
						?>

					   <div class="btn btn-xs btn-default" style="width:50px; background-color: <?php echo($colorbandera); ?>;  float:left;" onclick="FMR_FbanderaEditar('<?php echo($codigo_requerimiento); ?>');"><span><img src="imagenes/b_edit.png" title="Corregir" width="16"></span></div>
					 <?php } ?>
					 </tr>
					 <tr class="TituloDetalles">
							<th colspan="3">Acci&oacute;n</th>
							<th>#</th>
							<th>Descripci&oacute;n</th>							
							<th width="15">Cant</th>							
							<th width="15">Und</th>
							<th>Justificacion</th>
                            <th>Observaci&oacute;n</th>
                            <th>Poa</th>								
							<th>Proveedor</th>																									
							<th>Presupuesto</th>
							<th>Factura</th>
							<th>Orden</th>
							<th>Anexo</th>
							<th width="30">..</th>
					 </tr>
			 </thead>
			 <?php

				$i = 0;	
			if ($totalRows_RsListadoDeta_Requ > 0) {				
				do {
					//consultas del color correspondiente al estado	  
						if ($row_RsListadoDeta_Requ['APROBADO'] == 0)
						{
							$query_RsColor="SELECT `ESDECODI` CODIGO,
												 `ESDENOMB` NOMBRE, 
												 `ESDECOLO` COLOR, 
												 `ESDEFLAG` ESTADO 
											FROM `estado_detalle` 
										WHERE  `ESDECODI`='13' ";
							$RsColor = mysqli_query($conexion,$query_RsColor) or die(mysqli_error($conexion));
							$row_RsColor = mysqli_fetch_array($RsColor);
							$totalRows_RsColor = mysqli_num_rows($RsColor);
		  
						}else{
										$query_RsColor="SELECT `ESDECODI` CODIGO,
													 `ESDENOMB` NOMBRE, 
													 `ESDECOLO` COLOR, 
													 `ESDEFLAG` ESTADO 
												FROM `estado_detalle` 
											WHERE  `ESDECODI`='".$row_RsListadoDeta_Requ['APROBADO']."' ";
								$RsColor = mysqli_query($conexion,$query_RsColor) or die(mysqli_error($conexion));
								$row_RsColor = mysqli_fetch_array($RsColor);
								$totalRows_RsColor = mysqli_num_rows($RsColor);	
								}
						$claseaprobado=$row_RsColor['COLOR'];		

                    //Consulta Convenio
					$query_RsConsultaConvenio = "
									SELECT C.CONVCONS  ID,
										   C.CONVIDPR  PROVEEDOR,
										   P.PROVNOMB  PROVEEDOR_DES,
										   PR.PRODCONS CODIGO_PRODUCTO,
										   PR.PRODDESC PRODUCTO_DESC,
										   CP.COPRPREC PRODUCTO_PRECIO,
										   C.CONVCOCO  COD_CONVENIO,
										   C.CONVCONT  COD_CONTRATO,
										   C.CONVFEIN  FECH_INICIO,
										   C.CONVFEFI  FECH_FIN,
										   C.CONVID    COD_PARAMETRO
									  FROM productos    PR,
										   conve_produc CP,
										   detalle_requ  D,
										   convenios     C,
										   proveedores   P
									 WHERE D.DERECONS = '".$row_RsListadoDeta_Requ['CODIGO']."'
									   AND CP.COPRID    = D.DERECONV
									   AND CP.COPRIDPC  = PR.PRODCONS
									   and CP.COPRIDCO  = C.CONVCONS
									   and  C.CONVIDPR  = P.PROVCODI
												   ";
					 $RsConsultaConvenio = mysqli_query($conexion, $query_RsConsultaConvenio) or die(mysqli_error($conexion));
					 $row_RsConsultaConvenio = mysqli_fetch_array($RsConsultaConvenio);
					 $totalRows_RsConsultaConvenio = mysqli_num_rows($RsConsultaConvenio);

					//--inicio --estilo de cada fila
						$estilo="SB";
							if($i%2==0){$estilo="SB";}
							$i++;
					//--fin--		
			 ?>
					<tr class="<?php echo($estilo);?>" id="trtr_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>">
						<td> 
						
						<div class="infodetest" ><?php echo($row_RsListadoDeta_Requ['CODIGO']); ?></div>
						
						<div><input type="checkbox" class="multiplefilechk" value="<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" name="multiplefile_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" id="multiplefile_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>"></div>
								
								<?php if($_SESSION['MM_RolID'] == 3){ ?>
								<div>  <?php if($row_RsListadoDeta_Requ['APROBADO'] == '6'  or $row_RsListadoDeta_Requ['APROBADO'] == '17'){ ?>
									  
							         <!--Inicio Boton imagen firma director Administrativo -->
							         <a id="afirmarDirAdm_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" href="javascript: FirmaDirAdministrativo('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>');"><span><i class="fa fa-thumbs-up fa-X3" aria-hidden="true"></i></span></a>
			                         <!--Fin -->
									 
									 
									 <!--Inicio Boton imagen firma rector 
                                     <a id="afirmarRect_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" href="javascript: FirmaRector('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>');">Firmar Rector </a>
			                        Fin -->
			                         <?php } ?>
									 </div>
								<?php } ?>
								
								<div class="dropdown">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <i class="fa fa-cog"></i>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
    <li>
	<div class="actiondetalle">
						
							
								<?php if($_SESSION['MM_RolID'] == 1){ ?>
								<?php } ?>

								<!-- Seccion de botones para rol 2 -->
								<?php if($_SESSION['MM_RolID'] == 2){ ?>

								        <?php if ($cedula_sol == $_SESSION['MM_UserID']){?>
									<!--Inicio Boton imagen correguir un detalle -->
								         <?php if($row_RsListadoDeta_Requ['APROBADO'] == '2'){ ?>
									 	<div onclick="FMD_FcorregirDet('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>');"><span><img src="imagenes/save.png" title="Corregir" width="16"></span></div>
									      <?php } ?>
									 <!--Fin -->
                                         
									  <!--Inicio Boton imagen editar un detalle -->
									 	<div onclick="FMD_FeditarDet('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>');"><span><img src="imagenes/b_edit.png" title="editar"  width="16"></span></div>
									 <!--Fin --> 
                                         
									 <!--Inicio Boton imagen eliminar un detalle -->
										<div onclick="FMD_FdeleteDet('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>');"><span><img src="imagenes/delete.jpg" title="Eliminar" width="16" ></span></div>
						 			 <!--Fin -->
						 			 
						 			  <?php if($row_RsListadoDeta_Requ['APROBADO'] != '8'){ ?>
						 			  <!--Inicio Boton imagen recibido de usuario general -->
							         <div id="recibeusug_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" onclick="frecibo_usuariog('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>');"><img src="imagenes/ent_profe.jpg"  title="Recibir" width="16" >&nbsp;</div>
		                              <!--Fin -->
		                               <?php } ?>
						 			 <?php } ?>
						 			 <!--Inicio Boton imagen devolver -->
										<div onclick="mostrarTickbox('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>');" ><span><img src="imagenes/retroceder.png" width="16" title="Devolver"></span></div>
							         <!--Fin -->							        
							         <!--Inicio Boton imagen compra electronica un detalle -->							          
									 	<div onclick="FMD_Electronica('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>','<?php echo($row_RsListadoDeta_Requ['CAMPO_ELECTRONICA']); ?>','<?php echo($_SESSION['MM_RolID']); ?>');"><span><img src="imagenes/tarjetas.png" title="compra electronica"  width="16"></span></div>
									 <!--Fin -->
							          <!--Inicio Boton imagen viaticos un detalle -->							          
									 	<div onclick="FMD_Viaticos('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>','<?php echo($row_RsListadoDeta_Requ['CAMPO_VIATICOS']); ?>','<?php echo($_SESSION['MM_RolID']); ?>');"><span><img src="imagenes/avion.jpg" title="viaticos"  width="16"></span></div>
									 <!--Fin -->
									  <!--Inicio Boton imagen actualizar pagina -->
										<div onclick="location.reload();" ><span><img src="imagenes/devolver.png" width="16" title="actualizar"></span></div>
							         <!--Fin -->							         
                                      <!--Inicio Boton imagen Historial -->
										<div onclick="ver_historial('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>');" ><span><img src="imagenes/historial.png" width="16" title="historial"></span></div>
							         <!--Fin --> 
							         <!--Inicio Boton imagen reiniciar a 0 -->
										<div target="_blank" onclick="FMD_ReiniciarDet('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>');" ><span><img src="imagenes/reiniciar.jpg" width="16"  title="Reiniciar a 0" ></span></div>
							         <!--Fin -->
							         <!--Inicio Boton imagen comparativo -->
										<div target="_blank" onclick="location.href=('comparar.php?tipocompara=detalle&codDetalle=<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>')" ><span><img src="imagenes/compare.png" width="16"  title="Comparar Detalle Productos" ></span></div>
							         <!--Fin -->
									 <!--Inicio Boton imagen entregado a usuario general -->
										<div  onclick="fentrega('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>','0');" ><span><img src="imagenes/entregado.png" width="16"  title="Entregado" ></span></div>
							         <!--Fin -->
								<?php } ?>


								<?php if($_SESSION['MM_RolID'] == 3){ ?>

							         <!--Inicio Boton imagen autorizar 
										<div onclick="FMD_AprobarDetalle('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>');" ><span><img src="imagenes/aceptar.png" width="16" title="autorizar"></span></div>
							         Fin --> 
								          <!--Inicio Boton imagen Cancelar o Rechazar -->
							         <div onclick="CancelarDetalle('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>');" ><span><img src="imagenes/cancelar.png"  width="16" title="Cancelar - rechazar"></span></div>
                                     <!--Fin -->
                                     <!--Fin -->
                                      <?php if($row_RsListadoDeta_Requ['APROBADO'] == '32'){ ?>
										  <!--Inicio Boton imagen menor cuantia un detalle -->							          
									 	<div onclick="FMD_Menorcuantia('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>');"><span><img src="imagenes/pesos.png" title="menor cuantia"  width="16"></span></div>
									 <!--Fin -->
									  <?php } ?>
									   <?php if($row_RsListadoDeta_Requ['APROBADO'] == '29'){ ?>
                                     <!--Inicio Boton imagen compra electronica un detalle -->							          
									 	<div onclick="FMD_Electronica('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>','<?php echo($row_RsListadoDeta_Requ['CAMPO_ELECTRONICA']); ?>','<?php echo($_SESSION['MM_RolID']); ?>');"><span><img src="imagenes/tarjetas.png" title="compra electronica"  width="16"></span></div>
									 <!--Fin -->
									 <?php } ?>
									 <?php if($row_RsListadoDeta_Requ['APROBADO'] == '24'){ ?>
										  <!--Inicio Boton imagen viaticos un detalle -->							          
									 	<div onclick="FMD_Viaticos('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>','<?php echo($row_RsListadoDeta_Requ['CAMPO_VIATICOS']); ?>','<?php echo($_SESSION['MM_RolID']); ?>');"><span><img src="imagenes/avion.jpg" title="viaticos"  width="16"></span></div>
									 <!--Fin -->
									 <?php } ?>
									 <!--Inicio Boton imagen actualizar pagina -->
										<div onclick="location.reload();" ><span><img src="imagenes/devolver.png" width="16" title="actualizar"></span></div>
							         <!--Fin -->							         
                                      <!--Inicio Boton imagen Historial -->
										<div onclick="ver_historial('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>');" ><span><img src="imagenes/historial.png" width="16" title="historial"></span></div>
							         <!--Fin --> 
							         <!--Inicio Boton imagen comparativo -->
										<div target="_blank" onclick="location.href=('comparar.php?tipocompara=detalle&codDetalle=<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>')" ><span><img src="imagenes/compare.png" width="16"  title="Comparar Detalle Productos" ></span></div>
							         <!--Fin -->
							           
							        
									 
									 <?php if($row_RsListadoDeta_Requ['APROBADO'] == '17' ){ ?>							         
									 <!--Inicio Boton imagen firma rector 
                                     <a id="afirmarRect_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" href="javascript: FirmaRector('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>');">Aprobar Compra </a>
			                          <!--Fin -->
			                         <?php } ?>
								<?php } ?>
								
								
								
								
								
								
								
								
								
								
								
								<?php if($_SESSION['MM_RolID'] == 4){ ?>
								<?php if ($cedula_sol == $_SESSION['MM_UserID']){?>
									<!--Inicio Boton imagen correguir un detalle -->
								         <?php if($row_RsListadoDeta_Requ['APROBADO'] == '2'){ ?>
									 	<div onclick="FMD_FcorregirDet('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>');"><span><img src="imagenes/save.png" title="Corregir" width="16"></span></div>
									      <?php } ?>
									 <!--Fin -->
									 <?php if($bandera == 1){ ?>
									  <!--Inicio Boton imagen editar un detalle -->
									 	<div onclick="FMD_FeditarDet('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>');"><span><img src="imagenes/b_edit.png" title="editar"  width="16"></span></div>
									 <!--Fin -->
									 <?php } ?> 
									 <!--Inicio Boton imagen eliminar un detalle -->
										<div onclick="FMD_FdeleteDet('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>');"><span><img src="imagenes/delete.jpg" title="Eliminar" width="16" ></span></div>
						 			 <!--Fin -->

						 			  <?php if($row_RsListadoDeta_Requ['APROBADO'] != '8'){ ?>
						 			  <!--Inicio Boton imagen recibido de usuario general -->
							         <div id="recibeusug_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" onclick="frecibo_usuariog('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>');"><img src="imagenes/ent_profe.jpg"  title="Recibir" width="16" >&nbsp;</div>
		                              <!--Fin -->
		                               <?php } ?>
						 			 <?php } ?>
								<?php } ?>

								<?php if($_SESSION['MM_RolID'] == 5){ ?>

                                <?php if ($cedula_sol == $_SESSION['MM_UserID']){?>
                               
								<!--Inicio Boton imagen correguir un detalle -->
								         <?php if($row_RsListadoDeta_Requ['APROBADO'] == '2'){ ?>
									 	<div onclick="FMD_FcorregirDet('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>');"><span><img src="imagenes/save.png" title="Corregir" width="16"></span></div>
									      <?php } ?>
									 <!--Fin -->
									
									  <!--Inicio Boton imagen editar un detalle -->
									 	<div onclick="FMD_FeditarDet('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>');"><span><img src="imagenes/b_edit.png" title="editar"  width="16"></span></div>
									 <!--Fin --> 
									 <!--Inicio Boton imagen eliminar un detalle -->
										<div onclick="FMD_FdeleteDet('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>');"><span><img src="imagenes/delete.jpg" title="Eliminar" width="16" ></span></div>
						 			 <!--Fin -->
						 			 
						 			  <?php if($row_RsListadoDeta_Requ['APROBADO'] != '8'){ ?>
						 			  <!--Inicio Boton imagen recibido de usuario general -->
							         <div id="recibeusug_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" onclick="frecibo_usuariog('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>');"><img src="imagenes/ent_profe.jpg"  title="Recibir" width="16" >&nbsp;</div>
		                              <!--Fin -->
		                               <?php } ?>
						 			 <?php } ?>
						 			 <?php if ($row_RsListadoDeta_Requ['CAMPO_VIATICOS'] == 2){ ?>
									 <!--Inicio Boton imagen viaticos un detalle -->							          
									 	<div onclick="FMD_Viaticos('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>','<?php echo($row_RsListadoDeta_Requ['CAMPO_VIATICOS']); ?>','<?php echo($_SESSION['MM_RolID']); ?>');"><span><img src="imagenes/avion.jpg" title="viaticos"  width="16"></span></div>
									 <!--Fin -->
									 <!--Inicio Boton imagen Cancelar o Rechazar -->
							         <div onclick="CancelarDetalle('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>');" ><span><img src="imagenes/cancelar.png"  width="16" title="Cancelar - rechazar"></span></div>
                                     <!--Fin --> 

									 <?php }?> 
									 <?php if ($row_RsListadoDeta_Requ['CAMPO_ELECTRONICA'] == 2){ ?>
									  <!--Inicio Boton imagen compra electronica un detalle -->							          
									 	<div onclick="FMD_Electronica('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>','<?php echo($row_RsListadoDeta_Requ['CAMPO_ELECTRONICA']); ?>','<?php echo($_SESSION['MM_RolID']); ?>');"><span><img src="imagenes/tarjetas.png" title="compra electronica"  width="16"></span></div>
									 <!--Fin -->
									  <!--Inicio Boton imagen Cancelar o Rechazar -->
							         <div onclick="CancelarDetalle('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>');" ><span><img src="imagenes/cancelar.png"  width="16" title="Cancelar - rechazar"></span></div>
                                     <!--Fin --> 

									 <?php }?> 
									  
							          <?php if($row_RsListadoDeta_Requ['APROBADO'] == '17'){ ?>
							          <!--Inicio Boton imagen Cancelar o Rechazar -->
							         <div onclick="CancelarDetalle('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>');" ><span><img src="imagenes/cancelar.png"  width="16" title="Cancelar - rechazar"></span></div>
                                     <!--Fin --> 						         
							          
			                          
							          <?php }?>
							           <!--Inicio Boton imagen comparativo -->
										<div target="_blank" onclick="location.href=('comparar.php?tipocompara=detalle&codDetalle=<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>')" ><span><img src="imagenes/compare.png" width="16"  title="Comparar Detalle Productos" ></span></div>
							         <!--Fin -->
							          <!--Inicio Boton imagen actualizar pagina -->
										<div onclick="location.reload();" ><span><img src="imagenes/devolver.png" width="16" title="actualizar"></span></div>
							         <!--Fin -->							         
                                      <!--Inicio Boton imagen Historial -->
										<div onclick="ver_historial('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>');" ><span><img src="imagenes/historial.png" width="16" title="historial"></span></div>
							         <!--Fin -->      
						 			 

								<?php } ?>
								
								<!-- Seccion de botones para rol 6 -->
									<?php if($_SESSION['MM_RolID'] == 6){ ?>
										<!--Inicio Boton imagen Cancelar o Rechazar -->
											<div onclick="CancelarDetalle('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>');" ><span><img src="imagenes/cancelar.png"  width="16" title="Cancelar - rechazar"></span></div>
											
											<div><a title="aprobar Coordinador" onclick="AprobarCoordinador('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>');"><i class="fa fa-thumbs-up fa-2x" aria-hidden="true"></i><a></div>
										<!--Fin --> 	
									<?php } ?>
							    </div>		
	</li>
  </ul>
</div>

						</td>
						<td>
							 <?php  if ($_SESSION['MM_RolID'] != 4){?>
							 <?php  if ($_SESSION['MM_RolID'] == 2){?>
						    <!-- inicio boton combo de tipo de orden de compra -->		
								 <select name="TipoCompra_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" 
									     id="TipoCompra_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" 
										 class="chzn-select" 
										 onchange="FMD_TipoCompra('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>',this.value)">				
										<option value="">-Tipo-</option>
									         <?php
													require_once("scripts/funcionescombo.php");		
													$estados = dameTipoOrdenCompra();
													foreach($estados as $indice => $registro)
													{
														?>
														<option value="<?php echo($registro['CODIGO'])?>"><?php echo($registro['DESCRIPCION']);?></option>
														<?php
													}
											   ?>
								</select>
							<!-- fin -->
						
							<!-- inicio boton combo Classificacion de compra -->		
							    <select name="TipoClasCompra_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" 
									     id="TipoClasCompra_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" 
										 class="chzn-select" 
										 onchange="FMD_TipoClasCompra('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>',this.value)">				
										<option value="">-Clasificaci&oacute;n-</option>
									         <?php
													require_once("scripts/funcionescombo.php");		
													$estados = dameTipoCompra();
													foreach($estados as $indice => $registro)
													{
														?>
														<option value="<?php echo($registro['CODIGO'])?>"><?php echo($registro['DESCRIPCION']);?></option>
														<?php
													}
											   ?>
								</select>
							<!-- fin -->        
							<!-- inicio boton combo Convenio de compra -->
								<select name="Convdetalle_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" id="Convdetalle_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" class="chzn-select" onchange="ShowProductosConvenio('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>', this.value)">				
									<option value="">- SELECCIONAR LISTADO PROD -</option>
										<?php
											require_once("scripts/funcionescombo.php");		
											$estados = dameConvenio();
											foreach($estados as $indice => $registro)
											{
										?>
												<option value="<?php echo($registro['ID'])?>"><?php echo($registro['PROVEEDOR_DES']);?></option>
										<?php
											}
										?>
								</select>
                            <!-- fin -->
                            <!-- inicio boton combo Productos asociados a Convenio de compra -->
								<select id="productoconvenio_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" name="productoconvenio_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" class="combo_convenio" onchange="FMD_ConvenioProducto('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>',this.value)"> >
									<option value="">- Seleccionar un producto listado -</option>
								</select>
                            <!-- fin -->
                             <?php } ?>
                            <?php  
                            if ($_SESSION['MM_RolID'] == 3)
                            {?>
                            <!-- inicio boton combo POA  -->
	   						 	 <div id="divpoadeta_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" style="<?php if($row_RsListadoDeta_Requ['REQUIERE_OTRO']=='1'){ echo('display:none;');} ?>">
				  					<select 	name="poadetalle_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" 	
				  								id="poadetalle_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" 
				  								class="chzn-select" 
				  								onchange="FMD_Poa('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>',this.value)">				
										<option value="">- POA -</option>
											<?php
												require_once("scripts/funcionescombo.php");		
												$estados = damePoa();
												foreach($estados as $indice => $registro){
											?>
											<option value="<?php echo($registro['POACODI'])?>"><?php echo($registro['POANOMB']);?></option>
											<?php
												}
											?>
									</select>
							 <!-- fin -->
							  <!-- inicio boton combo Centro de costo (subpoa)  -->		
		  							<select name="subpoadetalle_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" 
		  									id="subpoadetalle_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>" 
		  									class="chzn-select"
		  									onchange="FMD_SubPoa('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>',this.value)">		  													
									<option value="">- CENTRO DE COSTO -</option>
										<?php
											require_once("scripts/funcionescombo.php");		
											$estados = dameCentroCosto();
					                        foreach($estados as $indice => $registro){
										?>
											<option value="<?php echo($registro['PODECODI'])?>"><?php echo($registro['PODENOMB']);?></option>
										<?php
											}
										?>
									</select>							  	
									&nbsp;
									
		 					  	</div>
		 					 <!-- fin -->	 
							 <!-- inicio boton de chequeo otro POA o SUBPOA  -->			
				  				<span class="otropoa">ingresar otro poa y subpoa</span>
		  						<input 	type="checkbox" 
		  								onclick="Otropoa('<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>')" 	
		  								name="otro_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" 
		  								value="1" id="otro_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" <?php if($row_RsListadoDeta_Requ['REQUIERE_OTRO']=='1'){ echo('checked');} ?>
                                        
		  								>
		  						
		  							<div id="divotropoa_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" style="<?php if($row_RsListadoDeta_Requ['REQUIERE_OTRO']=='1'){ echo('display:block;'); }else{ echo('display:none');} ?>">
		  								<span class="otropoa">otro</span>&nbsp;
		  								<input onchange="FMD_OtroPoa('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>')"
		  										type="text" 
		  										name="otropoa_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" 
		  										id="otropoa_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>"
		  										value="<?php echo($row_RsListadoDeta_Requ['OTRO_DES']);?>" 
		  										maxlength="100">
		  										&nbsp;
		 							</div>
		 					  <!-- fin -->

		 					  <!-- inicio check de concejos-->
		 					  <div class="actiondetalle">
		 					   <input type="checkbox" id="consejosuperior_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" name="consejosuperior_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" title="Consejo Superior" value="1" <?php if($row_RsListadoDeta_Requ['CONSEJO_SUPERIOR']==1){ echo('checked');} ?> >Consejo Superior<br>
					 			<input type="checkbox" id="consejotecnologico_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" name="consejotecnologico_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" title="Comite Tecnologico" value="1" <?php if($row_RsListadoDeta_Requ['CONSEJO_TECNOLOGICO']==1){ echo('checked');} ?>>Comite Tecnologico<br>
					 			<input type="checkbox" id="comiteinfra_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" name="comiteinfra_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" title="Comite Infraestructura" value="1" <?php if($row_RsListadoDeta_Requ['COMITE_INFRAESTRUCTURA']==1){ echo('checked');} ?> >Comite Infraestructura<br>
			                   </div>
		 					  <!--fin -->
		 					   <?php } ?>	
		 					        <?php }?>	
		  				</td>
						<td>
							<?php
							//Mensaje de Tipo Compra 
				 				if($row_RsListadoDeta_Requ['TIPOORDENCOMPRA_DES'] != '')
				 				{
				 				echo('<div class="infodetest" >**Tipo:<br>'.$row_RsListadoDeta_Requ['TIPOORDENCOMPRA_DES'].'**</div>');
				 			    }else{}
				 			//Mensaje Clasificacion de  Compra 
				 				if($row_RsListadoDeta_Requ['CLASIFICACION_DES'] != '')
				 				{
				 				echo('<div class="infodetest" >**Clasificaci&oacute;n:<br>'.$row_RsListadoDeta_Requ['CLASIFICACION_DES'].'**</div>');	
						        }else{}
						    //Mensaje de convenio
				 				if($totalRows_RsConsultaConvenio > 0)
				 				{
				 					echo('<div class="infodetest" >**Convenio:<br>'.$row_RsConsultaConvenio['PROVEEDOR_DES'].'** **Producto:<br>'.$row_RsConsultaConvenio['PRODUCTO_DESC'].'** **Precio:<br>'.$row_RsConsultaConvenio['PRODUCTO_PRECIO'].'</div>');
						    	}else{}
                            //mensajes concejos
						    	if($row_RsListadoDeta_Requ['CONSEJO_SUPERIOR']==1){ echo('<div class="infodetest" >*Consejo Superior</div><br>');} 
								if($row_RsListadoDeta_Requ['CONSEJO_TECNOLOGICO']==1){ echo('<div class="infodetest" >*Consejor Tecnologico</div><br>');} 
								if($row_RsListadoDeta_Requ['COMITE_INFRAESTRUCTURA']==1){ echo('<div class="infodetest" >*Comite Infraestructura</div><br>');} 

						    ?>
						</td>
						<td title="<?php echo($row_RsListadoDeta_Requ['APROBADO_DES']); ?>" bgcolor="<?php echo($claseaprobado);?>">		
							<?php echo($i); ?>
						</td>
						 <td>
						   <p id="parrafodescr_<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>"><?php if(strlen($row_RsListadoDeta_Requ['DESCRIPCION'])<100){ echo($row_RsListadoDeta_Requ['DESCRIPCION']); }else{ echo(substr($row_RsListadoDeta_Requ['DESCRIPCION'],0,100));?>&nbsp;&nbsp;<a href="javascript:crearfila('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>','desc');">...</a><?php } ?><br></p>
						</td>
						<td>
							 <?php  if ($_SESSION['MM_RolID'] != 4){?>
							<input type="text" onChange="FMD_CambiarCantidad('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>');"   name="inpcantidad_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" id="inpcantidad_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" value="<?php echo($row_RsListadoDeta_Requ['CANTIDAD']); ?>" size="4">
						      <?php }else {?>
						      <?php echo($row_RsListadoDeta_Requ['CANTIDAD']); ?>
						      <?php } ?>
						</td>
						<td width="6">
							 <?php echo($row_RsListadoDeta_Requ['UNIDAD_MEDIDA_DES']); ?>
			            </td>
						<td>
							<p><?php if(strlen($row_RsListadoDeta_Requ['JUSTIFICACION'])<100){ echo($row_RsListadoDeta_Requ['JUSTIFICACION']); }else{ echo(substr($row_RsListadoDeta_Requ['JUSTIFICACION'],0,100));?>&nbsp;&nbsp;&nbsp;<a href="javascript:crearfila('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>','just');" >... </a><?php } ?><br></p>
						</td>
						<td>
							<p><?php if(strlen($row_RsListadoDeta_Requ['OBSERVACION'])<100){ echo($row_RsListadoDeta_Requ['OBSERVACION']); }else{ echo(substr($row_RsListadoDeta_Requ['OBSERVACION'],0,100)); ?>&nbsp;&nbsp;&nbsp;<a href="javascript:crearfila('<?php echo($row_RsListadoDeta_Requ['CODIGO']); ?>','obs');">... </a><?php } ?><br></p>
						</td>
						<td>
							<?php
							        if( $row_RsListadoDeta_Requ['POA_DETALLE_DES'] != '' && $row_RsListadoDeta_Requ['SUBPOA_DETALLE_DES'] != ''){
							        	echo('<span style="color:#555"><b>Poa:</b>'.$row_RsListadoDeta_Requ['POA_DETALLE_DES'].'<br><b>Subpoa:</b> '.$row_RsListadoDeta_Requ['SUBPOA_DETALLE_DES'].'</span>');
							        }
							        if($row_RsListadoDeta_Requ['OTRO_DES'] != ''){
							        	echo('<span style="color:#555"><b>Otro:</b> '.$row_RsListadoDeta_Requ['OTRO_DES'].'</span><br>');
							        }
			   						
				 			   		
				 				?>
						</td>
						<td>
				        <?php echo($row_RsListadoDeta_Requ['PROVEEDOR_DESC_DET']); ?>
						</td>
						<td>
							 <?php  if ($_SESSION['MM_RolID'] == 3){?>
							<input onChange="FMD_validarSiNumero(this.value);" type="text" name="presup_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>" id="presup_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>"
		  value="<?php echo($row_RsListadoDeta_Requ['PRESUPUESTO']);?>" maxlength="100">
						 <?php  }else {?>
						 <?php echo($row_RsListadoDeta_Requ['PRESUPUESTO']);?>
						 <?php } ?>
						</td>						
						<td>
							<?php  if ($_SESSION['MM_RolID'] != 4){?>
							<div class="FactDetalles" id="commentdet_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>"><span class="numcoment"></span></div>
						 <?php  }else {?>
						 <?php echo('');?>
						 <?php } ?>
						</td>
						<td>
	
		<?php if($row_RsListadoDeta_Requ['APROBADO'] != 2 && $row_RsListadoDeta_Requ['APROBADO'] != 3) {
		
//consulta orden de compra normal		
			$query_RsConsultaFirma_orden = "SELECT ORCOIDPR ORDEN_PROVEEDOR,
												   ORCOCONS ORDEN_COD, 
												   (SELECT FIRMCONS 
												   	FROM FIRMAS 
												   	WHERE ORCOFIRM=FIRMCONS) FIRMA_ID,
												   	ORCOFIRM2 AUTORIZA_DIRECTORADMIN 
											FROM `orden_compradet`,
											 	  ORDEN_COMPRA 
											where ORCDDETA='".$row_RsListadoDeta_Requ['CODIGO']."' 
											AND ORCOCONS=ORCDORCO
						 ";
						 //echo($query_RsConsultaFirma_orden);
	$RsConsultaFirma_orden = mysqli_query($conexion,$query_RsConsultaFirma_orden) or die(mysqli_error($conexion));
	$row_RsConsultaFirma_orden = mysqli_fetch_array($RsConsultaFirma_orden);
	$totalRows_RsConsultaFirma_orden = mysqli_num_rows($RsConsultaFirma_orden);
		
// orden de compra de convenio
	$query_RsConsultaOrdenConvenio = "SELECT PROVCODI CODIGO_UNI_PROVEEDOR 
									FROM PROVEEDORES,
									ORDEN_COMPRA_CONVENIO,
									orden_compconv_detalle,
									convenios 
									WHERE ORCDDETA='".$row_RsListadoDeta_Requ['CODIGO']."' 
									AND PROVCODI=CONVIDPR 
									AND ORCOIDCO=CONVCONS 
									AND ORCDORCC=ORCOCONS
						 ";
	$RsConsultaOrdenConvenio = mysqli_query($conexion,$query_RsConsultaOrdenConvenio) or die(mysqli_error($conexion));
	$row_RsConsultaOrdenConvenio = mysqli_fetch_array($RsConsultaOrdenConvenio);
	$totalRows_RsConsultaOrdenConvenio = mysqli_num_rows($RsConsultaOrdenConvenio);

//orden de compra de menor cuantia

	$query_RsConsultaOrdenMenorCuantia = "SELECT PROVCODI CODIGO_UNI_PROVEEDOR_MC
										  FROM proveedores_nocotiza,
										  orden_compra_ncotiza,
										  orden_compradet_nocotiza
										  WHERE OCDNDETA='".$row_RsListadoDeta_Requ['CODIGO']."' 
										  and PROVCODI=ORNCIDPN 
										  and OCDNORCO=ORNCCONS
	
	
											
						 ";
	$RsConsultaOrdenMenorCuantia = mysqli_query($conexion,$query_RsConsultaOrdenMenorCuantia) or die(mysqli_error($conexion));
	$row_RsConsultaOrdenMenorCuantia = mysqli_fetch_array($RsConsultaOrdenMenorCuantia);
	$totalRows_RsConsultaOrdenMenorCuantia = mysqli_num_rows($RsConsultaOrdenMenorCuantia);	




//orden de compra de menor cuantia

	$query_RsConsultaOrdenAutorizaAdm = "	SELECT orcofirm,
												   orcofirm2  
											FROM   orden_compra,
												  `orden_compradet`, 
												   detalle_requ 
										    where  derecons='".$row_RsListadoDeta_Requ['CODIGO']."' 
										    and    orcddeta=derecons 
										    and    orcdorco=orcocons 
										    and    orcofirm = '0' 
										    and    orcofirm2 = '0'
										    and    dereapro =16

	
											
						 ";
	$RsConsultaOrdenAutorizaAdm = mysqli_query($conexion,$query_RsConsultaOrdenAutorizaAdm) or die(mysqli_error($conexion));
	$row_RsConsultaOrdenAutorizaAdm = mysqli_fetch_array($RsConsultaOrdenAutorizaAdm);
	$totalRows_RsConsultaOrdenAutorizaAdm = mysqli_num_rows($RsConsultaOrdenAutorizaAdm);	
			?>
		
		
		<?php if($row_RsListadoDeta_Requ['ORDEN_COTIZADA'] != '0'){?>
		<div><a   target="_blank" href="http://compras.sanboni.edu.co/O.php?codprov=<?php echo($row_RsConsultaFirma_orden['ORDEN_PROVEEDOR']); ?>&codcomp=<?php echo($row_RsConsultaFirma_orden['ORDEN_COD']); ?>&%=2&f=<?php echo($row_RsConsultaFirma_orden['FIRMA_ID']); ?>"><?php echo($row_RsConsultaFirma_orden['ORDEN_COD']); ?>
		
		</a></div>

		<?php if($_SESSION['MM_RolID']==3 &&  $totalRows_RsConsultaOrdenAutorizaAdm > '0' ){ ?>
		
		   <input type="button" id="btnautoriza_<?php echo($row_RsConsultaFirma_orden['ORDEN_COD']);?>" class="buttonazul" value="Autorizar" onclick="visto_director_admin('<?php echo($row_RsConsultaFirma_orden['ORDEN_COD']);?>','<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>');"/>
		
		<?php } ?>	
		
		<?php }
//190.107.23.165/
		if($row_RsListadoDeta_Requ['ORDEN_CONVENIO'] != '0'){ ?> 
			<div ><a  target="_blank" href="http://190.107.23.165/compras/C.php?codprov=<?php echo($row_RsConsultaOrdenConvenio['CODIGO_UNI_PROVEEDOR']); ?>&codcomp=<?php echo($row_RsListadoDeta_Requ['ORDEN_CONVENIO']); ?>&%=0"><?php echo($row_RsListadoDeta_Requ['ORDEN_CONVENIO']);?></a></div>&nbsp;
			<?php
		}
		
		if($row_RsListadoDeta_Requ['ORDEN_MENOR_CUANTIA'] != '0'){ ?> 
			<div ><a  target="_blank" href="http://190.107.23.165/compras/N.php?codprov=<?php echo($row_RsConsultaOrdenMenorCuantia['CODIGO_UNI_PROVEEDOR_MC']); ?>&codcomp=<?php echo($row_RsListadoDeta_Requ['ORDEN_MENOR_CUANTIA']); ?>&%=0"><?php echo($row_RsListadoDeta_Requ['ORDEN_MENOR_CUANTIA']);?></a></div>&nbsp;
			<?php
		}
		}?>
		</td>
		<?php 
		
		?>
		
												
						<td>
						
							<div class="AnexosDetalles" id="commentdet_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>"><span class="numcoment"></span></div>
						    
												</td>	
						<td>
		  					<div class="comentdetalle"     id="commentdet_<?php echo($row_RsListadoDeta_Requ['CODIGO']);?>"><span class="numcoment"><?php echo($row_RsListadoDeta_Requ['CANTIDAD_OBS']);?></span></div>
						</td>
						 
					</tr>
			 <?php } while ($row_RsListadoDeta_Requ = mysqli_fetch_array($RsListadoDeta_Requ));
			?>       
				    <tr bgcolor="#F4F3F1">
						<td colspan="19" align="center">
						 	 <?php  if ($_SESSION['MM_RolID'] == 2 && $estado == 2){?>
						 	 <input type="button" class="button2" id="btnsub_ns" value="Visto" onclick="return FVistoReq('5'); ">
						 	<input type="button" class="button2" id="btnsub_ns" value="Recibir" onclick="return Recibir('5'); ">
						 	<!-- visto el requerimiento para casos de requerimientos con todos los detalles en devuelto-->
							<input type="button" class="button2" id="btnsub_ns" value="No Recibir" onclick="return Recibir('4'); ">	
                              <?php  } ?>
							  <?php  if ($_SESSION['MM_RolID'] == 6 && $estado == 2){?>
							  <input type="button" class="button2" id="btnsub_ns" value="Visto" onclick="return FVistoReq('5'); ">						 	
							
                              <?php  } ?>
                              <?php  if ($codigo_req == ''){?>
							<input class="button2" type="submit"  value="Enviar " onclick="return Fenviar();"/>
							<input class="button2" type="button"  value="Posponer" onclick="return volveraListado();"/>
							<?php  } ?>
						</td>
					</tr>
			<?php }else{ echo('<td>Sin datos</td>');} ?>		
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
			  <td height="35"><a href="downloadfile.php?doc=<?php echo($row_RsArchivosLista['ARCHIVO']);?>&tipopath=ug" target="_blank" class="buttonrojo">descargar</a></td>
			  <?php if($_SESSION['MM_RolID']==4){ ?>
			  <td height="35"><a class="buttonrojo" onclick="ElimArchivo('<?php echo($row_RsArchivosLista['CODIGO'])?>');">eliminar</a></td>
			  <?php }?>
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

    <!-- Inicio formulario de archivos -->
    	<form name="form_req" id="form_req" method="post" action="" enctype="multipart/form-data">
 			<table border="0" width="800">
 				<?php
				if($estado == 1 && $totalRows_RsListadoDeta_Requ>0)
				{
				?><!--
 					<tr bgcolor="#F4F3F1">
						<td class="SLAB3">
							Anexo:
						</td>
						<td>
	 						<input type="file" name="archivo1" id="archivo1" />
	 						<input class="button2" type="submit"  value="Subir Archivo" onclick="return subirarchivo();"/>
						</td>
					</tr>-->
				<?php
				}
				?>
 					
 			</table>
 		</form>
    <!-- Fin de archivos-->
    			<table border="0" width="800">
					<tr>
   						<td colspan="6" align="center">
     						<input class="button2" type="button"  value="Volver a Listado Requerimientos" onclick="return volveraListado();"/>
   						</td>
 					</tr>
			     </table>











	

</div>











<script type="text/javascript"> $(".chzn-select, .combo_convenio").chosen(); $(".chzn-select-deselect").chosen({allow_single_deselect:true}); </script>

<script type="text/javascript">
function add_codevento(obj){
		if(obj.checked == true){
			$("#add_codevento").css("display","block");
		}else{
			if($("#codigo_evento").val() != ''){
				if(confirm("Realmente desea eliminar el codigo de evento ?")){
						$.ajax({
								 type: "POST",
								 url: "tipo_guardar.php?tipoGuardar=eliminarcodigoevento&codevento="+$("#codigo_evento").val()+"&codigo_requerimiento=<?php echo($codigo_requerimiento);?>",
								 dataType: 'json',
								 success : function(t)
								 {
									$("#add_codevento").css("display","none");
									$("#containerinfoeventolista").css("display","none");
									$("#codigo_evento").val('');
									$("#msgaddevento").text('');
									$("#msgaddeventolistado").text('');	
									
									toastr.success('<strong>codigo de evento Eliminado </strong>');
									/*setTimeout(function(){
									$("#add_codevento").css("display","none");
									},1000)*/
													
								 },	
									error   : callback_error,
								});						
					
				}else{
					obj.checked = true;
					$("#add_codevento").css("display","block");
				}
			}else{
				obj.checked = false;
				$("#add_codevento").css("display","none");	
				deleteevento();
				
			}
			
		}
}
function deleteevento(){
	if($("#msgaddevento").text() != ''){
		$.ajax({
				 type: "POST",
				 url: "tipo_guardar.php?tipoGuardar=eliminarcodigoevento&codevento="+$("#codigo_evento").val()+"&codigo_requerimiento=<?php echo($codigo_requerimiento);?>",
				 dataType: 'json',
				 success : function(t)
				 {
					$("#add_codevento").css("display","none");
					$("#containerinfoeventolista").css("display","none");
					$("#codigo_evento").val('');
					$("#msgaddevento").text('');
					$("#msgaddeventolistado").text('');	
					
					toastr.success('<strong>codigo de evento Eliminado </strong>');
					/*setTimeout(function(){
					$("#add_codevento").css("display","none");
					},1000)*/
									
				 },	
					error   : callback_error,
				});					
	}
}
function SaveCodeEvento(){	
 if($("#codigo_evento").val() == '')
  {
   inlineMsg('codigo_evento','debe ingresar el codigo de evento.',3);
		return false;
  }	
  if(confirm("Seguro desea almacenar este codigo de evento?")){
		$.ajax({
				 type: "POST",
				 url: "tipo_guardar.php?tipoGuardar=guardarcodigoevento&codevento="+$("#codigo_evento").val()+"&codigo_requerimiento=<?php echo($codigo_requerimiento);?>",
				 dataType: 'json',
				 success : function(t)
				 {
					$("#msgaddevento").text(': '+$("#codigo_evento").val());
					$("#msgaddeventolistado").text(''+$("#codigo_evento").val());
					$("#containerinfoeventolista").css("display","block");
				 	toastr.success('<strong>codigo de evento almacenado correctamente </strong>');
					/*setTimeout(function(){
					$("#add_codevento").css("display","none");
					},1000)*/
				 					
				 },	
					error   : callback_error,
				});	  
  }
  
}
//Funciones de manipulacion datos de Detalles
     
	function FMD_TipoCompra(cd,par)
	{
		$.ajax({
				 type: "POST",
				 url: "tipo_guardar.php?tipoGuardar=GuardarTipoCompra&tipocompra="+par+"&codigo_detalle="+cd,
				 dataType: 'json',
				 success : function(t)
				 {
				 	alert('Su Actividad Se Ejecuto Exitosamente');
				 					
				 },	
					error   : callback_error,
				});

	}

	function FMD_TipoClasCompra(cd,par)
	{
		//Parametro es de Convenios
		if(par=='3'){		   
			alert('Recuerde marcar el Listado de producto y el producto asociado');
		}

		//Parametro es de menor Cuantia
		if(par=='2'){		   
			alert('Recuerde las compras de menor cuantia deben ser inferiores a 1 SMLV Durante el mes Contabilizado ');
		}

		$.ajax({
				 type: "POST",
				 url: "tipo_guardar.php?tipoGuardar=GuardarTipoClasCompra&tipoclascomp="+par+"&codigo_detalle="+cd,
				 dataType: 'json',
				 success : function(t)
				 {  
				 	alert('Su Actividad Se Ejecuto Exitosamente');
				 					
				 },	
					error   : callback_error,
				});



	}

	function FMD_ConvenioProducto(cd){

		//variables c de convenio id del combo
	   	c =  document.getElementById('Convdetalle_'+cd).value; 
	   
	    //variables cp de producto asociado al convenio id del combo
	   	cp = document.getElementById('productoconvenio_'+cd).value;   
	  
	   $.ajax({
						type: "POST",
						url: "tipo_guardar.php?tipoGuardar=GuardarConvProdDetalle&coddet="+cd+"&conv="+c+"&conv_prod="+cp,
						dataType: 'json',
						success : function(t)
						 {  
						 	//console.log(t);
						 	alert('Su Actividad Se Ejecuto Exitosamente');
						 					
						 },	
						error   : callback_error
					});
	}


	function FMD_Poa(cd,par){

		$.ajax({
				 type: "POST",
				 url: "tipo_guardar.php?tipoGuardar=GuardarPoa&poa="+par+"&codigo_detalle="+cd,
				 dataType: 'json',
				 success : function(t)
				 {  
				 	alert('Su Actividad Se Ejecuto Exitosamente');
				 					
				 },	
					error   : callback_error,
				});
	}	

	function FMD_SubPoa(cd,par){

		$.ajax({
				 type: "POST",
				 url: "tipo_guardar.php?tipoGuardar=GuardarSubPoa&subpoa="+par+"&codigo_detalle="+cd,
				 dataType: 'json',
				 success : function(t)
				 {  
				 	alert('Su Actividad Se Ejecuto Exitosamente');
				 					
				 },	
					error   : callback_error,
				});
	}

	function FMD_OtroPoa(cd){

		 par=$('#otropoa_'+cd).val();


		$.ajax({
				 type: "POST",
				 url: "tipo_guardar.php?tipoGuardar=GuardarOtroPoa&otropoa="+par+"&codigo_detalle="+cd,
				 dataType: 'json',
				 success : function(t)
				 {  
				 	alert('Su Actividad Se Ejecuto Exitosamente');
				 					
				 },	
					error   : callback_error,
				});
	}


		function FMD_AprobarDetalle(cod_det)
	{   

		//poa        = document.getElementById('poadetalle_'+cod_det).value;
		//subpoa     = document.getElementById('subpoadetalle_'+cod_det).value;
		//otro   	   = document.getElementById('otropoa_'+cod_det).value;
		vcant      = document.getElementById('inpcantidad_'+cod_det).value;
		//presup     = document.getElementById('presup_'+cod_det).value;


		//valida la cantidad
				$.ajax({
				 type: "POST",
				 url: "tipo_guardar.php?tipoGuardar=Validar_cantidad&cantidad="+vcant+"&codigo_detalle="+cod_det,
				 dataType: 'json',
				 success : function(t)
				 {  
				 	if(t != '' && t == '1')
						{
							 alert("Se modificara la cantidad");

						}
				 					
				 },	
					error   : callback_error,
				});

				$.ajax({
				 type: "POST",
				 url: "tipo_guardar.php?tipoGuardar=AprobarDetalle&cantidad="+vcant+"&codigo_detalle="+cod_det,
				 dataType: 'json',
				 success : function(t)
				 {  
				 	if(t != '1' && t == ''){
				 		alert('Este detalle no requiere aprobación');
				 	}else{
				 		alert('Su Actividad Se Ejecuto Exitosamente');	
				 	}
				 	
				 					
				 },	
					error   : callback_error,
				}); 

		

		
	}

	
	 function FMD_validarSiNumero(numero)
		{
			if (!/^([0-9])*$/.test(numero))
			alert("El valor " + numero + " no es un número");
		}

     function FMD_CambiarCantidad(cod_det)
     {
     	vcant      = document.getElementById('inpcantidad_'+cod_det).value;
     			//validar cantidad
     			$.ajax({
						 	type: "POST",
				 			url: "tipo_guardar.php?tipoGuardar=Validar_cantidad&cantidad="+vcant+"&codigo_detalle="+cod_det,
				 			dataType: 'json',
				 			success : function(t)
				 			{  
				 				if(t != '' && t == '1')
								{
									if(confirm('¿Esta seguro de modificar la cantidad?'))
									{   	
							 			$.ajax({
				 									type: "POST",
				 									url: "tipo_guardar.php?tipoGuardar=MarcarCambioCantidad&cantidad="+vcant+"&codigo_detalle="+cod_det,
				 									dataType: 'json',
				 									success : function(t) 
				 									{  console.log(t);
				 										if(t == '1')
				 										{
				 						 					alert('Su Actividad Se Ejecuto Exitosamente');	
				 										}
				 			 						},	
													error   : callback_error,
												}); 
									}
				 				}	
				 			},	
							error   : callback_error,
						});
     }		

	function FMD_FdeleteDet(cod){
      
	  if(confirm('¿Esta seguro de eliminar este detalle del requerimiento?')){   

				$.ajax({
				 type: "POST",
				 url: "tipo_guardar.php?tipoGuardar=EliminarDetalle&codigo_detalle="+cod,
				 dataType: 'json',
				 success : function(t)
				 {  
				 			console.log(t);	 
				 		alert('Este detalle se a eliminado correctamente');	
				 		location.reload();
				 },	
					error   : callback_error,
				});
	  }
	  
	  

	}

	function FMD_FeditarDet(cod)
	{
		
		$.ajax({
				 type: "POST",
				 url: "tipo_guardar.php?tipoGuardar=CargarDatos&codigo_detalle="+cod,
				 dataType: 'json',
				 success : function(t)
				 {				
					$.each(t, function(i, item) 
					{
   							if(t.length>0)
				 			{			 		
				 				if(t[0].codigo != ''  )
				 				{      				
		 							 //$('#li_ns').addClass('active');
		  							 //$('#li_ss').removeClass('active');
		  							 //$('#fane1').css('display','block');
		  							 //$('#fane2').css('display','none');
		  							   	  $('#codigodetalle_ns'	).val(item.codigo);
										  $('#descrip_ns'		).val(item.descripcion);
										  $('#cantidad_ns'		).val(item.cantidad);
										  $('#justi_ns'			).val(item.justificacion);
										  $('#observ_ns'		).val(item.observacion);
										  $('#unidad_ns'		).val(item.unidad);
										  $('#btnsub_ns'		).val('Editar');
								  	 try{
										  document.getElementById('cantidad_ns').focus();
										  document.getElementById('cantidad_ns').select();
										}catch(e){}
										return false;
		  						}
                           	}
				 	});	
				 },	
					error   : callback_error,
			});

	}

	function FMD_FcorregirDet(cod){
      var date = new Date();
	  var timestamp = date.getTime();
	  
	  $.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=CorregirDet&codigo_detalle="+cod,
	            dataType: 'json',
				success : function(r){

				       if(r!=''){
					 alert("Se han guardado sus cambios");
					  location.reload();
					 return false;
					}
				},
				error   : callback_error,	           
	        });
	}	

// 	  function fentrega(id,Ncot){
//     if(confirm("seguro que desea marcar como recibido este detalle")){
// 		$("#loading_img_"+id).css("display","block");
// 			$.ajax({
// 	            type: "POST",
// 	            url: "tipo_guardar.php?tipoGuardar=marcarentregado&ncot=="+Ncot+"&codigo_e="+id,
// 	            dataType: 'json',
// 				success : function(r){
// 					if(r.length>0){
// 						if(r[0].afectado !='' && r[0].afectado == '1'){
// 						 //location.reload();
// 						 //return false;
// 						   if(r[0].background!=''){
// 							   $("#tdaprob_"+id).css("background", ""+r[0].background);
// 							}
// 						  $("#trtr_"+id+" .imgacciones").each(function(index){
// 							$(this).html('');
// 						  });
// 						  $("#loading_img_"+id).css("display","none");
// 						}
// 					}
// 				},
// 				error   : callback_error
// 	        });					
// 	}
	  
//   }


 function FMD_ReiniciarDet(det){
 	if(confirm("seguro que desea reiniciar este detalle")){
 		$.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=reiniciardetalle&codigo_detalle="+det,
	            dataType: 'json',
				success : function(r){
					
					alert('realizada con exito');
					location.reload();
				},
				error   : callback_error
	        });	
 	}
 }
  function FMD_Electronica(det,a,r){
//alert(r);

if(r == 2){
	 if(a == 1){

	 	alert('Este detalle debe ser aprobado por direccion administrativa');
	return false;
	 }

	 if(a == 2){

	 	alert('Este detalle debe ser aprobado por rectoria');
	return false;
	 }

	 if(a == 3){

	 	alert('Este detalle ya esta aprobado como compra electronica ');
	return false;
	 }
	
} 

if( r == 3){

	if(a == 0){
		alert('este detalle no se a marcado como compra electronica por el auxiliar admin');
		return false;
	}

	if(a == 2){
	alert('Este detalle debe ser aprobado por rectoria');
	return false;	
	}

	 if(a == 3){

	 	alert('Este detalle ya esta aprobado como compra electronica ');
	return false;
	 }

	
}


if( r == 5){

	if(a == 0){
		alert('este detalle no se a marcado como compra electronica por el auxiliar admin');
		return false;
	}

	if(a == 1){
	alert('Este detalle debe ser aprobado por direccion administrativa');
	return false;	
	}

	 if(a == 3){

	 	alert('Este detalle ya esta aprobado como compra electronica ');
	return false;
	 }

	
}



 	if(confirm("seguro que desea marcar para autorizacion de compra electronica")){
 		$.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=marcarcompraelectronica&parm="+a+"&codigo_detalle="+det,
	            dataType: 'json',
				success : function(r){
					
					alert('realizada con exito');
					location.reload();
				},
				error   : callback_error
	        });	
 	}


  }

function FMD_Menorcuantia(det){
if(confirm("seguro que desea marcar para autorizacion de compra Minima Cuantia")){
 		$.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=marcarcompraMinimaCuantia&codigo_detalle="+det,
	            dataType: 'json',
				success : function(r){
					
					alert('realizada con exito');
					location.reload();
				},
				error   : callback_error
	        });	
 	}	

}
 function FMD_Viaticos(det,a,r){

 	//alert(r);

if(r == 2){
	 if(a == 1){

	 	alert('Este detalle debe ser aprobado por direccion administrativa');
	return false;
	 }

	 if(a == 2){

	 	alert('Este detalle debe ser aprobado por rectoria');
	return false;
	 }

	 if(a == 3){

	 	alert('Este detalle ya esta aprobado como viatico ');
	return false;
	 }
	
} 

if( r == 3){

	if(a == 0){
		alert('este detalle no se a marcado como viatico por el auxiliar admin');
		return false;
	}

	if(a == 2){
	alert('Este detalle debe ser aprobado por rectoria');
	return false;	
	}

	 if(a == 3){

	 	alert('Este detalle ya esta aprobado como viatico ');
	return false;
	 }

	
}


if( r == 5){

	if(a == 0){
		alert('este detalle no se a marcado como viatico por el auxiliar admin');
		return false;
	}

	if(a == 1){
	alert('Este detalle debe ser aprobado por direccion administrativa');
	return false;	
	}

	 if(a == 3){

	 	alert('Este detalle ya esta aprobado como viatico ');
	return false;
	 }

	
}



 	if(confirm("seguro que desea marcar para autorizacion de viaticos")){
 		$.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=marcarviatico&parm="+a+"&codigo_detalle="+det,
	            dataType: 'json',
				success : function(r){
					
					alert('realizada con exito');
					location.reload();
				},
				error   : callback_error
	        });	
 	}


 }
   

   function frecibo_usuariog(id){

	$.ajax({
					type: "POST",
					url: "tipo_guardar.php?tipoGuardar=ConsultarEstadoDet&cd="+id,
					dataType: 'json',
					success : function(t)
					{
					    if(t!=7)
					    {
					    	alert('Este detalle no aparece marcado como entregado,Por favor informar a el area de compras sobre el recibido de este item, para habilitar este boton recibido a satisfacion');
					    }else
					    {
					    	if(confirm("seguro que desea marcar como recibido este detalle"))
					    	{
								$.ajax({
								type: "POST",
								url: "tipo_guardar.php?tipoGuardar=marcarRecibidousuariog&codigo_e="+id,
								dataType: 'json',
								success : function(r){
									console.log(r);
									$("#tdaprob_"+id).css("background",'#ff0500')
									if(r.length >0){
										if(r[0].afectado !='' && r[0].afectado == '1'){
										  $("#recibeusug_"+id+"").html('');
										  if(r[0].background!=''){
										   $("#tdaprob_"+id).css("background", ""+r[0].background);
										   console.log("entra");
										  }
										}
									}	
								},
								error   : callback_error
								});					
							}		
				        }	
					},
					error   : callback_error
				});

				
}
//fin

//Funciones de manipulacion datos de Requerimiento
function FMR_FbanderaEditar(){
	alert('Se va a marcar este requerimiento, para habilitar edicion');

	$.ajax({
								type: "POST",
								url: "tipo_guardar.php?tipoGuardar=marcarBanderaEdit&codreq=<?php echo($codigo_requerimiento);?>",
								dataType: 'json',
								success : function(r){
									alert('Realizada Con Exito');										
								},
								error   : callback_error
								});	
        }

function FVistoReq(id){
	alert('Se va a marcar este requerimiento como Visto');

	$.ajax({
								type: "POST",
								url: "tipo_guardar.php?tipoGuardar=marcarReqVisto&codreq=<?php echo($codigo_requerimiento);?>",
								dataType: 'json',
								success : function(r){
									alert('Realizada Con Exito');										
								},
								error   : callback_error
								});	
}

function Recibir(par){
 all=0;
 //cargar mensaje
 if(par==5){
  msj='recibido';
 }
 if(par==4){
  msj='No Recibido';
 }
 
	   //if(all==0){
	  if(par==5){
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
	 
   if(confirm('estas seguro que quiere pasar este requerimiento a  '+msj+'?')){
     var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=RecibirRequ&parm="+par+"&todosrecibido="+all+"&codreq=<?php echo($codigo_requerimiento);?>");
	 //alert (v_dato);
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



//para realizar el visto bueno de la orden de compra
function visto_director_admin(orden,det)
{
	if(confirm("Esta a punto de autorizar esta orden para compra, Desea Continuar"))
	{
			$.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=AutorizarOrden&detalle="+det+"&orden="+orden,
	            dataType: 'json',
				success : function(r){

                alert(r);


					$("#btnautoriza_"+orden).remove();
				},
				error   : callback_error,	           
	        });
	}
}


</script>