<?php
require_once('../../conexion/db.php');


  $query_RsLista_adjuntos="SELECT * FROM CONF_PROVEEDOR WHERE COPRTITU = 'adjuntos'
            and COPRESTA = 1
          ";
  $RsLista_adjuntos = mysqli_query($conexion,$query_RsLista_adjuntos) or die(mysqli_error($conexion));
  $row_RsLista_adjuntos = mysqli_fetch_array($RsLista_adjuntos);
  $totalRows_RsLista_adjuntos = mysqli_num_rows($RsLista_adjuntos);


  ?>
<html lang="es" >
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <!-- Angular Material style sheet -->
  <link href="../../js/toast/toastr.min.css" rel="stylesheet"/>
  <link rel="stylesheet" type="text/css" href="../../includes/selectmultiple/listswap.css" />
  <link rel="stylesheet" href="../../includes/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="../../css/stepper/bootstrap.min.css" />  
  <link rel="stylesheet" type="text/css" href="../../css/stepper/mdb.min.css" />
  <link rel="stylesheet" type="text/css" href="../../css/stepper/style.min.css" />
  <link rel="stylesheet" type="text/css" href="../../css/stepper/addons/datatables.min.css" />
  <!-- Angular Material requires Angular.js Libraries -->
  <script src="../../js/jquery1.11.3.min.js"></script>
  <script src="../../js/moment_2.14.1.js"></script>
  <style type="text/css">
.mandatory{
  color:#ff0000;
}
.form-control[readonly] {
    border:1px solid #ccc !important;
    background-color: #eee !important;
}
.md-form label {
  left:.5em !important;
}
  .source_wrapper ul, .listboxswap .destination_wrapper ul{
    overflow-y: scroll !important;
  }
.listbox_option{
  font-size:.8em !important;
}
.steps-form-2 {
    display: table;
    width: 100%;
    position: relative; }
.steps-form-2 .steps-row-2 {
    display: table-row; }
