<?php 
//Conexion de la base de datos
require_once('conexion/db.php');


//Definicion de Variables 
$filtro_estado_data='';
if(isset($_POST['filtro_estado_data']) && $_POST['filtro_estado_data'] !=''){
$filtro_estado_data = $_POST['filtro_estado_data'];
}

//Definir variables de paginador
	$currentPage = $_SERVER["PHP_SELF"];
	$tamanoPagina = 15;
	$maxRows_RsListaRequerimientos = $tamanoPagina;
	$pageNum_RsListaRequerimientos = 0;

	if (isset($_GET['pageNum_RsListaRequerimientos'])) {
	  $pageNum_RsListaRequerimientos = $_GET['pageNum_RsListaRequerimientos'];
	}
	$startRow_RsListaRequerimientos = $pageNum_RsListaRequerimientos * $maxRows_RsListaRequerimientos;
	
//consultar estados para los detalles de requerimientos 
   	$query_RsEstados = "SELECT `ESDECODI` CODIGO,
								     `ESDENOMB` NOMBRE,
									 `ESDECOLO` COLOR,
									 `ESDEFLAG`
							   FROM `ESTADO_DETALLE`E 
							   WHERE E.`ESDECODI` 
						 ORDER BY E.`ESDENOMB` ASC
							   ";
	$RsEstados = mysqli_query($conexion,$query_RsEstados) or die(mysqli_error($conexion));
	$row_RsEstados = mysqli_fetch_assoc($RsEstados);
    $totalRows_RsEstados = mysqli_num_rows($RsEstados);
	if($totalRows_RsEstados>0){
		do{
		$arrayestados[] = $row_RsEstados;
		}while($row_RsEstados = mysqli_fetch_array($RsEstados));
	}
//consulta de detalles por autorizar
	$query_RsDetalles="SELECT `DERECONS` CODIGO,	                           
								(SELECT A.AREANOMB 
								 FROM   AREA A,
 								        REQUERIMIENTOS R 
								 WHERE  AREAID=REQUAREA 
								 AND    R.REQUCODI=D.DEREREQU)AREA_DESC,
								(SELECT R2.REQUCODI 
							     FROM   REQUERIMIENTOS R2 
								 WHERE  R2.REQUCODI=D.DEREREQU)CODIGO_REQ,
								 (SELECT R3.REQUFEEN  
							     FROM   REQUERIMIENTOS R3 
								 WHERE  R3.REQUCODI=D.DEREREQU)FECHAENVIO_REQ,
								 SUBSTRING(DEREDESC, 1, 55) DESCRP,
								 DERECANT CANTIDAD,
								 E.ESDECOLO COLOR_ESTADO,
								 E.ESDENOMB ESTADO_DESC
								 
							  FROM detalle_requ D 
							  LEFT JOIN ESTADO_DETALLE E ON D.DEREAPRO = E.ESDECODI
							  WHERE 1	
								And E.ESDECODI != '0'
																			  
						      
						";
						  //echo($query_RsDetalles);	
					  
	if($filtro_estado_data != ""){
	$query_RsDetalles = $query_RsDetalles." and D.DEREAPRO = '".$filtro_estado_data."'";
	}
	
	$query_RsDetalles = $query_RsDetalles." ORDER BY FECHAENVIO_REQ "; 	
	
  //echo($query_RsDetalles);
	

//EJECUCION DE LA CONSULTA DE DETALLES

    $query_limit_RsListaRequerimientos = sprintf("%s LIMIT %d, %d", $query_RsDetalles, $startRow_RsListaRequerimientos, $maxRows_RsListaRequerimientos);
	$RsDetalles = mysqli_query($conexion,$query_limit_RsListaRequerimientos) or die(mysqli_connect_error());
	$row_RsDetalles  = mysqli_fetch_array($RsDetalles);
    $totalRows_RDetalles = mysqli_num_rows($RsDetalles);
	
	if (isset($_GET['totalRows_RDetalles'])) {
	  $totalRows_RDetalles = $_GET['totalRows_RDetalles'];
	} else {
	  $all_RsListaRequerimientos = mysqli_query($conexion, $query_RsDetalles);
	  $totalRows_RDetalles = mysqli_num_rows($all_RsListaRequerimientos);
	}
//Repetir los detalles mientras hallan detalles en la consulta	

//EJECUCION DE LA PAGINACION
	$totalPages_RsListaDetalles = ceil($totalRows_RDetalles/$maxRows_RsListaRequerimientos)-1;
	$totalPages_RsListaDetalles = $totalRows_RDetalles;

	$queryString_RsListaRequerimientos = "";
	if (!empty($_SERVER['QUERY_STRING'])) {
	  $params = explode("&", $_SERVER['QUERY_STRING']);
	  $newParams = array();
	  foreach ($params as $param) {
		if (stristr($param, "pageNum_RsListaRequerimientos") == false &&
			stristr($param, "totalPages_RsListaDetalles") == false) {
		  array_push($newParams, $param);
		}
	  }
	  if (count($newParams) != 0) {
		$queryString_RsListaRequerimientos = "&" . htmlentities(implode("&", $newParams));
	  }
	}

	$queryString_RsListaRequerimientos = sprintf("&totalPages_RsListaDetalles=%d%s", $totalRows_RDetalles, $queryString_RsListaRequerimientos);

	$paginaHasta = 0;
	if ($pageNum_RsListaRequerimientos == $totalPages_RsListaDetalles)
	{
		$paginaHasta = $totalPages_RsListaDetalles;
	}
	else
	{
		$paginaHasta = ($pageNum_RsListaRequerimientos+1)*$maxRows_RsListaRequerimientos;
	}
 
