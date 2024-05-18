<?php 


//CONEXION A BASE DE DATOS
	require_once('conexion/db.php');
	
	
$query_RsArea="SELECT count(derecons) detalles FROM `detalle_requ`,requerimientos where dererequ=requcodi and `REQUFEEN` > '2014-12-31 00:00:00'";
	$RsArea = mysqli_query($conexion,$query_RsArea) or die(mysqli_connect_error());
	$row_RsArea = mysqli_fetch_array($RsArea);
    $totalRows_RsArea = mysqli_num_rows($RsArea);
	
	
	$query_RsArea1="SELECT count(*)total FROM `requerimientos` WHERE `REQUFEEN` > '2014-12-31 00:00:00'";
	$RsArea1 = mysqli_query($conexion,$query_RsArea1) or die(mysqli_connect_error());
	$row_RsArea1 = mysqli_fetch_array($RsArea1);
    $totalRows_RsArea1 = mysqli_num_rows($RsArea1);
	
	$query_RsArea2="SELECT count(derecons)total FROM `detalle_requ` where dereconv <> 0";
	$RsArea2 = mysqli_query($conexion,$query_RsArea2) or die(mysqli_connect_error());
	$row_RsArea2 = mysqli_fetch_array($RsArea2);
    $totalRows_RsArea2 = mysqli_num_rows($RsArea2);
	
		$query_RsArea3="SELECT count(*)total FROM `detalle_requ`,requerimientos where dererequ=requcodi and `REQUFEEN` > '2014-12-31 00:00:00' and dereapro=8";
	$RsArea3 = mysqli_query($conexion,$query_RsArea3) or die(mysqli_connect_error());
	$row_RsArea3 = mysqli_fetch_array($RsArea3);
    $totalRows_RsArea3 = mysqli_num_rows($RsArea3);
	
	
		$query_RsArea4="SELECT count(*)total FROM `detalle_requ` where dereapro=3 ORDER BY `DERECONV` ASC";
	$RsArea4 = mysqli_query($conexion,$query_RsArea4) or die(mysqli_connect_error());
	$row_RsArea4 = mysqli_fetch_array($RsArea4);
    $totalRows_RsArea4 = mysqli_num_rows($RsArea4);
	
	$query_RsArea5="SELECT * FROM `detalle_requ`,requerimientos where dererequ=requcodi and `REQUFEEN` > '2014-12-31 00:00:00' and dereapro=1";
	$RsArea5 = mysqli_query($conexion,$query_RsArea5) or die(mysqli_connect_error());
	$row_RsArea5 = mysqli_fetch_array($RsArea5);
    $totalRows_RsArea5 = mysqli_num_rows($RsArea5);

	$query_RsArea6="SELECT count(*)total FROM `requerimientos` where requfeen > '2015-02-06 00:00:00' and requfeen < '2015-02-23 00:00:00'";
	$RsArea6 = mysqli_query($conexion,$query_RsArea6) or die(mysqli_connect_error());
	$row_RsArea6 = mysqli_fetch_array($RsArea6);
    $totalRows_RsArea6 = mysqli_num_rows($RsArea6);	
	
	$query_RsArea7="SELECT count(*)total FROM `requerimientos` where requfeen > '2015-03-06 00:00:00' and requfeen < '2015-03-24 00:00:00'";
	$RsArea7 = mysqli_query($conexion,$query_RsArea7) or die(mysqli_connect_error());
	$row_RsArea7 = mysqli_fetch_array($RsArea7);
    $totalRows_RsArea7 = mysqli_num_rows($RsArea7);
	
	$query_RsArea8="SELECT count(*)total FROM `requerimientos` where requfeen > '2015-03-31 00:00:00' and requfeen < '2015-04-27 00:00:00'";
	$RsArea8 = mysqli_query($conexion,$query_RsArea8) or die(mysqli_connect_error());
	$row_RsArea8 = mysqli_fetch_array($RsArea8);
    $totalRows_RsArea8 = mysqli_num_rows($RsArea8);
	
	$query_RsArea9="SELECT count(*)total FROM `requerimientos` where requfeen > '2015-05-08 00:00:00' and requfeen < '2015-05-25 00:00:00'";
	$RsArea9 = mysqli_query($conexion,$query_RsArea9) or die(mysqli_connect_error());
	$row_RsArea9 = mysqli_fetch_array($RsArea9);
    $totalRows_RsArea9 = mysqli_num_rows($RsArea9);
	
	$query_RsArea10="SELECT count(*)total FROM `requerimientos` where requfeen > '2015-06-05 00:00:00' and requfeen < '2015-07-27 00:00:00'";
	$RsArea10 = mysqli_query($conexion,$query_RsArea10) or die(mysqli_connect_error());
	$row_RsArea10 = mysqli_fetch_array($RsArea10);
    $totalRows_RsArea10 = mysqli_num_rows($RsArea10);
	
	$query_RsArea11="SELECT count(*)total FROM `requerimientos` where requfeen > '2015-08-06 00:00:00' and requfeen < '2015-08-24 00:00:00'";
	$RsArea11 = mysqli_query($conexion,$query_RsArea11) or die(mysqli_connect_error());
	$row_RsArea11 = mysqli_fetch_array($RsArea11);
    $totalRows_RsArea11 = mysqli_num_rows($RsArea11);
	
	$query_RsArea12="SELECT count(*)total FROM `requerimientos` where requfeen > '2015-09-05 00:00:00' and requfeen < '2015-09-28 00:00:00'";
	$RsArea12 = mysqli_query($conexion,$query_RsArea12) or die(mysqli_connect_error());
	$row_RsArea12 = mysqli_fetch_array($RsArea12);
    $totalRows_RsArea12 = mysqli_num_rows($RsArea12);
	
	$query_RsArea13="SELECT count(*)total FROM `requerimientos` where requfeen > '2015-10-09 00:00:00' and requfeen < '2015-10-26 00:00:00'";
	$RsArea13 = mysqli_query($conexion,$query_RsArea13) or die(mysqli_connect_error());
	$row_RsArea13 = mysqli_fetch_array($RsArea13);
    $totalRows_RsArea13 = mysqli_num_rows($RsArea13);
	
	$query_RsArea14="SELECT count(*)total FROM `requerimientos` where requfeen > '2015-11-07 00:00:00' and requfeen < '2015-11-23 00:00:00'";
	$RsArea14 = mysqli_query($conexion,$query_RsArea14) or die(mysqli_connect_error());
	$row_RsArea14 = mysqli_fetch_array($RsArea14);
    $totalRows_RsArea14 = mysqli_num_rows($RsArea14);
	
	
	
