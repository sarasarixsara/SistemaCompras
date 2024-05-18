<?php
require_once('conexion/db.php');
	if (!isset($_SESSION)) {
  session_start();
}
if(!isset($_SESSION['MM_AccesoCorrectoApp']) || $_SESSION['MM_AccesoCorrectoApp'] != 'ACTIVO'){
  header("location: index.php");
}
$nombre_filtro="";
if(isset($_POST['nombre_filtro']) && $_POST['nombre_filtro']!=''){
$nombre_filtro = $_POST['nombre_filtro'];
}

$categoria_filtro="";
if(isset($_POST['categoria_prov']) && $_POST['categoria_prov']!=''){
$categoria_filtro = $_POST['categoria_prov'];
}
$con_conveniofiltro="";
if(isset($_POST['con_conveniofiltro']) && $_POST['con_conveniofiltro']!=''){
$con_conveniofiltro = $_POST['con_conveniofiltro'];
}
$correo_filtro="";
if(isset($_POST['correo_filtro']) && $_POST['correo_filtro']!=''){
$correo_filtro = $_POST['correo_filtro'];
}
if(isset($_GET['correo_filtro']) && $_GET['correo_filtro']!=''){
$correo_filtro = $_GET['correo_filtro'];
}
$ProveedorUpdate="";


$currentPage = $_SERVER["PHP_SELF"];
$tamanoPagina = 50;
$maxRows_RsListaProveedores = $tamanoPagina;
$pageNum_RsListaProveedores = 0;
if (isset($_GET['pageNum_RsListaProveedores'])) {
  $pageNum_RsListaProveedores = $_GET['pageNum_RsListaProveedores'];
}
$startRow_RsListaProveedores = $pageNum_RsListaProveedores * $maxRows_RsListaProveedores;

$codigo_requerimiento='';
if($codigo_requerimiento!=''){
$query_RsListadoDeta_Requ = "SELECT
							DERECONS CODIGO,
							DEREDESC DESCRIPCION,
							DERECANT CANTIDAD,
							DEREJUST JUSTIFICACION,
							DEREOBSE OBSERVACION,
							DERETISE TIPO
							FROM `detalle_requ`
						where DEREREQU = '".$codigo_requerimiento."'
							";

				// echo($query_RsListadoDeta_Requ);echo("<br>");
	$RsListadoDeta_Requ = mysqli_query($query_RsListadoDeta_Requ, $conexion) or die(mysqli_error());
	$row_RsListadoDeta_Requ = mysqli_fetch_array($RsListadoDeta_Requ);
    $totalRows_RsListadoDeta_Requ = mysqli_num_rows($RsListadoDeta_Requ);
}

//CONSULTA DE CATEGORIA DE PROVEEDORES     
	$query_RsEstados="SELECT E.ESTACODI CODIGO,
	                         E.ESTANOMB NOMBRE
					   FROM ESTADOS E";
	$RsEstados = mysqli_query($conexion,$query_RsEstados) or die(mysqli_connect_error());
	$row_RsEstados = mysqli_fetch_array($RsEstados);
    $totalRows_RsEstados = mysqli_num_rows($RsEstados);

//echo(date('Y-m-d'));
//$textdistinct = '';
//if($categoria_filtro != ''){
	$textdistinct = 'DISTINCT';
