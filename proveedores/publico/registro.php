<?php
require_once('../../conexion/db.php');
include_once('../../utils.php');
$config = getConfig("dev");
if (!isset($_SESSION)) {
  session_start();
}

$id_proveedor = '';
if(isset($_GET['cod_prov']) && $_GET['cod_prov'] != ''){
	$id_proveedor = $_GET['cod_prov'];
}
if(!(isset($_SESSION['MM_RolID']))){
	exit("not authorized");
}
if($_SESSION['MM_RolID'] != '2'){
	exit("no hay permiso para esta acción");
}
    //$id_proveedor =  7;
    $existe_proveedor = 0;
	$query_RsProveedorDataTemp = "select P.PROVCODI CODIGO,
										 P.PROVREGI REGISTRO,
										 P.PROVDIVE DIGITO_VERIFICACION,
										 P.PROVNOMB NOMBRE,
										 '' APELLIDOS,
										 P.PROVNOCO NOMBRE_COMERCIAL,
										 P.PROVTELE TELEFONO,
										 P.PROVPWEB WEB,
										 P.PROVDIRE DIRECCION,
										 P.PROVCON1 NOMBRE_CONTACTO1,
										 P.PROVTEC1 TELEFONO_CONTACTO1,
										 P.PROVCCO1 CARGO_CONTACTO1,
										 P.PROVCON2 NOMBRE_CONTACTO2,
										 P.PROVTEC2 TELEFONO_CONTACTO2,
										 P.PROVCCO2 CARGO_CONTACTO2,
										 P.PROVCOME COMENTARIO,
										 P.PROVCORR CORREO,
										 P.PROVUSUA USUARIO,
										 P.PROVPASS PASSWORD,
										 P.PROVTIID TIPO_IDENTIFICACION,
										 P.PROVTIPE TIPO_PERSONA,
										 P.PROVREGM REGIMEN,
										 CASE P.PROVREGM
										  WHEN 1 THEN 'NO RESPONSABLE DE IVA'
										  WHEN 2 THEN 'RESPONSABLE DE IVA'
										  WHEN 3 THEN 'NO APLICA'
										  ELSE ''
										  END  REGIMEN_DES,										  
										 P.PROVAURE AUTORETENEDOR,
										 CASE P.PROVAURE
										  WHEN 1 THEN 'SI'
										  WHEN 2 THEN 'NO'
										  ELSE ''
										  END AUTORETENEDOR_DES,
										 P.PROVGRCO GRAN_CONTRIBUYENTE,
										 CASE P.PROVGRCO
										  WHEN 1 THEN 'SI'
										  WHEN 2 THEN 'NO'
										  ELSE '' 
										  END GRAN_CONTRIBUYENTE_DES,
										 P.PROVCICA CONTRIBUYENTE_ICA,
										 CASE P.PROVCICA
										 	WHEN 1 THEN 'SI'
										 	WHEN 2 THEN 'NO'
											ELSE ''
											END CONTRIBUYENTE_ICA_DES,
										 P.PROVAUTO AUTORIZACION,
										 CASE P.PROVAUTO
										  WHEN 1 THEN 'SI'
										  WHEN 2 THEN 'NO'
										  ELSE ''
										  END AUTORIZACION_DES,
										 P.PROVESSO ESTADO_SOLICITUD,
										 CASE P.PROVESSO
										  WHEN 0 THEN 'SIN REVIZAR'
										  WHEN 1 THEN 'APROBADO' 
										  WHEN 2 THEN 'NO APROBADO'
										  ELSE 'SIN REVIZAR'
										  END ESTADO_SOLICITUD_DES
									FROM PROVEEDORES_TEMPORAL P 
								   WHERE P.PROVCODI = '".$id_proveedor."'";
   	$RsProveedorDataTemp = mysqli_query($conexion,$query_RsProveedorDataTemp) or die(mysqli_error($conexion));
	$row_RsProveedorDataTemp = mysqli_fetch_assoc($RsProveedorDataTemp);
    $totalRows_RsProveedorDataTemp = mysqli_num_rows($RsProveedorDataTemp);
    if($totalRows_RsProveedorDataTemp > 0){
    	$existe_proveedor = 1;
    }
    if($existe_proveedor == 0){
    	echo("error proveedor temporal no existe");
    	exit;
    }

    $query_RsProveedoresDataTempExtra = "SELECT * FROM PROVEEDOR_DATATEMP
											WHERE PDTEPROV = '".$id_proveedor."'
     ";
   	$RsProveedoresDataTempExtra = mysqli_query($conexion,$query_RsProveedoresDataTempExtra) or die(mysqli_error($conexion));
	$row_RsProveedoresDataTempExtra = mysqli_fetch_assoc($RsProveedoresDataTempExtra);
    $totalRows_RsProveedoresDataTempExtra = mysqli_num_rows($RsProveedoresDataTempExtra);
    $categorias = array();
    $archivos   = array();
    if($totalRows_RsProveedoresDataTempExtra > 0){
    	$categorias = json_decode($row_RsProveedoresDataTempExtra['PDTECATE']);
    	$archivos   = json_decode($row_RsProveedoresDataTempExtra['PDTEARCH']);
    }

    $tipo_identificacion_array = array(
    									array("id"=> 1, "nombre" => "Cédula de Ciudadanía"),
    									array("id"=> 2, "nombre" => "Número de Identificación Tributaria")
    									);  
  $tipo_persona_array = array(
    									array("id"=> 1, "nombre" => "Persona Natural"),
    									array("id"=> 2, "nombre" => "Persona Jurídica")
    									);

