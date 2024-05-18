<?php 
require_once('conexion/db.php'); 
			$query_RsCotizacionesLista ="SELECT C.COTICODI CODIGO,
			                                    C.COTIPROV PROVEEDOR,
												P.PROVNOMB PROVEEDOR_DES,
												DATE_FORMAT(C.COTIFECH, '%d/%m/%Y') FECHA_CREACION,
												DATE_FORMAT(C.COTIFEEN, '%d/%m/%Y') FECHA_ENVIO,
												C.COTIOBSE OBSERVACIONES,
												C.COTIFOPA FORMA_PAGO,
												C.COTIGARA GARANTIA,
												C.COTITIEN TIEMPO_ENTREGA
			                               FROM COTIZACION  C,
										        PROVEEDORES P
										WHERE C.COTIESTA = 1 
										  AND C.COTIPROV = P.PROVCODI
										 ORDER BY C.COTIFECH DESC";
									//echo $query_RsCotizacionesLista;
			$RsCotizacionesLista = mysqli_query($conexion,$query_RsCotizacionesLista) or die(mysqli_error());
			$row_RsCotizacionesLista = mysqli_fetch_array($RsCotizacionesLista);
			$totalRows_RsCotizacionesLista = mysqli_num_rows($RsCotizacionesLista); 
?><!DOCTYPE HTML>
<head>
<title>cotizaciones lista</title>
<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/page.css"/>
	<style type="text/css">
	</style>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
	<script type="text/javascript" src="js/jquery.1.7.2.js"></script>	
<script type="text/javascript">
$(function() {	
  $(".btnfiltro").click(function(evento){
  $("#filtros").toggle();
  //var options = "";
  //$( "#filtros" ).toggle( 'drop', options, 500 );
  });
});
</script>
<style type="text/css">
body{
font-family:verdana;
}
.Titulodet{
 background:#B9CC8A;
 font-weight:bold;
 font-size:17px;
}
</style>
</head>

<form name="form1" id="form1" method="post" action="">
  <?php /*<div id="contenedor" style=" width:967px;  margin: 0 auto; min-height:600px; box-shadow: 1px 1px 3px 1px #BB9696; background:#FCFCFC; border: solid 1px #ccc; border-radius:5px;">
  */?>
    <table width="1000" border="0">
	 <tr class="Titulo1 trtitle">
	  <td>Codigo</td>
	  <td>Proveedor</td>
	  <td>Observaciones</td>
	  <td>garantia</td>
	  <td>tiempo de entrega</td>
	 </tr>
	 <?php
		if($totalRows_RsCotizacionesLista >0){
		 $i=0;
		  do{
		   $i++;
		   $estilo ="SB";
		   if($i%2==0){
		     $estilo ="SB2";
		   }
		  ?>
	 <tr class="<?php echo($estilo);?>">
	     <td><a target="_blank" href="comparar.php?cot=<?php echo($row_RsCotizacionesLista['CODIGO']);?>"><?php echo($row_RsCotizacionesLista['CODIGO']);?></a></td>
	     <td><?php echo($row_RsCotizacionesLista['PROVEEDOR_DES']);?></td>
	     <td><?php echo($row_RsCotizacionesLista['OBSERVACIONES']);?></td>
	     <td><?php echo($row_RsCotizacionesLista['GARANTIA']);?></td>
	     <td><?php echo($row_RsCotizacionesLista['TIEMPO_ENTREGA']);?></td>
	 </tr>
	 <tr>
	    <td colspan="12">
		 <table width="90%" align="center">
		  <tr class="Titulodet">
		   <td align="center" colspan="12">Detalles</td>
		  </tr>
		  <tr class="SLAB trtitle">
		    <td>Valor</td>
			<td>Detalle</td>
			<td>Descripcion prov</td>
		  </tr>
		  <?php
		    $query_RsListaDetalles = "SELECT C.CODECODI CODIGO,
			                           C.CODECOTI CODIGO_COTIZACION,
									   C.CODEDETA CODIGO_DETALLE,
									   C.CODEVALO VALOR,
									   C.CODEDESC DESCRIPCION_PROV,
									   D.DEREDESC DESCRIPCION
								  FROM COTIZACION_DETALLE C,
								       DETALLE_REQU       D
								WHERE C.CODEDETA = D.DERECONS
								  AND C.CODECOTI = '".$row_RsCotizacionesLista['CODIGO']."'";
            $RsListaDetalles = mysqli_query($conexion,$query_RsListaDetalles) or die(mysqli_error($conexion));
			$row_RsListaDetalles = mysqli_fetch_array($RsListaDetalles);
			$totalRows_RsListaDetalles = mysqli_num_rows($RsListaDetalles);
			
			if($totalRows_RsListaDetalles >0){
			 $k=0;
			 do{
			   $k++;
			   $estilod="SB";
			   if($k%2==0){
			    $estilod ="SB2";
			   }
			 ?>
			 <tr class="<?php echo($estilod);?>">
			   <td><?php echo($row_RsListaDetalles['VALOR']);?></td>
			   <td><?php echo($row_RsListaDetalles['DESCRIPCION']);?></td>
			   <td><?php echo($row_RsListaDetalles['DESCRIPCION_PROV']);?></td>
			 </tr>
			 <?php
			   }while($row_RsListaDetalles = mysqli_fetch_array($RsListaDetalles));
			}
		  ?>
		 </table>
		</td>
	 </tr>
		  <?php
		   }while($row_RsCotizacionesLista = mysqli_fetch_array($RsCotizacionesLista));
		}
	 ?>
	</table>
  <?php /*</div> */ ?>
</form>
</html>