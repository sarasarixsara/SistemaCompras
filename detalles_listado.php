<?php 
//Conexion de la base de datos
	require_once('conexion/db.php');

//consulta de detalles por autorizar
	$query_RsDetalles="SELECT 	`DERECONS`, 
								`DEREMODA`,
								`DERECLAS`, 
								`DEREDESC`, 
								`DERECANT`, 
								`DEREJUST`, 
								`DEREOBSE`, 
								`DERETISE`, 
								`DEREREQU`, 
								`DEREAPRO`, 
								`DEREUNME`, 
								`DERECOSU`, 
								`DERECOTE`, 
								`DERECOIN`, 
								`DEREPOA`, 
								`DERESUPO`, 
								`DEREOTRO`, 
								`DEREREOT`, 
								`DEREPRES`, 
								`DERETIPO`, 
								`DERECOOC`, 
								`DEREIOCC`, 
								`DERECOMC`, 
								`DERENCOT`, 
								`DEREPROV`, 
								`DERECONV`, 
								`DEREMPAS`, 
								`DEREPROV2`, 
								`DEREDCOM`, 
								`DERECARE`, 
								`DEREFIRM`, 
								`DEREFFDA`, 
								`DEREFFRE`, 
								`DEREPRRE`, 
								`DEREFERE`, 
								`DEREFRSO`, 
								`DEREFOCR`, 
								`DEREFEMU`, 
								`DEREFPCO`, 
								`DEREFECO`, 
								`DEREFEEC`, 
								`DEREFERC`, 
								`DEREFEAP`, 
								`DEREFEPA`, 
								`DEREFEEO`, 
								`DEREFEOC`, 
								`DEREFOAP` 
						FROM 	`detalle_requ` 
						WHERE 	`DEREAPRO` IN (6,11,22)
									  ";
						  //echo($query_RsDetalles);	 
	
    $RsDetalles = mysqli_query($conexion,$query_RsDetalles) or die(mysqli_error($conexion));
	$row_RsDetalles = mysqli_fetch_array($RsDetalles);
	$totalRows_RDetalles = mysqli_num_rows($RsDetalles);

//Repetir los detalles mientras hallan detalles en la consulta	
?>
<style type="text/css" media="all">
@import "thickbox.css";
</style>

<link rel="stylesheet" type="text/css" href="css/estilo_solicitud.css" />
<link rel="stylesheet" href="chosen/chosen.min.css" />
<script src="js/thickbox.js" type="text/javascript"></script>
<script src="chosen/chosen.jquery.min.js" type="text/javascript"></script>


			<table class="bordered" style="clear:both; padding-bottom:20px; margin-top:30px; min-width:950px; width:100%">
			<thead>
					 <tr>
							<th colspan="19"><div id="footer"><span>Listado detalle </span></div></th>
					 </tr>
					 <tr class="TituloDetalles">
							<th colspan="3">Acci&oacute;n</th>
							<th>#</th>
							<th>Descripci&oacute;n</th>							
							<th width="15">Cant</th>							
							<th width="15">Und</th>
							<th>Justificacion</th>
                            <th>Observaci&oacute;n</th>
                            <th>Poa</th>								
							<th>Proveedor</th>
							<th> Descripcion Proveedor</th>																		
							<th>Presupuesto</th>
							<th>Factura</th>
							<th>Orden</th>
							<th>Anexo</th>
							<th width="30">..</th>
					 </tr>
			 </thead>
<?php			 
//Repetir los detalles mientras hallan detalles en la consulta	
			 $i=0;
	//validar el no vacio
	if ($totalRows_RDetalles  > 0) 
	{		
		//ciclo repetitivo
		do{
?>			
			<tr>
			<td>

			<td>	
			</tr>	
			</table>
<?php 			
           
		   }while($row_RsDetalles = mysqli_fetch_array($RsDetalles));
    }

?>