?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="../../includes/font-awesome/css/font-awesome.min.css">    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
|
    <title>Registro de Proveedores </title>

  </head>
  <style>

   .carousel-control-next { width:5%; }
   .carousel-control-prev { width:5%; }
   .titulocard{
		background:#780002; 
		color:#ffffff !important;	
   }   
  </style>
  <body>
  	<div class="container"  >
  		<!-- Content here -->
  		<div class="row"  >
    		<div class="col">
    			<h3>Registro de proveedores <?php echo($_SESSION['MM_Usernombre'].' '.$_SESSION['MM_Rolnombre']);?> </h3>
    			<div class="text-center card titulocard" id="div_estado_des"><?php echo($row_RsProveedorDataTemp['ESTADO_SOLICITUD_DES']); ?></div>
    		</div>
	    </div>
  		<div class="row">
    		<div class="col-4" ><div class="accordion" id="accordionExample">
				<div class="card">
					<div class="card-header titulocard" id="headingOne">
						<h5 class="mb-0">
							<button class="btn btn-block text-white" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
							Información Basica
							</button>
						</h5>
					 </div>
					<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
						<div class="card-body flex-fill" style="max-height:250px; overflow:scroll">
                        <form>
							<div class="form-group">
								<label for="exampleFormControlInput1">Tipo de Identificación:</label>
								<select class="form-control form-control-sm" type="text" placeholder="">
									<?php 
										foreach ($tipo_identificacion_array as $key => $value) {
											?>
											<option value="<?php echo($value['id']);?>" <?php if($value['id'] == $row_RsProveedorDataTemp['TIPO_IDENTIFICACION']){ echo("selected");} ?>><?php echo($value['nombre']);?></option>
											<?php
										}
									?>
								</select>

							</div>
							<div class="form-group">
								<label for="exampleFormControlInput1">Número de identificación Tributario:</label>
								<input class="form-control form-control-sm" type="text" placeholder="" value="<?php echo($row_RsProveedorDataTemp['REGISTRO']);?>">

							</div>
							<div class="form-group">
								<label for="exampleFormControlInput1">Tipo Persona:</label>
								<select class="form-control form-control-sm" type="text" placeholder="" >
									<?php 
										foreach ($tipo_persona_array as $key => $value) {
											?>
											<option value="<?php echo($value['id']);?>" <?php if($value['id'] == $row_RsProveedorDataTemp['TIPO_PERSONA']){ echo("selected");} ?>><?php echo($value['nombre']);?></option>
											<?php
										}
									?>
								</select>								

							</div>
							<?php 
							if($row_RsProveedorDataTemp['TIPO_PERSONA'] == '2'){
							?>
							<div class="form-group">
								<label for="exampleFormControlInput1">Razón Social:</label>
								<input class="form-control form-control-sm" type="text" placeholder="" value="<?php echo($row_RsProveedorDataTemp['NOMBRE']);?>">
							</div>
							<?php 
							}
	
							if($row_RsProveedorDataTemp['TIPO_PERSONA'] == '1'){
							?>
							<div class="form-group">
								<label for="exampleFormControlInput1">Nombre y Apellidos:</label>
								<input class="form-control form-control-sm" type="text" placeholder="" value="<?php echo($row_RsProveedorDataTemp['NOMBRE']);?>">
							</div>
							<?php 
							}
							?>
							<div class="form-group">
								<label for="exampleFormControlInput1">Nombre Comercial:</label>
								<input class="form-control form-control-sm" type="text" placeholder="" value="<?php echo($row_RsProveedorDataTemp['NOMBRE_COMERCIAL']);?>">

							</div>
							<div class="form-group">
								<label for="exampleFormControlInput1">Email:</label>
								<input class="form-control form-control-sm" type="text" placeholder="" value="<?php echo($row_RsProveedorDataTemp['CORREO']);?>">

							</div>
							<div class="form-group">
								<label for="exampleFormControlInput1">Página Web:</label>
								<input class="form-control form-control-sm" type="text" placeholder="" value="<?php echo($row_RsProveedorDataTemp['WEB']);?>">

							</div>
							<div class="form-group">
								<label for="exampleFormControlInput1">Teléfono:</label>
								<input class="form-control form-control-sm" type="text" placeholder="" value="<?php echo($row_RsProveedorDataTemp['TELEFONO']);?>">
							</div>							
							<div class="form-group">
								<label for="exampleFormControlInput1">Régimen:</label>
								<input class="form-control form-control-sm" type="text" placeholder="" value="<?php echo($row_RsProveedorDataTemp['REGIMEN_DES']);?>">
							</div>
							<div class="form-group">
								<label for="exampleFormControlInput1">Autoretenor:</label>
								<input class="form-control form-control-sm" type="text" placeholder="" value="<?php echo($row_RsProveedorDataTemp['AUTORETENEDOR_DES']);?>">
							</div>							
							<div class="form-group">
								<label for="exampleFormControlInput1">Gran Contribuyente:</label>
								<input class="form-control form-control-sm" type="text" placeholder="" value="<?php echo($row_RsProveedorDataTemp['GRAN_CONTRIBUYENTE_DES']);?>">
							</div>							
							<div class="form-group">
								<label for="exampleFormControlInput1">Contribuyente ICA:</label>
								<input class="form-control form-control-sm" type="text" placeholder="" value="<?php echo($row_RsProveedorDataTemp['CONTRIBUYENTE_ICA_DES']);?>">
							</div>							
			
						</form>


					
						
						
					
						
						
						</div>
			        </div>
				</div>
				<div class="card">
					<div class="card-header titulocard" id="headingTwo">
					<h5 class="mb-0">
						<button class="btn btn-block text-white collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
						Productos y Servicios
						</button>
					</h5>
					</div>
					<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
					<div class="card-body">
						<ul class="list-group">
						<?php 
							foreach($categorias as $categoria){
									$query_RsCategoriaDetalle = "SELECT * FROM CLASIFICACION WHERE CLASCODI = '".$categoria."'";
									$RsCategoriaDetalle = mysqli_query($conexion,$query_RsCategoriaDetalle) or die(mysqli_error($conexion));
									$row_RsCategoriaDetalle = mysqli_fetch_assoc($RsCategoriaDetalle);
									$totalRows_RsCategoriaDetalle = mysqli_num_rows($RsCategoriaDetalle);	
									if($totalRows_RsCategoriaDetalle > 0){
								?>
							
							  <li class="list-group-item"><?php echo($row_RsCategoriaDetalle['CLASNOMB']);?></li>
						<?php
								}
							}
						?>
							</ul>
					</div>
					</div>
				</div>				
				<div class="card ">
					<div class="card-header titulocard" id="headingThree">
					<h5 class="mb-0">
						<button class="btn btn-block  text-white collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						Datos de usuario
						</button>
					</h5>
					</div>
					<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
					<div class="card-body">
							<div class="form-group">
								<label for="exampleFormControlInput1">Usuario:</label>
								<input class="form-control form-control-sm" type="text" placeholder="" value="<?php echo($row_RsProveedorDataTemp['USUARIO']);?>">
							</div>							
							<div class="form-group" style="display:none;">
								<label for="exampleFormControlInput1">Password:</label>
								<input class="form-control form-control-sm" type="text" placeholder="" value="<?php //echo($row_RsProveedorDataTemp['PASSWORD']);?>">
							</div>
							<ul style="font-size:.8em">
								<?php 
								foreach ($archivos as $key => $value) {
									if($value->archivo != ''){
									?>
										<li><?php echo($value->archivo); ?></li>
									<?php
									}
								}
								?>
							</ul>
					</div>
					</div>
				</div>
				<div class="row  mt-2">					
						<button class="btn btn-secondary btn-block" onclick="location.href='<?php echo($config['url_root'].'/home.php?page=proveedores_solicitados');?>'"><i class="fa fa-chevron-left"></i> volver&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
				</div>
