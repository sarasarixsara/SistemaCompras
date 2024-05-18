<?php
require_once('../../conexion/db.php');

$form_ok = '';
	if(isset($_POST['form_ok']) && $_POST['form_ok'] != ''){
		$form_ok = $_POST['form_ok'];
    }    
	$tipo_identificacion = '';
	if(isset($_POST['tipo_identificacion']) && $_POST['tipo_identificacion'] != ''){
		$tipo_identificacion = $_POST['tipo_identificacion'];
	}	
	$identificacion = '';
	if(isset($_POST['identificacion']) && $_POST['identificacion'] != ''){
		$identificacion = $_POST['identificacion'];
    }	
    $response='';
    if($form_ok == '102'){
                $query_LoginRS = "SELECT 	P.PROVUSUA USUARIO_PROVEEDOR, 
                                            P.PROVCODI CODIGO_PROVEEDOR, 
                                            P.PROVNOMB NOMBRE_PROVEEDOR, 
                                            P.PROVREGI REGISTRO, 
                                            P.PROVCORR EMAIL
                                    
                                    FROM PROVEEDORES P
                                    WHERE P.PROVREGI = '".$identificacion."'  
                                         
                                        ";
                                        //AND 	USUAPASS=AES_ENCRYPT('".$password."','".$clave."')
                                        //echo($query_LoginRS);
                   $LoginRS = mysqli_query($conexion,$query_LoginRS) or die(mysqli_connect_error());
                   $row_LoginRS = mysqli_fetch_array($LoginRS);
                   $loginFoundUser = mysqli_num_rows($LoginRS);
                   if($loginFoundUser > 0){
                       $email               =$row_LoginRS['EMAIL'];  
                       $nombre_proveedor    =$row_LoginRS['NOMBRE_PROVEEDOR']; 
                       $nit                 =$row_LoginRS['REGISTRO']; 
                       $codigo_proveedor    =$row_LoginRS['CODIGO_PROVEEDOR'];                   
                       $response='ok';
                   }else{
                    $response='sin_registro';
                    $mensaje=' Este NIT no se encuentra registrado en nuestra base de datos de proveedores';	                    						  	
              }

    }else{
       // header("Location: proveedorpublico.php?acceso=no"  );									  	
  }
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Portal De Proveedores :: San Bonifacio de Las Lanzas</title>
  </head>
  <body>
  <div class="jumbotron jumbotron-fluid">
  <div class="container">
  <?php if($form_ok == '102' && $response == 'ok'){ ?>
    <h1 class="display-4">Información de recuperación</h1>
    <p class="lead">El proceso de restablecimiento de contraseña a sido exitoso</p>
    <p class="lead">Proveedor  <b><?php echo($nombre_proveedor); ?></b> en los proximos minutos le enviaremos a <b><?php echo($email); ?></b> las instrucciones para ingresar la nueva contraseña</p>
    <div class="row">
						<div style="padding-top:2em;">
							<div class="col-md-12" >
								<div class="text-right">									
									<button type="button"
                                           class="btn btn-primary btn-block" 
                                              id="btn_volver" 
                                              onclick="location.href='proveedorpublico.php';">                                              
                                             Ir al inicio de portal de proveedores
                                    </button>
								</div>
							</div>
						</div>
					</div> 


  <?php }else{
      
      if($response =='sin_registro'){?>
    <div class="row">
		<div class="col-md-12 " >
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong><?php  echo($identificacion ); ?></strong><?php echo($mensaje); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        </div>
  </div>
  <?php  } ?>
  <form action="recuperarpassword.php" id="form1" name="form1" method="post">
    <h1 class="display-4">Olvidé mi contraseña</h1>
    <p class="lead">Llena los datos y recibirás un email con tu nueva contraseña. <br>Escriba el número de identificación tributaria NIT, sin puntos ni caracteres especiales</p>
                   <!--<div class="row">
						<div class="col-md-12" >
							<span>Tipo identificaci&oacute;n</span>
							<select  name="tipo_identificacion" id="tipo_identificacion" class="form-control">
								<option value="">Seleccione...</option>
								<option value="1">Cédula de Ciudadania</option>
								<option value="2">Número de identificación tributario</option>
							</select>
						</div>
					</div> -->
					<div class="row">
						<div class="col-md-3">
							<span>Identificaci&oacute;n Tributaria</span>
							<input type="text" pattern="[0-9]*" onkeypress='return acceptNum(event)'; name="identificacion" id="identificacion" value="" class="form-control">
						</div>
            <div class="col-md-1">
							<span>Digito</span>
							<input type="text" pattern="[0-9]*" onkeypress='return acceptNum(event)'; name="digito" id="digito" value="" class="form-control" placeholder="-">
						</div>
					</div>					
					<div class="row">
						<div style="padding-top:2em;">
							<div class="col-md-12" >
								<div class="text-right">
									<input type="hidden" name="form_ok" id="form_ok" value="102">
									<button type="button"
                                           class="btn btn-primary btn-block" 
                                              id="btn_restablecer_contrasena">
                                              <i class="fa fa-refresh fa-spin" style="display:none;"></i> Restablecer Contraseña
                                    </button>
								</div>
							</div>
						</div>
					</div>
        </form> 
  <?php } ?>           
  </div>
</div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="   crossorigin="anonymous"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script type="text/javascript">
            $(function(){
                $( "#btn_restablecer_contrasena" ).click(function() {
                    if($("#tipo_identificacion").val() == ''){
                        alert("Debe seleccionar el tipo de identificación");
                        return false;
                    }
                    if($("#identificacion").val() == ''){
                        alert("Debe ingresar la identificación tributaria");
                        return false;
                    }
                    
                    $("#btn_restablecer_contrasena > .fa-refresh").css("display","block");		
                    $("#btn_restablecer_contrasena").attr("disabled",true);	

                    document.form1.submit();
                });


            <?php if($form_ok == '102' && $response == 'ok'){ ?>
            	var date = new Date();
			    var timestamp = date.getTime();			
                $.ajax({
                type: "POST",
                url: "php/tipoguardar.php?tipoGuardar=recuperar_password&codigo_proveedor_oficial="+<?php echo($codigo_proveedor); ?>+"&timers="+timestamp,
                dataType: 'json',
                success : function(r){                 
                    console.log(r);
                    
                    },
                error   : error_call
                });		  
            
            <?php }?>

            });

        function error_call(XMLHttpRequest, textStatus, errorThrown)
        {
            alert("Respuesta del servidor "+XMLHttpRequest.responseText);
            alert("Error "+textStatus);
            alert(errorThrown);
        }
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
    </script>
  </body>
</html>