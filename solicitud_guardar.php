<?php
require_once('conexion/db.php');

	if (!isset($_SESSION)) {
  session_start();
}
//no seleccionados

$descrip_ns='';
if(isset($_POST['descrip_ns'])&&$_POST['descrip_ns']!='')
{
$descrip_ns=$_POST['descrip_ns'];
}

$cantidad_ns='';
if(isset($_POST['cantidad_ns'])&&$_POST['cantidad_ns']!='')
{
$cantidad_ns=$_POST['cantidad_ns'];
}

$unidad_ns='';
if(isset($_POST['unidad_ns'])&&$_POST['unidad_ns']!='')
{
$unidad_ns=$_POST['unidad_ns'];
}

$justi_ns='';
if(isset($_POST['justi_ns'])&&$_POST['justi_ns']!='')
{
$justi_ns=$_POST['justi_ns'];
}

$observ_ns='';
if(isset($_POST['observ_ns'])&&$_POST['observ_ns']!='')
{
$observ_ns=$_POST['observ_ns'];
}

//si seleccionados

$clasif_ss='';
if(isset($_POST['clasif_ss'])&&$_POST['clasif_ss']!='')
{
$clasif_ss=$_POST['clasif_ss'];
}

$cantidad_ss='';
if(isset($_POST['cantidad_ss'])&&$_POST['cantidad_ss']!='')
{
$cantidad_ss=$_POST['cantidad_ss'];
}

$justi_ss='';
if(isset($_POST['justi_ss'])&&$_POST['justi_ss']!='')
{
$justi_ss=$_POST['justi_ss'];
}

$observ_ss='';
if(isset($_POST['observ_ss'])&&$_POST['observ_ss']!='')
{
$observ_ss=$_POST['observ_ss'];
}

$tipoGuardar='';
if(isset($_GET['tipoGuardar'])&&$_GET['tipoGuardar']!='')
{
$tipoGuardar=$_GET['tipoGuardar'];
}

$modalidad_ss='';
if(isset($_POST['modalidad_ss'])&&$_POST['modalidad_ss']!='')
{
$modalidad_ss=$_POST['modalidad_ss'];
}

$clasificacion_ss='';
if(isset($_POST['clasificacion_ss'])&&$_POST['clasificacion_ss']!='')
{
$clasificacion_ss=$_POST['clasificacion_ss'];
}

$descripcion_ss='';
if(isset($_POST['descripcion_ss'])&&$_POST['descripcion_ss']!='')
{
$descripcion_ss=$_POST['descripcion_ss'];
}

 $area='';
if(isset($_POST['area'])&&$_POST['area']!='')
{
 $area=$_POST['area'];
}
if($area==''){
 $area=1;
}

  $codreq='';
if(isset($_GET['codreq']) && $_GET['codreq']!=''){
  $codreq=$_GET['codreq'];
}

if ($tipoGuardar == 'pasarDeestado'){
	$query_RsUpdateEstado = "UPDATE REQUERIMIENTOS SET REQUESTA = '".$_POST['pasar_estado']."' WHERE REQUCODI='".$codreq."' ";
	$RsUpdateEstado = mysqli_query($conexion,$query_RsUpdateEstado) or die(mysqli_error($conexion));
	$redireccionar = "location: home.php?page=solicitud&codreq=".$codreq;
    header($redireccionar);	
}

if ($tipoGuardar == 'pasarDeestado2'){
	$query_RsUpdateEstado = "UPDATE REQUERIMIENTOS SET REQUESTA = '".$_GET['estado_req']."' WHERE REQUCODI='".$codreq."' ";
	$RsUpdateEstado = mysqli_query($conexion,$query_RsUpdateEstado) or die(mysqli_error($conexion));
	$afecado = mysqli_affected_rows($conexion);
	//$redireccionar = "location: home.php?page=solicitud&codreq=".$codreq;
    //header($redireccionar);
   echo('1');	
	
}

if ($tipoGuardar == 'pasarDeestadoDetalle'){
	$query_RsUpdateEstado = "UPDATE detalle_requ SET DEREAPRO = '".$_GET['estado_det']."' WHERE DERECONS='".$_GET['cod_det']."' ";
	$RsUpdateEstado = mysqli_query($conexion,$query_RsUpdateEstado) or die(mysqli_error($conexion));
	$afecado = mysqli_affected_rows($conexion);
	//$redireccionar = "location: home.php?page=solicitud&codreq=".$codreq;
    //header($redireccionar);
   echo('1');	
	
}


