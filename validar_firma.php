<?php 
require_once('conexion/db.php');

$variable=$_GET['key'];
	
	$query_RsConsulta=" SELECT FIRMCODI CODIGO,
							   FIRMDOCU DOCUMENTO,
							   FIRMFECH,
							   FIRMPERS,
							   FIRMCMD5
						FROM firmas F,
						     orden_compra O
					    WHERE F.FIRMCONS=".$variable." 
						AND F.FIRMCONS=O.ORCOFIRM ";
						//echo($query_RsConsulta);
	$RsConsulta = mysqli_query($conexion,$query_RsConsulta) or die(mysqli_connect_error());
	$row_RsConsulta = mysqli_fetch_array($RsConsulta);
    $totalRows_RsConsulta = mysqli_num_rows($RsConsulta);
	
	

?>

<style type="text/css">

.info{
	color: #151313 !important;
	font-size: 30px;
  background: #F4EBEA;
  padding: 200px 6px;
  border: solid 1px #B8A1A1;
  border-radius: 5px;
  box-shadow: 1px 2px 3px 1px #D5D5D5;
  text-align: center;
}
</style>

<?php 
echo ('<div class="info">La verificaci&oacute;n a sido exitosa, la clave del documento '.$row_RsConsulta['DOCUMENTO'].' es:<br>
	<FONT SIZE="30">'.$row_RsConsulta['CODIGO'].'</font></div>');
?>