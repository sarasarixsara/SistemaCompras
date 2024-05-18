<?php
//inicio del php

require_once('conexion/db.php');

//echo($_SESSION['MM_IDUsuario']);
$msg = 0;
if(isset($_GET['msg']) && ($_GET['msg']==1 || $_GET['msg']==2)){
$msg = $_GET['msg'];	
}

?>



<!DOCTYPE html>

<html>
<!-- inicio del html -->
<head>

<title>Cambiar Contrase&ntilde;a</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="js/jquery.ui.1.8.16.js"></script>
<script type="text/javascript">
// inicio del javascript
//crear usuario

  function volveraListado(){
   document.form1.action ="home.php?page=listar_usuarios";
 document.form1.submit();
 }
 function UpdatePassword(){
	if($("#nueva_clave").val() == '')
	  {
	   inlineMsg('nueva_clave','¿debe ingresar la nueva contraseña?.',3);
			return false;
	  } 	
	  if($("#nueva_clave_confirm").val() == '')
	  {
	   inlineMsg('nueva_clave_confirm','¿debe confirmar la contraseña?.',3);
			return false;
	  } 
	  if($("#nueva_clave_confirm").val() != $("#nueva_clave").val())
	  {
	   inlineMsg('nueva_clave_confirm','¿las contraseñas no coinciden?.',3);
			return false;	  
	  }		
	 if(confirm('Esta seguro de actualizar la contraseña?'))
		{
			document.form1.action="cambiar_password_guardar.php?tipoGuardar=Actualizar";
			document.form1.submit(); 
		}else{
			return false;
		}
 }
 function redireccionar(){
	 setTimeout(function(){
		window.location="logout.php";
	 }, 5000);
 }
</script>
 <script type="text/javascript">
function upperCase() {
   var x=document.getElementById("nombre").value;
   var y=document.getElementById("apellido").value;
   document.getElementById("nombre").value=x.toUpperCase();
   document.getElementById("apellido").value=y.toUpperCase();
}
</script>
</head>

<body>
<?php if($msg==0){ ?>
<form name="form1" id="form1" action="" method="post">
 <table border="0" class="tableadmin" width="600">
    <tr>
	  <td colspan="2" ><h1>ACTUALIZAR CONTRASE&Ntilde;A</h1></td>	  
	</tr>
	<tr>
	  <td width="90">Contrase&ntilde;a</td>
	  <td width="180"><input type="password" placeholder="Contrase&ntilde;a" name="nueva_clave" id="nueva_clave" value="" size="30" class="form-control"></td>
	</tr>	
	<tr>
	  <td width="90">Confirmar Contrase&ntilde;a</td>
	  <td width="180"><input type="password" placeholder="Confirmar Contrase&ntilde;a" name="nueva_clave_confirm" id="nueva_clave_confirm" value="" size="30" class="form-control"></td>
	</tr>
	<tr>
		<td><br></br></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" class="btn btn-primary" value="Actualizar" onclick="return UpdatePassword()"></td>
	</tr>
 </form> 
<?php }
if($msg==1){
	?>
<div class="alert alert-success" role="alert">
        <strong>Ok!</strong> Tu contraseña se ha cambiado correctamente. <strong>debes volver a iniciar sesión.</strong>
      </div>
<script type="text/javascript">
redireccionar();
</script>
	<?php
}
if($msg==2){
	?>
<div class="alert alert-danger" role="alert">
        <strong>Error!</strong> Ha sucedido un problema, tu contraseña no ha podido ser actualizada.
      </div>	
	<?php
}
?>
</body>

</html>