<?php 
	if($row_RsProveedorDataTemp['ESTADO_SOLICITUD'] == '0' ){
?>
				<div class="row mt-1" id="row_noaprobar">
						<button class="btn btn-danger btn-block" data-toggle="modal" data-target="#modalnoaprobado"><i class="fa fa-close"></i> No Aprobar</button>
				</div>		


				<div class="row mt-1 mb-5" id="row_aprobar">
						<button class="btn btn-success btn-block" data-toggle="modal" data-target="#modalaprobado" ><i class="fa fa-check" ></i> Aprobar</button>
				</div>
<?php 
	}
 ?>					
</div>
</div>
    <div class="col-8"> 
	<div id="carouselExampleControls" class="carousel slide " data-ride="carousel">
  <div class="carousel-inner">
    	<?php 
    		$j = 0;
			foreach ($archivos as $key => $value) {

				//echo("php/temporalfiles/$value->archivo");
				if($value->archivo != ''){
					$clase_active = ($j == 0) ? 'active' : '';
			?>
			    	<div class="carousel-item <?php echo($clase_active);?>" data-interval="">
						  <div class="embed-responsive embed-responsive-4by3">
								<iframe class="embed-responsive-item"
										src="php/temporalfiles/<?php echo($value->archivo);?>" 
					  		  			style="width:100%; height:500px;" 
							  			frameborder="0">
								</iframe>
						 </div>	
			    	</div>			 		
				<?php
					$j++;			
				}
			}
    	?>


  </div>
  <a class="carousel-control-prev" style="color:#000000;" href="#carouselExampleControls" role="button" data-slide="prev">
    <span aria-hidden="true"><i class="fa fa-chevron-left"></i></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" style="right:-40px; color:#000000;" href="#carouselExampleControls" role="button" data-slide="next" >
    <span aria-hidden="true" class="text-dark;" > <i class="fa fa-chevron-right"></i> </span>
    <span class="sr-only">Next</span>
  </a>