if ($tipoGuardar == 'AprobarRequerimiento')
{
$aprob_devuelto='';
if(isset($_GET['aprob_devuelto']) && $_GET['aprob_devuelto']!=''){
  $aprob_devuelto=$_GET['aprob_devuelto'];
}

echo($aprob_devuelto);
     //CONSULTA EL ESTADO Y CODIGO DEL REQUERIMIENTO
	 $query_RsListaDetalles="SELECT D.DERECONS CODIGO,
                          D.DEREAPRO ESTADO	 
	               FROM DETALLE_REQU D
				  WHERE D.DEREREQU = '".$codreq."'";
	$RsListaDetalles = mysqli_query($conexion,$query_RsListaDetalles) or die(mysqli_error($conexion));
	$row_RsListaDetalles = mysqli_fetch_array($RsListaDetalles);
    $totalRows_RsListaDetalles = mysqli_num_rows($RsListaDetalles);


	if($totalRows_RsListaDetalles>0){
	  do{
	     //ACTUALIZA PARAMETRO DE CONSEJO SUPERIOR 
	     if(isset($_POST['consejosuperior_'.$row_RsListaDetalles['CODIGO']]) && $_POST['consejosuperior_'.$row_RsListaDetalles['CODIGO']]!=''){
		   $query_RsUpdateConsejoSup ="UPDATE DETALLE_REQU SET DERECOSU = '".$_POST['consejosuperior_'.$row_RsListaDetalles['CODIGO']]."'
		                                 WHERE DERECONS = '".$row_RsListaDetalles['CODIGO']."'";
										 //echo('consejosuperior'.$query_RsUpdateConsejoSup."<br>");
		   $RsUpdateConsejoSup = mysqli_query($conexion,$query_RsUpdateConsejoSup) or die(mysqli_error($conexion));
		 }
		 //ACTUALIZA PARAMETRO DE CONSEJO TECNOLOGICO
	     if(isset($_POST['consejotecnologico_'.$row_RsListaDetalles['CODIGO']]) && $_POST['consejotecnologico_'.$row_RsListaDetalles['CODIGO']]!=''){
		 		   $query_RsUpdateConsejoTec ="UPDATE DETALLE_REQU SET DERECOTE = '".$_POST['consejotecnologico_'.$row_RsListaDetalles['CODIGO']]."'
		                                 WHERE DERECONS = '".$row_RsListaDetalles['CODIGO']."'";
										 //echo('consejotecnologico'.$query_RsUpdateConsejoTec."<br>"); 
		   $RsUpdateConsejoTec = mysqli_query($conexion,$query_RsUpdateConsejoTec) or die(mysqli_error($conexion));		 
		 }
		 //ACTUALIZA PARAMETRO DE CONSEJO INFRAESTRUCTURA
	     if(isset($_POST['comiteinfra_'.$row_RsListaDetalles['CODIGO']]) && $_POST['comiteinfra_'.$row_RsListaDetalles['CODIGO']]!=''){
		 		   $query_RsUpdateComiteInfra ="UPDATE DETALLE_REQU SET DERECOIN = '".$_POST['comiteinfra_'.$row_RsListaDetalles['CODIGO']]."'
		                                 WHERE DERECONS = '".$row_RsListaDetalles['CODIGO']."'";
										 //echo('comiteinfra'.$query_RsUpdateComiteInfra."<br>"); 
		   $RsUpdateComiteInfra = mysqli_query($conexion,$query_RsUpdateComiteInfra) or die(mysqli_error($conexion));		  
		  
		 }
	//almacenar poa y subpoa
	if(isset($_POST['otro_'.$row_RsListaDetalles['CODIGO']]) && $_POST['otro_'.$row_RsListaDetalles['CODIGO']] ==1){ //almacenar valor de otropoa
	   if(isset($_POST['otropoa_'.$row_RsListaDetalles['CODIGO']]) && $_POST['otropoa_'.$row_RsListaDetalles['CODIGO']] !=''){
		 	$query_RsUpdateOtro ="UPDATE DETALLE_REQU SET DEREOTRO = '".$_POST['otropoa_'.$row_RsListaDetalles['CODIGO']]."',
			                                                     DEREREOT = '1'
		                                 WHERE DERECONS = '".$row_RsListaDetalles['CODIGO']."'";
										 //echo('UPDATEOTRO'.$query_RsUpdateOtro."<br>"); 
		   $RsUpdateOtro = mysqli_query($conexion,$query_RsUpdateOtro) or die(mysqli_error($conexion));	     
		}
	}else{ //almacenar poa y subpoa
	  if(isset($_POST['poadetalle_'.$row_RsListaDetalles['CODIGO']]) && $_POST['poadetalle_'.$row_RsListaDetalles['CODIGO']] !='' && isset($_POST['subpoadetalle_'.$row_RsListaDetalles['CODIGO']]) && $_POST['subpoadetalle_'.$row_RsListaDetalles['CODIGO']] !=''){
		 	$query_RsUpdatePoaSubpoa ="UPDATE DETALLE_REQU SET   DEREPOA = '".$_POST['poadetalle_'.$row_RsListaDetalles['CODIGO']]."',
																 DERESUPO = '".$_POST['subpoadetalle_'.$row_RsListaDetalles['CODIGO']]."',
			                                                     DEREREOT = '0'
		                                 WHERE DERECONS = '".$row_RsListaDetalles['CODIGO']."'";
										 //echo('UPDATEPOASUBPOA'.$query_RsUpdatePoaSubpoa."<br>"); 
		   $RsUpdatePoaSubpoa = mysqli_query($conexion,$query_RsUpdatePoaSubpoa) or die(mysqli_error($conexion));		    
	  }
	}
     //end	    
		
		
		}while($row_RsListaDetalles = mysqli_fetch_array($RsListaDetalles));
	}
	

      $query_RsUpdateAprobado="UPDATE REQUERIMIENTOS SET REQUESTA = 5,
														 REQUFEAP = SYSDATE(),
														 REQUPEAP = '".$_SESSION['MM_UserID']."'
                            where REQUCODI = '".$codreq."'";
      $RsUpdateAprobado = mysqli_query($conexion,$query_RsUpdateAprobado) or die(mysqli_error($conexion)); 	  
 
	$redireccionar = "location: home.php?page=solicitud&codreq=".$codreq;
    header($redireccionar);
}



