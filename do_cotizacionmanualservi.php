<?php
//conexion de base de datos 
require_once('conexion/db.php');

// control de acceso correcto
if (!isset($_SESSION)) {
      session_start();
    }
if(!isset($_SESSION['MM_AccesoCorrectoApp']) || $_SESSION['MM_AccesoCorrectoApp'] != 'ACTIVO'){
  header("location: index.php");
}


//definicion de variables
//orden=190&proveedor=93&cotizacion=312
$orden			=$_GET['orden'];
$proveedor		=$_GET['proveedor'];
$cotizacion		=$_GET['cotizacion'];



//Consulta de detalles
$query_RsConsultarDetalles = "SELECT 	
										`DERECONS` 	DETALLE_CODIGO, 
										`DEREMODA`,
										`DERECLAS`, 
										`DEREDESC` 	DETALLE_DES, 
										`DERECANT`	DETALLE_CANTIDAD, 
										`DEREJUST` 	DETALLE_JUSTIFICACION, 
										`DEREOBSE`	DETALLE_OBSERVACION, 
										`DERETISE`, 
										`DEREREQU`, 
										`DEREAPRO`, 
										`DEREUNME` DETALLE_UNIDAD,
										(SELECT UNMENOMB
										 FROM unidad_medida U
										 WHERE U.UNMECONS=D.DEREUNME)DETALLE_UNIDAD_DES, 
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
										`DEREFOAP`,
										
										`COTICODI`,
										`COTIORDE`, 
										`COTIPROV`, 
										`COTIFECH`, 
										`COTIFEEN`, 
										`COTITIEN2`, 
										`COTIESTA`, 
										`COTIPERS`, 
										`COTIOBSE`, 
										`COTIFOPA`, 
										`COTIGARA`, 
										`COTITIEN`, 
										`COTITOTA`, 
										`COTISIEN`, 
										`COTIFORE`, 
										`COTIFLET`, 
										`COTIVAAG`,
										
										`CODECODI` COTDETA_CODIGO,
										`CODECOTI`, 
										`CODEDETA`, 
										`CODEVALO`, 
										`CODEDESC`, 
										`CODEVAIV`, 
										`CODEVIVA`, 
										`CODECAPR`, 
										`CODEPREC`,

										`PROVCODI`,
										`PROVREGI`,
										`PROVNOMB` PROVEEDOR_DES,
										`PROVTELE`, 
										`PROVPWEB`, 
										`PROVDIRE`, 
										`PROVCON1`, 
										`PROVTEC1`, 
										`PROVCCO1`, 
										`PROVCON2`, 
										`PROVTEC2`, 
										`PROVCCO2`, 
										`PROVCOME`, 
										`PROVPERE`, 
										`PROVFERE`, 
										`PROVESTA`, 
										`PROVCORR`, 
										`PROVIDCA`, 
										`PROVCALI`, 
										`PROVFAVO`, 
										`PROVCONV`, 
										`PROVIDCI`

								FROM 	`detalle_requ` D,
										`cotizacion`,
										`cotizacion_detalle`,
										`proveedores`

								WHERE 	`COTICODI`='".$cotizacion."'
								AND 	`PROVCODI`='".$proveedor."'
								AND 	`CODEDETA`=`DERECONS`
								AND 	`CODECOTI`=`COTICODI`
								AND     `PROVCODI`=`COTIPROV`
											  
										
									  
		                                 ";
								//echo($query_RsConsultarDetalles);
		$RsConsultarDetalles = mysqli_query($conexion,$query_RsConsultarDetalles) or die(mysqli_connect_error());
		$row_RsConsultarDetalles = mysqli_fetch_array($RsConsultarDetalles);
		$totalRows_RsConsultarDetalles = mysqli_num_rows($RsConsultarDetalles);

		$proveedor_des 		=$row_RsConsultarDetalles['PROVEEDOR_DES'];
		$cotizacion_detalle	=$row_RsConsultarDetalles['COTDETA_CODIGO'];