?>
<table>
<tr>
<td><h1>Total Requerimientos:</h1></td> <td><h1><?php echo($row_RsArea1['total']);?></h1></td>
</tr>
<tr>
<td><h1>Total Detalles:</h1></td> <td><h1><?php echo($row_RsArea['detalles']);?></h1></td>
</tr>
<tr>
<td><h1></h1></td> <td><h1>Total Recibidos a satisfacion:</h1></td><td><h1><?php echo($row_RsArea3['total']);?></h1></td>
</tr>
<tr>
<td><h1></h1></td> <td><h1>Total Cancelados:</h1></td><td><h1><?php echo($row_RsArea4['total']);?></h1></td>
</tr>
<tr>
<td><h1></h1></td> <td><h1>Total Detalles De Convenio:</h1></td><td><h1><?php echo($row_RsArea2['total']);?></h1></td>
</tr>
<tr>
<td><h1></h1></td> <td><h1>Total Requerimiento fuera de fecha marzo</h1></td><td><h1><?php echo($row_RsArea6['total']);?></h1></td>
</tr>
<tr>
<td><h1></h1></td> <td><h1>Total Requerimiento fuera de fecha abril</h1></td><td><h1><?php echo($row_RsArea7['total']);?></h1></td>
</tr>
<tr>
<td><h1></h1></td> <td><h1>Total Requerimiento fuera de fecha mayo</h1></td><td><h1><?php echo($row_RsArea8['total']);?></h1></td>
</tr>
<tr>
<td><h1></h1></td> <td><h1>Total Requerimiento fuera de fecha junio</h1></td><td><h1><?php echo($row_RsArea9['total']);?></h1></td>
</tr>
<tr>
<td><h1></h1></td> <td><h1>Total Requerimiento fuera de fecha julio</h1></td><td><h1><?php echo($row_RsArea10['total']);?></h1></td>
</tr>
<tr>
<td><h1></h1></td> <td><h1>Total Requerimiento fuera de fecha agosto</h1></td><td><h1><?php echo($row_RsArea11['total']);?></h1></td>
</tr>
<tr>
<td><h1></h1></td> <td><h1>Total Requerimiento fuera de fecha septiembre</h1></td><td><h1><?php echo($row_RsArea12['total']);?></h1></td>
</tr>
<tr>
<td><h1></h1></td> <td><h1>Total Requerimiento fuera de fecha octubre</h1></td><td><h1><?php echo($row_RsArea13['total']);?></h1></td>
</tr>
<tr>
<td><h1></h1></td> <td><h1>Total Requerimiento fuera de fecha noviembre</h1></td><td><h1><?php echo($row_RsArea14['total']);?></h1></td>
</tr>
</table>