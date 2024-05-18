<?php
require_once('conexion/db.php');
	if (!isset($_SESSION)) {
  session_start();
}
//Variable Crear Usuarios
$id_persona='';
if(isset($_POST['id_persona'])&&$_POST['id_persona']!='')
{
$id_persona=$_POST['id_persona'];
}

$nombre='';
if(isset($_POST['nombre'])&&$_POST['nombre']!='')
{
$nombre=$_POST['nombre'];
}

$apellido='';
if(isset($_POST['apellido'])&&$_POST['apellido']!='')
{
$apellido=$_POST['apellido'];
}

$email='';
if(isset($_POST['email'])&&$_POST['email']!='')
{
$email=$_POST['email'];
}

$area='';
if(isset($_POST['area'])&&$_POST['area']!='')
{
$area=$_POST['area'];
}

$rol='';
if(isset($_POST['rol'])&&$_POST['rol']!='')
{
$rol=$_POST['rol'];
}

$contrasena2='';
if(isset($_POST['contrasena2'])&&$_POST['contrasena2']!='')
{
$contrasena2=$_POST['contrasena2'];
}

$usuario='';
if(isset($_POST['usuario'])&&$_POST['usuario']!='')
{
$usuario=$_POST['usuario'];
}

//variables de asignacion de poa
$area2='';
if(isset($_POST['area2'])&&$_POST['area2']!='')
{
$area2=$_POST['area2'];
}


if(isset($_POST['poa'])&&$_POST['poa']!='')
{
$poa=$_POST['poa'];
}else if($poa='')
{
//usuario administrador sin poa

}

//tipo de manejo de datos
$tipoGuardar='';
if(isset($_GET['tipoGuardar']) && $_GET['tipoGuardar']!=''){
$tipoGuardar=$_GET['tipoGuardar'];
}

//formularios
$formulario='';
if(isset($_GET['numForm']) && $_GET['numForm']!=''){
$formulario=$_GET['numForm'];
}

if($tipoGuardar=='Guardar'){

if($formulario=='1'){
//Se realizara la insersion de los datos de persona
$query_RsPersona_Nueva="INSERT INTO  PERSONAS (
								`PERSID` ,
								`PERSNOMB` ,
								`PERSAPEL` ,
								`PERSDIRE` ,
								`PERSTELE` ,
								`PERSUSUA` ,
								`PERSREGI` ,
								`PERSTARI` ,
								`PERSCIUD` ,
								`PERSCINO` ,
								`PERSCORR` ,
								`PERSFIRMA` ,
								`PERSSUIS` ,
								`PERSENCO` ,
								`PERSEST`
								)
								VALUES (
								'".$id_persona."',
								'".$nombre."',
								'".$apellido."',
								'',
								'',
								'".$usuario."',
								'0',
								'0',
								'',
								'',
								'".$email."',
								'blanco.JPG',
								'',
								'0',
								'0'
								)";
    $RsPersona_Nueva = mysqli_query($conexion,$query_RsPersona_Nueva) or die(mysqli_error($conexion));

//Se Ingresara el registro del Usuario correspondiente a la persona anterior 

		$query_RsUsuario_Nuevo ="INSERT INTO  `USUARIOS` (
											`USUALOG` ,
											 USUAPASS,
											`USUAPASPO`,
											`USUAROL` ,
											`USUAPASSBK` ,
											`USUAPASPOBK`
											)
											VALUES (
											'".$usuario."',
											AES_ENCRYPT( '".$contrasena2."', 'mc$90ui1' ),
											'',
											'".$rol."',
											'".$contrasena2."',
											'".$contrasena2."'
											)";
    $RsUsuario_Nuevo = mysqli_query($conexion,$query_RsUsuario_Nuevo) or die(mysqli_error($conexion));

if($area != '')	{
//buscara el ultimo usuario creado para asignarle el area

$query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
				$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
				$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);
				$usuario_creado = $row_RsUltInsert['DATO'];
				
				 
//se realizara el registro del area asignada al usuario				
$query_RsAreaAsignada ="INSERT INTO AREA_POA(
											ARPOID ,
											ARPOIDAR ,
                                            ARPOIDPO ,           											
											ARPOIDUS
											)
											VALUES (
											NULL ,
											'".$area."',
											'".$poa."',
											'".$usuario_creado."'
											)";
    echo($query_RsAreaAsignada);
	$RsAreaAsignada = mysqli_query($conexion,$query_RsAreaAsignada) or die(mysqli_error($conexion));
}

// Realiza notificacion por correo electronico
	$infomacion_codi=$usuario;
	//echo($infomacion_codi);
	include("correo_new_usuario.php");
	
//se direccionara de nuevo al listado de usuarios	
	$redireccionar = "location: home.php?page=listar_usuarios";
    header($redireccionar);
}
if($formulario=='2'){
//INSERT INTO `compras`.`area_poa` (`ARPOID`, `ARPOIDAR`, `ARPOIDPO`, `ARPOIDUS`) VALUES (NULL, '2', '22', '2');

}	

}


if($tipoGuardar=='Editar'){

$query_RsEditar="UPDATE PERSONAS 
								SET `PERSCORR` = '".$email."'
							  WHERE PERSID = '".$id_persona."'";
$RsEditar = mysqli_query($conexion,$query_RsEditar) or die(mysqli_error());
           			//exit($query_RsEditar);
					
$query_RsEditarArea="UPDATE PERSONAS 
								SET `PERSCORR` = '".$email."'
							  WHERE PERSID = '".$id_persona."'";
$RsEditarArea = mysqli_query($conexion,$query_RsEditarArea) or die(mysqli_error());
           			//exit($query_RsEditar);
   
?>
<body>
<script type="text/javascript">
window.location="home.php?page=listar_usuarios";
</script>
</body>
<?php
}

if($tipoGuardar=='Eliminar'){
$query_RsEliminar= "DELETE FROM`USUARIOS`
                     WHERE USUALOG = '".$_GET['cod_eliminar']."'   ";
						//echo($query_RsEliminar);
	$RsEliminar = mysqli_query($conexion,$query_RsEliminar) or die(mysqli_error());

	$query_RsEliminar2= "DELETE FROM area_poa 
	                 WHERE ARPOID= '".$_GET['cod_usuario']."'   ";
						//echo($query_RsEliminar2);
	$RsEliminar2 = mysqli_query($conexion,$query_RsEliminar2) or die(mysqli_error());
	
	$query_RsEliminar3= "DELETE FROM `personas` 
					 WHERE `PERSUSUA`= '".$_GET['cod_eliminar']."'   ";
						//echo($query_RsEliminar3);
	$RsEliminar3 = mysqli_query($conexion,$query_RsEliminar3) or die(mysqli_error());
	
   ?>
<body>
<script type="text/javascript">
window.location="home.php?page=listar_usuarios";
</script>
</body>
<?php
}


?>