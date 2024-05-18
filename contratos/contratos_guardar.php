 <?php
include('../conexion/db.php');

if (!isset($_SESSION)) {
  session_start();
}

//Llegada de variables

$contrato_codigo		='';if(isset($_POST['contrato_codigo'])&&$_POST['contrato_codigo']!='')	{$contrato_codigo=$_POST['contrato_codigo'];}
$contrato_numero  		='';if(isset($_POST['contrato_numero'])&&$_POST['contrato_numero']!=''){$contrato_numero=$_POST['contrato_numero'];}
$contrato_objeto		='';if(isset($_POST['contrato_objeto'])&&$_POST['contrato_objeto']!=''){$contrato_objeto=$_POST['contrato_objeto'];}
$contrato_clase			='';if(isset($_POST['contrato_clase'])&&$_POST['contrato_clase']!=''){$contrato_clase=$_POST['contrato_clase'];}
$contrato_contratista	='';if(isset($_POST['contrato_contratista'])&&$_POST['contrato_contratista']!=''){$contrato_contratista=$_POST['contrato_contratista'];}
$fecha_inicio			='';if(isset($_POST['fecha_inicio'])&&$_POST['fecha_inicio']!=''){$fecha_inicio=$_POST['fecha_inicio'];}
$fecha_fin				='';if(isset($_POST['fecha_fin'])&&$_POST['fecha_fin']!=''){$fecha_fin=$_POST['fecha_fin'];}
$contrato_nhoras		='';if(isset($_POST['contrato_nhoras'])&&$_POST['contrato_nhoras']!=''){$contrato_nhoras=$_POST['contrato_nhoras'];}
$contrato_fpago			='';if(isset($_POST['contrato_fpago'])&&$_POST['contrato_fpago']!=''){$contrato_fpago=$_POST['contrato_fpago'];}
$contrato_valor			='';if(isset($_POST['contrato_valor'])&&$_POST['contrato_valor']!=''){$contrato_valor=$_POST['contrato_valor'];}
$descripcion_archivo    ='';if(isset($_POST['descripcion_archivo'])&&$_POST['descripcion_archivo']!=''){$descripcion_archivo=$_POST['descripcion_archivo'];}
//Dinamica de Insertar en base de datos

$tipoGuardar='';if(isset($_GET['tipoGuardar'])&&$_GET['tipoGuardar']!=''){$tipoGuardar=$_GET['tipoGuardar'];}


// Procedimientos de Manipulacion de Datos 

if ($tipoGuardar == 'Guardar'){	

    $query_RsCrearInsercion="INSERT INTO CONTRATOS (`CONTID`,	
													`CONTNUME`,
													`CONTCLAS`,
													`CONTOBJE`,
													`CONTCOID`,													
													`CONTFEIN`,
													`CONTFEFI`,
													`CONTFETR`,
													`CONTNHOR`,
													`CONTFOPA`,
													`CONTVACU`
													) 
													VALUES 
														(
													NULL,													
													'".$contrato_numero."',											
													'".$contrato_clase."',
													'".$contrato_objeto."',
													'".$contrato_contratista."',													
													str_to_date('".$fecha_inicio."','%d/%m/%Y'),   
													str_to_date('".$fecha_fin."','%d/%m/%Y'),
													NULL,
													'".$contrato_nhoras."',
													'".$contrato_fpago."',
													'".$contrato_valor."'
													)";
 	$RsCrearInsercion = mysqli_query($conexion,$query_RsCrearInsercion) or die(mysqli_error($conexion));	
	
	$nombre_carpeta=$contrato_numero;
	$ruta = 'C:/wamp64/www/SistemaCompras/contratos/bodega/';
	$carpeta=$ruta.$nombre_carpeta;
	if (!file_exists($carpeta)) {
		mkdir($carpeta, 0777, true);
	}
	
	//se direccionara de nuevo al listado de usuarios	
	$redireccionar = "Location: /home.php?page=contratos/contratos_listar";
	header($redireccionar); 
}

