<?php
//inicio del php

require_once('conexion/db.php');

//Declaracion de variables
$tipo='';
if(isset($_GET['tipo'])&&$_GET['tipo']!='')
{
$tipo=$_GET['tipo'];
}

$tipoGuardar='';

    //consulta rol
   $query_RsListaRol="SELECT R.ROLCODI CODIGO,
                          R.ROLNOMB NOMBRE
					 FROM ROLES R";
	$RsListaRol = mysqli_query($conexion,$query_RsListaRol) or die(mysqli_error());
	$row_RsListaRol = mysqli_fetch_array($RsListaRol);
    $totalRows_RsListaRol = mysqli_num_rows($RsListaRol);
    
	//consulta area
	$query_RsListaArea="SELECT AREAID CODIGO,
							   AREANOMB NOMBRE,
							   AREAESTA
						FROM AREA
						ORDER BY AREANOMB ASC";
	$RsListaArea = mysqli_query($conexion,$query_RsListaArea) or die(mysqli_error());
	$row_RsListaArea = mysqli_fetch_array($RsListaArea);
    $totalRows_RsListaArea = mysqli_num_rows($RsListaArea);
	
    //consulta poa	
	$query_RsListaPoa="SELECT POACODI CODIGO,
       						   POANOMB NOMBRE,  
							   POAESTA
						 FROM `poa` 
						WHERE `POAESTA`='1' ";
	$RsListaPoa = mysqli_query($conexion,$query_RsListaPoa) or die(mysqli_error());
	$row_RsListaPoa = mysqli_fetch_array($RsListaPoa);
    $totalRows_RsListaPoa = mysqli_num_rows($RsListaPoa);
	
	
if(isset($_GET['cod_editar']) && $_GET['cod_editar'] != ''){

$query_RsEditar="SELECT USUALOG 	CODIGO,
						PERSID      ID_PERSONA,
						USUAROL 	ROL_ID,
						ROLNOMB 	ROL_NOMBRE,	
                        PERSAPEL    APELLIDO,
						PERSNOMB 	NOMBRE, 
						PERSUSUA 	USUARIO,                        						
						PERSEST 	ESTADO,
						PERSCORR    EMAIL
				FROM    PERSONAS, 
						USUARIOS, 
						ROLES
		        WHERE   PERSUSUA = USUALOG
				 AND    ROLCODI  = USUAROL
				 AND	USUALOG  = '".$_GET['cod_editar']."'";
				// echo($query_RsEditar);echo("<br>");
	$RsEditar = mysqli_query($conexion,$query_RsEditar) or die(mysqli_error());
	$row_RsEditar = mysqli_fetch_assoc($RsEditar);
    $totalRows_RsEditar = mysqli_num_rows($RsEditar);
	
	
	$identificacion  = $row_RsEditar['ID_PERSONA'];
	$nombre  		 = $row_RsEditar['NOMBRE'];
	$apellido 		 = $row_RsEditar['APELLIDO'];
	$correo   		 = $row_RsEditar['EMAIL'];
	$rol             = $row_RsEditar['ROL_ID'];
	$rol_des 		 = $row_RsEditar['ROL_NOMBRE'];
	$usuario  		 = $row_RsEditar['USUARIO'];

	$tipoGuardar= 'Editar';
	 
	
}else{
    //variables nuevo usuario
	$identificacion     = "";
	$nombre			 	= "";
	$apellido			= "";
	$correo   			= "";
	$rol 				= "";
	$usuario  			= "";
    //variables asignar poa
	$area2              ="";
	$poa2               ="";
	
	$tipoGuardar='Guardar';
}

?>



<!DOCTYPE html>

<html>
<!-- inicio del html -->
<head>

