 <?php
require_once('conexion/db.php');

if (!isset($_SESSION)) {
  session_start();
}
//Llegada de variables

$nombre='';if(isset($_POST['nombre'])&&$_POST['nombre']!=''){$nombre=$_POST['nombre'];}

$nit='';if(isset($_POST['nit'])&&$_POST['nit']!=''){$nit=$_POST['nit'];}

$codigoprovreg='';if(isset($_POST['codigoprov'])&&$_POST['codigoprov']!=''){$codigoprovreg=$_POST['codigoprov'];}


$telefono='';if(isset($_POST['telefono'])&&$_POST['telefono']!=''){$telefono=$_POST['telefono'];}

$pagina='';if(isset($_POST['pagina'])&&$_POST['pagina']!=''){$pagina=$_POST['pagina'];}

$direccion='';if(isset($_POST['direccion'])&&$_POST['direccion']!=''){$direccion=$_POST['direccion'];}

$ciudad='';if(isset($_POST['ciudad'])&&$_POST['ciudad']!=''){$ciudad=$_POST['ciudad'];}

$categoria_prov='';if(isset($_POST['categoria_prov'])&&$_POST['categoria_prov']!=''){$categoria_prov=$_POST['categoria_prov'];}

$personac1='';if(isset($_POST['personac1'])&&$_POST['personac1']!=''){$personac1=$_POST['personac1'];}

$telefonoc1='';if(isset($_POST['telefonoc1'])&&$_POST['telefonoc1']!=''){$telefonoc1=$_POST['telefonoc1'];}

$cargo1='';if(isset($_POST['cargo1'])&&$_POST['cargo1']!=''){$cargo1=$_POST['cargo1'];}

$personac2='';if(isset($_POST['personac2'])&&$_POST['personac2']!=''){$personac2=$_POST['personac2'];}

$telefonoc2='';if(isset($_POST['telefonoc2'])&&$_POST['telefonoc2']!=''){$telefonoc2=$_POST['telefonoc2'];}

$cargo2='';if(isset($_POST['cargo2'])&&$_POST['cargo2']!=''){$cargo2=$_POST['cargo2'];}

$comentarios='';if(isset($_POST['comentarios'])&&$_POST['comentarios']!=''){$comentarios=$_POST['comentarios'];}

$correo='';if(isset($_POST['correo'])&&$_POST['correo']!=''){$correo=$_POST['correo'];}

$convenio='0';if(isset($_POST['convenio'])&&$_POST['convenio']!=''){$convenio=$_POST['convenio'];}
$nombre_comercial='';if(isset($_POST['nombre_comercial'])&&$_POST['nombre_comercial']!=''){$nombre_comercial=$_POST['nombre_comercial'];}
$regimen='';if(isset($_POST['regimen'])&&$_POST['regimen']!=''){$regimen=$_POST['regimen'];}
$autoretenedor='';if(isset($_POST['autoretenedor'])&&$_POST['autoretenedor']!=''){$autoretenedor=$_POST['autoretenedor'];}
$gran_contribuyente='';if(isset($_POST['gran_contribuyente'])&&$_POST['gran_contribuyente']!=''){$gran_contribuyente=$_POST['gran_contribuyente'];}
$contribuyente_ica='';if(isset($_POST['contribuyente_ica'])&&$_POST['contribuyente_ica']!=''){$contribuyente_ica=$_POST['contribuyente_ica'];}
$tipo_persona='';if(isset($_POST['tipo_persona'])&&$_POST['tipo_persona']!=''){$tipo_persona=$_POST['tipo_persona'];}
$tipo_adjunto='';if(isset($_POST['tipo_adjunto'])&&$_POST['tipo_adjunto']!=''){$tipo_adjunto=$_POST['tipo_adjunto'];}
$estado_act='';if(isset($_POST['estado_act'])&&$_POST['estado_act']!=''){$estado_act=$_POST['estado_act'];}


//Dinamica de Insertar en base de datos

$tipoGuardar='';if(isset($_GET['tipoGuardar'])&&$_GET['tipoGuardar']!=''){$tipoGuardar=$_GET['tipoGuardar'];}


// Procedimientos de Manipulacion de Datos 

