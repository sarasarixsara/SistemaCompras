<?php
require_once('conexion/db.php');
	if (!isset($_SESSION)) {
  session_start();
}
//Variable Crear Usuarios
$nueva_clave='';
if(isset($_POST['nueva_clave'])&&$_POST['nueva_clave']!='')
{
$nueva_clave=$_POST['nueva_clave'];
}

$nueva_clave_confirm='';
if(isset($_POST['nueva_clave_confirm'])&&$_POST['nueva_clave_confirm']!='')
{
$nueva_clave_confirm=$_POST['nueva_clave_confirm'];
}

//tipo de manejo de datos
$tipoGuardar='';
if(isset($_GET['tipoGuardar']) && $_GET['tipoGuardar']!=''){
$tipoGuardar=$_GET['tipoGuardar'];
}
$idusuario = $_SESSION['MM_IDUsuario'];
//$idusuario =2342342342;

$afectado = 0;
if($tipoGuardar=='Actualizar'){

	if($idusuario!= '' && ($nueva_clave == $nueva_clave_confirm) && $nueva_clave!=''){
		$query_RsEditar="UPDATE USUARIOS 
										SET `USUAPASS` = AES_ENCRYPT( '".$nueva_clave."', 'mc$90ui1' ),
										    USUAPASSBK = '".$nueva_clave."',
										    USUAPASPOBK = '".$nueva_clave."'
									  WHERE USUACODI   = '".$idusuario."'";
		$RsEditar = mysqli_query($conexion,$query_RsEditar) or die(mysqli_error());
		$afectado = mysqli_affected_rows($conexion);
	}

           			//exit($query_RsEditar);
 if($afectado == 1){ 
?>
<body>
<script type="text/javascript">
window.location="home.php?page=cambiar_password&msg=1";
</script>
</body>
<?php
 }
 if($afectado == 0){
	 ?>
<body>
<script type="text/javascript">
window.location="home.php?page=cambiar_password&msg=2";
</script>
</body>	 
	 <?php
 }
}
?>