//}
	$query_RsListaProveedores="SELECT ".$textdistinct." P.PROVCODI CODIGO,
                                      P.PROVNOMB NOMBRE,
									  P.PROVTELE TELEFONO,
									  P.PROVPWEB WEB,
									  P.PROVDIRE DIRECCION,
									  P.PROVCON1 CONTACTO1,
									  P.PROVTEC1 TEL_CONTACTO1,
									  P.PROVCON2 CONTACTO2,
									  P.PROVTEC2 TEL_CONTACTO2,
									  P.PROVCORR CORREO,
									  (SELECT C.CONVCONS 
									     FROM convenios C
									   where C.CONVIDPR = P.PROVCODI
									    AND  '".date('Y-m-d')."' >= C.CONVFEIN 
									    AND  '".date('Y-m-d')."' <= C.CONVFEFI
									   LIMIT 1
									  ) CONVENIO,
									  P.PROVCONV TIENE_CONVENIO,
									  P.PROVESSO ESTADO_ACT
									  
							    FROM PROVEEDORES P INNER JOIN proveedor_clasificacion C ON P.PROVCODI = C.PRCLPROV 
							 WHERE  1";
	
	if($nombre_filtro!=''){
	  $query_RsListaProveedores = $query_RsListaProveedores." AND P.PROVNOMB LIKE '%".$nombre_filtro."%'";
	}

if($categoria_filtro!=''){
	  $query_RsListaProveedores = $query_RsListaProveedores." AND C.PRCLCLAS = '".$categoria_filtro."'  ";
	}

	if($con_conveniofiltro != ''){
	  $query_RsListaProveedores = $query_RsListaProveedores." AND P.PROVCONV = '".$con_conveniofiltro."'";
	}
	if($correo_filtro != ''){
	  $query_RsListaProveedores = $query_RsListaProveedores." AND P.PROVCORR like '%".$correo_filtro."%'";
	}

	$query_RsListaProveedores = $query_RsListaProveedores." ORDER BY P.PROVCODI DESC";
	
    //echo($query_RsListaProveedores);
	$query_limit_RsListaProveedores = sprintf("%s LIMIT %d, %d", $query_RsListaProveedores, $startRow_RsListaProveedores, $maxRows_RsListaProveedores);	
    
    $RsListaProveedores = mysqli_query($conexion, $query_limit_RsListaProveedores) or die(mysqli_error($conexion));
    $row_RsListaProveedores = mysqli_fetch_array($RsListaProveedores);	

if (isset($_GET['totalRows_RsListaProveedores'])) {
  $totalRows_RsListaProveedores = $_GET['totalRows_RsListaProveedores'];
} else {
  $all_RsListaProveedores = mysqli_query($conexion,$query_RsListaProveedores);
  $totalRows_RsListaProveedores = mysqli_num_rows($all_RsListaProveedores);
}

//if ($maxRows_RsProducto != 0)
$totalPages_RsListaProveedores = ceil($totalRows_RsListaProveedores/$maxRows_RsListaProveedores)-1;
//else
//$totalPages_RsProducto = ceil($totalRows_RsProducto/1)-1;

$queryString_RsListaProveedores = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_RsListaProveedores") == false &&
        stristr($param, "totalRows_RsListaProveedores") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_RsListaProveedores = "&" . htmlentities(implode("&", $newParams));
  }
}

$queryString_RsListaProveedores = sprintf("&totalRows_RsListaProveedores=%d%s", $totalRows_RsListaProveedores, $queryString_RsListaProveedores);

$paginaHasta = 0;
if ($pageNum_RsListaProveedores == $totalPages_RsListaProveedores)
{
	$paginaHasta = $totalRows_RsListaProveedores;
}
else
{
	$paginaHasta = ($pageNum_RsListaProveedores+1)*$maxRows_RsListaProveedores;
}
//echo($query_RsListaProveedores);

?><!DOCTYPE html>
<html>

<head>
<style type="text/css">
body{
/*background-image: url("img/Bottom_texture.png");*/
}

#menu{
/*background:#26B826;*/
/*background:#4C954B;*/
height:50px;
font-size:13px;
margin-top:-10px;
border-radius:13px;
}
#menu_proveedores{
 /* background:#26B826; */
 padding-top:15px;
 color:#FDF8F8;
 font-weight:bold;
}
#menu_proveedores li{
display:inline;
padding-left:15px;
padding-right:15px;
padding-top:10px;
padding-bottom:10px;
background:#4C954B;
border-radius:13px;
list-style-type:none;
}

