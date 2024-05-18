<?php 
// verificacion de session
	if (!isset($_SESSION)) 
	{
  		session_start();
	}

// Validacion de seguridad	
	if(!isset($_SESSION['MM_AccesoCorrectoApp']) || $_SESSION['MM_AccesoCorrectoApp'] != 'ACTIVO')
	{
		header("location: index.php");
	}

phpinfo();

?>