</div>








	</div>
  </div>
 

</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>-->
<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
   <script>
	$('.carousel').carousel({
     ride: false,
     pause: true,
     interval: false,
    }) 
	</script> 
<div class="modal fade" id="modalnoaprobado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">No Aprobado</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<p>Ingrese el motivo por el cual el proveedor no es aprobado</p>
        <textarea name="motivo_noaprobado" id="motivo_noaprobado" rows="5" class="w-100 form-control"></textarea>        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-success" id="btn-noaprobar" onclick="noAprobar('<?php echo($id_proveedor);?>')"><i class="fa fa-send"></i> Enviar</button>
      </div>
    </div>
  </div>
</div>
<form id="form_aprobado" name="form_aprobado" action="enviarmailaprobado.php" method="post" enctype="multipart/form-data">
<div class="modal fade" id="modalaprobado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="titleaprobar">Aprobar Proveedor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">	  
      	<h6>Formato de evaluación inicial del proveedor</h6>
		<div class="form-group">
			<label for="incluye_archivo">Desea incluir el archivo de evaluación de proveedor: <span class="text-danger">*</span></label>
			<select name="incluye_archivo" id="incluye_archivo" class="form-control">
				<option value="">Seleccione...</option>
				<option value="1">Si</option>
				<option value="2">No</option>
			</select>
		</div>
		<div class="jumbotron">
			<input type="file" name="archivo1" id="archivo1">
		</div>
		<div class="row">
			<div class="col">
				<div id="mensajero" class="alert alert-info" style="display:none"></div>
			</div>
		</div>		
      </div>
      <div class="modal-footer">
	    <input type="hidden" name="post_form" id="post_form" value="101">
	    <input type="hidden" name="id_proveedor" id="id_proveedor" value="<?php echo($id_proveedor);?>">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-success" id="btn-aprobar" onclick="FaccionesSolicitado('<?php echo($id_proveedor);?>','1')">
			<i class="fa fa-send"></i> Enviar
		</button>
      </div>
    </div>
  </div>
