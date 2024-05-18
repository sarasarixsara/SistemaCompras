<?php
require_once('conexion/db.php');
	if (!isset($_SESSION)) {
  session_start();
}
if(!isset($_SESSION['MM_AccesoCorrectoApp']) || $_SESSION['MM_AccesoCorrectoApp'] != 'ACTIVO'){
 exit('no autorizado');
}

// recuperamos el criterio de la busqueda
$criterio = strtolower($_GET["term"]);
//$criterio = 'ADM';
if (!$criterio) return;

if(isset($_GET['tipo']) && $_GET['tipo'] == 'cargarproductosconveniocotizacion'){
   $query_RsCuentas= " SELECT   P.PROVCODI CODIGO_PROVEEDOR,
								P.PROVNOMB NOMBRE_PROVEEDOR,
								C.COTICODI CODIGO_COTIZACION,
								CD.CODEDETA CODIGO_DETALLE,
								D.DEREDESC DESCRIPCION_DETALLE,
								CD.CODEDESC DESCRIPCION_PROVEEDOR,
								CD.CODEVALO VALOR_DETALLE_COTIZACION,
								D.DERECANT CANTIDAD_DETALLE
							  
								
						FROM proveedores         P, 
							 cotizacion          C,
							 cotizacion_detalle  CD,
							 detalle_requ         D


						WHERE   D.DEREDESC LIKE '%".$criterio."%'
						AND P.PROVCODI='".$_GET['proveedor']."'
						AND C.COTIPROV  = P.PROVCODI
						AND CD.CODECOTI = C.COTICODI
						AND  D.DERECONS = CD.CODEDETA 
						AND  CD.CODEVALO != ''
						#AND  P.PROVCODI=D.DEREPROV
						";
						//echo($query_RsCuentas);
	$RsCuentas = mysqli_query($conexion,$query_RsCuentas) or die(mysql_error());
	$row_RsCuentas = mysqli_fetch_array($RsCuentas);
	$totalRows_RsCuentas = mysqli_num_rows($RsCuentas);
	$productos = array();
	if($totalRows_RsCuentas>0){
		do{
		$productos[] = array('label' => $row_RsCuentas['DESCRIPCION_DETALLE'],
							 'value' => array('nombre'            => $row_RsCuentas['DESCRIPCION_DETALLE'],
							                  'codigo'            => $row_RsCuentas['CODIGO_DETALLE'],
							                  'precio'            => $row_RsCuentas['VALOR_DETALLE_COTIZACION'],
							                  'cantidad'            => $row_RsCuentas['CANTIDAD_DETALLE'],
							                 )
		                     );
		}while($row_RsCuentas = mysqli_fetch_array($RsCuentas));
	}
	echo(json_encode($productos));
	
	
}
if(isset($_GET['tipo']) && $_GET['tipo']=='cargarproductosconvenio'){
   $query_RsCuentas= "SELECT P.PRODCONS  CONSECUTIVO,
                             P.PRODCODI CODIGO_GENERAL,
							 P.PRODCOSI CODIGO_SIGO,
							 P.PRODDESC  NOMBRE,
							 P.PRODIDCAT CATEGORIA,
							 'GENERICO' CATEGORIA_DES,
							 P.PRODPREC PRECIO,
							 P.PRODCANT CANTIDAD,
							 P.PRODSTOK STOCK,
							 P.PRODIDUM UNIDAD_MEDIDA,
							 (select U.UNMESIGL
							   FROM unidad_medida U
							  where U.UNMECONS = P.PRODIDUM) UNIDAD_MEDIDA_DES
							 
						FROM productos P
					    WHERE P.PRODDESC LIKE '%".$criterio."%'
						 AND (SELECT count(CO.CONVCONS)
									     FROM conve_produc C,
										      convenios    CO
									   where  CO.CONVCONS = C.COPRIDCO
									      AND C.COPRIDPC = P.PRODCONS
									      AND  '".date('Y-m-d')."' >= CO.CONVFEIN 
									      AND  '".date('Y-m-d')."' <= CO.CONVFEFI
									   LIMIT 1
								) = 0						
						  
						";
						//echo($query_RsCuentas);
	$RsCuentas = mysqli_query($conexion,$query_RsCuentas) or die(mysql_error());
	$row_RsCuentas = mysqli_fetch_array($RsCuentas);
	$totalRows_RsCuentas = mysqli_num_rows($RsCuentas);
	$productos = array();
	if($totalRows_RsCuentas>0){
		do{
		$productos[] = array('label' => $row_RsCuentas['NOMBRE'],
							 'value' => array('nombre'            => $row_RsCuentas['NOMBRE'],
							                  'codigo'            => $row_RsCuentas['CONSECUTIVO'],
							                  'codigo_general'    => $row_RsCuentas['CODIGO_GENERAL'],
							                  'codigo_sigo'       => $row_RsCuentas['CODIGO_SIGO'],
							                  'categoria'         => $row_RsCuentas['CATEGORIA'],
							                  'categoria_des'     => $row_RsCuentas['CATEGORIA_DES'],
											  'cantidad'          => $row_RsCuentas['CANTIDAD'],
							                  'precio'            => $row_RsCuentas['PRECIO'],
							                  'stock'             => $row_RsCuentas['STOCK'],
							                  'um'                => $row_RsCuentas['UNIDAD_MEDIDA'],
							                  'umdes'             => $row_RsCuentas['UNIDAD_MEDIDA_DES'],
							                 )
		                     );
		}while($row_RsCuentas = mysqli_fetch_array($RsCuentas));
	}
	echo(json_encode($productos));
}
if(isset($_GET['tipo']) && $_GET['tipo']=='cargarpoa'){
   $query_RsCuentas= "SELECT P.POACODI   IDENTIFICACION,
                             P.POANOMB  NOMBRE
						FROM POA P
					    WHERE P.POANOMB LIKE '%".$criterio."%'
						";
	$RsCuentas = mysqli_query($conexion,$query_RsCuentas) or die(mysql_error());
	$row_RsCuentas = mysqli_fetch_array($RsCuentas);
	$totalRows_RsCuentas = mysqli_num_rows($RsCuentas);

?>[<?php
$nombre='';
$identificacion='';
$contador=0;
if($totalRows_RsCuentas>0){
do{
$nombre=$row_RsCuentas['NOMBRE'];
$identificacion=$row_RsCuentas['IDENTIFICACION'];
  if($contador++>0)print ", ";
  print "{ \"label\" : \"$nombre\", \"value\" : { \"nombre\" : \"$nombre\", \"nit\" : $identificacion } }";
  }while($row_RsCuentas = mysqli_fetch_array($RsCuentas));
}
?>]<?php
 }