?>

<nav class="navbar navbar-light bg-light"> 

<button type="button" class="btn btn-sm btn-outline-success my-2 my-sm-0">
     <!--Inicio Boton imagen devolver -->
		<div  onclick="mostrarTickbox('<?php ?>');" ><span><i class="fa fa-reply fa-lg" aria-hidden="true"></i></span></div>
	 <!--Fin --> Devolver
  </button>
   <button type="button" class="btn btn-sm btn-outline-success my-2 my-sm-0">
    <!--Inicio Boton imagen compra electronica un detalle -->							          
		<div onclick="FMD_Electronica('<?php ?>','<?php ?>','<?php ?>');"><span><i class="fa fa-shopping-cart fa-lg" aria-hidden="true"></i></span></div>
	<!--Fin --> Compra Online
  </button>
     <button type="button" class="btn btn-sm btn-outline-success my-2 my-sm-0">
    <!--Inicio Boton imagen viaticos un detalle -->							          
		<div onclick="FMD_Viaticos('<?php ?>','<?php ?>','<?php ?>');"><span><i class="fa fa-plane fa-lg" aria-hidden="true"></i></span></div>
	 <!--Fin -->Comprar Viaticos
  </button>
  <button type="button" class="btn btn-sm btn-outline-success my-2 my-sm-0">
    <!--Inicio Boton imagen actualizar pagina -->
		<div onclick="location.reload();" ><span><i class="fa fa-refresh fa-lg" aria-hidden="true"></i></span></div>
	<!--Fin -->Actualizar
  </button>
  <button type="button" class="btn btn-sm btn-outline-success my-2 my-sm-0">
    <!--Inicio Boton imagen Historial -->
		<div onclick="ver_historial('<?php ?>');" ><span><i class="fa fa-hourglass-half fa-lg" aria-hidden="true"></i></span></div>
	<!--Fin --> Historial
  </button>
  <button type="button" class="btn btn-sm btn-outline-success my-2 my-sm-0">
     <!--Inicio Boton imagen reiniciar a 0 -->
		<div target="_blank" onclick="FMD_ReiniciarDet('<?php ?>');" ><span><i class="fa fa-power-off fa-lg" aria-hidden="true"></i></span></div>
	 <!--Fin --> Reiniciar
  </button>
  <button type="button" class="btn btn-sm btn-outline-success my-2 my-sm-0">
     <!--Inicio Boton imagen comparativo -->
		<div target="_blank" onclick="location.href=('comparar.php?tipocompara=detalle&codDetalle=<?php ?>')" ><span><i class="fa fa-file fa-lg" aria-hidden="true"></i></span></div>
	<!--Fin --> Comparar
  </button>
  <button type="button" class="btn btn-sm btn-outline-success my-2 my-sm-0">
     <!--Inicio Boton imagen firma director Administrativo -->
		<div><a id="afirmarDirAdm_<?php ?>" href="javascript: FirmaDirAdministrativo('<?php ?>');"></a><span><i class="fa fa-thumbs-up fa-lg" aria-hidden="true"></i></span></div>
	<!--Fin -->Aprobar Proveedor DA
  </button>
	<button type="button" class="btn btn-sm btn-outline-success my-2 my-sm-0">
     <!--Inicio Boton imagen firma director Administrativo -->
		<div><a id="afirmarDirAdm_<?php ?>" href="javascript: FirmaDirAdministrativo('<?php ?>');"></a><span><i class="fa fa-thumbs-up fa-lg" aria-hidden="true"></i></span></div>
	<!--Fin -->Aprobar Coordinador
  </button>
	<button type="button" class="btn btn-sm btn-outline-success my-2 my-sm-0">
     <!--Inicio Boton imagen firma director Administrativo -->
		<div><a id="afirmarDirAdm_<?php ?>" href="javascript: FirmaDirAdministrativo('<?php ?>');"></a><span><i class="fa fa-thumbs-up fa-lg" aria-hidden="true"></i></span></div>
	<!--Fin -->Aprobar Rector
  </button>		
   
  <button type="button" class="btn btn-sm btn-outline-success my-2 my-sm-0">
     <!--Inicio Boton imagen entregado a usuario general -->
		<div  onclick="fentrega('<?php 	?>','0');" ><span><i class="fa fa-check fa-lg" aria-hidden="true"></i></span></div>
	  <!--Fin --> Enregar
  </button>
  <button type="button" class="btn btn-sm btn-outline-success my-2 my-sm-0">
  <!--Inicio Boton imagen Cancelar o Rechazar -->
			<div onclick="CancelarDetalle('<?php ?>');" ><span><i class="fa fa-ban fa-lg" aria-hidden="true"></i></span></div>
    <!--Fin --> Cancelar
  </button>	 