if ($tipoGuardar == 'crear_req')
{
$x='';
if(isset($_GET['x'])&&$_GET['x']!='')
{
$x=$_GET['x'];
}



if($x=='director'){
$MM_UserID='39550544';
$MM_Area='3';
}else{
$MM_UserID=$_SESSION['MM_UserID'];
$MM_Area=$_SESSION['MM_Area'];

}
   $query_RsCrearRequerimiento="INSERT INTO REQUERIMIENTOS (
	                                                         REQUCODI,
															 REQUIDUS,
															 REQUAREA,
															 REQUFESO,
															 REQUFLED
															 
															 )
															 VALUES
															 (
															 NULL,
															 '".$MM_UserID."',
															 '".$MM_Area."',
															 sysdate(),
															 '1'

															 )";
															// exit($query_RsCrearRequerimiento);
  	$RsCrearRequerimiento = mysqli_query($conexion,$query_RsCrearRequerimiento) or die(mysqli_error($conexion));

				$query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
				$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
				$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);
				$requerimiento_creado = $row_RsUltInsert['DATO'];

if($x=='director'){
 $redireccionar = "location: home.php?page=solicitud_director&MM_UserID=".$MM_UserID."&MM_Area=".$MM_Area."&codreq=".$requerimiento_creado;
}else{
 $redireccionar = "location: home.php?page=solicitud&codreq=".$requerimiento_creado;
}
   
   
   header($redireccionar);

}

