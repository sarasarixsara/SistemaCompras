<?php
require_once('../Connections/db.php');
	if (!isset($_SESSION)) {
  session_start();
}
	$inicial = 0;
	$final   = 50;
	$tamanoPagina  = 50;
	$totalRows_RsBeneficiarios =0;
$maxRows_RsBeneficiarios = $tamanoPagina;
$pageNum_RsBeneficiarios = 0;
//$pageNum_RsBeneficiarios=2;
//$totalRows_RsBeneficiarios=195;
$currentPage = $_SERVER["PHP_SELF"];
$currentPage = '';

if (isset($_GET['pageNum_RsBeneficiarios'])) {
  $pageNum_RsBeneficiarios = $_GET['pageNum_RsBeneficiarios'];
}
$startRow_RsBeneficiarios = $pageNum_RsBeneficiarios * $maxRows_RsBeneficiarios;
   $query_RsBeneficiarios= "SELECT B.BENECODI CODIGO,
							  B.BENECLID    TIPO_DOCUMENTO,
							  B.BENENUID     CEDULA,
	                          B.BENENOMB    NOMBRES,
	                          B.BENEAPELL   APELLIDOS,
							  B.BENEPERS    REGISTRO
							  
	                     FROM BENEFICIARIOS B 
						 "; //LIMIT ".$inicial.", ".$tamanoPagina."
						//";
	/*$RsBeneficiarios = mysqli_query( $conexion, $query_RsBeneficiarios) or die(mysql_error());
	$row_RsBeneficiarios = mysqli_fetch_array($RsBeneficiarios);
	$totalRows_RsBeneficiarios = mysqli_num_rows($RsBeneficiarios);
	*/
	$array = array();
	$data  = array();

 /*   if($totalRows_RsBeneficiarios>0){
		do{
			//$array[] = $row_RsBeneficiarios;
			$data[] = array('CODIGO'       => $row_RsBeneficiarios['CODIGO'],
	                       'TIPO_DOCUMENTO' => $row_RsBeneficiarios['TIPO_DOCUMENTO'],
	                       'CEDULA'         => $row_RsBeneficiarios['CEDULA'],
	                       'NOMBRES'        => $row_RsBeneficiarios['NOMBRES'],
	                       'APELLIDOS'      => $row_RsBeneficiarios['APELLIDOS'],
	                       'REGISTRO'       => $row_RsBeneficiarios['REGISTRO'],
					  );
		}while($row_RsBeneficiarios = mysqli_fetch_array($RsBeneficiarios));
    }
	$array[] = array('dato'  => $data,
					 'info'  => $info);
	echo(json_encode($array)); 
*/	
	
	$query_limit_RsBeneficiarios = sprintf("%s LIMIT %d, %d", $query_RsBeneficiarios, $startRow_RsBeneficiarios, $maxRows_RsBeneficiarios);
$RsBeneficiarios = mysqli_query($conexion, $query_limit_RsBeneficiarios) or die(mysql_error());
$row_RsBeneficiarios = mysqli_fetch_assoc($RsBeneficiarios);

if (isset($_GET['totalRows_RsBeneficiarios'])) {
  $totalRows_RsBeneficiarios = $_GET['totalRows_RsBeneficiarios'];
} else {
  $all_RsBeneficiarios = mysqli_query($conexion,$query_RsBeneficiarios);
  $totalRows_RsBeneficiarios = mysqli_num_rows($all_RsBeneficiarios);
}
//echo $query_RsBeneficiarios;
//if ($maxRows_RsProducto != 0)
$totalPages_RsBeneficiarios = ceil($totalRows_RsBeneficiarios/$maxRows_RsBeneficiarios)-1;
//else
//$totalPages_RsProducto = ceil($totalRows_RsProducto/1)-1;

$queryString_RsBeneficiarios = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_RsBeneficiarios") == false &&
        stristr($param, "totalRows_RsBeneficiarios") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_RsBeneficiarios = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_RsBeneficiarios = sprintf("&totalRows_RsBeneficiarios=%d%s", $totalRows_RsBeneficiarios, $queryString_RsBeneficiarios);

$paginaHasta = 0;
if ($pageNum_RsBeneficiarios == $totalPages_RsBeneficiarios)
{
	$paginaHasta = $totalRows_RsBeneficiarios;
}
else
{
	$paginaHasta = ($pageNum_RsBeneficiarios+1)*$maxRows_RsBeneficiarios;
}

if($totalRows_RsBeneficiarios>0){
		do{
			//$array[] = $row_RsBeneficiarios;
			$data[] = array('CODIGO'       => $row_RsBeneficiarios['CODIGO'],
	                       'TIPO_DOCUMENTO' => $row_RsBeneficiarios['TIPO_DOCUMENTO'],
	                       'CEDULA'         => $row_RsBeneficiarios['CEDULA'],
	                       'NOMBRES'        => $row_RsBeneficiarios['NOMBRES'],
	                       'APELLIDOS'      => $row_RsBeneficiarios['APELLIDOS'],
	                       'REGISTRO'       => $row_RsBeneficiarios['REGISTRO'],
					  );
		}while($row_RsBeneficiarios = mysqli_fetch_array($RsBeneficiarios));
    }
	$primero = '';
	$anterior = '';
	$siguiente = '';
	$ultimo = '';
	$sigpag = '';
	$antpage ='';
	if($pageNum_RsBeneficiarios>0){
		$primero = sprintf("%s?pageNum_RsBeneficiarios=%d%s", $currentPage, 0, $queryString_RsBeneficiarios);
		$anterior = sprintf("%s?pageNum_RsBeneficiarios=%d%s", $currentPage, max(0, $pageNum_RsBeneficiarios - 1), $queryString_RsBeneficiarios);
		$antpage = max(0, $pageNum_RsBeneficiarios - 1);
	}
	if ($pageNum_RsBeneficiarios < $totalPages_RsBeneficiarios) {
	    $siguiente = sprintf("%s?pageNum_RsBeneficiarios=%d%s", $currentPage, min($totalPages_RsBeneficiarios, $pageNum_RsBeneficiarios + 1), $queryString_RsBeneficiarios);
		$sigpag = min($totalPages_RsBeneficiarios, $pageNum_RsBeneficiarios + 1);
		$ultimo =  sprintf("%s?pageNum_RsBeneficiarios=%d%s", $currentPage, $totalPages_RsBeneficiarios, $queryString_RsBeneficiarios);
	}
	$paginas = array();
	$paginas[] = array('name'  => 'primero',
	                   'value' => $primero,
					   'totalr'=> $totalRows_RsBeneficiarios,
					   'pag'   => 0,
					   );
    $paginas[] = array('name'  => 'anterior',
	                   'value' => $anterior,
					   'totalr'=> $totalRows_RsBeneficiarios,
					   'pag'   => $antpage,
					   );
    $paginas[] = array('name'  => 'siguiente',
	                   'value' => $siguiente,
					   'totalr'=> $totalRows_RsBeneficiarios,
					   'pag'   => $sigpag,
					   );	
    $paginas[] = array('name'  => 'ultimo',
	                   'value' => $ultimo,
					   'totalr'=> $totalRows_RsBeneficiarios,
					   'pag'   => $totalPages_RsBeneficiarios,
					   );
	

	$array[] = array('dato'  => $data,
					 'info'  => $paginas);
	echo(json_encode($array)); 
?>