if ($tipoGuardar == 'Editar')
{
$query_RsEditar="UPDATE CONTRATOS 
								 SET 	
										`CONTNUME`='".$contrato_numero."',
										`CONTCLAS`='".$contrato_clase."',
										`CONTOBJE`='".$contrato_objeto."',
										`CONTCOID`='".$contrato_contratista."',										
										`CONTFEIN`='".$fecha_inicio."',
										`CONTFEFI`='".$fecha_fin."',
										#`CONTFETR`='',
										`CONTNHOR`='".$contrato_nhoras."',
										`CONTFOPA`='".$contrato_fpago."',
										`CONTVACU`='".$contrato_valor."'
										WHERE `CONTID`='".$contrato_codigo."'
										
										";
										echo($query_RsEditar);
$RsEditar = mysqli_query($conexion,$query_RsEditar) or die(mysqli_error($conexion));
           							

//se direccionara de nuevo al listado de usuarios	
	$redireccionar = "Location: /home.php?page=contratos/contratos_listar";
    header($redireccionar); 

}



if ($tipoGuardar == 'Archivo_Cargar')
{
 	$rutaArchivos = 'C:/inetpub/wwwroot/SistemaCompras/contratos/bodega/';
$carpeta=$_GET['carpeta']."/";

	if (is_uploaded_file($_FILES['archivo1']['tmp_name']))
	{
		$upload_archivo_dir = $rutaArchivos.$carpeta;
		$nombre_archivo = str_replace("Ñ", "N",$_FILES['archivo1']['name']);
		$nombre_archivo = str_replace("ñ", "n",$nombre_archivo);
		$ext=date("Ymd_His");
		$nombre_archivo = $ext."-".$nombre_archivo;
		$tipo_archivo = $_FILES['archivo1']['type'];

		if ( move_uploaded_file($_FILES['archivo1']['tmp_name'],$upload_archivo_dir.$nombre_archivo) )
		{
			$query_RsInsertarAnexos="INSERT INTO `CONTRATOS_ANEXOS` (
																	`COANCODI`,	
																	`COANCONT`,
																	`COANARCH`,
																	`COANFECH`,
																	`COANDESC`
																	) 
																	VALUES 
																	(NULL,
																	'".$contrato_codigo."',
																	'".$nombre_archivo."',
																	sysdate(),
																	'".$descripcion_archivo."'
																	)";															
		    $RsInsertarAnexos = mysqli_query($conexion,$query_RsInsertarAnexos) or die(mysqli_error($conexion));
		}
	}

	$redireccionar = "location:/home.php?page=contratos/contratos_crear&tipoGuardar=Editar&cod=".$contrato_codigo;
	header($redireccionar);
}


if($tipoGuardar=='EliminarAnexo'){
	
 if(isset($_GET['codanexo'])  && $_GET['codanexo']!=''){
	 $query_RsParametroRuta = "SELECT PARAVALOR,
                                      (SELECT A.COANARCH
									    FROM CONTRATOS_ANEXOS A
									   WHERE A.COANCODI = '".$_GET['codanexo']."'
									   ) ARCHIVO
	                              FROM PARAMETROS 
							   WHERE PARANOMB = 'RUTAARCHIVO_NAS'";
	 $RsParametroRuta = mysqli_query($conexion, $query_RsParametroRuta) or die(mysqli_error($conexion));
	 $row_RsParametroRuta = mysqli_fetch_assoc($RsParametroRuta);	
	 
	$query_RsEliminarAnexo = "DELETE FROM CONTRATOS_ANEXOS WHERE COANCODI = '".$_GET['codanexo']."'";
	$RsEliminarAnexo = mysqli_query($conexion,$query_RsEliminarAnexo) or die(mysqli_error($conexion));

     $carpeta = 'contratos/bodega/';
     $rutaArchivos = $row_RsParametroRuta['PARAVALOR'].$carpeta;	 
     $archivo = $row_RsParametroRuta['ARCHIVO'];	 
   // echo('archivo eliminado '. $rutaArchivos.$archivo);
	unlink($rutaArchivos.$archivo);	
	
	$redireccionar = "location: ../home.php?page=contratos/contratos_crear&tipoGuardar=Editar&cod=".$contrato_codigo;
	
	header($redireccionar);
 }
}




//Seccion de condiciones de manipulacion de datos de detalle


?>