if ($tipoGuardar == 'ArchivoReq')
{
 	$query_RsParametroRuta = "SELECT PARAVALOR FROM PARAMETROS WHERE PARANOMB = 'RUTAARCHIVO_NAS'";
	$RsParametroRuta = mysqli_query($conexion,$query_RsParametroRuta) or die(mysqli_error($conexion));
	$row_RsParametroRuta = mysqli_fetch_array($RsParametroRuta);

	$rutaArchivos = $row_RsParametroRuta['PARAVALOR'];
	$carpeta = '/archivos_usuario_g/';
	$rutaArchivos = $row_RsParametroRuta['PARAVALOR'].$carpeta;

	//$rutaArchivos = '//175.176.0.6/compras/';
	//$rutaArchivos = 'C:/wamp/www/compras/archivos_usuario_g/';

	if (is_uploaded_file($_FILES['archivo1']['tmp_name']))
	{
		$upload_archivo_dir = $rutaArchivos;
		$nombre_archivo = str_replace("�", "N",$_FILES['archivo1']['name']);
		$nombre_archivo = str_replace("�", "n",$nombre_archivo);
		$ext=date("Ymd_His");
		$nombre_archivo = $ext."-".$nombre_archivo;
		$tipo_archivo = $_FILES['archivo1']['type'];

		if ( move_uploaded_file($_FILES['archivo1']['tmp_name'],$upload_archivo_dir.$nombre_archivo) )
		{
		$query_RsUpdate="INSERT INTO REQUERIMIENTOSARCH (
		                                                 REARCODI,
														 REARREQU,
														 REARARCH
														 )
														 VALUES
														 (
														 NULL,
														 '".$codreq."',
														 '".$nombre_archivo."'
														 )
														 ";
														 //exit($query_RsUpdate);
		$RsUpdate = mysqli_query($conexion,$query_RsUpdate) or die(mysqli_error($conexion));
		}
	}

$MM_UserID='';
if(isset($_GET['MM_UserID']) && $_GET['MM_UserID']!=''){
$MM_UserID=$_GET['MM_UserID'];
}

$MM_Area='';
if(isset($_GET['MM_Area']) && $_GET['MM_Area']!=''){
$MM_Area=$_GET['MM_Area'];
}

if(isset($_GET['MM_Area']) && isset($_GET['MM_UserID']) ){	
	$redireccionar = "location: home.php?page=solicitud_director&MM_UserID=".$MM_UserID."&MM_Area=".$MM_Area."&codreq=".$codreq;
	}else{
	$redireccionar = "location: home.php?page=solicitud&codreq=".$codreq;
	}
	
   header($redireccionar);
}


if ($tipoGuardar == 'enviar_req')
{
    $query_RsAsignarCodRequerimiento="SELECT P.PARAVALOR+1 VALOR, PARADEFI DEFINICION FROM PARAMETROS P WHERE PARACODI = 2";
	$RsAsignarCodRequerimiento = mysqli_query($conexion, $query_RsAsignarCodRequerimiento) or die(mysqli_error($conexion));
	$row_RsAsignarCodRequerimiento = mysqli_fetch_array($RsAsignarCodRequerimiento);
    //$totalRows_RsAsignarCodRequerimiento = mysql_num_rows($RsAsignarCodRequerimiento);

	$CODIGOREQUERIMIENTO = $row_RsAsignarCodRequerimiento['DEFINICION']."-".$row_RsAsignarCodRequerimiento['VALOR'];
    $query_RsCrearRequerimiento="UPDATE REQUERIMIENTOS SET
	                                    REQUCORE = '".$CODIGOREQUERIMIENTO."',
										REQUESTA = 2,
										REQUFLED = 0,
										REQUFEEN = SYSDATE()
	                               WHERE REQUCODI = '".$codreq."'";
    $RsCrearRequerimiento = mysqli_query($conexion,$query_RsCrearRequerimiento) or die(mysql_error());

	$query_RsUpdateParametro="update PARAMETROS SET PARAVALOR = '".$row_RsAsignarCodRequerimiento['VALOR']."'
	                           WHERE PARACODI = 2
	 ";
	$RsUpdateParametros = mysqli_query($conexion, $query_RsUpdateParametro) or die(mysqli_error($conexion));
	
	$query_RsUpdateReqEspecial="update personas SET PERSPERE = '1'
	                           WHERE PERSID = '".$_SESSION['MM_UserID']."'
	 ";
	$RsUpdateReqEspecials = mysqli_query($conexion, $query_RsUpdateReqEspecial) or die(mysqli_error($conexion));	
		
	$redireccionar = "location: home.php?page=solicitud&env=1&codreq=".$codreq;
    header($redireccionar);

}

if ($tipoGuardar == 'reenviar_req')
{
    
	
    $query_RsCrearRequerimiento="UPDATE REQUERIMIENTOS SET
										REQUESTA = 2,
										REQUFREE = SYSDATE()
	                               WHERE REQUCODI = '".$codreq."'";
    $RsCrearRequerimiento = mysqli_query($conexion,$query_RsCrearRequerimiento) or die(mysql_error());
		
	$redireccionar = "location: home.php?page=solicitud&codreq=".$codreq;
    header($redireccionar);

}


