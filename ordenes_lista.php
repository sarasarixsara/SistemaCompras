<?php
require_once('conexion/db.php');
	if (!isset($_SESSION)) {
  session_start();
}

if(!isset($_SESSION['MM_AccesoCorrectoApp']) || $_SESSION['MM_AccesoCorrectoApp'] != 'ACTIVO'){
  header("location: index.php");
}

$currentPage = $_SERVER["PHP_SELF"];

$nit_filtro='';
if(isset($_POST['nit_filtro']) && $_POST['nit_filtro'] !=''){
 $nit_filtro = $_POST['nit_filtro'];
}

$proveedor_filtro='';
if(isset($_POST['proveedor_filtro']) && $_POST['proveedor_filtro'] !=''){
 $proveedor_filtro = $_POST['proveedor_filtro'];
}

$tamanoPagina = 20;
$maxRows_RsListaRequerimientos = $tamanoPagina;
$pageNum_RsListaRequerimientos = 0;
if (isset($_GET['pageNum_RsListaRequerimientos'])) {
  $pageNum_RsListaRequerimientos = $_GET['pageNum_RsListaRequerimientos'];
}
$startRow_RsListaRequerimientos = $pageNum_RsListaRequerimientos * $maxRows_RsListaRequerimientos;

    
	$query_RsListaRequerimientos=" SELECT O.COORCODI CODIGO_ORDEN,
										  DATE_FORMAT(O.COORFECR, '%d/%m/%Y') FECHA_CREACION,
										  DATE_FORMAT(O.COORFEEN, '%d/%m/%Y') FECHA_ENVIO,
										  O.COORESTA ESTADO,
										  CASE O.COORESTA 
										  WHEN 0 
										  THEN 'CREADO' 
										  WHEN 1 
										  THEN 'ASIGNANDO PROVEEDOR' 
										  ELSE '' 
										  END ESTADO_DES, 
										  `PROVNOMB` proveedor_des 
									FROM cotizacion_orden O 
									INNER JOIN cotizacion C ON O.coorcodi = C.cotiorde 
									INNER JOIN proveedores P ON C.cotiprov=P.provcodi 
									WHERE 1   							
									";
 
 //si los filtros proveedor y nit llegan vacios
 	if ($proveedor_filtro =='' && $nit_filtro == '')
			{ 
			$query_RsListaRequerimientos = $query_RsListaRequerimientos." AND 1 ";
			}	

// si el filtro proveedor esta lleno y nit esta vacio
if($proveedor_filtro != '' && $nit_filtro == ''){			 
		$query_RsListaRequerimientos = $query_RsListaRequerimientos."  AND `PROVNOMB` LIKE '%$proveedor_filtro%' ";
		}    

//si el filtro nit esta lleno y proveedor esta vacio
if($nit_filtro != '' && $proveedor_filtro == ''){			 
		$query_RsListaRequerimientos = $query_RsListaRequerimientos."  AND `PROVREGI` LIKE '%$nit_filtro%' ";
		} 

//si el filtro nit esta lleno y proveedor esta lleno
if($nit_filtro != '' && $proveedor_filtro == ''){			 
		$query_RsListaRequerimientos = $query_RsListaRequerimientos."  `PROVREGI` LIKE '%$nit_filtro%' AND `PROVNOMB` LIKE '%$proveedor_filtro%' ";
		}		
	
  $query_RsListaRequerimientos = $query_RsListaRequerimientos." GROUP BY O.coorcodi";	  
  
  $query_RsListaRequerimientos = $query_RsListaRequerimientos." order by O.COORCODI DESC";	  
    
	    //echo($query_RsListaRequerimientos);
	    $query_limit_RsListaRequerimientos = sprintf("%s LIMIT %d, %d", $query_RsListaRequerimientos, $startRow_RsListaRequerimientos, $maxRows_RsListaRequerimientos);
		$RsListaRequerimientos = mysqli_query($conexion,$query_limit_RsListaRequerimientos) or die(mysqli_connect_error());
		$row_RsListaRequerimientos = mysqli_fetch_array($RsListaRequerimientos);
    
if (isset($_GET['totalRows_RsListaRequerimientos'])) {
  $totalRows_RsListaRequerimientos = $_GET['totalRows_RsListaRequerimientos'];
} else {
  $all_RsListaRequerimientos = mysqli_query($conexion, $query_RsListaRequerimientos);
  $totalRows_RsListaRequerimientos = mysqli_num_rows($all_RsListaRequerimientos);
}
	

//paginacion
$totalPages_RsListaRequerimientos = ceil($totalRows_RsListaRequerimientos/$maxRows_RsListaRequerimientos)-1;

$queryString_RsListaRequerimientos = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_RsListaRequerimientos") == false &&
        stristr($param, "totalRows_RsListaRequerimientos") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_RsListaRequerimientos = "&" . htmlentities(implode("&", $newParams));
  }
}