#menu_proveedores a{
width:260px;
/*background:#ff0000;*/
text-decoration:none;
color:#ffffff;
}

#menu_proveedores a:hover{
color:#000000;
}

#menu_proveedores li:hover{
background:#99F199;
font-size:13px;
border-radius:13px;
}

form{
 padding-left:15px;
 padding-top:20px;
 padding-right:15px;
}
.toolstable{
	min-height:25px;
}
.toolstable a:hover{
	color:#ff0000;
	cursor:pointer;
	font-size:1.2em;
}
</style>
<title>proveedores</title>
<link rel="stylesheet" type="text/css" href="css/estilo_solicitud.css" />
<link rel="stylesheet" type="text/css" href="messages.css"/>
<script src="messages.js" type="text/javascript"></script>
</head>

<body>
	<div id="pagina">
		<form name="form1" id="form1" action="" method="post">
			<div id="divfiltros" style="display:none; border:solid 1px #ccc; width:100%;">
 				<table width="100%" class="table">
   					<tr>
     					<td class="SLAB trtitle" colspan="9" align="center">Filtros de Busqueda</td>
   					</tr>
						<tr>
							<td class="SB">Nombre Proveedor</td>
							<td>
	 							<input type="text" name="nombre_filtro" id="nombre_filtro" value="<?php echo($nombre_filtro);?>" class="form-control">
							</td>
							<td>
								<button type="button" class="btn btn-default "  name="fcategoria" id="fcategoria"  onclick="limpiarfiltros('nombre_filtro');"><i class="fa fa-close"></i></button>
							</td>
							<td class="SB">Correo</td>
							<td>
								<input type="text" class="form-control" value="<?php echo($correo_filtro);?>" id="correo_filtro" name="correo_filtro" >
							</td>
   						</tr>
						<tr>
						<td class="SB">Listado Productos</td>
    						<td>
								<select name="con_conveniofiltro" id="con_conveniofiltro" class="form-control">
									<option value="">Seleccione</option>
									<option value="1" <?php if($con_conveniofiltro == 1) echo("selected");?>>Con Listado</option>
									<option value="0" <?php if($con_conveniofiltro == 0 && $con_conveniofiltro != '') echo("selected");?>>Sin Listado</option>
								</select>
							</td>
							<td>
								<button type="button" class="btn btn-default "  name="fcategoria" id="fcategoria"  onclick="limpiarfiltros('con_conveniofiltro');"><i class="fa fa-close"></i></button>
							</td>
						<td class="SB">Categorias</td>
							<td>
								<select name="categoria_prov" id="categoria_prov" class="inputtext form-control" style="margin-left:0" >				
									<option value="">- Seleccione una Categoria -</option>
									<?php
										require_once("scripts/funcionescombo.php");		
										$categoria_proveedor = dameCategoria();
										foreach($categoria_proveedor as $indice => $registro){
									?>
									<option value="<?php echo($registro['CLASCODI'])?>" <?php if($categoria_filtro == $registro['CLASCODI']) echo("selected");?>><?php echo($registro['CLASNOMB']);?></option>
									<?php
									}
									?>
								</select>
							</td>
							<td>	
								<button type="button" class="btn btn-default "  name="fcategoria" id="fcategoria"  onclick="limpiarfiltros('categoria_prov');"><i class="fa fa-close"></i></button>
							</td>							
						</tr>
  			 			<tr>
    					<td colspan="6" align="left">
	 <button type="submit" name="butonfiltro" id="butonfiltro" class="button2" value="Buscar"><i class="fa fa-search"></i> Buscar</button>
	</td>
   </tr>
 </table>
 </div>
  <table class="tablalistado table" cellspacing="2" border="0">
   <tr>
  <td colspan="12">
   <button type="button" name="consultar" id="consultar" value="consultar" class="button2" onclick="mostrarfiltros();"><i class="fa fa-filter"></i>  Consultar</button>
  <button type="button" onclick="location.href='home.php?page=crear_proveedor&tipoGuardar=Guardar';" name="crearnuevo"  class="button2"><i class="fa fa-plus-circle"></i>  Proveedor</button>
