<?php

require_once('../../conexion/db.php');


$msg = '';
if(isset($_REQUEST['msg']) && $_REQUEST['msg'] != ''){
	$msg =$_REQUEST['msg'];
}


$codigo_proveedor_oficial = '';
if(isset($_REQUEST['u']) && $_REQUEST['u'] != ''){	
	$codigo_proveedor_oficial = ''.decoded($_REQUEST['u']).'';	
}

$tipo_reset = '';
if(isset($_REQUEST['v']) && $_REQUEST['v'] != ''){	
	$tipo_reset = ''.decoded($_REQUEST['v']).'';
}


 function decoded($str)  
{  
   $alpha_array =array('Y','D','U','R','P','S','B','M','A','T','H');  
   $decoded = base64_decode($str);  
   list($decoded,$letter) = explode('+',$decoded); 

   for($i=0;$i<count($alpha_array);$i++)  
   {  
	if($alpha_array[$i] == $letter)  
	break;  
   }  
   	for($j=1;$j<=$i;$j++)  
   {  
    $decoded = base64_decode($decoded);  
   }  
   return $decoded;  
}//end of decoded function  

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

</head>

<body>
	<div class="jumbotron jumbotron-fluid">
		<div class="container">
		
			<?php

				if($tipo_reset == 2) 
				{//no tiene usuario creado verificacion 2
				
			?>
				<form name="form1" id="form1" action="" method="post">
				<h1> Le damos la bienvenidos al portal de compras, por favor diligencie los siguientes campos para crear su nueva contrase&ntilde;a</h1>
				<div class="form-group">
				             <div class="col-md-3">
								<label for="exampleFormControlInput1">Nueva Contrase&ntilde;a</label>							
								<input  class="form-control form-control-sm" type="password" placeholder="Digite aqui su nueva Contrase&ntilde;a" name="nueva_clave" id="nueva_clave" value="" >
								</div>
				</div>
				<div class="form-group">
				             <div class="col-md-3">
								<label for="exampleFormControlInput1">Confirme Contrase&ntilde;a</label>		
								<input class="form-control form-control-sm" type="password" placeholder="Confirme aqui su  Contrase&ntilde;a" name="nueva_clave_confirm" id="nueva_clave_confirm" value="" >
								</div>
				</div>
				<div class="form-group">
						<div style="padding-top:2em;">
							<div class="col-md-3" >
								<div class="text-right">
									<input type="hidden" name="form_ok" id="form_ok" value="102">
									<button type="button" onclick="return UpdatePassword()" class="btn btn-primary btn-block" id="btn_cambiar_contrasena"><i class="fa fa-refresh fa-spin" style="display:none;"></i> Nueva Contraseña
                                    </button>
								</div>
							</div>
						</div>
					</div>
				</form>

				<?php 
					}elseif($tipo_reset == 1)
					 {//si tiene usuario creado
				?>
					<form name="form1" id="form1" action="" method="post">
					   <h1> Diligencie los siguientes campos para actualizar su nueva contrase&ntilde;a</h1>
						<div class="form-group">
									<div class="col-md-3">
										<label for="exampleFormControlInput1">Nueva Contrase&ntilde;a</label>							
										<input  class="form-control form-control-sm" type="password" placeholder="Digite aqui su nueva Contrase&ntilde;a" name="nueva_clave" id="nueva_clave" value="" >
										</div>
						</div>
						<div class="form-group">
									<div class="col-md-3">
										<label for="exampleFormControlInput1">Confirme Contrase&ntilde;a</label>		
										<input class="form-control form-control-sm" type="password" placeholder="Confirme aqui su Contrase&ntilde;a" name="nueva_clave_confirm" id="nueva_clave_confirm" value="" >
										</div>
						</div>
						<div class="form-group">
								<div style="padding-top:2em;">
									<div class="col-md-3" >
										<div class="text-right">
											<input type="hidden" name="form_ok" id="form_ok" value="102">
											<button type="button" onclick="return UpdatePassword()" class="btn btn-primary btn-block" id="btn_cambiar_contrasena"><i class="fa fa-refresh fa-spin" style="display:none;"></i> Restablecer Contraseña
											</button>
										</div>
									</div>
								</div>
							</div>
					</form>
					
					<?php 
						}else{

							//error de registro
							//echo('error en la plataforma');
						}
					
					if($tipo_reset == 3){
						echo('En desarrollo');
                      
					}
					?>
					
        <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
		<?php if($msg==1){ ?>
            <h1>Gracias por se parte de nuestros proveedores </h1>
			<h2>En los proximos minutos le enviaremos la confirmación del restablecimiento de su contraseña</h2>
		  <?php }  

		if($msg==2){
          ?>
<h1></h1>
		<?php	
		}
		?>

		</div>
		</div>
	</div>
	

	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="   crossorigin="anonymous"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script type="text/javascript">

		function UpdatePassword()
			{
				if($("#nueva_clave").val() == '')
				{
				alert('¿debe ingresar la nueva contraseña?.');				
				} 

				if($("#nueva_clave_confirm").val() == '')
				{
				alert('¿debe confirmar la contraseña?.');
					
				} 
				if($("#nueva_clave_confirm").val() != $("#nueva_clave").val())
				{
				alert('¿las contraseñas no coinciden?.');
					  
				}		
				if(confirm('Esta seguro de actualizar la contraseña?'))
					{
						document.form1.action="cambiar_password_guardar.php?tipoGuardar=Actualizar&v=<?php echo($tipo_reset);?>&n=<?php echo($codigo_proveedor_oficial);?>";
						document.form1.submit(); 
					}else{
						return false;
					}
			}
   </script>	 
</body>
</html>