?>
<!DOCTYPE html>
<html>
	
	<head>
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <meta name="description" content="">
	    <meta name="author" content="">

	    <title>Compras - Servicios</title>

	    <!-- Bootstrap Core CSS -->
	    <link href="css/bootstrap.min.css" rel="stylesheet">	  
	  
	    <!-- Mesajes para validar -->
		<link rel="stylesheet" type="text/css" href="messages.css"/>
		
		 <!-- jQuery -->
	    <script src="js/jquery.min.js"></script>	
		
		<script src="js/thickbox.js" type="text/javascript"></script>
	</head>
	<body> 
		<div id="wrapper">	
		 	<div id="page-wrapper">
            	<div class="container-fluid">
            		<form name="form1" id="form1" method="post" action="">
                		<div class="row"> 
                			<div class="col-lg-12">	
                				<h2>PROPUESTA DE PRESTACION DE SERVICIOS </h2>
                			</div>  
                			<div class="col-lg-12">	                	
                				<h3>PROVEEDOR: <?php echo($proveedor_des); ?></h3>
                			</div> 	
		        		</div>
						<div class="row">						
                    		<div class="col-lg-12">                        
                        		<div class="table-responsive">
                            		<table class="table table-hover table-striped">
                                		<thead>
	                                    	<tr>
		                                        <th>Items			</th>
		                                        <th>Descripcion 	</th>
		                                        <th>Unidad 			</th>
		                                        <th>Cantidad 		</th>
		                                        <th>Justificacion 	</th>
		                                        <th>Observaciones 	</th>                                        						
	                                    	</tr>
                                		</thead>
								        <tbody>
											<?php
												$i = 0;
												if ($totalRows_RsConsultarDetalles > 0) 
													{ // recorrer si no esta vacia
								 						do {
									  							$i++;
									  		?>
																<tr>
																	<td><?php echo($i); ?>													</td>
																	<td><?php echo($row_RsConsultarDetalles['DETALLE_DES']); ?>				</td>
																	<td><?php echo($row_RsConsultarDetalles['DETALLE_UNIDAD_DES']); ?>		</td>
																	<td><?php echo($row_RsConsultarDetalles['DETALLE_CANTIDAD']); ?>		</td>
																	<td><?php echo($row_RsConsultarDetalles['DETALLE_JUSTIFICACION']); ?>	</td>
																	<td><?php echo($row_RsConsultarDetalles['DETALLE_OBSERVACION']); ?>		</td>
																</tr>	                                    
											<?php 			} while ($row_RsConsultarDetalles = mysqli_fetch_array($RsConsultarDetalles));
													}
											?>
                                    	</tbody>
                            		</table>
                        		</div>
                    		</div>
                           	<div class="form-group col-xs-12">
                            	<label>Objetivo</label>
                                <textarea class="form-control" rows="3" name="objetivo_<?php echo($cotizacion_detalle); ?>" id="objetivo_<?php echo($cotizacion_detalle); ?>"></textarea>
                            </div>
                            <div class="form-group col-xs-2">
                                <label>Unidad</label>
                                <select class="form-control" name="unidad_<?php echo($cotizacion_detalle); ?>" id="unidad_<?php echo($cotizacion_detalle); ?>" >
                                	<option value="">Seleccione...</option>
										<?php
											require_once("scripts/funcionescombo.php");		
											$estados = dameUnidadMedida();
											foreach($estados as $indice => $registro)
											{
										?>
											<option value="<?php echo($registro['CODIGO'])?>"><?php echo($registro['DESCRIPCION']);?></option>
										<?php
											}
										?>
								</select> 
                            </div>
                            <div class="form-group col-xs-2">
                                <label>Cantidad</label>
                               	<input 	type="text" 
                                		name="cantidad_<?php echo($cotizacion_detalle); ?>" 
                                        id="cantidad_<?php echo($cotizacion_detalle); ?>" 
                                        value=""													
                                        class="form-control"> 
                            </div>
                            <div class="form-group col-xs-2">
                                <label>V/unit</label>
                                <input 	type="text" 	
                               			name="v_unit_<?php echo($cotizacion_detalle); ?>" 		
                               			id="v_unit_<?php echo($cotizacion_detalle); ?>" 
                               			value=""                                         		
                               			class="form-control"/>  
                            </div>
                            <div class="form-group col-xs-2">
                                <label>V/Total</label>
                                <input 	type="text" 	
                               			name="vtotal_<?php echo($cotizacion_detalle); ?>" 		
                               			id="vtotal_<?php echo($cotizacion_detalle); ?>" 
                               			value=""                                         		
                               			class="form-control"/>  
                            </div>                 
                            <div class="form-group col-xs-4">
                                <label>Fecha Inicio</label>
                                <input 	type="date" 	
                               			name="fechaInicio_<?php echo($cotizacion_detalle); ?>" 		
                              			id="fechaInicio_<?php echo($cotizacion_detalle); ?>" 
                               			value=""                                         		
                               			class="form-control"/> 
                            </div>
                            <div class="form-group col-xs-4">
                                <label>Fecha Fin</label>
                                <input 	type="date" 	
                               			name="fechaFin_<?php echo($cotizacion_detalle); ?>" 		
                              			id="fechaFin_<?php echo($cotizacion_detalle); ?>" 
                               			value=""                                         		
                               			class="form-control"/> 
                            </div>
                            <div class="form-group col-xs-12">
                            	 <input type="hidden" name="cod_cotizacion_det" value="<?php echo($cotizacion_detalle);?>" />
                            	 <input type="hidden" name="cod_orden" value="<?php echo($orden);?>" />
                                 <input type="hidden" name="cod_cotizacion" value="<?php echo($cotizacion);?>" />
                            	 
                                <input type="submit" class="btn btn-default" name="limpiar_ns" value="Guardar" onclick="Guardar();">
                            </div>
                       	</div>
               			 <!-- /.row -->
               		</form>	 
				</div>
         	</div>
       	</div>
    </body>
</html>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
	
	 <!-- mensajes para validar campos -->
	<script src="messages.js" type="text/javascript"></script>		
	<script type="text/javascript">
    
    function Guardar(){
    
    if(confirm("Seguro que desea guardar estos valores?")){
      document.form1.action="do_cotizacionserv_guardar.php?tipoguardar=save";
	}else{
		return false;
	}

    }
    
    
	</script>