if ($tipoGuardar == 'editar_ss')
{
      $query_RsInsertDeta_Requ= "UPDATE DETALLE_REQU SET
								DEREMODA = '".$modalidad_ss."',
								DERECLAS = '".$clasificacion_ss."',
								DEREDESC = '".addslashes($descripcion_ss)."',
								DERECANT = '".$cantidad_ss."',
								DEREJUST = '".addslashes($justi_ss)."',
								DEREOBSE = '".addslashes($observ_ss)."'
								WHERE DERECONS = '".$_GET['codigo_detalle']."'
								";

	$RsRsInsertDeta_Requ = mysqli_query($conexion,$query_RsInsertDeta_Requ) or die(mysqli_error($conexion));
    $redireccionar = "location:home.php?page=solicitud&codreq=".$codreq;
    header($redireccionar);

}

if ($tipoGuardar == 'adicionar_ss')
{
 $query_RsInsertDeta_Requ= "INSERT INTO DETALLE_REQU(
								DERECONS ,
								DEREMODA ,
								DERECLAS ,
								DEREDESC ,
								DERECANT ,
								DEREJUST ,
								DEREOBSE ,
								DERETISE ,
								DEREREQU
								)
								VALUES (
								NULL ,
								'".$modalidad_ss."',
								'".$clasificacion_ss."',
								'".addslashes($descripcion_ss)."',
								'".$cantidad_ss."',
								'".addslashes($justi_ss)."',
								'".addslashes($observ_ss)."',
								'1',
								'".$codreq."'
								)";

						//echo($query_RsInsertDeta_Requ);
	$RsRsInsertDeta_Requ = mysqli_query($conexion,$query_RsInsertDeta_Requ) or die(mysqli_error($conexion));
	
	if(isset($_GET['insert']) && $_GET['insert'] != ''){
	 $query_RsUpdateRequ="UPDATE REQUERIMIENTOS SET REQUPOA  = '".$_GET['poa']."',
												   REQUSUPO = '".$_GET['subpoa']."'";
	 $RsUpdateRequ = mysqli_query($conexion,$query_RsUpdateRequ) or die(mysqli_error($conexion));
	}
	
    $redireccionar = "location: home.php?page=solicitud&codreq=".$codreq;
    header($redireccionar);

}

if ($tipoGuardar == 'editar_ns')
{
     $query_RsInsertDeta_Requ= "UPDATE DETALLE_REQU SET
								DEREDESC = '".addslashes($descrip_ns)."',
								DERECANT = '".$cantidad_ns."',
								DEREJUST = '".addslashes($justi_ns)."',
								DEREOBSE = '".addslashes($observ_ns)."',
								DEREUNME = '".$unidad_ns."'
								WHERE DERECONS = '".$_GET['codigo_detalle']."'
								";

	$RsRsInsertDeta_Requ = mysqli_query($conexion,$query_RsInsertDeta_Requ) or die(mysqli_error($conexion));
    $redireccionar = "location: home.php?page=solicitud&codreq=".$codreq;
    header($redireccionar);
}

