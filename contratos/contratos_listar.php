<?php
include('../conexion/db.php');

	if (!isset($_SESSION)) {
  session_start();
}

$filtro1="";
if(isset($_POST['filtro1']) && $_POST['filtro1']!=''){
$filtro1 = $_POST['filtro1'];
}

$filtro2="";
if(isset($_POST['filtro2']) && $_POST['filtro2']!=''){
$filtro1 = $_POST['filtro2'];
}

$filtro3="";
if(isset($_POST['filtro3']) && $_POST['filtro3']!=''){
$filtro3 = $_POST['filtro3'];
}


$currentPage = $_SERVER["PHP_SELF"];
$tamanoPagina = 15;
$maxRows_RsLista = $tamanoPagina;
$pageNum_RsLista = 0;

if (isset($_GET['pageNum_RsLista'])) {
  $pageNum_RsLista = $_GET['pageNum_RsLista'];
}
$startRow_RsLista = $pageNum_RsLista * $maxRows_RsLista;

	$query_RsLista="			SELECT `CONTID`  ID,
									  `CONTNUME` NUMERO,
									  `CONTCLAS` CLASE,
									  `CONTOBJE` OBJETO,									    									  
									  (SELECT P.PROVNOMB 
									   FROM  proveedores P 
									   WHERE P.PROVCODI=C.CONTCOID) CONTRATISTA,									  
									   date_format(CONTFEIN,'%d/%m/%Y') FECHA_INICIO,
									   date_format(CONTFEFI,'%d/%m/%Y') FECHA_FIN,
									   (SELECT SUBSTRING(CONTNUME,1,4)) A,
									   (SELECT SUBSTRING(CONTNUME,6,4)*2)B,
									  `CONTFETR` FECHA_DEFINITIVA,
									  `CONTNHOR` NUMHORAS,
									  `CONTFOPA` FORMA_PAGO,
									  `CONTVACU` VALOR
							 FROM CONTRATOS C 
							 WHERE  1";
	
	if($filtro1!=''){
	  $query_RsLista = $query_RsLista." AND CONTOBJE LIKE '%".$filtro1."%'";
	}

	if($filtro2!=''){
	  $query_RsLista = $query_RsLista." AND CONTCONO = '".$filtro2."'  ";
	}

	if($filtro3 != ''){
	  $query_RsLista = $query_RsLista." AND CONTNUME = '".$filtro3."'";
	}

	$query_RsLista = $query_RsLista." ORDER BY A , B ASC";
	
	
    //echo($query_RsLista);
	$query_limit_RsLista = sprintf("%s LIMIT %d, %d", $query_RsLista, $startRow_RsLista, $maxRows_RsLista);	
    
    $RsLista = mysqli_query($conexion, $query_limit_RsLista) or die(mysqli_error($conexion));
    $row_RsLista = mysqli_fetch_array($RsLista);	

if (isset($_GET['totalRows_RsLista'])) {
  $totalRows_RsLista = $_GET['totalRows_RsLista'];
} else {
  $all_RsLista = mysqli_query($conexion,$query_RsLista);
  $totalRows_RsLista = mysqli_num_rows($all_RsLista);
}

//if ($maxRows_RsProducto != 0)
$totalPages_RsLista = ceil($totalRows_RsLista/$maxRows_RsLista)-1;
//else
//$totalPages_RsProducto = ceil($totalRows_RsProducto/1)-1;

$queryString_RsLista = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_RsLista") == false &&
        stristr($param, "totalRows_RsLista") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_RsLista = "&" . htmlentities(implode("&", $newParams));
  }
}

$queryString_RsLista = sprintf("&totalRows_RsLista=%d%s", $totalRows_RsLista, $queryString_RsLista);

$paginaHasta = 0;
if ($pageNum_RsLista == $totalPages_RsLista)
{
	$paginaHasta = $totalRows_RsLista;
}
else
{
	$paginaHasta = ($pageNum_RsLista+1)*$maxRows_RsLista;
}
//echo($query_RsLista);

