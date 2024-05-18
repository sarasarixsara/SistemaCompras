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
$des_proveedor	='gilberto rodriguez';
$des_detalle	='servicio profesional';

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
		 
			<h1>Propuesta de Servicios - <?php echo($des_proveedor); ?></h1>
         	<div class="row">
         		<div class="col-lg-12">
         			<?php echo($des_detalle); ?>
         		</div>	
         	</div>
         	<div class="row"> 
         		<div class="form-group col-xs-12">
         			  <label>Objetivo</label>
                      <textarea class="form-control" rows="3" name="justi_ns" id="justi_ns"></textarea>
         		</div>
         		<div class="form-group col-xs-12">
         			  <label>Alcance</label>
                      <textarea class="form-control" rows="3" name="justi_ns" id="justi_ns"></textarea>
         		</div>
         		<div class="form-group col-xs-12">
         			  <label>Actividades - Operaciones</label>
                      <textarea class="form-control" rows="3" name="justi_ns" id="justi_ns"></textarea>
         		</div>	
         		<div class="form-group col-xs-12">
         			  <label>Metodologia</label>
                      <textarea class="form-control" rows="3" name="justi_ns" id="justi_ns"></textarea>
         		</div>
         		<div class="form-group col-xs-12">
         			  <label>Procedimientos</label>
                      <textarea class="form-control" rows="3" name="justi_ns" id="justi_ns"></textarea>
         		</div>
         		<div class="form-group col-xs-12">
         			  <label>Cronograma de tiempo</label>
                      <textarea class="form-control" rows="3" name="justi_ns" id="justi_ns"></textarea>
         		</div>
         		<div class="form-group col-xs-12">
         			  <label>Aspectos Economicos</label>
                      <textarea class="form-control" rows="3" name="justi_ns" id="justi_ns"></textarea>
         		</div>
         		<div class="form-group col-xs-12">
         			 <input type="submit" class="btn btn-default" id="btnsub_ns"    value="guardar"  />
					<input type="button" class="btn btn-default" name="limpiar_ns" value="Limpiar"  />
         		</div>
         		
							

            </div>

         </div>
        </div>
        </div> 
	</body>
</html>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
	
	 <!-- mensajes para validar campos -->
	<script src="messages.js" type="text/javascript"></script>		