if ($tipoGuardar == 'adicionar_ns')
{

$poa_usuario ='';

    $query_RsObtenerPoa="SELECT A.ARPOID   CODIGO,
                                A.ARPOIDAR AREA,
                                A.ARPOIDPO POA,
                                A.ARPOIDUS IDUSUARIO								
	                         FROM AREA_POA A
							WHERE A.ARPOIDUS = '".$_SESSION['MM_IDUsuario']."'";
	$RsObtenerPoa = mysqli_query($conexion,$query_RsObtenerPoa) or die(mysqli_error($conexion));
	$row_RsObtenerPoa = mysqli_fetch_array($RsObtenerPoa);
    $totalRows_RsObtenerPoa = mysqli_num_rows($RsObtenerPoa);
	if($totalRows_RsObtenerPoa){
	 $poa_usuario = $row_RsObtenerPoa['POA'];
	}
	
 $query_RsInsertDeta_Requ= "INSERT INTO DETALLE_REQU (
								DERECONS ,
								DEREDESC ,
								DERECANT ,
								DEREJUST ,
								DEREOBSE ,
								DERETISE ,
								DEREREQU ,
								DEREUNME ,
								DEREPOA
								)
								VALUES (
								NULL ,
								'".addslashes($descrip_ns)."',
								'".$cantidad_ns."',
								'".addslashes($justi_ns)."',
								'".addslashes($observ_ns)."',
								'-1',
								'".$codreq."',
								'".$unidad_ns."',
								'".$poa_usuario."'
								)";

						//echo($query_RsInsertDeta_Requ);
	$RsRsInsertDeta_Requ = mysqli_query($conexion,$query_RsInsertDeta_Requ) or die(mysqli_error($conexion));
	if(isset($_GET['insert']) && $_GET['insert'] != ''){
	 $query_RsUpdateRequ="UPDATE REQUERIMIENTOS SET REQUPOA  = '".$_GET['poa']."',
												   REQUSUPO = '".$_GET['subpoa']."'";
	 $RsUpdateRequ = mysqli_query($conexion,$query_RsUpdateRequ) or die(mysqli_error($conexion));
	}
	
    $redireccionar = "location: home.php?page=solicitud&codreq=".$codreq;
    header($redireccionar);

}


if ($tipoGuardar == 'Detalle_NS')
{
 $query_RsInsertDeta_Requ= "INSERT INTO DETALLE_REQU (
								DERECONS ,
								DEREDESC ,
								DERECANT ,
								DEREJUST ,
								DEREOBSE ,
								DERETISE
								)
								VALUES (
								NULL ,
								'$descrip_ns',
								'$cantidad_ns',
								'$justi_ns',
								'$observ_ns',
								'0'
								)";

						//echo($query_RsInsertRequerimiento);
	$RsRsInsertDeta_Requ = mysqli_query($conexion,$query_RsInsertDeta_Requ) or die(mysql_error());
$redireccionar = "location: home.php?page=solicitud";
header($redireccionar);

}

if ($tipoGuardar == 'Detalle_SS')
{
 $query_RsInsertDeta_Requ= "INSERT INTO DETALLE_REQU (
								DERECONS ,
								DEREDESS ,
								DERECANT ,
								DEREJUST ,
								DEREOBSE,
								DERETISE
								)
								VALUES (
								NULL ,
								'$clasif_ss',
								 CONCAT('$cantidad_ss', "-", '$cantidad_ss' ),
								'$justi_ns',
								'$observ_ns',
								'1'
								)";

						//echo($query_RsInsertDeta_Requ);
	$RsRsInsertDeta_Requ = mysqli_query($conexion,$query_RsInsertDeta_Requ) or die(mysqli_error());
$redireccionar = "location: home.php?page=solicitud";
header($redireccionar);

}

if ($tipoGuardar == 'Eliminar')
{
 $query_RsInsertRequerimiento= "DELETE FROM `comp_requerimiento`
                                       WHERE `REQUCONS` = ".$codirequeli."";
						//echo($query_RsInsertRequerimiento);
	$RsInsertRequerimiento = mysqli_query($conexion,$query_RsInsertRequerimiento) or die(mysqli_error());

$redireccionar = "location: listar.php";
header($redireccionar);
}

if ($tipoGuardar == 'actualizar')
{ //ECHO($tipoGuardar);

 $query_RsInsertRe= "UPDATE comp_requerimiento SET REQUPRIO = '$menu1' WHERE REQUCONS ='$codi'";
						//echo($query_RsInsertRequerimiento);
	$RsInsertRe = mysqli_query($conexion,$query_RsInsertRe) or die(mysqli_error());

$redireccionar = "location: listar2.php";
header($redireccionar);
}

if ($tipoGuardar == 'Combo_Producto')
{
$query_RsListadoDeta_Requ = "SELECT
								`PRODCONS` ID,
								`PRODNOMB` DESC,
								`PRODESTA`
							FROM `productos`
							WHERE 1";

				// echo($query_RsListadoDeta_Requ);echo("<br>");
	$RsListadoDeta_Requ = mysqli_query($conexion,$query_RsListadoDeta_Requ) or die(mysqli_error());
	$row_RsListadoDeta_Requ = mysqli_fetch_array($RsListadoDeta_Requ);
    $totalRows_RsListadoDeta_Requ = mysqli_num_rows($RsListadoDeta_Requ);

}

