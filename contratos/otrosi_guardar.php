 <?php
include('../conexion/db.php');

if (!isset($_SESSION)) {
  session_start();
}

//Llegada de variables

$otrosi_numero  	  	 ='';if(isset($_POST['otrosi_numero'])&&$_POST['otrosi_numero']!=''){$otrosi_numero=$_POST['otrosi_numero'];}
$otrosi_clase    		 ='';if(isset($_POST['otrosi_clase'])&&$_POST['otrosi_clase']!=''){$otrosi_clase=$_POST['otrosi_clase'];}
$otrosi_objeto   		 ='';if(isset($_POST['otrosi_objeto'])&&$_POST['otrosi_objeto']!=''){$otrosi_objeto=$_POST['otrosi_objeto'];}
$otrosi_contratista  	 ='';if(isset($_POST['otrosi_contratista'])&&$_POST['otrosi_contratista']!=''){$otrosi_contratista=$_POST['otrosi_contratista'];}
$otrosi_fecha  	     	 ='';if(isset($_POST['otrosi_fecha'])&&$_POST['otrosi_fecha']!=''){$otrosi_fecha=$_POST['otrosi_fecha'];}
$otrosi_nhoras 	     	 ='';if(isset($_POST['otrosi_nhoras'])&&$_POST['otrosi_nhoras']!=''){$otrosi_nhoras=$_POST['otrosi_nhoras'];}
$otrosi_fpago 	     	 ='';if(isset($_POST['otrosi_fpago'])&&$_POST['otrosi_fpago']!=''){$otrosi_fpago=$_POST['otrosi_fpago'];}
$otrosi_valor 	     	 ='';if(isset($_POST['otrosi_valor'])&&$_POST['otrosi_valor']!=''){$otrosi_valor=$_POST['otrosi_valor'];}
$contrato_codigo		 ='';if(isset($_POST['contrato_codigo'])&&$_POST['contrato_codigo']!=''){$contrato_codigo=$_POST['contrato_codigo'];}

//Dinamica de Insertar en base de datos

$tipoGuardar='';if(isset($_GET['tipoGuardar_otrosi'])&&$_GET['tipoGuardar_otrosi']!=''){$tipoGuardar=$_GET['tipoGuardar_otrosi'];}


// Procedimientos de Manipulacion de Datos 

if ($tipoGuardar == 'Guardar')
	{	
		$query_RsCrearInsercion="INSERT INTO CONTRATOS_OTROSI	(	COOTID,
																	COOTNUME,
																	COOTCLAS,
																	COOTOBJE,
																	COOTCOID,
																	COOTCONO,																	
																	COOTFEFI,
																	COOTNHOR,
																	COOTFOPA,
																	COOTVACU,
																	COOTIDCO
																) 
														VALUES  (	NULL,
																	'".$otrosi_numero."',
																	'".$otrosi_clase."',
																	'".$otrosi_objeto."',
																	'".$otrosi_contratista."',
																	'".$otrosi_contratista."',
																	str_to_date('".$otrosi_fecha."','%d/%m/%Y'),																    
																	'".$otrosi_nhoras."',
																	'".$otrosi_fpago."',
																	'".$otrosi_valor."',
																	'".$contrato_codigo."'
																)";	
           //echo($query_RsCrearInsercion);																
		$RsCrearInsercion = mysqli_query($conexion,$query_RsCrearInsercion) or die(mysqli_error($conexion));	
	
		//se direccionara de nuevo al listado de usuarios	
		$redireccionar = "Location: ../home.php?page=contratos/contratos_crear&tipoGuardar=Editar&cod=".$contrato_codigo;	
		header($redireccionar); 
	}

if ($tipoGuardar == 'Editar')
{
$query_RsEditar="UPDATE CONTRATOS_OTROSI 
								 SET 	
										`COOTNUME`='".$otrosi_numero."',
										`COOTCLAS`='".$otrosi_clase."',
										`COOTOBJE`='".$otrosi_objeto."',
										`COOTCOID`='".$otrosi_contratista."',										
										`COOTFEIN`=str_to_date('".$otrosi_fecha."','%d/%m/%Y'),										
										`COOTNHOR`='".$otrosi_nhoras."',
										`COOTFOPA`='".$otrosi_fpago."',
										`COOTVACU`='".$otrosi_valor."'
										WHERE `COOTID`='".$_POST['otrosi_codigo']."'
										
										";
										//echo($query_RsEditar);
$RsEditar = mysqli_query($conexion,$query_RsEditar) or die(mysqli_error($conexion));
           							

//se direccionara de nuevo al listado de usuarios	
	$redireccionar = "location: ../home.php?page=contratos/contratos_crear&tipoGuardar=Editar&cod=".$contrato_codigo;
    header($redireccionar); 

}

if ($tipoGuardar == 'cargar_otrosi')
{
	$query_RsListaOtrosi="SELECT 	 COOTID ID,
									 COOTNUME NUMERO,
									 COOTCLAS CLASE_OTROSI,
									 COOTOBJE OBJETO,
									 COOTCOID ID_CONTRATISTA,
									 COOTCONO PROVEEDOR,								 
									 date_format(COOTFEFI,'%d/%m/%Y') FECHA_FIN_OTROSI,
									 COOTNHOR N_HORAS,
									 COOTFOPA FORMA_PAGO_OTROSI,
									 COOTVACU VALOR_CUANTIA_OTROSI								  
							   FROM  CONTRATOS_OTROSI 
							   WHERE COOTID='".$_GET['id']."'";
					  //echo($query_RsListaOtrosi);
		$RsListaOtrosi = mysqli_query($conexion,$query_RsListaOtrosi) or die(mysqli_error($conexion));
		$row_RsListaOtrosi = mysqli_fetch_assoc($RsListaOtrosi);
		$totalRows_RsListaOtrosi = mysqli_num_rows($RsListaOtrosi);
		
		$response=array();
		$data=array();	
		$status = 'failed';
		
		if($totalRows_RsListaOtrosi > 0){
			$status = 'ok';
			$data=$row_RsListaOtrosi;			
		}

	  $response = array(
						"status"   => $status,
						"data"     => $data				
	  );
	  echo(json_encode($response));	

	
}



if ($tipoGuardar == 'Archivo_Cargar')
{
 	$rutaArchivos = 'C:/wamp64/www/SistemaCompras/contratos/bodega/';
	

	if (is_uploaded_file($_FILES['archivo1']['tmp_name']))
	{
		$upload_archivo_dir = $rutaArchivos;
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

	$redireccionar = "location: ../home.php?page=contratos/contratos_crear&tipoGuardar=Editar&cod=".$contrato_codigo;
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