if ($tipoGuardar == 'Guardar')
{

   $query_RsCrearRequerimiento="INSERT INTO PROVEEDORES (
	                                                      PROVCODI,
														  PROVREGI,
														  PROVNOMB,
														  PROVTELE,
														  PROVPWEB,
														  PROVDIRE,
														  PROVIDCI,
														  PROVCON1,
														  PROVTEC1,
														  PROVCCO1,
														  PROVCON2,
														  PROVTEC2,
														  PROVCCO2,
														  PROVCOME,
														  PROVPERE,
														  PROVFERE,
														  PROVCORR,
														  PROVCONV,
														  PROVNOCO,
														  PROVTIPE,
														  PROVREGM,
														  PROVAURE,
														  PROVCICA,
														  PROVGRCO,
														  PROVESSO														  
														 )
														 VALUES
														 (
														 NULL,
														 '".$codigoprovreg."',
														 '".$nombre."',
														 '".$telefono."',
														 '".$pagina."',
														 '".$direccion."',
														 '".$ciudad."',
														 '".$personac1."',
														 '".$telefonoc1."',
														 '".$cargo1."',
														 '".$personac2."',
														 '".$telefonoc2."',
														 '".$cargo2."',
														 '".$comentarios."',
														 '".$_SESSION['MM_UserID']."',
														 sysdate(),
														 '".$correo."',
														 '".$convenio."',
														 '".$nombre_comercial."',
														 '".$tipo_persona."',
														 '".$regimen."',
														 '".$autoretenedor."',
														 '".$contribuyente_ica."',
														 '".$gran_contribuyente."',
														 '".$estado_act."'
														 )";
												//echo( $query_RsCrearRequerimiento);		 
  	$RsCrearRequerimiento = mysqli_query($conexion,$query_RsCrearRequerimiento) or die(mysqli_error($conexion));
           			//exit($query_RsCrearRequerimiento);
				$query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
				$RsUltInsert = mysqli_query($conexion,$query_RsUltInsert) or die(mysqli_error($conexion));
				$row_RsUltInsert = mysqli_fetch_array($RsUltInsert);
				$proveedor_creado = $row_RsUltInsert['DATO'];
				
   $query_RsInsertarCategoria ="INSERT INTO `proveedor_clasificacion` (
																		`PRCLCODI`,
																		`PRCLPROV`, 
																		`PRCLCLAS` 
																	) 
																VALUES (
																		 NULL,
																		 '".$proveedor_creado."',
																		 '".$categoria_prov."'
																		 
																		 )";
											//echo($query_RsInsertarCategoria);						 
   $RsInsertarCategoria = mysqli_query($conexion,$query_RsInsertarCategoria) or die(mysqli_error($conexion));					
   
?>
<body>
<script type="text/javascript">
window.location="home.php?page=proveedores_lista";
</script>
</body>
<?php
}

if ($tipoGuardar == 'Editar')
{

$query_RsEditarRequerimiento="UPDATE PROVEEDORES 
								SET 
									PROVNOMB = '".$nombre."',
									PROVTELE = '".$telefono."',
									PROVPWEB = '".$pagina."',
									PROVDIRE = '".$direccion."',
									PROVIDCI = '".$ciudad."',
									PROVCON1 = '".$personac1."',
									PROVTEC1 = '".$telefonoc1."',
									PROVCCO1 = '".$cargo1."',
									PROVCON2 = '".$personac2."',
									PROVTEC2 = '".$telefonoc2."',
									PROVCCO2 = '".$cargo2."',
									PROVCOME = '".$comentarios."',
									PROVPERE = '".$_SESSION['MM_UserID']."',
									PROVFERE = sysdate(),
									PROVCORR = '".$correo."',
									PROVCONV = '".$convenio."',
									PROVNOCO = '".$nombre_comercial."',
								    PROVTIPE = '".$tipo_persona."',
								    PROVREGM = '".$regimen."',
								    PROVAURE = '".$autoretenedor."',
								    PROVCICA = '".$contribuyente_ica."',
								    PROVGRCO = '".$gran_contribuyente."',
								    PROVESSO = '".$estado_act."'
									
							  WHERE PROVCODI = '".$nit."'";
$RsEditarRequerimiento = mysqli_query($conexion,$query_RsEditarRequerimiento) or die(mysqli_error($conexion));
           			//exit($query_RsEditarRequerimiento);
					
if($categoria_prov!=''){
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
   // echo($query_RsInsertarCategoria);
}

//se direccionara de nuevo al listado de usuarios	
	$redireccionar = "location: home.php?page=crear_proveedor&cod_prov=".$nit."";
    header($redireccionar); 

}


if ($tipoGuardar == 'EliminarCategoriaProv')
{
//'".$_GET['time']."'
//'".$_GET['codigo']."'
//'".$_GET['proveedor']."'
//'".$_GET['clasificacion']."'

$query_RsElimProve= "DELETE FROM `proveedor_clasificacion` WHERE `PRCLCODI` = '".$_GET['codigo']."'   ";
						//echo($query_RsElimProve);
	$RsElimProve = mysqli_query($conexion,$query_RsElimProve) or die(mysqli_error($conexion));

	if($RsElimProve)

{
echo('1');
}else
{echo("none");}
}