?><!DOCTYPE html>
<html>
<head>
<title>Contratos</title>
<link rel="stylesheet" type="text/css" href="../../css/contratos/contratos.css"/>
</head>
<body>
	<div id="pagina">
		<form name="form1" id="form1" action="" method="post">
			<div id="divfiltros" style="display:none; border:solid 0px #ccc; width:100%;">
 				<table width="100%">
   					<tr>
     					<td class="SLAB trtitle" colspan="6" align="center">Filtros de Busqueda</td>
   					</tr>
						<tr>
							<td class="SB">Objeto del contrato</td>
							<td>
	 							<input type="text" name="filtro1" id="filtro1" value="<?php echo($filtro1);?>">
	 							<input  type="button" name="fil1" id="fil1" value="x" onclick="limpiarfiltros('filtro1');">
							</td>
							<td class="SB">Categorias</td>
							<td>
								<input type="text" name="filtro2" id="filtro2" value="<?php echo($filtro2);?>">
	 							<input  type="button" name="fnombre" id="fnombre" value="x" onclick="limpiarfiltros('filtro2');">
							</td>

							<td class="SB">Listado Productos</td>
    						<td>
								<select name="filtro3" id="filtro3">
									<option value="">Seleccione</option>
									<option value="1">Con Listado</option>
									<option value="0">Sin Listado</option>
								</select>
								<input  type="button" name="fcategoria" id="fcategoria" value="x" onclick="limpiarfiltros('filtro3');">
							</td>
   						</tr>
  			 			<tr>
    					<td colspan="6" align="left">
	 <input type="submit" name="butonfiltro" id="butonfiltro" class="button2" value="Buscar">
	</td>
   </tr>
   <tr>
    <td><br></td>
   </tr>
 </table>
 </div>
 
  <table class="tablalistado" cellspacing="2" border="0">
   <tr>
  <td colspan="9">
   <input  type="button" name="consultar" id="consultar" value="consultar" class="button2" onclick="mostrarfiltros();">
  <input type="button" onclick="location.href='home.php?page=contratos/contratos_crear&tipoGuardar=Guardar';" name="crearnuevo" value="Nuevo" class="button2">
  </td>
 </tr>
 <tr>
  <td colspan="9"><br></td>
 </tr>
     
	  
	
	<tr>
	 <td colspan="9" class="" >
	  <a href="/contratos/contratos_export_xls.php"><i class="fa fa-file-excel-o fa-2x d-flex" aria-hidden="true"></i></a>
	 
	  <?php
			if ($totalRows_RsLista > 0)
		{
					?>
		Mostrando <b><?php echo($startRow_RsLista+1); ?></b> a <b><?php echo($paginaHasta); ?></b> de <b><?php echo($totalRows_RsLista);
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
   <tr class="small SLAB trtitle text-center">
    <td>..</td>	
    <td width="10%">N° CONTRATO</td>
	<td width="35%">OBJETO</td>
	<td width="24%">CONTRATISTA</td>
	<td width="8%">F. INICIO</td>
	<td width="8%">F. FIN</td>
	<td width="15%">VALOR</td>
	
	
   </tr>
   <?php
    if($totalRows_RsLista >0){
	  $j=0;
      do{
	   $j++;
	    $estilo="SB";
	    if($j%2==0){
		$estilo="SB2";
		}
	  ?>
	  <tr class="<?php echo($estilo);?>">
	  <td>
	  <i class="fa fa-download" aria-hidden="true"></i>
	  </td>
	   	<td>
		<a href="home.php?page=contratos/contratos_crear&tipoGuardar=Editar&cod=<?php echo($row_RsLista['ID']);?>" class="btn btn-link" role="button"><?php echo($row_RsLista['NUMERO']);?></a>		
	   </td>	   
	   <td class="small text-justify"><?php echo($row_RsLista['OBJETO']);?></td>
	   <td class="small text-left"><?php echo($row_RsLista['CONTRATISTA']);?></td>
	   <td class="small text-justify"><?php echo($row_RsLista['FECHA_INICIO']);?></td>
	   <td class="small text-justify"><?php echo($row_RsLista['FECHA_FIN']);?></td>
	   <td class="small text-right precio"><strong><?php echo(number_format($row_RsLista['VALOR'],0,',','.'));?></strong></td>
	   
	  </tr>
	  <?php
	   }while($row_RsLista = mysqli_fetch_array($RsLista));
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
			   <?php if ($pageNum_RsLista > 0) { // Show if not first page ?>
			   <li>
				  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsLista=%d%s", $currentPage, 0, $queryString_RsLista); ?>')" class="submenus">Primero</a>
               </li>
				  <?php } // Show if not first page ?>
			   <?php if ($pageNum_RsLista > 0) { // Show if not first page ?>
			    <li>
				  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsLista=%d%s", $currentPage, max(0, $pageNum_RsLista - 1), $queryString_RsLista); ?>')" class="submenus">Anterior</a>
				 </li>
				  <?php } // Show if not first page ?>
			<?php if ($pageNum_RsLista < $totalPages_RsLista) { // Show if not last page ?>
			     <li>
                  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsLista=%d%s", $currentPage, min($totalPages_RsLista, $pageNum_RsLista + 1), $queryString_RsLista); ?>')" class="submenus">Siguiente</a>
				 </li>
				  <?php } // Show if not last page ?>
			<?php if ($pageNum_RsLista < $totalPages_RsLista) { // Show if not last page ?>
			      <li>
                  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsLista=%d%s", $currentPage, $totalPages_RsLista, $queryString_RsLista); ?>')" class="submenus">&Uacute;ltimo</a>
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