if(isset($_GET['tipo']) && $_GET['tipo']=='proveedor'){
   $query_RsCuentas= "SELECT P.PROVCODI   IDENTIFICACION,
                             P.PROVNOMB  NOMBRE
						FROM PROVEEDORES P
					WHERE    P.PROVESTA = 0
					    AND  P.PROVNOMB LIKE '%".$criterio."%'
						";
	$RsCuentas = mysqli_query($conexion,$query_RsCuentas) or die(mysql_error());
	$row_RsCuentas = mysqli_fetch_array($RsCuentas);
	$totalRows_RsCuentas = mysqli_num_rows($RsCuentas);

?>[<?php

$nombre='';
$identificacion='';
$contador=0;
if($totalRows_RsCuentas>0){
do{
$nombre=$row_RsCuentas['NOMBRE'];
$identificacion=$row_RsCuentas['IDENTIFICACION'];
  if($contador++>0)print ", ";
  //print "{ \"label\" : \"$nombre\", \"value\" : { \"nombre\" : \"$nombre\", \"identificacion\" : $identificacion } }";
  print "{ \"label\" : \"$nombre\", \"value\" : { \"nombre\" : \"$nombre\", \"nit\" : $identificacion } }";
  }while($row_RsCuentas = mysqli_fetch_array($RsCuentas));
}
?>]<?php
 }
if(isset($_GET['tipo']) && $_GET['tipo']=='requerimiento'){
   $query_RsCuentas= "SELECT P.REQUCODI   IDENTIFICACION,
                             P.REQUCORE  NOMBRE
						FROM REQUERIMIENTOS P
					WHERE    P.REQUCORE LIKE '%".$criterio."%'
						";
	$RsCuentas = mysqli_query($conexion, $query_RsCuentas) or die(mysql_error());
	$row_RsCuentas = mysqli_fetch_array($RsCuentas);
	$totalRows_RsCuentas = mysqli_num_rows($RsCuentas);

?>[<?php

$nombre='';
$identificacion='';
$contador=0;
if($totalRows_RsCuentas>0){
do{
$nombre=$row_RsCuentas['NOMBRE'];
$identificacion=$row_RsCuentas['IDENTIFICACION'];
  if($contador++>0)print ", ";
  //print "{ \"label\" : \"$nombre\", \"value\" : { \"nombre\" : \"$nombre\", \"identificacion\" : $identificacion } }";
  print "{ \"label\" : \"$nombre\", \"value\" : { \"nombre\" : \"$nombre\", \"nit\" : $identificacion } }";
  }while($row_RsCuentas = mysqli_fetch_array($RsCuentas));
}
// lo que haremos es algo extremadamente sencillo, recuerda que este no es el objetivo del demo:
// recorre el arreglo y si encuentras el texto, imprime el elemento.
// cada elemento debe tener la forma:
// { label : "lo que quieras que aparezca escrito", value: { datos del producto... } }
/*$contador = 0;
foreach ($productos as $descripcion => $valor)
{
	if (strpos(strtolower($descripcion), $criterio) !== false)
	{
		if ($contador++ > 0) print ", "; // agregamos esta linea porque cada elemento debe estar separado por una coma
		print "{ \"label\" : \"$descripcion\", \"value\" : { \"descripcion\" : \"$descripcion\", \"precio\" : $valor } }";
	}
} */// siguiente producto
?>]<?php
 }
?>