if ($tipoGuardar == 'Eliminar_Prov')
{
$query_RsElimProve= "DELETE FROM`PROVEEDORES`
                                       WHERE `PROVCODI` = '".$_GET['cod_prov']."'   ";
						//echo($query_RsElimProve);
	$RsElimProve = mysqli_query($conexion,$query_RsElimProve) or die(mysqli_error($conexion));

	$query_RsElimCateg= "DELETE FROM `proveedor_clasificacion` WHERE PRCLPROV = '".$_GET['cod_prov']."'   ";
						//echo($query_RsElimCateg);
	$RsElimCateg = mysqli_query($conexion,$query_RsElimCateg) or die(mysqli_error($conexion));
   ?>
<body>
<script type="text/javascript">
window.location="home.php?page=proveedores_lista";
</script>
</body>
<?php
}
function VerificarExisteDirectorio($carpeta_prov){
	$existe = 'failed'; /* inicia no existe directorio */
		if (file_exists($carpeta_prov)) {
			$existe = 'existe'; /* directorio existe*/
		} else {
			if(!mkdir($carpeta_prov, 0777, true)) {
				die('');
				$existe = 'nook';
			}else{
				$existe = 'existe';
			}
		}
	return $existe;	
}

if ($tipoGuardar == 'Archivo')
{
	$codprov=$_GET['codigo'];
	
 	$query_RsParametroRuta = "SELECT PARAVALOR FROM PARAMETROS WHERE PARANOMB = 'RUTAARCHIVO_NAS'";
	$RsParametroRuta = mysqli_query($conexion,$query_RsParametroRuta) or die(mysqli_error($conexion));
	$row_RsParametroRuta = mysqli_fetch_array($RsParametroRuta);
	$carpeta = '/archivos_compras/PROVEEDORES/';
	$tiene_directorio = ''; /*Si tiene directorio dentro de /PROVEEDORES/ {id_proveedor provcodi}*/	
	
		$dir_destino = $row_RsParametroRuta['PARAVALOR'].$carpeta.$codprov;
		$verificar_directorio = VerificarExisteDirectorio($dir_destino);
		if($verificar_directorio == 'existe'){
			$tiene_directorio = $codprov.'/';			
		}	
	

	$rutaArchivos = $row_RsParametroRuta['PARAVALOR'].$carpeta.$tiene_directorio;
	//$rutaArchivos = '//175.176.0.6/compras/';
	//$rutaArchivos = 'C:/wamp/www/compras/archivos_usuario_g/';

	if (is_uploaded_file($_FILES['archivo1']['tmp_name']))
	{
		$upload_archivo_dir = $rutaArchivos;
		$nombre_archivo = str_replace("Ñ", "N",$_FILES['archivo1']['name']);
		$nombre_archivo = str_replace("ñ", "n",$nombre_archivo);
		$ext=date("Ymd_His");
		$tipo_archivo = $_FILES['archivo1']['type'];
		//$nombre_archivo = $ext."-".$nombre_archivo;
		$extension = end((explode(".", $_FILES['archivo1']['name'])));
		$nombre_archivo = $tipo_adjunto.'_'.$ext.'.'.$extension;
		
		

		if ( move_uploaded_file($_FILES['archivo1']['tmp_name'],$upload_archivo_dir.$nombre_archivo) )
		{
			$tiene_dir = '0';
			if($tiene_directorio != ''){
				$tiene_dir = '1';
			}
		$query_RsUpdate="INSERT INTO PROVEEDORESARCH (
		                                                 PRARCODI,
														 PRARPROV,
														 PRARARCH,
														 PRARTICA
														 )
														 VALUES
														 (
														 NULL,
														 '".$codprov."',
														 '".$nombre_archivo."',
														 '".$tiene_dir."'
														 )
														 ";
														 //exit($query_RsUpdate);
		$RsUpdate = mysqli_query($conexion,$query_RsUpdate) or die(mysqli_error($conexion));
		}
	}





	$redireccionar = "location:home.php?page=crear_proveedor&tipoGuardar=Editar&cod_prov=".$codprov;
	
	
   header($redireccionar);
}


if ($tipoGuardar == 'Eliminar_Archivo')
{
    $codigo=$_GET['codigo'];
	$codprov=$_GET['prov'];

	
	$query_RsDelete="DELETE FROM `proveedoresarch` WHERE `PRARCODI` = '".$codigo."' ";
														 //exit($query_RsDelete);
		$RsDelete = mysqli_query($conexion,$query_RsDelete) or die(mysqli_error($conexion));
	
	$redireccionar = "location:home.php?page=crear_proveedor&tipoGuardar=Editar&cod_prov=".$codprov;
	
	
   header($redireccionar);
}

?>