<title>Sanboni-Compras</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="js/jquery.ui.1.8.16.js"></script>
<script type="text/javascript">
// inicio del javascript
//crear usuario
function validarCampos(TG,F){

//validacion de nuevo usuario
 if($("#id_persona").val() == '')
  {
   inlineMsg('id_persona','¿Cual es su Numero de identidad?.',3);
		return false;
  }
 if($("#nombre").val() == '')
  {
   inlineMsg('nombre','¿Cual es el Nombre?',3);
		return false;
  }  
 if($("#apellido").val() == '')
  {
   inlineMsg('apellido','¿Cual es el Apellido?',3);
		return false;
  } 
if($("#email").val() == '')
  {
   inlineMsg('email','Falta el correo para enviarle la correspondencia.',3);
		return false;
  } 
if($("#rol").val() == '')
  {
   inlineMsg('rol','¿Cual es el Rol?',3);
		return false;
  } 
if($("#usuario").val() == '')
  {
   inlineMsg('usuario','¿Que usuario va ha utilizar?',3);
		return false;
  }
if($("#contrasena").val() == '')
  {
   inlineMsg('contrasena','¿Cual es la Contraseña?',3);
		return false;
  }
 if($("#contrasena2").val() == '')
  {
   inlineMsg('contrasena2','Repita la contraseña para evitar errores.',3);
		return false;
  } 
if($("#contrasena").val() != $("#contrasena2").val())
  {
   inlineMsg('contrasena2','las claves ingresadas no coinciden.',3);
		return false;
  } 
  
 //validaciones de asignacion de poa 
if($("#area2").val() == '')
  {
   inlineMsg('area2','¿Que area va ha utilizar?',3);
		return false;
  }
  
  if($("#poa2").val() == '')
  {
   inlineMsg('poa2','¿Que Poa va ha utilizar?',3);
		return false;
  }
  

	
		 if(TG=="Guardar")
		 {		
			if(confirm('Esta seguro de guardar estos datos ?'))
			{
				
			   switch (F) {
							case '1': document.form1.action="administrar_guardar.php?tipoGuardar=Guardar&numForm=1";
				                      document.form1.submit();
									  break;
							case '2':
									document.form2.action="administrar_guardar.php?tipoGuardar=Guardar&numForm=2";
				                    document.form2.submit();
									break;
						  } 
			}
		 }
		 
		  if(TG=="Editar")
		  {  
			if(confirm('Esta seguro de Editar estos datos?'))
			{
				document.form1.action="administrar_guardar.php?tipoGuardar=Editar";
				document.form1.submit(); 
			}
		  }	
	

}
 
  function volveraListado(){
   document.form1.action ="home.php?page=listar_usuarios";
 document.form1.submit();
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
<?php
if($tipo!='a'){?> 
<form name="form1" id="form1" action="" method="post">
	<div class="container">
		<div class="col-md-offset-1 col-lg-offset-1 col-lg-10 col-md-10">
		<div class="row">
		<h4 class="SLAB trtitle text-center">INFORMACIÓN PERSONAL</h4>
		</div>
		<div class="row">
			<div class="col-md-offset-3 col-lg-offset-3  col-lg-6 col-md-6">
				<div class="form-group md-form">
					<label for="id_persona">No. Identificación</label>	  
					<input type="text" placeholder="N° Identificación" name="id_persona" id="id_persona" value="<?php echo($identificacion);?>" size="30" class="form-control">
				</div>				
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 col-lg-6">
				<div class="form-group md-form">
		 			<label for="nombre">Nombre</label> 
		 			<input type="text" name="nombre" onblur="upperCase();" placeholder="Nombre"id="nombre" value="<?php echo($nombre);?>" size="30" class="form-control">
				</div>				
			</div>
			<div class="col-md-6 col-lg-6">
				<div class="form-group md-form">
					<label for="apellido">Apellidos</label>
					<input type="text" name="apellido" id="apellido" onblur="upperCase();"  placeholder="Apellido" value="<?php echo($apellido);?>" size="30" class="form-control">
				</div>				
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 col-lg-6">
			<div class="form-group md-form">
			<label for="rol">Rol</label>
			<select name="rol" id="rol" class="form-control">
	     <option value="">Seleccione Rol</option>
		 <?php 
	
    if($totalRows_RsListaRol >0){
	  do{
	   ?>
	   <option value="<?php echo($row_RsListaRol['CODIGO']);?>" <?php if($row_RsListaRol['CODIGO']==$rol){ echo('selected'); } ?>><?php echo($row_RsListaRol['NOMBRE']);?></option>
	   <?php
	    }while($row_RsListaRol = mysqli_fetch_array($RsListaRol));
	}
		 
		 ?>
	   </select>			
		</div>	  

			</div>
			<div class="col-md-6 col-lg-6">
			<div class="form-group md-form">
		  <label for="area">Area</label>
	   <select name="area" id="area" class="form-control">
	     <option value="">Seleccione Area</option>
		 <?php 
	
    if($totalRows_RsListaArea >0){
	  do{
	   ?>
	   <option value="<?php echo($row_RsListaArea['CODIGO']);?>" <?php if($row_RsListaArea['CODIGO']==$rol){ echo('selected'); } ?>><?php echo($row_RsListaArea['NOMBRE']);?></option>
	   <?php
	    }while($row_RsListaArea = mysqli_fetch_array($RsListaArea));
	}
		 
		 ?>
	   </select>
	  </div>		  
				
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 col-lg-6">
			<div class="form-group md-form">
				<label for="poa">Poa</label>
				<select name="poa" id="poa" class="form-control">
						<option value="">Seleccione Poa</option>
						<?php 
					
					if($totalRows_RsListaPoa >0){
					do{
					?>
					<option value="<?php echo($row_RsListaPoa['CODIGO']);?>" <?php if($row_RsListaPoa['CODIGO']==$rol){ echo('selected'); } ?>><?php echo($row_RsListaPoa['NOMBRE']);?></option>
					<?php
						}while($row_RsListaPoa = mysqli_fetch_array($RsListaPoa));
					}
						
						?>
					</select>				
			</div>
			</div>
		</div>
		<div class="row">
			<h4 class="SLAB trtitle text-center">INFORMACIÓN DE USUARIO</h4>
		</div>
		<div class="row">
			<div class="col-md-6 col-lg-6">
				<div class="form-group md-form">
			  		<label for="usuario">Usuario</label>	  
					  <input type="text" size="40" placeholder="Usuario" name="usuario" value="<?php echo($usuario);?>" id="usuario" class="form-control">
	   			</div>

			</div>
			<div class="col-md-6 col-lg-6">
			<div class="form-group md-form">
					<label for="email">Email</label>
					<input type="email" name="email" placeholder="Email" id="email" value="<?php echo($correo);?>" size="40" class="form-control">
				</div>
			</div>
		</div>
	</div>
	</div>

 <table border="0" class="tableadmin" align="center">
	<?php if($tipoGuardar == 'Guardar'){?>
	 <tr>
	 <td><input type="password" size="40" placeholder="Contraseña" name="contrasena" id="contrasena" class="form-control"></td>
	</tr>
	 <tr>
	 <td><input type="password" size="40" placeholder="Repite la Contraseña" name="contrasena2" id="contrasena2" class="form-control"></td>
	</tr>
	<?php }?>
	<tr><td >
	  <input class="button2" type="button" name="guardarprov" value="<?php echo($tipoGuardar);?>" onclick="validarCampos('<?php echo($tipoGuardar);?>','1');">
<input class="button2" type="button" name="salir" value="salir" onclick="return volveraListado();"></td>
	</tr>
  </table>
 </form>
 
<!--  aqui inicia la pintura de la interfas de asignar poa a a las areas --> 
 
 <?php }else{?> 
  <form name="form2" method="post" action="">
<tr>
	  <td>
	   <select name="area2" id="area2">
	     <option value="">Seleccione Area</option>
		 <?php 
	
    if($totalRows_RsListaArea >0){
	  do{
	   ?>
	   <option value="<?php echo($row_RsListaArea['CODIGO']);?>" <?php if($row_RsListaArea['CODIGO']==$rol){ echo('selected'); } ?>><?php echo($row_RsListaArea['NOMBRE']);?></option>
	   <?php
	    }while($row_RsListaArea = mysqli_fetch_array($RsListaArea));
	}
		 
		 ?>
	   </select>
	  </td>
	  
	  <td >
	  <input class="button2" type="button" name="" value="<?php echo($tipoGuardar);?>" onclick="validarCampos('<?php echo($tipoGuardar);?>','2');">
      <input class="button2" type="button" name="salir" value="salir" onclick="return volveraListado();"></td>
	</tr>
  </form>
 <?php }?>
 
</body>

</html>