</div>
</form>

	    <script type="text/javascript">
		/*$(function () {
		  	
		}) */
function noAprobar(id){
	if(id == ''){ return; }
	if($("#motivo_noaprobado").val() == ''){
		alert("debe ingresar el motivo por que no se aprueba el proveedor");
		return;
	}
	if(confirm("Seguro que no aprueba este proveedor?")){
		$("#btn-noaprobar").attr("disabled",true);
	   	var date = new Date();
	   	var timestamp = date.getTime();
		   $.ajax({
			type: "POST",
			url: "php/tipoguardar.php?tipoGuardar=NoAprobarProveedor&codigo_prov="+id+"&timers="+timestamp,
			dataType: 'json',
			success : function(r){
						if(r.status == 'error'){
							alert("error al marcar como no aprobado este proveedor");
							location.href='<?php echo($config['url_root'].'/home.php?page=proveedores_solicitados');?>';
						}  													
						if(r.status == 'ok'){
							$('#modalnoaprobado').modal('hide');
							$("#row_noaprobar").css("display", "none" );
							$("#row_aprobar").css("display", "none" );
							$("#div_estado_des").html(r.msg);
							alert("se ha marcado el proveedor como no aprobado");
						}
						$("#btn-noaprobar").attr("disabled",false);
						
			  },
			data: {motivo: $("#motivo_noaprobado").val()},
			error   : error_call
		  });
	}
}
function error_call(XMLHttpRequest, textStatus, errorThrown)
{
    alert("Respuesta del servidor "+XMLHttpRequest.responseText);
    alert("Error "+textStatus);
    alert(errorThrown);
}		