if ($tipoGuardar == 'AgregarImagenRespuesta')
{

 	$query_RsParametroRuta = "SELECT PARAVALOR FROM PARAMETROS WHERE PARANOMB = 'RUTAARCHIVO'";
	$RsParametroRuta = mysqli_query($query_RsParametroRuta, $psico) or die(mysql_error());
	$row_RsParametroRuta = mysql_fetch_assoc($RsParametroRuta);

	$rutaArchivos = $row_RsParametroRuta['PARAVALOR'];
	//$rutaArchivos = 'C://wamp2i/www/psicometriaqa/imagenes_preguntas/';

	if (is_uploaded_file($_FILES['archivoimg_'.$idrespuesta]['tmp_name']))
	{
		$upload_archivo_dir = $rutaArchivos;
		$nombre_archivo = str_replace("�", "N",$_FILES['archivoimg_'.$idrespuesta]['name']);
		$nombre_archivo = str_replace("�", "n",$nombre_archivo);
		$ext=date("Ymd_His");
		$nombre_archivo = $ext."-".$nombre_archivo;
		$tipo_archivo = $_FILES['archivoimg_'.$idrespuesta]['type'];

		if ( move_uploaded_file($_FILES['archivoimg_'.$idrespuesta]['tmp_name'],$upload_archivo_dir.$nombre_archivo) )
		{
		$query_RsUpdate="UPDATE RESPUESTAS SET RESPARCH = '".$nombre_archivo."' where RESPCODI = '".$idrespuesta."' ";
		$RsUpdate = mysql_query($query_RsUpdate, $psico) or die(mysql_error());
		}
	}
	$redireccionar = "location: home.php?doc=pregunta&codigo=".$codigo;
	header($redireccionar);
}

if ($tipoGuardar == 'add_new_det')
{

$poa_usuario ='';

$MM_UserID='';
if(isset($_GET['MM_UserID']) && $_GET['MM_UserID']!=''){
$MM_UserID=$_GET['MM_UserID'];
}

$MM_Area='';
if(isset($_GET['MM_Area']) && $_GET['MM_Area']!=''){
$MM_Area=$_GET['MM_Area'];
}

    $query_RsObtenerPoa="SELECT A.ARPOID   CODIGO,
                                A.ARPOIDAR AREA,
                                A.ARPOIDPO POA,
                                A.ARPOIDUS IDUSUARIO								
	                         FROM AREA_POA A
							WHERE A.ARPOIDUS = '11'";
	$RsObtenerPoa = mysqli_query($conexion,$query_RsObtenerPoa) or die(mysqli_error($conexion));
	$row_RsObtenerPoa = mysqli_fetch_array($RsObtenerPoa);
    $totalRows_RsObtenerPoa = mysqli_num_rows($RsObtenerPoa);
	if($totalRows_RsObtenerPoa){
	 $poa_usuario = $row_RsObtenerPoa['POA'];
	}
	
 $query_RsInsertDeta_Requ= "INSERT INTO DETALLE_REQU (
								DERECONS ,
								DEREDESC ,
								DERECANT ,
								DEREJUST ,
								DEREOBSE ,
								DERETISE ,
								DEREREQU ,
								DEREUNME ,
								DEREPOA
								)
								VALUES (
								NULL ,
								'".addslashes($descrip_ns)."',
								'".$cantidad_ns."',
								'".addslashes($justi_ns)."',
								'".addslashes($observ_ns)."',
								'-1',
								'".$codreq."',
								'".$unidad_ns."',
								'".$poa_usuario."'
								)";

						//echo($query_RsInsertDeta_Requ);
	$RsRsInsertDeta_Requ = mysqli_query($conexion,$query_RsInsertDeta_Requ) or die(mysqli_error($conexion));
	if(isset($_GET['insert']) && $_GET['insert'] != ''){
	 $query_RsUpdateRequ="UPDATE REQUERIMIENTOS SET REQUPOA  = '".$_GET['poa']."',
												   REQUSUPO = '".$_GET['subpoa']."'";
	 $RsUpdateRequ = mysqli_query($conexion,$query_RsUpdateRequ) or die(mysqli_error($conexion));
	}
	
    $redireccionar = "location: home.php?page=solicitud_director&MM_Area=".$MM_Area."&MM_UserID=".$MM_UserID."&codreq=".$codreq;
	
	
	
   header($redireccionar);

}
?>