<?php 
require_once('conexion/db.php'); 

    $query_RsAsignarCodCotizacion="SELECT P.PARAVALOR+1 VALOR, PARADEFI DEFINICION FROM PARAMETROS P WHERE PARACODI = 5";
	$RsAsignarCodCotizacion = mysqli_query($conexion, $query_RsAsignarCodCotizacion) or die(mysqli_error($conexion));
	$row_RsAsignarCodCotizacion = mysqli_fetch_array($RsAsignarCodCotizacion);
    //$totalRows_RsAsignarCodCotizacion = mysql_num_rows($RsAsignarCodCotizacion);

	$CODIGOCOTIZACION = $row_RsAsignarCodCotizacion['DEFINICION']."-".$row_RsAsignarCodCotizacion['VALOR'];
	
	// consulta de los proveedores existentes
	$query_RsProveedores="SELECT P.PROVCODI CODIGO,
	                             P.PROVNOMB NOMBRE,
								 P.PROVPWEB WEB, 
								 P.PROVFAVO FAVORITO
						    FROM PROVEEDORES P
						  ORDER BY PROVNOMB";
	$RsProveedores = mysqli_query($conexion,$query_RsProveedores) or die(mysqli_error($conexion));
	$row_RsProveedores = mysqli_fetch_array($RsProveedores);
    $totalRows_RsProveedores = mysqli_num_rows($RsProveedores);	
	
	if($totalRows_RsProveedores>0){
	  do{
	  //carga el array con los proveedores clasificados 
	     $arrcategorias = array();
		$query_RsCategorias="SELECT  P.PRCLCODI CODIGO,
									 P.PRCLCLAS CLASIFICACION,
									 P.PRCLCALI CALIFICACION,
									 C.CLASNOMB CLASIFICACION_DES
								FROM PROVEEDOR_CLASIFICACION P,
								     CLASIFICACION C
							 WHERE  P.PRCLCLAS = C.CLASCODI
							    AND P.PRCLPROV = '".$row_RsProveedores["CODIGO"]."'";
								//echo($query_RsCategorias);
		$RsCategorias = mysqli_query($conexion,$query_RsCategorias) or die(mysqli_error($conexion));
		$row_RsCategorias = mysqli_fetch_array($RsCategorias);
		$totalRows_RsCategorias = mysqli_num_rows($RsCategorias);
		  }while($row_RsProveedores = mysqli_fetch_array($RsProveedores));
		  }
?>
<!doctype html>
<html ng-app>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="chrome=1">
  <title>Bootstrap-modal by jschr</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
  <link href="http://getbootstrap.com/2.3.2/assets/css/bootstrap.css" rel="stylesheet" />
  <link href="http://getbootstrap.com/2.3.2/assets/css/bootstrap-responsive.css" rel="stylesheet" />
  <link href="css/bootstrap-modal.css" rel="stylesheet" />

</head>

<body role="document" >
<div class="container theme-showcase" role="main">
				<ul class="nav nav-tabs">
				<li class="active"><a href="#">Solicitar Cotizacion</a></li>
				<li><a href="#">Perfil</a></li>
				<li><a href="#">Mensajes</a></li>
				</ul>
			    <div class="jumbotron">
				<form><div class="text-center">
				   <span class="label label-info"><?php echo($CODIGOCOTIZACION);?></span>
				   </div>
         <div class="container" style="position: relative">
          <div class="long" style="position: relative; overflow: hidden">
            
            <button class="btn btn-primary btn-lg" data-toggle="modal" href="#long">Proveedor</button>
			<!--<a class="btn btn-primary btn-lg" role="button">Guardar</a>-->
          
          </div>
		    </div>	 
               </form>
			   </div>
<div id="long" class="modal hide fade" tabindex="-1" data-replace="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>SELECCIONAR PROVEEDOR</h3>
  </div>
  <div class="modal-body" ng-controller="ProveedoresController">
    <button class="btn" data-toggle="modal" href="#notlong" style="position: absolute; top: 50%; right: 12px">Not So Long Modal</button>
    <!--<img style="height: 800px" src="http://i.imgur.com/KwPYo.jpg" />-->
		<div ng-repeat="proveedor in Proveedores">
		<div class="btn-group">
		  <button class="btn">{{proveedor.clasificado}}</button>
		  <button class="btn dropdown-toggle" data-toggle="dropdown">
			<span class="caret"></span>
		  </button>
		  <ul class="dropdown-menu">
			<!-- dropdown menu links -->
		  </ul>
		</div>
	</div>
  </div>
  <div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn">Close</button>
  </div>
</div>


    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script src="http://getbootstrap.com/2.3.2/assets/js/bootstrap.js"></script>
    <script src="js/bootstrap-modalmanager.js"></script>
    <script src="js/bootstrap-modal.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js" type="text/javascript"> </script>
    <script src="cotizar2.js" type="text/javascript"> </script>


</div>   
</body>
</html>
