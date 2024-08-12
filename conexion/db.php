<?php

// Variables de conexion a base de datos
$hostname = "localhost";
$database = "bdcompras";
$username = "root";
$password = "";

//S4nv0n1f4c10 prueba

// Ejecución de la conexion a base de datos
$conexion = mysqli_connect($hostname, $username,$password,$database); 

$conexion->set_charset("utf8"); 

// Prueba de conexión 
//if($conexion){echo "conexion exitosa";}else {echo"no conecto";};

?>
