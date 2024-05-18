<?php 
require_once('conexion/db.php');

//Llegada de variables

$nit			='';if(isset($_POST['nit'			])&&$_POST['nit'		]!=''){$nit			=$_POST['nit'			];}
$proveedor		='';if(isset($_POST['proveedor'		])&&$_POST['proveedor'	]!=''){$proveedor	=$_POST['proveedor'		];}
$contrato		='';if(isset($_POST['contrato'		])&&$_POST['contrato'	]!=''){$contrato	=$_POST['contrato'		];}
$fecha_inicio		='';if(isset($_POST['fecha_inicio'		])&&$_POST['fecha_inicio'	]!=''){$fecha_inicio	=$_POST['fecha_inicio'		];}
$fecha_fin		='';if(isset($_POST['fecha_fin'		])&&$_POST['fecha_fin'	]!=''){$fecha_fin	=$_POST['fecha_fin'		];}
$c_conv_des		='';if(isset($_POST['c_conv_des'	])&&$_POST['c_conv_des'	]!=''){$c_conv_des	=$_POST['c_conv_des'	];}
$contrato		='';if(isset($_POST['contrato'		])&&$_POST['contrato'	]!=''){$contrato	=$_POST['contrato'		];}

//Dinamica de Insertar en base de datos

$tipoGuardar='';if(isset($_GET['tipoGuardar'])&&$_GET['tipoGuardar']!=''){$tipoGuardar=$_GET['tipoGuardar'];}


// Procedimientos de Manipulacion de Datos 

if ($tipoGuardar == 'Guardar')
{
	
   $query_RsInsertar="INSERT INTO `convenios` (`CONVCONS`,
														 `CONVIDPR`,
														 `CONVCOCO`,
														 `CONVCONT`, 
														 `CONVFEIN`, 
														 `CONVFEFI`, 
														 `CONVID`
														 ) 
														 VALUES 
														 (
														 NULL,
														 '".$nit."',
														 '".$c_conv_des."',
														 '".$contrato."',
														 STR_TO_DATE('".$fecha_inicio."','%d/%m/%Y'),
														 STR_TO_DATE('".$fecha_fin."','%d/%m/%Y'),
														 '1'
														 )";
														 
  	$RsInsertar = mysqli_query($conexion,$query_RsInsertar) or die(mysqli_error($conexion));
     /*      			//exit($query_RsCrearRequerimiento);
			$query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
				$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
				$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);
				$proveedor_creado = $row_RsUltInsert['DATO'];
				
   $query_RsInsertarCategoria ="INSERT INTO PROVEEDOR_CLASIFICACION (
																	 PRCLCODI,
																	 PRCLPROV,
																	 PRCLCLAS
																	 )
																	 VALUES
																	 (
																	  NULL,
																	  '".$nit."',
																	  '".$categoria_prov."'
																	 )";					
   $RsInsertarCategoria = mysqli_query($conexion,$query_RsInsertarCategoria) or die(mysqli_error($conexion));					
  */

//echo(mysqli_affected_rows($conexion));	

	$query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
	$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
	$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);
	$convenio_creado = $row_RsUltInsert['DATO'];
	
	header("location:home.php?page=convenio&c=".$nit."&convenio=".$convenio_creado);
}

if($tipoGuardar=='prueba'){

	
	
	$query_Rsvalidar_cantidad = "SELECT * FROM `proveedores` where PROVCODI='800242106-2'";
    $Rsvalidar_cantidad = mysqli_query($conexion, $query_Rsvalidar_cantidad) or die(mysqli_error($conexion));
	$row_Rsvalidar_cantidad = mysqli_fetch_array($Rsvalidar_cantidad);
    $totalRows_Rsvalidar_cantidad = mysqli_num_rows($Rsvalidar_cantidad);
	
	if((2+2)==4){
	echo('1');
	}else{
	echo('0');
	}
	
	}

?>