<?php
if($_SESSION['MM_RolID'] == 3 || $_SESSION['MM_RolID']==2)
{
?>
  <button type="button" onclick="location.href='home.php?page=proveedores_solicitados';" name="crearnuevo"  class="button2"><i class="fa fa-eye"></i>  Proveedores solicitados</button>
  <button type="button" onclick="location.href='home.php?page=proveedores_inconsistencias';" name="crearnuevo"  class="button2"><i class="fa fa-eye"></i>  Proveedores inconsistencias</button>
  <button type="button" onclick="location.href='home.php?page=proveedores_inconsistencias';" name="crearnuevo"  class="button2"><i class="fa fa-envelope-o"></i> Correo</button>
 <?php 
}
 ?>
  </td>
 </tr>
     
	  
	
	<tr>
	 <td colspan="12" class="" >
	  <?php
			if ($totalRows_RsListaProveedores > 0)
		{
					?>
		Mostrando <b><?php echo($startRow_RsListaProveedores+1); ?></b> a <b><?php echo($paginaHasta); ?></b> de <b><?php echo($totalRows_RsListaProveedores);
		 ?></b> Registros
		<?php
					}
		else
		{
		?>
		Mostrando <b>0</b> a <b>0</b> de <b>0</b> Registros
		<?php
		}
		?>
	 </td>
	</tr>
   <tr class="SLAB trtitle">
    <td width="30"></td>
    <td>Proveedor</td>
	<td>Telefono</td>
	<td>Correo</td>	
	<td>Contacto</td>
	<td>Telefono</td>
	<td>Lista</td>
   </tr>
   <?php
    if($totalRows_RsListaProveedores >0){
	  $j=0;
      do{
	   $j++;
	    $estilo="SB";
	    if($j%2==0){
		$estilo="SB2";
		}
	  ?>
	  <tr class="<?php echo($estilo);?> small text-justify">
	   	<td <?php if($row_RsListaProveedores['ESTADO_ACT']==0){?>style="background: #a29a6f;"<?php } ?> >
		<div style="width:30px " class="toolstable">
	      
	   	  
		  
		<!-- Single button -->
			<div class="btn-group">
			  <a class=" dropdown-toggle" data-toggle="dropdown">
				 &nbsp;<span class="fa fa-ellipsis-v text-danger"></span> 
			  </a>
			  <ul class="dropdown-menu" role="menu" style="min-width:104px;">
				<li><a class="fz-1"  title="Editar" onclick="Feditarprov('<?php echo($row_RsListaProveedores['CODIGO']);?>');"><i class="fa fa-pencil"></i> Editar</a></li>
				<li><a  title="Eliminar" onclick="Feliminarprov('<?php echo($row_RsListaProveedores['CODIGO']);?>');"><i class="fa fa-close"></i> Eliminar</a></li>
				<li><a  title="Ver" onclick="Fverprov('<?php echo($row_RsListaProveedores['CODIGO']);?>');"> <i class="fa fa-eye"></i> Ver</a></li>
			  </ul>
			</div>		  
		  </div>
	   </td>
	   <td><?php echo($row_RsListaProveedores['NOMBRE']);?></td>
	   <td><?php echo($row_RsListaProveedores['TELEFONO']);?></td>
	   <td><?php echo($row_RsListaProveedores['CORREO']);?></td>	   
	   <td><?php echo($row_RsListaProveedores['CONTACTO1']);?></td>
	   <td><?php echo($row_RsListaProveedores['TEL_CONTACTO1']);?></td>
	    <td><?php 
			if($row_RsListaProveedores['TIENE_CONVENIO']==1)
			 { 
                if($row_RsListaProveedores['CONVENIO'] >0){		 
		    ?>
			<a href="home.php?page=convenio&c=<?php echo($row_RsListaProveedores['CODIGO']); ?>&convenio=<?php echo($row_RsListaProveedores['CONVENIO']);?>" ><img src="imagenes/interrogante.jpeg" width="16"></a>
			<?php  
				}else{
					?>
				<a href="home.php?page=convenio&c=<?php echo($row_RsListaProveedores['CODIGO']); ?>" >Crear </a>		
					<?php
				}
			}
			?>	
		</td>
	  </tr>
	  <?php
	   }while($row_RsListaProveedores = mysqli_fetch_array($RsListaProveedores));
	}
   ?>
  </table>
		<table border="0" align="left" class="datagrid">
		 <tr>
		  <td colspan="4">&nbsp;</td>
		 </tr>
		  <tr class="texto_gral">
			<td>
			 <ul>
			   <?php if ($pageNum_RsListaProveedores > 0) { // Show if not first page ?>
			   <li>
				  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaProveedores=%d%s", $currentPage, 0, $queryString_RsListaProveedores); ?>')" class="submenus">Primero</a>
               </li>
				  <?php } // Show if not first page ?>
			   <?php if ($pageNum_RsListaProveedores > 0) { // Show if not first page ?>
			    <li>
				  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaProveedores=%d%s", $currentPage, max(0, $pageNum_RsListaProveedores - 1), $queryString_RsListaProveedores); ?>')" class="submenus">Anterior</a>
				 </li>
				  <?php } // Show if not first page ?>
			<?php if ($pageNum_RsListaProveedores < $totalPages_RsListaProveedores) { // Show if not last page ?>
			     <li>
                  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaProveedores=%d%s", $currentPage, min($totalPages_RsListaProveedores, $pageNum_RsListaProveedores + 1), $queryString_RsListaProveedores); ?>')" class="submenus">Siguiente</a>
				 </li>
				  <?php } // Show if not last page ?>
			<?php if ($pageNum_RsListaProveedores < $totalPages_RsListaProveedores) { // Show if not last page ?>
			      <li>
                  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaProveedores=%d%s", $currentPage, $totalPages_RsListaProveedores, $queryString_RsListaProveedores); ?>')" class="submenus">&Uacute;ltimo</a>
				  </li>
				  <?php } // Show if not last page ?>
				</ul>
			</td>
		  </tr>
		</table>  
  </form>
