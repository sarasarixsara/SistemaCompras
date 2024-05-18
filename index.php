<?php

//conexión a base de datos 
 require_once('conexion/db.php');

//llamado al inicio de sesiiones
if (!isset($_SESSION)) {
  session_start();
}

//validación de la session
if (!isset($_SESSION['MM_UsernameIndex'])) {
  //header("location: index.php");
}

$loginFormAction = $_SERVER['PHP_SELF'];

if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['usuario'])) {

  $clave="";
  $loginUsername=$_POST['usuario'];
  $password=$_POST['contrasena'];
  $ruta='home.php';

  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = $ruta;
  $MM_redirectLoginFailed = "index.php?acceso=no";
  $MM_redirecttoReferrer = false;

  $query_LoginRS = "SELECT 	P.PERSID,
  							P.PERSNOMB NOMBRE_USUARIO,
							U.USUACODI  IDUSUARIO,
							U.USUALOG,
							U.USUAPASS,
							R.ROLCODI ROL,
							R.ROLNOMB NOMBRE_ROL,
							(select A.ARPOIDAR 
							  FROM AREA_POA A
							 WHERE A.ARPOIDUS = U.USUACODI
							  ) AREA,
							(SELECT A.AREANOMB
							  FROM AREA A 
							 WHERE A.AREAID = (select A2.ARPOIDAR 
							  FROM AREA_POA A2
							  WHERE A2.ARPOIDUS = U.USUACODI) LIMIT 1) AREA_DES,
							  P.PERSPERE PERMISO_ESPECIAL
					FROM 	USUARIOS U,
							PERSONAS P,
							ROLES R,
							PARAMETROS PA
					WHERE 	U.USUALOG = P.PERSUSUA
					AND		R.ROLCODI = U.USUAROL
					AND 	U.USUALOG='".$loginUsername."'
					AND     PA.PARACOES = 3
					AND 	USUAPASS=AES_ENCRYPT('".$password."',PA.PARAVALOR)
					AND     P.PERSEST = 0
					AND     U.USUAESTA = 0
					";
					//AND 	USUAPASS=AES_ENCRYPT('".$password."','".$clave."')
  $LoginRS = mysqli_query($conexion,$query_LoginRS) or die(mysqli_connect_error());
  $row_LoginRS = mysqli_fetch_array($LoginRS);
  $loginFoundUser = mysqli_num_rows($LoginRS);
  
  if ($loginFoundUser>0) {
     $loginStrGroup = "";
    $_SESSION['MM_FechaActiva'] = 0;
    $query_RsFechaHabilitada = "SELECT F.FERECODI
									FROM fechas_req F
									where F.FEREESTA = 0
									 AND sysdate() >= F.FEREFEDE
									 AND SYSDATE() <= F.FEREFEHA
									 LIMIT 1 ";
	$RsFechaHabilitada = mysqli_query($conexion,$query_RsFechaHabilitada) or die(mysqli_connect_error());
	$row_RsFechaHabilitada = mysqli_fetch_assoc($RsFechaHabilitada);
	$totalRows_RsFechaHabilitada = mysqli_num_rows($RsFechaHabilitada);
	if($totalRows_RsFechaHabilitada > 0){
		if($row_RsFechaHabilitada['FERECODI']>0){
			$_SESSION['MM_FechaActiva'] = 1;
		}
	}

  	$_SESSION['MM_PermisoEspecial'] = $row_LoginRS['PERMISO_ESPECIAL'];
	$_SESSION['MM_UserID'] = $row_LoginRS['PERSID'];
	$_SESSION['MM_IDUsuario'] = $row_LoginRS['IDUSUARIO'];
	$_SESSION['MM_RolID'] = $row_LoginRS['ROL'];
	$_SESSION['MM_Rolnombre'] = $row_LoginRS['NOMBRE_ROL'];
    $_SESSION['MM_Username'] = $loginUsername;
	$_SESSION['MM_Usernombre'] = $row_LoginRS['NOMBRE_USUARIO'];
	$_SESSION['MM_AccesoCorrectoApp'] = 'ACTIVO';
	$_SESSION['MM_Area'] = $row_LoginRS['AREA'];
	$_SESSION['MM_Areades'] = $row_LoginRS['AREA_DES'];

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];
    }

	$query_RsRegistroLogin = "UPDATE USUARIOS SET USUAULTACC = SYSDATE() WHERE USUALOG = '".$loginUsername."'";
	$RsRegistroLogin = mysqli_query($conexion,$query_RsRegistroLogin) or die(mysqli_connect_error());

    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}

if (isset($_GET['acceso']))
{
?>
	<script language="javascript">
		alert("Usuario o contrase\u00f1a incorrectos.\nPor favor intente de nuevo.");
		window.history.back();
	</script>
<?php
}
?>

<script language="javascript">
	function validar()
	{
		if (document.form1.usuario.value == "")
		{
			alert("Por favor escriba su nombre de usuario.");
			return false;
		}
		if (document.form1.contrasena.value == "")
		{
			alert("Por favor escriba su contrase\u00f1a.");
			return false;
		}
	}

</script>

<!DOCTYPE html>

<html>
<!-- inicio del html -->
<head>
	<title>	Compras	</title>
 	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
 	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="css/estilo_login.css" />
</head>

<body>
	 <div id="container">
			<form action="<?php echo $loginFormAction; ?>" method="post" name="form1" id="form1">
				<div class="login"></div>
					<div class="username-text">Usuario:</div>
					<div class="password-text">Contraseña:</div>
					<div class="username-field">
						<input type="text" name="usuario" value="" />
					</div>
					<div class="password-field">
						<input type="password" name="contrasena" value="" />
					</div>
					<div style="clear:both;">
					  <input type="submit" name="submit" value="Ingresar" onClick="return validar();"/>
					 </div>
			</form>
	</div>
</body>

</html>
