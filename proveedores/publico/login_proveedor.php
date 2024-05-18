<?php 
require_once('../../conexion/db.php');

if (!isset($_SESSION)) {
  session_start();
}	
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
	$password = '';
	if(isset($_POST['password']) && $_POST['password'] != ''){
		$password = $_POST['password'];
	}

	if($form_ok == '101'){
	
		  $query_LoginRS = "SELECT 	P.PROVUSUA,
		  				            P.PROVCODI CODIGO_PROVEEDOR,
		  							P.PROVNOMB NOMBRE_USUARIO,
		  							P.PROVREGI REGISTRO,
									U.USUACODI  IDUSUARIO,
									U.USUALOG,
									U.USUAPASS,
									R.ROLCODI ROL,
									R.ROLNOMB NOMBRE_ROL
							FROM 	USUARIOS    U,
									PROVEEDORES P,
									ROLES R,
									PARAMETROS PA
							WHERE 	U.USUALOG = P.PROVUSUA
							AND		R.ROLCODI = U.USUAROL
							AND 	U.USUALOG='".$identificacion."'
							AND     PA.PARACOES = 3
							AND 	USUAPASS=AES_ENCRYPT('".$password."',PA.PARAVALOR)
							AND     U.USUAROL = 7
							AND     P.PROVESTA = 0
							AND    U.USUAESTA = 0
							";
							//AND 	USUAPASS=AES_ENCRYPT('".$password."','".$clave."')
							echo($query_LoginRS);
		  $LoginRS = mysqli_query($conexion,$query_LoginRS) or die(mysqli_connect_error());
		  $row_LoginRS = mysqli_fetch_array($LoginRS);
		  $loginFoundUser = mysqli_num_rows($LoginRS);
		  if($loginFoundUser > 0){
				$_SESSION['MM_ProveedorID'] = $row_LoginRS['CODIGO_PROVEEDOR'];
				$_SESSION['MM_UserID'] = $row_LoginRS['REGISTRO'];
				$_SESSION['MM_IDUsuario'] = $row_LoginRS['IDUSUARIO'];
				$_SESSION['MM_RolID'] = $row_LoginRS['ROL'];
				$_SESSION['MM_Rolnombre'] = $row_LoginRS['NOMBRE_ROL'];
			    $_SESSION['MM_Username'] = $loginUsername;
				$_SESSION['MM_Usernombre'] = $row_LoginRS['NOMBRE_USUARIO'];
				$_SESSION['MM_AccesoCorrectoApp'] = 'ACTIVO';

				$query_RsRegistroLogin = "UPDATE USUARIOS SET USUAULTACC = SYSDATE() WHERE USUALOG = '".$loginUsername."'";
				$RsRegistroLogin = mysqli_query($conexion,$query_RsRegistroLogin) or die(mysqli_connect_error());

				header("Location: ../../crear_proveedor.php"  );							

		  }else{
				header("Location: proveedorpublico.php?acceso=no"  );									  	
		  }
	}

