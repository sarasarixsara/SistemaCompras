<?php
require_once('../../conexion/db.php');
	
$acceso = '';
	if(isset($_GET['acceso']) && $_GET['acceso'] != ''){
		$acceso = $_GET['acceso'];
	}	

	$query_RsLista_prov="SELECT *
							   

						FROM CONF_PROVEEDOR
							 

						WHERE COPRTITU = 'aviso_proveedor' 
						and COPRESTA = 1   
					";
	$RsLista_prov = mysqli_query($conexion,$query_RsLista_prov) or die(mysqli_error($conexion));
	$row_RsLista_prov = mysqli_fetch_array($RsLista_prov);
    $totalRows_RsLista_prov = mysqli_num_rows($RsLista_prov);
    if($totalRows_RsLista_prov > 0){
    	$imagen = $row_RsLista_prov['COPRIMAG'];
	}



?>

<!DOCTYPE html>
<html>
<head>
<title>Proveedores</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css" />
<link rel="stylesheet" href="../../includes/font-awesome/css/font-awesome.min.css">
</head>


<style type="text/css">

</style>
<body>
	<form action="login_proveedor.php" id="form1" name="form1" method="post">
<div class="container">
	
	<div class="row">
		<div class="col-md-8">	
			<div class="page-header">
				<h1>Portal Proveedores</h1>
			</div>	
		</div>
		<div class="col-md-4">
			<div class="page-header text-right">
				<a href="proveedorregistro.php"><button type="button" class="btn btn-danger">Registrarse</button></a>	
			</div>
			
		</div>		  
	</div>
	<div class="row">
		<div class="col-md-12">
			Aqui puede diligenciar los datos necesarios para registrarse como proveedor....
		</div>
	</div>		
	<div class="row">
		<div class="col-md-8">
			
				<img src="avisos/<?php echo($imagen);?>" width="100%"></img>
			
		</div>
		<div class="col-md-4">
			<div class="well">
					<h4><i class="fa fa-sign-in"></i>&nbsp;Iniciar Sesión</h4>
					<?php 
						if($acceso == 'no'){
					?>
							<script type="text/javascript">
								alert("No existe un proveedor asociado, verifique su usuario y contrasena ");
								location.href="proveedorpublico.php";
							</script>
					<?php
						}
					?>
				<div style="background: #ffffff; padding: 1em; border-radius: .5em;">
					<div class="row">
						<div class="col-md-12" >
							<span>Tipo identificaci&oacute;n</span>
							<select  name="tipo_identificacion" id="tipo_identificacion" class="form-control">
								<option value="">Seleccione...</option>
								<option value="1">Cédula de Ciudadania</option>
								<option value="2">Número de identificación tributario</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<span>identificaci&oacute;n</span>
							<input type="text" name="identificacion" id="identificacion" value="" class="form-control">
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<span>Contrase&ntilde;a</span>
							<input type="password" name="password" id="password" value="" class="form-control">
						</div>
					</div>
					<div class="row">
						<div style="padding-top:2em;">
							<div class="col-md-12" >
								<div class="text-right">
									<input type="hidden" name="form_ok" id="form_ok" value="101">
									<button type="button" class="btn btn-primary btn-block" id="btn_iniciar"><i class="fa fa-refresh fa-spin" style="display:none;"></i> Inciar Sesi&oacute;n</button>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div style="padding-top:2em;">
							<div class="col-md-12">
								<a href="recuperarpassword.php">Olvido su contrase&ntilde;a</a>
							</div>

						</div>
					</div>					
				</div>
			</div>
		</div>
	</div> 
</div>	
</form>

<script   src="https://code.jquery.com/jquery-3.3.1.min.js"   integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="   crossorigin="anonymous"></script>
<script type="text/javascript">
	$(function(){
		$( "#btn_iniciar" ).click(function() {
			if($("#tipo_identificacion").val() == ''){
				alert("debe ingresar el tipo de identificación");
				return false;
			}
			if($("#identificacion").val() == ''){
				alert("debe ingresar la identificación");
				return false;
			}
			if($("#password").val() == ''){
				alert("debe ingresar el password");
				return false;
			}
			$("#btn_iniciar > .fa-refresh").css("display","block");		
			$("#btn_iniciar").attr("disabled",true);	

			document.form1.submit();
		})
	})

function error_call(XMLHttpRequest, textStatus, errorThrown)
{
    alert("Respuesta del servidor "+XMLHttpRequest.responseText);
    alert("Error "+textStatus);
    alert(errorThrown);
}
</script>
</body>
</html>