 <?php
require_once('conexion/db.php');

	if (!isset($_SESSION)) {
  session_start();
}
//Llegada de variables

$observaciones='';if(isset($_POST['observacion'])&&$_POST['observacion']!=''){$observaciones=$_POST['observacion'];}

$fecha_entrega='';if(isset($_POST['fecha_entrega'])&&$_POST['fecha_entrega']!=''){$fecha_entrega=$_POST['fecha_entrega'];}

$iva_desc='';if(isset($_POST['iva_desc'])&&$_POST['iva_desc']!=''){$iva_desc=$_POST['iva_desc'];}






//Dinamica de Insertar en base de datos

$tipoGuardar='';if(isset($_GET['tipoGuardar'])&&$_GET['tipoGuardar']!=''){$tipoGuardar=$_GET['tipoGuardar'];}


// Procedimientos de Manipulacion de Datos 

if ($tipoGuardar == 'Guardar')
{
	$proveedor='';if(isset($_GET['prov'])&&$_GET['prov']!=''){$proveedor=$_GET['prov'];}
	
	$query_RsInsertar="INSERT INTO orden_compra (
												 `ORCOCONS`,
												 `ORCOFECH`,
												 `ORCOIDPR`,
												 `ORCOFEEN`,
												 `ORCOOBSE`,
												  ORCODIVA
												 ) 
												 VALUES (
														 NULL,
														 SYSDATE(), 
														 '".$proveedor."', 
														 '".$fecha_entrega."', 
														 '".$observaciones."',
														 '".$iva_desc."'
														 )";
 					
 $RsInsertar = mysqli_query($conexion,$query_RsInsertar) or die(mysqli_error($conexion));

   
?>
<body>
<script type="text/javascript">
window.location="home.php?page=ordenar_compra_lista";
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
									PROVCONV = '".$convenio."'
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

?>