.steps-form-2 .steps-row-2:before {
    top: 14px;
    bottom: 0;
    position: absolute;
    content: " ";
    width: 100%;
    height: 2px;
    background-color: #7283a7; }
.steps-form-2 .steps-row-2 .steps-step-2 {
    display: table-cell;
    text-align: center;
    position: relative; }
.steps-form-2 .steps-row-2 .steps-step-2 p {
    margin-top: 0.5rem; }
.steps-form-2 .steps-row-2 .steps-step-2 button[disabled] {
    opacity: 1 !important;
    filter: alpha(opacity=100) !important; }
.steps-form-2 .steps-row-2 .steps-step-2 .btn-circle-2 {
    width: 70px;
    height: 70px;
    border: 2px solid #59698D;
    background-color: white !important;
    color: #59698D !important;
    border-radius: 50%;
    padding: 22px 18px 15px 18px;
    margin-top: -22px; }
.steps-form-2 .steps-row-2 .steps-step-2 .btn-circle-2:hover {
    border: 2px solid #4285F4;
    color: #4285F4 !important;
    background-color: white !important; }
.steps-form-2 .steps-row-2 .steps-step-2 .btn-circle-2 .fa {
    font-size: 1.7rem; }
.adjuntodocumento{
background: #f7f7f7;
    padding: .2em;
    border: solid 1px #afaaaa;
    border-radius: .3em;
    color: #232523;
}
.avav{
    background: #f3f0df;
    color: #464040;
    font-weight: 600;
}
	</style>
</head>
<body >
  <div class="container" style="display:none; min-height:100%" id="formularioproveedorok">
  <div class="row">
    <div class="col-md-12">
      <div class="well" style="background: #000000; color:#ffffff">
      <h5 class="text-center font-bold pt-2 pb-2 mb-5"><strong>Formulario de registro de Usuarios</strong></h5>
      </div>
		<div class="jumbotron">
		  <h1 class="display-4"><i class="fa fa-check-circle"></i> Pre-registro Exitoso!</h1>
		  <p class="lead">Su información se ha procesado correctamente, Su solicitud ha sido entregada al departamento de compras, cuando se haya verificado la misma tendra un correo con la confirmación de su registro.</p>
		  <hr class="my-4">
		  <p>.</p>
		</div>	  
	  
</div>
</div>
</div>
  <div class="container" style="border:solid 1px #ccc;display:block" id="formularioproveedor">
  <div class="row">
    <div class="col-md-12">
      <div class="well" style="background: #000000; color:#ffffff">
      <h5 class="text-center font-bold pt-2 pb-2 mb-5"><strong>Formulario de registro de Usuarios</strong></h5>
      </div>
<!-- Stepper -->
<div class="steps-form-2">
    <div class="steps-row-2 setup-panel-2 d-flex justify-content-between">
        <div class="steps-step-2">
            <a href="#step-1" type="button" class="btn btn-amber btn-circle-2 waves-effect ml-0" data-toggle="tooltip" data-placement="top" title="Informacion basica"><i class="fa fa-folder-open-o" aria-hidden="true"></i></a>
        </div>
        <div class="steps-step-2">
            <a href="#step-2" type="button" class="btn btn-blue-grey btn-circle-2 waves-effect" data-toggle="tooltip" data-placement="top" title="Productos y Servicios"><i class="fa fa-pencil" aria-hidden="true"></i></a>
        </div>
        <div class="steps-step-2" id="documents_adjuntos">
            <a href="#step-3" type="button" class="btn btn-blue-grey btn-circle-2 waves-effect" data-toggle="tooltip" data-placement="top" title="Documentos Adjuntos"><i class="fa fa-photo" aria-hidden="true"></i></a>
        </div>
        <div class="steps-step-2">
            <a href="#step-4" type="button" class="btn btn-blue-grey btn-circle-2 waves-effect mr-0" data-toggle="tooltip" data-placement="top" title="Usuario"><i class="fa fa-check" aria-hidden="true"></i></a>
        </div>
    </div>
</div>
los campos marcados con <span class="mandatory">*</span> son obligatorios.
<!-- First Step -->
<form role="form" action="" name="form_registro" id="form_registro" method="post">
    <div class="row setup-content-2" id="step-1">
        <div class="col-md-12">
            <h3 class="font-weight-bold pl-0 my-4"><strong>Informaci&oacute;n B&aacute;sica</strong></h3>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-3">
                    Tipo Identificaci&oacute;n <span class="mandatory">*</span>
                    <select id="tipo_identificacion" name="tipo_identificacion"  class="form-control validate">
                      <option value=""></option>
                      <option value="1">C&eacute;dula de ciudadan&iacute;a</option>
                      <option value="2">N&uacute;mero de identificaci&oacute;n tributaria</option>
                    </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group md-form">
					<div class="row">
						<div class="col-md-9 col-lg-9">
						<label for="numero_identificacion" data-error="wrong" data-success="right">N&uacute;mero de identificaci&oacute;n Tributaria<span class="mandatory">*</span></label>					
							<input  pattern="[0-9]*" onkeypress='return acceptNum(event)'; id="numero_identificacion" type="number"  class="unica form-control validate" name="numero_identificacion">
						</div>
						<div class="col-md-2 col-lg-2">
						<label for="div" data-error="wrong" data-success="right">div<span class="mandatory">*</span></label>					
							<input size="1" maxlength="1"  pattern="[0-9]*" onkeypress='return acceptNum(event)'; id="div" type="number"  class=" form-control validate" name="div">
						</div>
					</div>					

                </div>
				
              </div>
            </div>
			<div class="row">
				<div class="alert alert-warning w-100 m-1" id="mensajeunico" style="display:none;"></div>
			</div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group md-form">
                    Tipo Persona<span class="mandatory">*</span>
                    <select id="tipo_persona" name="tipo_persona"  class="form-control validate" onchange="activefields(this.value)">
                      <option value=""></option>
                      <option value="1">Persona Natural</option>
                      <option value="2">Persona Jur&iacute;dica</option>
                    </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group md-form">
                    <label for="razon_social" data-error="wrong" data-success="right">Raz&oacute;n Social</label>
                    <input id="razon_social" name="razon_social" type="text"  class="form-control validate" readonly>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group md-form">
                    <label for="nombre" data-error="wrong" data-success="right">Nombre</label>
                    <input id="nombre" name="nombre" type="text"  class="form-control" readonly>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group md-form">
                    <label for="apellidos" data-error="wrong" data-success="right">Apellidos</label>
                    <input id="apellidos" type="text" name="apellidos"  class="form-control" readonly>
                </div>
              </div>
            </div>
			
            <div class="row">
              <div class="col-md-6">
                <div class="form-group md-form">
                    <label for="nombre_comercial" data-error="wrong" data-success="right">Nombre Comercial</label>
                    <input id="nombre_comercial" name="nombre_comercial" type="text"  class="form-control">
                </div>
              </div>
            </div>			

            <div class="row">
              <div class="col-md-6">
                <div class="form-group md-form">
                    <label for="email" data-error="wrong" data-success="right">Email</label>
                    <input id="email" type="email" name="email"  class="form-control validate" autocomplete="off">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group md-form">
                    <label for="pagina_web" data-error="wrong" data-success="right">P&aacute;gina Web</label>
                    <input id="pagina_web" type="text" name="pagina_web"  class="form-control validate">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group md-form">
                    R&eacute;gimen<span class="mandatory">*</span>
                    <select id="regimen" name="regimen"  class="form-control validate">
                      <option value=""></option>
                      <option value="1">No responsable de IVA</option>
                      <option value="2">Responsable de IVA</option>
                      <option value="3">No Aplica</option>
                    </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group md-form">
                    AutoRetenedor<span class="mandatory">*</span>
                    <select id="autoretenedor" name="autoretenedor"  class="form-control validate">
                      <option value=""></option>
                      <option value="1">Si</option>
                      <option value="2">No</option>
                    </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group md-form">
                    Gran Contribuyente<span class="mandatory">*</span>
                    <select id="gran_contribuyente" name="gran_contribuyente"  class="form-control validate">
                      <option value=""></option>
                      <option value="1">Si</option>
                      <option value="2">No</option>
                    </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group md-form">
                    Contribuyente de Ica<span class="mandatory">*</span>
                    <select id="contribuyente_ica" name="contribuyente_ica"  class="form-control validate">
                      <option value=""></option>
                      <option value="1">Si</option>
                      <option value="2">No</option>
                    </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group md-form mt-3">
                    <label for="telefono" data-error="wrong" data-success="right">Tel&eacute;fono<span class="mandatory">*</span></label>
                    <input id="telefono" type="text" name="telefono" required="required" class="form-control validate" maxlength="50">
                </div>
              </div>
            </div>




















            <button class="btn btn-mdb-color btn-rounded nextBtn-2 float-right" type="button">Siguiente</button>
        </div>
    </div>

<!-- Second Step -->
    <div class="row setup-content-2" id="step-2">
        <div class="col-md-12">
            <h3 class="font-weight-bold pl-0 my-4"><strong>Productos y Servicios</strong></h3>
            <div class="form-group md-form">
                Categor&iacute;a
                  <select id="categoria_prov" name="categoria_prov" data-search="Buscar" multiple>
                    <?php
                    require_once("../../scripts/funcionescombo.php");
                    $estados = dameCategoria();
                      foreach($estados as $indice => $registro){
                      ?>
                        <option value="<?php echo($registro['CLASCODI'])?>"><?php echo($registro['CLASNOMB']);?></option>
                      <?php
                      }

                    ?>
                  </select>
                  <select id="destination_categoriaprov" name="destination_categoriaprov"  data-search="buscar" multiple>
                  </select>

            </div>

            <button class="btn btn-mdb-color btn-rounded prevBtn-2 float-left" type="button">Anterior</button>
            <button class="btn btn-mdb-color btn-rounded nextBtn-2 float-right" type="button">Siguiente</button>
        </div>
    </div>

    <!-- Third Step -->
    <div class="row setup-content-2" id="step-3">
      <div class="col-md-12">
        <div class="">

          <h3 class="font-weight-bold pl-0 my-4"><strong>Documentos Adjuntos</strong></h3>
          Formatos permitidos pdf
        </div>
      </div>




          <?php
            if($totalRows_RsLista_adjuntos >0){
               do{
              ?>
            <div class="col-md-6">
            <div class="form-group md-form mt-3 adjuntodocumento">
              <div class=" ">
                  <div class="">
                    <b><?php echo($row_RsLista_adjuntos['COPRDESC']);?></b>
                  </div>





                       <div class="container ">
                             <!-- The fileinput-button span is used to style the file input field as button -->
                            <span class="btn avav btn-sm fileinput-button" id="filesdivupload_<?php echo $row_RsLista_adjuntos['COPRCODI']?>">
                              <i class="fa fa-upload"></i>
                              <span style="font-size:9px;">Seleccione archivo...</span>
                              <!-- The file input field used as target for the file upload widget -->
                              <input class="avav" onclick="subirArchivoNewJ('newarchivo1_<?php echo $row_RsLista_adjuntos['COPRCODI']?>','<?php echo $row_RsLista_adjuntos['COPRCODI']?>', '<?php echo $row_RsLista_adjuntos['COPRLABE']?>')" id="newarchivo1_<?php echo $row_RsLista_adjuntos['COPRCODI']?>" type="file" name="newarchivo10<?php //echo $row_RsRedcomper['COPRCODI']?>" >
                            </span>
                            <div id="carga_<?php echo $row_RsLista_adjuntos['COPRCODI']?>" class="carga" style="display:none; font-size: .7rem">Cargando Archivo  <img src="../images/loading.gif" width="20"></img></div>
                            <!-- The global progress bar -->
                            <div id="progress_<?php echo $row_RsLista_adjuntos['COPRCODI']?>" class="progress" style="height: .2rem">
                              <div class="progress-bar progress-bar-success"></div>
                            </div>
                            <!-- The container for the uploaded files -->
                            <div id="files_<?php echo $row_RsLista_adjuntos['COPRCODI']?>" class="row files_<?php echo $row_RsLista_adjuntos['COPRCODI']?> text-right"></div>
                            <input type="hidden" name="nombre_delarchivo_<?php echo $row_RsLista_adjuntos['COPRCODI']?>" id="nombre_delarchivo_<?php echo $row_RsLista_adjuntos['COPRCODI']?>" value="">
                            <div id="sierror_<?php echo $row_RsLista_adjuntos['COPRCODI']?>" class="text-right text-danger" style="; font-size:.8em;"></div>
                            <br>

                    </div>



              </div>
            </div>
          </div>
              <?php
                  }while($row_RsLista_adjuntos = mysqli_fetch_array($RsLista_adjuntos));

              }
          ?>


        <div class="col-md-12">
            <button class="btn btn-mdb-color btn-rounded prevBtn-2 float-left" type="button">Anterior</button>
            <button class="btn btn-mdb-color btn-rounded nextBtn-2 float-right" type="button">Siguiente</button>
        </div>
    </div>

    <!-- Fourth Step -->
    <div class="row setup-content-2" id="step-4">
        <div class="col-md-12">
            <h3 class="font-weight-bold pl-0 my-4"><strong>Datos de la cuenta</strong></h3>
            <div>
              <strong>Autoriza el tratamiento de sus datos?.</strong></br>
Con el fin de dar cumplimiento a la Ley 1581 de 2012 y el Decreto 1377 de 2013, el oferente autoriza el tratamiento de sus datos personales suministrados para fines relacionados exclusivamente con el proceso de negociación o contratación. Se aclara que los requisitos exigidos por LA EMPRESA, no requiere la presentación de ningún dato sensible que afecten la intimidad del titular o cuyo uso indebido puede generar su discriminación, en caso que estos datos sean suministrados por oferente, contratista o proveedor, El Colegio San Bonifacio de las Lanzas., no lo incluirá en ninguna de sus bases de datos y por tanto no realiza tratamiento sobre ellos.

            </div>
             <div class="form-group md-form">
                Autorizaci&oacute;n
                <select id="autorizacion" name="autorizacion"  required="required" class="form-control validate">
                  <option value=""></option>
                  <option value="1">Si</option>
                  <option value="2">No</option>
                </select>
            </div>
			<div class="row">
				<div class="alert alert-warning w-100 m-1" id="mensajeunicousuario" style="display:none;"></div>
			</div>

            <div class="form-group md-form">
                <label for="usuario" data-error="wrong" data-success="right">Usuario</label>
                <input id="usuario" type="text" name="usuario" required="required" class="form-control validate unicouser" maxlength="20" readonly>
            </div>
            <div class="form-group md-form">
                <label for="password" data-error="wrong" data-success="right">Contrase&ntilde;a</label>
                <input id="password" type="password" name="password" required="required" class="form-control validate">
            </div>
            <div class="form-group md-form">
                <label for="repassword" data-error="wrong" data-success="right">Confirme Contrase&ntilde;a</label>
                <input id="repassword" type="password" name="repassword" required="required" class="form-control validate">
            </div>
            <button class="btn btn-mdb-color btn-rounded prevBtn-2 float-left" type="button">Anterior</button>
            <button class="btn btn-success btn-rounded float-right" type="button" id="btnguardarproveedor" onclick="enviarregistro()">Enviar</button>
        </div>
    </div>
</form>
</div>
    </div>
  </div>
  </div>



<script src="../../includes/selectmultiple/jquery.listswap.js"></script>
  <script src="../../js/stepper/popper.min.js"></script>
  <script src="../../js/stepper/bootstrap.min.js"></script>
  <script src="../../js/stepper/mdb.min.js"></script>
  <script src="../../js/jqueryfileupload/vendor/jquery.ui.widget.js"></script>
<script src="../../js/jqueryfileupload/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="../../js/jqueryfileupload/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="../../js/jqueryfileupload/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="../../js/jqueryfileupload/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="../../js/jqueryfileupload/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="../../js/jqueryfileupload/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="../../js/jqueryfileupload/jquery.fileupload-validate.js"></script>
<script type="text/javascript" src="../../js/toast/toastr.min.js"></script>
  <!-- Your application bootstrap  -->
  <script type="text/javascript">
// Tooltips Initialization
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

// Steppers
$(document).ready(function () {
  var navListItems = $('div.setup-panel-2 div a'),
          allWells = $('.setup-content-2'),
          allNextBtn = $('.nextBtn-2'),
          allPrevBtn = $('.prevBtn-2');

  allWells.hide();

  navListItems.click(function (e) {
      e.preventDefault();
      var $target = $($(this).attr('href')),
              $item = $(this);

      if (!$item.hasClass('disabled')) {
          navListItems.removeClass('btn-amber').addClass('btn-blue-grey');
          $item.addClass('btn-amber');
          allWells.hide();
          $target.show();
          $target.find('input:eq(0)').focus();
      }
  });

  allPrevBtn.click(function(){
      var curStep = $(this).closest(".setup-content-2"),
          curStepBtn = curStep.attr("id"),
          prevStepSteps = $('div.setup-panel-2 div a[href="#' + curStepBtn + '"]').parent().prev().children("a");

          prevStepSteps.removeAttr('disabled').trigger('click');
  });

  allNextBtn.click(function(){
      var curStep = $(this).closest(".setup-content-2"),
          curStepBtn = curStep.attr("id"),
          nextStepSteps = $('div.setup-panel-2 div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
          curInputs = curStep.find("input[type='text'],input[type='url']"),
          isValid = true;

      $(".form-group").removeClass("has-error");
      for(var i=0; i< curInputs.length; i++){
          if (!curInputs[i].validity.valid){
              isValid = false;
              $(curInputs[i]).closest(".form-group").addClass("has-error");
          }
      }

      if (isValid)
          nextStepSteps.removeAttr('disabled').trigger('click');
  });

  $('div.setup-panel-2 div a.btn-amber').trigger('click');
  
	$( ".unica" ).change(function() { 
	ide = $(this).val();
	  if(ide != ''){
       $("#usuario").val(ide);
			var date = new Date();
			var timestamp = date.getTime();
			//var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=CargarDatos&time="+timestamp+"&codigo_detalle="+det);
			 $.ajax({
			  type: "POST",
			  url: "php/tipoguardar.php?tipoGuardar=VerificarIdentificacionExiste&identificacion="+$(this).val()+"&timers="+timestamp,
			  dataType: 'json',
			  success : function(r){ console.log(r);
				if(r != ''){
					if(r.status == 'erroruser'){
						$("#mensajeunico").css("display","block");
						$("#mensajeunico").html("el número de identificación: "+ ide  +" ya se encuentra registrado como proveedor, si desea iniciar sesión o recordar contraseña click <a href='proveedorpublico.php'>aquí</a>");
						msgerror('el número de identificación: <b>'+ ide  +'</b> ya se encuentra registrado como proveedor, si desea iniciar sesión o recordar contraseña click <a href="proveedorpublico.php">aquí</a>');
					    DisabledInput();	
						
					}
					if(r.status == 'errorusertemporal'){
						if(r.data_temp == '0'){
							msgerror('ya se ha ingresado un registro previo con esta identificación, que se encuentra en proceso de aprobación');
						$("#mensajeunico").css("display","block");
						$("#mensajeunico").html("ya se ha ingresado un registro previo con la identificación ingresada, que se encuentra en proceso de aprobación");
						DisabledInput();						
						}else{
								msgerror('Esta identificación ya se encuentra registrada, por favor inicie sesión, si no recuerda su contraseña acceda mediante el recurso recordar contraseña, de la pantalla inicial click <a href="proveedorpublico.php">aquí</a>');
								$("#mensajeunico").html("Esta identificación ya se encuentra registrada, por favor inicie sesión, si no recuerda su contraseña acceda mediante el recurso recordar contraseña, de la pantalla inicial click <a href='proveedorpublico.php'>aquí</a>");
								DisabledInput();								
							}
						}
					if(r.status == 'ok'){
						$("#mensajeunico").css("display","none");
						$("#mensajeunico").html("");
						EnabledInput();						
					}
				   }
				},
			  error   : callback_error
			});		  
	  }else{
		   $("#mensajeunico").css("display","none");
		   DisabledInput();
	  }
	});
	
	$( ".unicouser" ).change(function() {
	ide = $(this).val();
	  if(ide != ''){
			var date = new Date();
			var timestamp = date.getTime();
			//var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=CargarDatos&time="+timestamp+"&codigo_detalle="+det);
			 $.ajax({
			  type: "POST",
			  url: "php/tipoguardar.php?tipoGuardar=VerificarUsuarioExiste&usuario="+$(this).val()+"&timers="+timestamp,
			  dataType: 'json',
			  success : function(r){ console.log(r);
				if(r != ''){
					if(r.status == 'errortbluser'){
						$("#mensajeunicousuario").css("display","block");
						$("#mensajeunicousuario").html("nombre de usuario no disponible: "+ ide  +" ya se encuentra registrado como proveedor, si desea iniciar sesión o recordar contraseña click <a href='proveedorpublico.php'>aquí</a>");
						msgerror('el usuario: <b>'+ ide  +'</b> ya se encuentra registrado como proveedor, si desea iniciar sesión o recordar contraseña click <a href="proveedorpublico.php">aquí</a>');
					}
					if(r.status == 'errortblprovtemporal'){
						if(r.data_temp == '0'){
							msgerror('usuario no disponible, ya hay un usuario proceso de aprobación');
							$("#mensajeunicousuario").css("display","block");
							$("#mensajeunicousuario").html("usuario no disponible, ya hay un usuario proceso de aprobación");							
						}else{
								msgerror('Este usuario ya se encuentra registrado, por favor inicie sesión, si no recuerda su contraseña acceda mediante el recurso recordar contraseña, de la pantalla inicial click <a href="proveedorpublico.php">aquí</a>');
								$("#mensajeunicousuario").html("Este usuario ya se encuentra registrado, por favor inicie sesión, si no recuerda su contraseña acceda mediante el recurso recordar contraseña, de la pantalla inicial click <a href='proveedorpublico.php'>aquí</a>");								
							}
						}
					if(r.status == 'ok'){
						$("#mensajeunicousuario").css("display","none");
						$("#mensajeunicousuario").html("");						
					}
				   }
				},
			  error   : callback_error
			});		  
	  }else{
		   $("#mensajeunico").css("display","none");
	  }
	});	
});

function DisabledInput(){
  $(".form-control").each(function(index){
     id = $(this).attr('id');
   	 $("#"+id).attr("disabled", true);  
	 $("#"+id).attr("readonly", true);
 })
 $("#tipo_identificacion").attr("disabled", false); 
 $("#tipo_identificacion").attr("readonly", false);  
 $("#numero_identificacion").attr("disabled", false); 
 $("#numero_identificacion").attr("readonly", false);  
 $("#div").attr("disabled", false); 
 $("#div").attr("readonly", false); 
}

function EnabledInput(){
  $(".form-control").each(function(index){
     id = $(this).attr('id');
   	 $("#"+id).attr("disabled", false);  
	 $("#"+id).attr("readonly", false);
 	})
 $("#usuario").attr("disabled", false); 
 $("#usuario").attr("readonly", true); 	
 
}


function CalcularDv()
{ 
 var arreglo, x, y, z, i, nit1, dv1;
 nit1=document.form1.nit.value;
	if (isNaN(nit1))
	{
 	document.form1.dv.value="X";
      alert('Número del Nit no valido, ingrese un número sin puntos, ni comas, ni guiones, ni espacios');		
	} else {
  arreglo = new Array(16); 
 	x=0 ; y=0 ; z=nit1.length ;
 	arreglo[1]=3;  	arreglo[2]=7; 	arreglo[3]=13; 
 	arreglo[4]=17; 	arreglo[5]=19; 	arreglo[6]=23;
 	arreglo[7]=29; 	arreglo[8]=37; 	arreglo[9]=41;
 	arreglo[10]=43; arreglo[11]=47; arreglo[12]=53;  
 	arreglo[13]=59; arreglo[14]=67; arreglo[15]=71;
  for(i=0 ; i<z ; i++)
 	{ 
 	 y=(nit1.substr(i,1));
     x+=(y*arreglo[z-i]);
 	} 
  y=x%11
  if (y > 1){ dv1=11-y; } else { dv1=y; }
 	document.form1.dv.value=dv1;
	}
}

var archivos_load = [];

function subirArchivoNewJ(divupload,codigo,label){
    'use strict';
    // Change this to the location of your server-side upload handler:
    //var url = window.location.hostname === 'blueimp.github.io' ? '//localhost/uploads/uploads/' : 'server/php/';
    var url = 'php/',
        cancelButton = $('<button data-toggle="tooltip" title="Eliminar Archivo" data-placement="top" id="botonnewarchivo_'+codigo+'"/>')
      .addClass('btn btn-sm btn-danger')
      .html('<i class="fa fa-close"></i><span> ')
      .on('click', function () {
      var $this = $(this),
        data = $this.data();
        if($('#nombre_delarchivo_'+codigo).val()!=''){
           $('#progress .progress-bar').css(
            'width', '0%'
          );
               $('#files_'+codigo).html('');

               EliminarArchivoupload2($('#nombre_delarchivo_'+codigo).val(), codigo, 'button');
            }

                data.done().always(function () {
                    $this.remove();
                });
      });
      //data.abort();
      //

    $('#'+divupload).fileupload({
        url: url,
        dataType: 'json',
        maxFileSize: 20000000, // 20 MB
        autoUpload: false,
        acceptFileTypes: /(\.|\/)(pdf)$/i,
        formData : {documento:'adjunto',tipodoc: ''+label},
        add: function (e, data) {
        var uploadErrors = [];
        var acceptFileTypes = /(\.|\/)(pdf)$/i;
        if(data.originalFiles[0]['type'].length && !acceptFileTypes.test(data.originalFiles[0]['type'])) {
            uploadErrors.push('No se acepta este tipo de archivo');
        }
        /*if(data.originalFiles[0]['size'].length && data.originalFiles[0]['size'] > 5000000) {
            uploadErrors.push('Filesize is too big');
        }*/
        if(uploadErrors.length > 0) {
            $('#sierror_'+codigo).text('ERROR '+uploadErrors.join("\n"));
        } else {
                         $.each(data.files, function (index, file) {
                            if ((file.name)) {
              //alert('nombre del archivo original '+file.name);
                                $('#carga_'+codigo).css(
                                     'display','block'
                                );
                              data.submit();
                            }
                          });
        }



         },
        done: function (e, data) {
     <?php //var filesd='newarchivo10'; newarchivo10 $.each de la siguiente linea es el nombre generico del array json retornado ?>
            $.each(data.result.newarchivo10, function (index, file) {
        //alert(codigo);
        //alert(file.name);
        var nuevointento=0;

        if (file.error) {
           var abort=0;
           $("#buttondelete_"+codigo).remove();
           if(file.error!='abort'){
            $('#sierror_'+codigo).text('ERROR '+file.error);
          abort=1;
          nuevointento=1;
            $('#carga_'+codigo).css(
                   'display','none'
             );
          $('#progress_'+codigo+' .progress-bar').css(
            'width',
             '0%'
          );
          }

          if(nuevointento==0 && file.error!='abort'){
          //$('#files').text('');
          $('#progress_'+codigo+' .progress-bar').css(
            'width',
             '0%'
          );
                  }
        }
                if(nuevointento==0){
        //$("#files_"+codigo).children().remove();
        $('<div class="col-md-8 col-sm-8 pt-2" style="font-size:.8rem"/>').text(file.name).appendTo('#files_'+codigo);
        $('<div class="col-md-2 col-sm-2 fa fa-check pt-2 text-success"/>').appendTo('#files_'+codigo);
        $('#nombre_delarchivo_'+codigo).val(file.name);
        $('#sierror_'+codigo).text('');
        $('#carga_'+codigo).css(
                   'display','none'
             );
        setTimeout(function() {
            toastr.success('<strong>archivo cargado exitosamente </strong>');
            }, 100 );
            archivos_load.push({'codigo':codigo, 'name': file.name });
            console.log(archivos_load);
        }


            });
        },
    fail: function (e, data) {//para eventos fallidos o abortados
            $('#carga_'+codigo).css(
                   'display','none'
             );
            $('#progress_'+codigo+' .progress-bar').css(
            'width',
             '0%'
               );
      $('#sierror_'+codigo).text('Error al cargar intente nuevamente '+codigo);
    },
    always: function (e, data) {//para eventos cuando ya se completaron
    },
    change: function (e, data) {


      if($('#nombre_delarchivo_'+codigo).val()!=''){
        // $('#botonnewarchivo_'+codigo).remove();
       //$('#files_'+codigo).html('');
       //$("#files_"+codigo).empty();
       //alert($('#files_'+codigo).text());
       $('#files_'+codigo).innerHTML='';
       $('#files_'+codigo).html('');
       $("#files_"+codigo).children().remove();
         EliminarArchivoupload2($('#nombre_delarchivo_'+codigo).val(),codigo, 'change');
      }
    },

        progressall: function (e, data) {
        $('#carga_'+codigo).css(
                     'display','block'
            );
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress_'+codigo+' .progress-bar').css(
                'width',
                progress + '%'
            );
      if(progress=='100'){
      //alert('archivo cargado exitosamente');
      }
        }
    })
  .on('fileuploadsend', function (e, data) {
        data.context = $('<div/>').appendTo('#files_'+codigo);
        var counter = 0;
      if($('#buttondelete_'+codigo).length == 0 ){
          var node = $('<div id="buttondelete_'+codigo+'"/>');
          node.append(cancelButton.clone(true).data(data));
          node.appendTo(data.context);
      }
        /*
      $.each(data.files, function (index, file) {
                  var node = $('<div id="buttondelete_'+codigo+'"/>');
                    //.append($('<span/>').text(file.name));
                    console.log('ini');
                    console.log(file);
                    console.log('end');
                    console.log('counter'+counter);

            if (!index) {
                //node
                    //.append('<br>')
                    //.append(cancelButton.clone(true).data(data));
                    console.log('el length es '+$('#buttondelete_'+codigo).length);
                    if($('#buttondelete_'+codigo).length == 0 ){
                      node.append(cancelButton.clone(true).data(data));

                    }
                    if($('#buttondelete_'+codigo).length == 1){
                      $('#buttondelete_'+codigo).remove();
                      //$('#files_'+codigo).html('');
                    }

            }
             node.appendTo(data.context);


    }); */
    })
  .prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');

}

function EliminarArchivoupload2(name,codigo, action){
  /*$('#nombre_delarchivo_'+codigo).val('');
  var v_dato = getDataServer("tipoguardar.php","?tipoGuardar=BorrarUploadFile&name_archivo="+name);
}*/
    var date = new Date();
    var timestamp = date.getTime();
    //var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=CargarDatos&time="+timestamp+"&codigo_detalle="+det);
     $.ajax({
      type: "POST",
      url: "php/tipoguardar.php?tipoGuardar=BorrarUploadFileProveedor&name_archivo="+name+"&timers="+timestamp,
      success : function(r){
        if(r != ''){
            if(r=='2'){
              if(action == 'button'){
              $('#nombre_delarchivo_'+codigo).val('');
              $('#botonnewarchivo_'+codigo).remove();

                toastr.success('<strong>Adjunto eliminado Correctamente </strong>');
                    $('#progress_'+codigo+' .progress-bar').css(
                  'width',
                   '0%'
                     );
              }
            }else{
              if(action == 'button'){
                toastr.error('<strong>Se ha presentado un error eliminando el archivo </strong>');
              }
            }
          }
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
function showmessages(validacampo,idshow){
  sections  = [
                {'section':'step-1', 'label':'informacion basica'},
                {'section':'step-2', 'label':'productos y servicios'},
                {'section':'step-3', 'label':'documentos adjuntos'},
                {'section':'step-4', 'label':'final'}
              ];
  for(i=0; i<sections.length; i++){
    $("#"+sections[i].section).css('display','none');
  }
  $("#"+sections[idshow].section).css('display','flex');
  //$('#'+validacampo).focus()
  $("#modal-validar").modal();
    $("#msgvalidar").html('');
    for(t=0; t<validacampo.length; t++){
      $("#msgvalidar").html($("#msgvalidar").html()+' <div class="alert alert-danger"><strong class="fa fa-warning"> Advertencia!</strong> '+validacampo[t].msg+' no puede estar vacío!</div>');
    }
}
function validarcampos(){
  validacampo = [];
  <?php /*campos obligatorios por cada seccion*/ ?>
  info_basica = [
                  {'campo': 'tipo_identificacion', 'msg': 'tipo identificación'},
                  {'campo': 'numero_identificacion', 'msg': 'número identificación'},
                  {'campo': 'div', 'msg': 'Código de verificación Dian en la primera pestaña , datos basicos'},
                  {'campo': 'tipo_persona', 'msg': 'tipo persona'},
                  {'campo': 'regimen', 'msg': 'regimen'},
                  {'campo': 'autoretenedor', 'msg': 'tipo autoretenedor'},
                  {'campo': 'gran_contribuyente', 'msg': 'gran contribuyente'},
                  {'campo': 'contribuyente_ica', 'msg': 'contribuyente ica'},
                  {'campo': 'telefono' , 'msg': 'teléfono'}

                ];
info_usuario = [
  {'campo': 'autorizacion' , 'msg': 'autorizacion'},
  {'campo': 'usuario' , 'msg': 'usuario'}
];

  for(i=0; i < info_basica.length; i++){
    if($('#'+info_basica[i].campo).val() == ''){
          validacampo.push({'campo': info_basica[i].campo, 'msg': info_basica[i].msg});<?php /*Si hay campos vacios se pobla el array validacampo*/ ?>
        /*if(validacampo == ''){
            validacampo = ''+info_basica[i].campo ;
        }*/
    }
  }
  if(validacampo.length > 0){ <?php /*se valida que si existen campos vacios en la seccion 1 de información básica salga return */ ?>
    showmessages(validacampo,0); <?php /*el valor cero corresponde al primer dato de array sections */?>
    return validacampo;
    }
    if(validacampo.length == 0){ <?php /*Si la pestaña 1 de info basica esta correctamente diligenciada se valida la seccion 4 usuario lo de guardar */ ?>
      for(i=0; i < info_usuario.length; i++){
        if($('#'+info_usuario[i].campo).val() == ''){
              validacampo.push({'campo': info_usuario[i].campo, 'msg': info_usuario[i].msg});<?php /*Si hay campos vacios se pobla el array validacampo esta vez con la info del array info_usuario */ ?>
            /*if(validacampo == ''){
                validacampo = ''+info_basica[i].campo ;
            }*/
        }
      }
    }

    if(validacampo.length > 0){
      showmessages(validacampo,3); <?php /*el valor 3 corresponde al ultimo dato de array sections cuarto record*/?>
      }


    return validacampo;
  }

function msgerror(msg){
  $("#modal-validar").modal();
  $("#msgvalidar").html('');
  $("#msgvalidar").html('<div class="alert alert-danger"><strong class="fa fa-warning"> Advertencia!</strong> '+msg+'!</div>');
}

function showmsgOne(field,idshow){
  $("#modal-validar").modal();
  $("#msgvalidar").html('<div class="alert alert-danger"><strong class="fa fa-warning"> Advertencia!</strong> campo '+field+' no puede estar vacío!</div>');
  sections  = [
                {'section':'step-1', 'label':'informacion basica'},
                {'section':'step-2', 'label':'productos y servicios'},
                {'section':'step-3', 'label':'documentos adjuntos'},
                {'section':'step-4', 'label':'final'}
              ];
  for(i=0; i<sections.length; i++){
    $("#"+sections[i].section).css('display','none');
  }
  $("#"+sections[idshow].section).css('display','flex');
}

function enviarregistro(){
  valida = validarcampos();
  if(valida.length > 0){
    return;
  }
  if($("#tipo_persona").val() == '1'){
    if($("#nombre").val() == '' || $("#apellidos").val() == ''){
      showmsgOne('nombre y apellidos',0);
      return;
    }

  }
  if($("#tipo_persona").val() == '2'){
    if($("#razon_social").val() == '' ){
      $("#msgvalidar").html('<div class="alert alert-danger"><strong class="fa fa-warning"> Advertencia!</strong> campo razón social no puede estar vacío!</div>');
      showmsgOne('razón social',0);
      return;
    }
  }
if($("#password").val() == ''){
  showmsgOne('contraseña',3);
  return;
}
if($("#password").val()  !=  $("#repassword").val()) {
  $("#modal-validar").modal();
  $("#msgvalidar").html('<div class="alert alert-danger"><strong class="fa fa-warning"> Advertencia!</strong>contraseña y confirmar contraseña no es igual!</div>');
  return;
}

  img ="<i class='fa fa-spinner fa-spin' id='ispinner' style='color:#ff0000'></i>";
  $("#btnguardarproveedor").append(img);
  var date = new Date();
  var timestamp = date.getTime();
  var form = $('#form_registro');
  //$("#step-3").remove();
  $("#modalguardar").modal();
  $("#btnguardarproveedor").prop("disabled", true);

  var cat_prov = $('#destination_categoriaprov option').map(function() { return $(this).val(); }).get();
   $.ajax({
    type: "POST",
    url: "php/tipoguardar.php?tipoGuardar=saveproveedor&name_archivo&timers="+timestamp,
    dataType: 'json',
    success : function(r){
      if(r.status == 'erroruser'){
        msgerror('Error al registrar usuario, la identificación que ingreso ya se encuentra registrada');
      }      
	  if(r.status == 'errorusertemporal'){
        msgerror('Error al registrar usuario, identificación no disponible, este número de identificación ya se encuentra solicitado');
      }	 
	  if(r.status == 'errortblprovtemporal'){
        msgerror('Usuario no disponible, este nombre de usuario ya se encuentra solicitado');
      }	  
	  if(r.status == 'errortbluser'){
        msgerror('Error al registrar datos, usuario no disponible');
      }
      if(r.status == 'failed'){
        msgerror('Se ha presentado un error al guardar la información, Intente más tarde.');
      }
      if(r.status == 'ok'){
          $("#btnguardarproveedor > i").remove();
          $("#formularioproveedor").css("display","none");
          $("#formularioproveedorok").css("display","block");
          $('#modalguardar').modal('hide');
        }
        setTimeout(function () {
          $('#modalguardar').modal('hide');
          $("#btnguardarproveedor > i").remove();
          $("#btnguardarproveedor").prop("disabled", false);
        }, 1000);
      },
    data: form.serialize()+'&categorias='+JSON.stringify(cat_prov),
    error   : error_call
  });
}
function error_call(XMLHttpRequest, textStatus, errorThrown)
{
    /*alert("Respuesta del servidor "+XMLHttpRequest.responseText);
    alert("Error "+textStatus);
    alert(errorThrown);*/
    $('#modalguardar').modal('hide');
    $("#btnguardarproveedor").prop("disabled", false);
    $("#btnguardarproveedor > i").remove();
    msgerror('Se ha presentado un error al guardar la información, Intente más tarde.');
}
$('#categoria_prov, #destination_categoriaprov').listswap({
	truncate:false,
	height:300,
  label_add:'Agregar',
	label_remove:'Remover',
	add_class:'list_ar',
});

function acceptNum(evt)
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

  activefields = function(value){
     if(value==''){
       $('#nombre').prop('readonly',true);
       $('#apellidos').prop('readonly',true);
       $('#razon_social').prop('readonly',true);
       $('#nombre').val('');
       $('#apellidos').val('');
       $('#razon_social').val('');
     }
      if(value == 1){
        $('#nombre').prop('readonly',false);
        $('#apellidos').prop('readonly',false);
        $('#razon_social').prop('readonly',true);
        $('#razon_social').val('');

      }
      if(value==2){
        $('#razon_social').prop('readonly',false);
        $('#nombre').prop('readonly',true);
        $('#apellidos').prop('readonly',true);
        $('#nombre').val('');
        $('#apellidos').val('');

      }

  }

  </script>
  <!-- Modal -->
  <div class="modal fade" id="modalguardar" tabindex="-1" role="dialog" aria-labelledby="modal-guardar" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          Guardando Información
        </div>
        <div class="modal-body">
          <div style="text-align:center">
          <i class="fa fa-spinner fa-spin fa-4x" id="ispinner" style="color:#ff0000"></i><br></br>

         </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="modal-validar" role="dialog" aria-labelledby="modal-guardar" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modaltitlevalidar">Validando datos</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        </div>
        <div class="modal-body" id="msgvalidar">

        </div>
      </div>
    </div>
  </div>
</body>
</html>