</nav>
<div class="row">
<nav class="navbar navbar-light bg-light"> 

<form class="form-inline" name="form2" id="form2" method="post" action="">
   
	 				<select name="filtro_estado_data" id="filtro_estado_data" class="form-control mr-sm-2">
						<option value=""  >Seleccione Estado Del Detalle...</option>
						<?php 
							for($i=0; $i<count($arrayestados); $i++)
							{
						?>
								<option value="<?php echo($arrayestados[$i]['CODIGO']);?>"><?php echo($arrayestados[$i]['NOMBRE']);?></option>	
						<?php
							}
						?>
	 				</select>
	 <button class="btn btn-sm btn-outline-success my-2 my-sm-0" type="button" id="filtro_estado" value="" onclick="return pasaraestado();" >Buscar</button> 							
 
   
	 				  </form>

</nav>
<form name="form1" id="form1" method="post" action="">
<div><?php if ($totalPages_RsListaDetalles > 0)
									{
							?>
										Mostrando <b><?php echo($startRow_RsListaRequerimientos+1); ?></b> a <b><?php echo($paginaHasta); ?></b> de <b><?php echo($totalPages_RsListaDetalles);
										?></b> Registros
							<?php
									}
									else
									{
							?>
										Mostrando <b>0</b> a <b>0</b> de <b>0</b> Registros
							<?php
									}
							?></div>
<table class="table table-bordered">
  <thead>
    <tr>
	  <th scope="col-sm-1"></th>
      <th scope="col-sm-1">#</th>	  
      <th scope="col-sm-5">Descripci&oacute;n</th>
      <th scope="col-sm-1">Cantidad</th>
	  <th scope="col-sm-2">&Aacute;rea</th>
      <th scope="col-sm-2">Fecha</th> <?php // Esta fecha hace referencia al campo fecha de envio del requerimiento ?>
    </tr>
  </thead>
  <tbody>
<?php  
  do{
	?>			
			
  
    <tr>
	   
      <th scope="row">
	  <div><input type="checkbox" class="multiplefilechk" value="<?php echo($row_RsDetalles['CODIGO']); ?>" name="multiplefile_<?php echo($row_RsDetalles['CODIGO']); ?>" id="multiplefile_<?php echo($row_RsDetalles['CODIGO']); ?>"></div>
	  </th>
	  <td><a href="home.php?page=solicitud&codreq=<?php echo($row_RsDetalles['CODIGO_REQ']); ?>" class="btn btn-link" role="button"><?php echo($row_RsDetalles['CODIGO']); ?></a></td>
      <td><?php echo($row_RsDetalles['DESCRP']); ?></td>
      <td><?php echo($row_RsDetalles['CANTIDAD']); ?></td>
	  <td><?php echo($row_RsDetalles['AREA_DESC']); ?></td>
	  <td bgcolor="<?php echo($row_RsDetalles['COLOR_ESTADO']); ?>" title="<?php echo($row_RsDetalles['ESTADO_DESC']); ?>" ><?php echo($row_RsDetalles['FECHAENVIO_REQ']); ?></td>
    </tr>    
 
  <?php
  }while($row_RsDetalles = mysqli_fetch_array($RsDetalles));
  ?>
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
									<?php if ($pageNum_RsListaRequerimientos < $totalPages_RsListaDetalles) { // Show if not last page ?>
										 <li>
										  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaRequerimientos=%d%s", $currentPage, min($totalPages_RsListaDetalles, $pageNum_RsListaRequerimientos + 1), $queryString_RsListaRequerimientos); ?>')" class="submenus">Siguiente</a>
										 </li>
										  <?php } // Show if not last page ?>
									<?php if ($pageNum_RsListaRequerimientos < $totalPages_RsListaDetalles) { // Show if not last page ?>
										  <li>
										  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaRequerimientos=%d%s", $currentPage, $totalPages_RsListaDetalles, $queryString_RsListaRequerimientos); ?>')" class="submenus">&Uacute;ltimo</a>
										  </li>
										  <?php } // Show if not last page ?>
										</ul>
									</td>
								  </tr>
						</table>  
   </tbody>
</table>
</form>
</div>
<script type="text/javascript">
//paginación
 function f_abrir_link(v_link)
	{
	  document.form1.action=v_link;
	  document.form1.submit();

    }
	
$(document).ready(function(){

			$("#poa_des").autocomplete({
			source: "buscar.php?tipo=cargarpoa", 				
			minLength: 2,									
			select: function(event, ui){
					 $("#poa_des").val(ui.item.value.nombre);
					 $("#poa").val(ui.item.value.nit);
					 //subpoa();
					 event.preventDefault();
					},
			focus: function(event, ui){
					 $("#poa_des").val(ui.item.value.nombre);
					 event.preventDefault();
					}
		});
	});	
	
function pasaraestado(){
	if($("#filtro_estado_data").val()==''){
		alert("debe ingresar el estado ");
		return false;
	}else{
		document.form2.submit();
	}
		
	 
	
}
</script>	