</body>
</html>


<script type="text/javascript">
	function getDataServer(url, vars){
		 var xml = null;
		 try{
			 xml = new ActiveXObject("Microsoft.XMLHTTP");
		 }catch(expeption){
			 xml = new XMLHttpRequest();
		 }
		 xml.open("GET",url + vars, false);
		 xml.send(null);
		 if(xml.status == 404) alert("Url no valida");
		 return xml.responseText;
	}

	   function f_abrir_link(v_link)
	{
	  document.form1.action=v_link;
	  document.form1.submit();

    }
  function mostrarfiltros(){
   $( "#divfiltros" ).toggle();
  }	
  
  function Feliminarprov(cod){
      
	  if(confirm('esta seguro de eliminar este proveedor?')){
       document.form1.action='crear_proveedor_guardar.php?tipoGuardar=Eliminar_Prov&cod_prov='+cod;
	   document.form1.submit();
	   }
	  }
	  
  function Feditarprov(cod){
      
       document.form1.action='home.php?page=crear_proveedor&tipoGuardar=Editar&cod_prov='+cod;
	   document.form1.submit();
	  } 
	  
function Fverprov(cod){
      
       document.form1.action='home.php?page=proveedor&cod_prov='+cod;
	   document.form1.submit();
	  }	  

	  function limpiarfiltros (campo){
  document.getElementById(''+campo).value=""; 
  }

  
	  
</script>
