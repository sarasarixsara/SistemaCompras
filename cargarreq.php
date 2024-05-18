<?php
require_once('conexion/db.php');
	if (!isset($_SESSION)) {
  session_start();
}
if(!isset($_SESSION['MM_AccesoCorrectoApp']) || $_SESSION['MM_AccesoCorrectoApp'] != 'ACTIVO'){
 exit('no autorizado');
}

// recuperamos el criterio de la busqueda
$criterio = strtolower($_GET["req"]);
if (!$criterio) return;


if(isset($_GET['req']) && $_GET['req']!=''){
   $query_RsDetallesReq= "SELECT D.DERECONS CODIGO,
                             D.DEREMODA MODALIDAD,
							 D.DERECLAS CLASIFICACION,
							 D.DEREDESC DESCRIPCION,
							 D.DERECANT CANTIDAD,
							 D.DERETISE SELECCIONADO,
							 D.DEREREQU REQUERIMIENTO
						FROM DETALLE_REQU D
					  WHERE  D.DEREAPRO = 1
					  AND D.DEREREQU = '".$criterio."'
						";
	$RsDetallesReq = mysqli_query( $conexion, $query_RsDetallesReq) or die(mysql_error());
	$row_RsDetallesReq = mysqli_fetch_array($RsDetallesReq);
	$totalRows_RsDetallesReq = mysqli_num_rows($RsDetallesReq);
    if($totalRows_RsDetallesReq>0){
	 $i=0;
	 echo('<table class="tablareq" border="1" id="addreq_'.$criterio.'" width="95%"><tr class="TituloDetalles"><td>Detalle</td>
	       <td  width="20">Cantidad</td></tr>');
	   do{
	     $i++;
		   $estilo='SB';
		   if($i%2==0){
		    $estilo='SB2';
		   }
	      echo('<tr class="'.$estilo.'"><td id="tddescrip_'.$row_RsDetallesReq["CODIGO"].'">&nbsp;<input type="checkbox" class="addchkdet" id="chk_'.$row_RsDetallesReq["CODIGO"].'" value="'.$row_RsDetallesReq["CODIGO"].'" name="chk_'.$criterio.'_'.$row_RsDetallesReq["CODIGO"].'">&nbsp;'.$row_RsDetallesReq["DESCRIPCION"].'</td><td align="center" id="tdcanti_'.$row_RsDetallesReq["CODIGO"].'">'.$row_RsDetallesReq["CANTIDAD"].'</td></tr>');
	    }while($row_RsDetallesReq = mysqli_fetch_array($RsDetallesReq));
	 echo('</table>');
	}
 }
?>