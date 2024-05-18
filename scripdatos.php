<?php
require_once('conexion/db.php');

$query_RsInsertLink = "ALTER TABLE  
`PROVEEDOR_LINKS` CHANGE  `PRLILINK` 
 `PRLILINK` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'CODIGO DE INGRESO'

	";
	$RsInsertLink = mysqli_query($conexion,$query_RsInsertLink) or die(mysqli_error($conexion)); 
	if($RsInsertLink ){
	echo"bien bien ";
	}
?>