$queryString_RsListaRequerimientos = sprintf("&totalRows_RsListaRequerimientos=%d%s", $totalRows_RsListaRequerimientos, $queryString_RsListaRequerimientos);

$paginaHasta = 0;
if ($pageNum_RsListaRequerimientos == $totalPages_RsListaRequerimientos)
{
	$paginaHasta = $totalRows_RsListaRequerimientos;
}
else
{
	$paginaHasta = ($pageNum_RsListaRequerimientos+1)*$maxRows_RsListaRequerimientos;
}	
?>

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

	function VerOrden(req){
  		location.href="home.php?page=orden&codorden="+req;
	}

//paginación

 function f_abrir_link(v_link)
	{
	  document.form1.action=v_link;
	  document.form1.submit();

    }
</script>

<style type="text/css">
.contenttable{
 width:100%;
 overflow:hidden;
 min-height:150px;
 border-radius:12px;
}

</style>
</head>

<body>	
<div id="pagina">
	<form name="form1" id="form1" method="post" action="">
		<div class="contenttable">
			<table border="0">
 				<tr>
  					<td colspan="6">
  						<input  type="button" name="" id="" class="button2" value="Filtrar" onclick="mostrarfiltros();">
   						<input  type="button" name="consultar" id="consultar" class="button2" value="Elaborar Cotizaciones" onclick="window.open('cotizarjson.php','_blank');">  					    
  					</td>
 				</tr>
 				<tr>
 					<td colspan="6">
 						<div id="divfiltros" style="display:none; border:solid 1px #ccc; width:100%; -moz-border-radius: 4px; border-radius: 3px;">
 							<table width="100%">
								<tr>
									<td class="SLAB trtitle" colspan="7" align="center">Filtros de Busqueda</td>
				    			</tr>	
								<tr>
									<td  align="center">
										<input type="submit" name="butonfiltro" id="butonfiltro" class="button2" value="Buscar" onclick="Busqueda();">
									</td>
									<td class="">Proveedor</td>
									<td> 
									    <input type="text" name="proveedor_filtro" id="proveedor_filtro" value="<?php echo($proveedor_filtro);?>">										
										<input  type="button" name="fproveedor" id="fproveedor" value="x" onclick="limpiarfiltros('proveedor_filtro');">
									</td>						
									<td class="">Nit</td>
									<td>
										<input type="text" name="nit_filtro" id="nit_filtro" value="<?php echo($nit_filtro);?>">
										<input  type="button" name="fnit" id="fnit" value="x" onclick="limpiarfiltros('nit_filtro');">
									</td>									   
								</tr>														
							</table>
 						</div>
 					</td>
 				</tr> 
 				<tr>	 				
	   				<td colspan="6">
	    					<table border="1" align="left" class="datagrid" >			 					
		  						<tr class="texto_gral">
									<td>
			 							<ul>
			   								<?php if ($pageNum_RsListaRequerimientos > 0) { // Show if not first page ?>
			   								<li>
				  								<a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaRequerimientos=%d%s", $currentPage, 0, $queryString_RsListaRequerimientos); ?>')" class="submenus">Primero</a>
               								</li>
				  							<?php } // Show if not first page ?>
			   								<?php if ($pageNum_RsListaRequerimientos > 0) { // Show if not first page ?>
			    							<li>
				  								<a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaRequerimientos=%d%s", $currentPage, max(0, $pageNum_RsListaRequerimientos - 1), $queryString_RsListaRequerimientos); ?>')" class="submenus">Anterior</a>
				 							</li>
				  							<?php } // Show if not first page ?>
											<?php if ($pageNum_RsListaRequerimientos < $totalPages_RsListaRequerimientos) { // Show if not last page ?>
			     							<li>
                  								<a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaRequerimientos=%d%s", $currentPage, min($totalPages_RsListaRequerimientos, $pageNum_RsListaRequerimientos + 1), $queryString_RsListaRequerimientos); ?>')" class="submenus">Siguiente</a>
				 							</li>
				  							<?php } // Show if not last page ?>
											<?php if ($pageNum_RsListaRequerimientos < $totalPages_RsListaRequerimientos) { // Show if not last page ?>
			      							<li>
                  								<a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaRequerimientos=%d%s", $currentPage, $totalPages_RsListaRequerimientos, $queryString_RsListaRequerimientos); ?>')" class="submenus">&Uacute;ltimo</a>
				  							</li>
				  							<?php } // Show if not last page ?>
										</ul>
									</td>
		  						</tr>
		  						
							</table>
					</td>
				</tr>
				<tr>
					<td colspan="6">
   			 	 						<?php
											if ($totalRows_RsListaRequerimientos > 0)
											{
												?>
												Mostrando <b><?php echo($startRow_RsListaRequerimientos+1); ?></b> a <b><?php echo($paginaHasta); ?></b> de <b><?php echo($totalRows_RsListaRequerimientos);
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

<?php
	
    if($totalRows_RsListaRequerimientos >0){
	 ?>
	   <tr class="SLAB trtitle" align="center">
		   <td></td>
		   <td>Orden de Cotización</td>
		   <td>Proveedores</td>
		   <td>Estado</td>
		   <td>Fecha Creacion</td>
		   <td>Fecha Envio</td>
		</tr>
	 <?php
	  $k=0;
	  do{
	    $k++;
	     if($k%2==0){
		  $estilo="SB";
		 }else{
		  $estilo="SB2";
		 }
	  ?>
	  <tr CLASS="<?php echo($estilo);?>">
	    <td>
		<a type="button" class="buttonazul" value="Ver" href="home.php?page=orden&codorden=<?php echo($row_RsListaRequerimientos['CODIGO_ORDEN']);?>">Ver</a>
		</td>
	    <td align='center'><?php echo($row_RsListaRequerimientos['CODIGO_ORDEN']);?></td>
		
		<?php 
		
		$query_RsProveedor_des="SELECT P.PROVNOMB DES_PROVEEDOR
									FROM   cotizacion C,
										   cotizacion_orden O,
										   proveedores P
									WHERE  COORCODI=COTIORDE 
									AND    PROVCODI=COTIPROV
									AND    COORCODI='".$row_RsListaRequerimientos['CODIGO_ORDEN']."'";
				$RsProveedor_des = mysqli_query($conexion,$query_RsProveedor_des) or die(mysqli_connect_error());
				$row_RsProveedor_des = mysqli_fetch_array($RsProveedor_des);
				$totalRows_RsProveedor_des = mysqli_num_rows($RsProveedor_des); 
				 
		
		//
		
				?>
		
		<td align='left'><?php do{
			
			echo('- '.$row_RsProveedor_des['DES_PROVEEDOR'].'<br>'); 
			
			}while($row_RsProveedor_des = mysqli_fetch_array($RsProveedor_des));?> </td>
		<?php  //}while($row_RsProveedor_des = mysqli_fetch_array($row_RsProveedor_des)); ?>
	    <td align='center'><?php echo($row_RsListaRequerimientos['ESTADO_DES']);?></td>
	    <td align='center'><?php echo($row_RsListaRequerimientos['FECHA_CREACION']);?></td>
	    <td align='center'><?php echo($row_RsListaRequerimientos['FECHA_ENVIO']);?></td>
	  </tr>
	  <?php
	  
	   }while($row_RsListaRequerimientos = mysqli_fetch_array($RsListaRequerimientos));
	}else{
	?>
	<tr>
	  <td colspan="4">No existen registros</td>
	</tr>
	<?php
	}
?>
</table>
<table border="0" align="left" class="datagrid" >
		 <tr>
		  <td colspan="4">&nbsp;</td>
		 </tr>
		  <tr class="texto_gral">
			<td>
			 <ul>
			   <?php if ($pageNum_RsListaRequerimientos > 0) { // Show if not first page ?>
			   <li>
				  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaRequerimientos=%d%s", $currentPage, 0, $queryString_RsListaRequerimientos); ?>')" class="submenus">Primero</a>
               </li>
				  <?php } // Show if not first page ?>
			   <?php if ($pageNum_RsListaRequerimientos > 0) { // Show if not first page ?>
			    <li>
				  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaRequerimientos=%d%s", $currentPage, max(0, $pageNum_RsListaRequerimientos - 1), $queryString_RsListaRequerimientos); ?>')" class="submenus">Anterior</a>
				 </li>
				  <?php } // Show if not first page ?>
			<?php if ($pageNum_RsListaRequerimientos < $totalPages_RsListaRequerimientos) { // Show if not last page ?>
			     <li>
                  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaRequerimientos=%d%s", $currentPage, min($totalPages_RsListaRequerimientos, $pageNum_RsListaRequerimientos + 1), $queryString_RsListaRequerimientos); ?>')" class="submenus">Siguiente</a>
				 </li>
				  <?php } // Show if not last page ?>
			<?php if ($pageNum_RsListaRequerimientos < $totalPages_RsListaRequerimientos) { // Show if not last page ?>
			      <li>
                  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaRequerimientos=%d%s", $currentPage, $totalPages_RsListaRequerimientos, $queryString_RsListaRequerimientos); ?>')" class="submenus">&Uacute;ltimo</a>
				  </li>
				  <?php } // Show if not last page ?>
				</ul>
			</td>
		  </tr>
		</table>
</div>
</form>

 <script type="text/javascript">
  function mostrarfiltros(){
   $( "#divfiltros" ).toggle();
  }
  
  function limpiarfiltros (campo){
  document.getElementById(''+campo).value=""; 
  }
  function Busqueda(){
   document.form1.action="home.php?page=ordenes_lista";
  }

  function mostrarfiltros(){
   $( "#divfiltros" ).toggle();
  }
 </script>