function btnHideIndicator(){
	$("#btn-aprobar").attr("disabled", false);
	$("#btn-aprobar > i").removeClass('fa-refresh fa-spin');
	$("#btn-aprobar > i").addClass('fa-send');
	$("#incluye_archivo").attr("disabled",false);
	$("#mensajero").css("display","none");
	$("#mensajero").html("Procesando ...");	
}
function btnShowIndicator(){
	$("#btn-aprobar > i").removeClass('fa-send');
	$("#btn-aprobar > i").addClass('fa-refresh fa-spin');
	$("#btn-aprobar").attr("disabled", true);
	$("#incluye_archivo").attr("disabled",true);
	$("#mensajero").css("display","block");
	$("#mensajero").html("Procesando ...");
}

  function FaccionesSolicitado(codigo, accion){
 	 
	if(codigo == '') return false;
	label = '';
	if(accion == '1') label = " Aprobar ";
	if(accion == '2') label = " Rechazar ";
	if(accion == '3') label = " Ver ";
	if($("#incluye_archivo").val() == ''){
		$("#incluye_archivo").focus();
		return;
	}
	if(accion <= '2'){		
		if(confirm("Seguro que desea "+label+" este proveedor?")){
		
			   btnShowIndicator();								
			
			   var date = new Date();
			   var timestamp = date.getTime();
			   $.ajax({
				type: "POST",
				url: "<?php echo($config['url_root']);?>/tipo_guardar.php?tipoGuardar=ProveedorTemporalaOficial&codigo_prov="+codigo+"&accion="+ accion +"&timers="+timestamp,
				dataType: 'json',
				success : function(r){
							if(r.status == 'erroruser'){
								msgerror('error en la accion del proveedor, el proveedor ya existe en la tabla oficial proveedores');
							}  							
							if(r.status == 'errorusertemporal'){
								if(r.data_temp == '0'){
									msgerror('ya se ha ingresado un registro previo con la identificación ingresada, que se encuentra en proceso de aprobación');
								}else{
										msgerror('Esta identificación ya se encuentra registrada, por favor inicie sesión, si no recuerda su contraseña acceda mediante el recurso recordar contraseña, de la pantalla inicial');
									}
								}
														
							if(r.status == 'failed'){
								msgerror('Se ha presentado un error al guardar la información, Intente más tarde.');
							}							
							if(r.status == 'errprovnocreado'){
								msgerror('Se ha presentado un error al guardar la información, Proveedor no ha sido creado en la tabla oficial.');
							}							
							if(r.status == 'rechazado'){
								msgerror('Se ha rechazado correctamente el proveedor.');
								setTimeout(function(){ location.reload(); }, 1000);
							}
							if(r.status == 'norechazado'){
								msgerror('Se ha presentado un error al rechazar el proveedor, intente más tarde, contacte con soporte');
								setTimeout(function(){ location.reload(); }, 1000);
							}							
							if(r.status == 'creado'){
								$("#mensajero").html("Creando directorio proveedor ...");
								crearDirectorio(r);								
							}
							if(r.status != 'creado'){
								btnHideIndicator();
							}
							
				  },
				data: {},
				error   : error_call
			  });			
		}
	}
	if(accion == '3'){
		
	}
  }

 function crearDirectorio(data){
	 		   var date = new Date();
			   var timestamp = date.getTime();
			   $.ajax({
				type: "POST",
				url: "<?php echo($config['url_root']);?>/tipo_guardar.php?tipoGuardar=CrearDirectorioProveedor&cod_prov_oficial="+data.cod_prov_oficial+"&cod_prov_temp="+ data.cod_prov_temporal +"&timers="+timestamp,
				dataType: 'json',
				success : function(r){
							if(r.status == 'ok'){
								$("#mensajero").html("Directorio de proveedor creado correctamente");
								copiarArchivos(r);
							}  							
														
							if(r.status == 'failed'){
								$("#mensajero").html("Error al crear Directorio de proveedor, contacte con soporte");
								btnHideIndicator();
							}							
							
				  },
				data: {},
				error   : error_call
			  });
 }  

  function copiarArchivos(data){
	 		   var date = new Date();
			   var timestamp = date.getTime();
			   $.ajax({
				type: "POST",
				url: "<?php echo($config['url_root']);?>/tipo_guardar.php?tipoGuardar=CopiarArchivosProveedor&cod_prov_oficial="+data.cod_prov_oficial+"&cod_prov_temp="+ data.cod_prov_temporal +"&timers="+timestamp,
				dataType: 'json',
				success : function(r){
							if(r.status == 'ok'){
								$("#mensajero").html("proceso finalizado correctamente, proveedor aprobado correctamente");	
								//setTimeout(function(){
								$("#incluye_archivo").attr("disabled",false);
								document.form_aprobado.submit();
								//}, 13000);
								
							}  							
														
							if(r.status == 'failed'){
								$("#mensajero").html("Error al copiar categorias y copiar archivos de proveedor");
							}							
							if(r.status == 'creado'){
								$("#mensajero").html("proceso finalizado correctamente, proveedor aprobado correctamente");	
								//$("#modalaprobado").hide()
								//alert("registro de proveedor aprobado correctamente");
								//setTimeout(function(){ location.reload(); }, 3000);
								document.form_aprobado.submit();
								$("#mensajero").html("proceso finalizado correctamente, proveedor aprobado correctamente");	
								
							}
							if(r.status !='ok'){
								btnHideIndicator();
							}
							
				  },
				data: {},
				error   : error_call
			  });
 } 

    </script>
  </body>
</html>