<?php
//inicio del php
require_once('conexion/db.php');
$search_usuario = '';
if(isset($_POST['search_usuario']) && $_POST['search_usuario'] !=''){
	$search_usuario = $_POST['search_usuario']; 
}
$search_rol = '';
if(isset($_POST['search_rol']) && $_POST['search_rol'] !=''){
	$search_rol = $_POST['search_rol']; 
}
$search_area = '';
if(isset($_POST['search_area']) && $_POST['search_area'] !=''){
	$search_area = $_POST['search_area']; 
}

$currentPage = $_SERVER["PHP_SELF"];
$tamanoPagina = 50;
$maxRows_RsListadoPersona = $tamanoPagina;
$pageNum_RsListadoPersona = 0;



if (isset($_GET['pageNum_RsListadoPersona'])) {
  $pageNum_RsListadoPersona = $_GET['pageNum_RsListadoPersona'];
}
$startRow_RsListadoPersona = $pageNum_RsListadoPersona * $maxRows_RsListadoPersona;


$query_RsListadoPersona = "SELECT USUALOG CODIGO,
 								  USUACODI CODE,
								  USUAESTA ESTADO_USER,
								  USUAROL ROL_ID,
								  ROLNOMB ROL_NOMBRE,
								  PERSNOMB NOMBRE,
								  PERSAPEL APELLIDO,
								  PERSUSUA USUARIO,
								  PERSEST ESTADO,
								  AREANOMB AREA,
								  POANOMB POA,
								  ARPOID ID_AREA_POA
							FROM PERSONAS,
								 USUARIOS,
								 ROLES,
								 AREA_POA,
								 POA,
								 AREA
						 WHERE   PERSUSUA = USUALOG
						 AND     ROLCODI=USUAROL
						 AND     USUACODI= ARPOIDUS
						 AND     AREAID=ARPOIDAR 
						 AND     POACODI=ARPOIDPO
							";

			

if($search_usuario != ''){ 
	$query_RsListadoPersona = $query_RsListadoPersona. " AND (PERSNOMB LIKE '%".$search_usuario."%' or PERSAPEL LIKE '%".$search_usuario."%')";	
}
if($search_rol != ''){ 
	$query_RsListadoPersona = $query_RsListadoPersona. " AND USUAROL = '".$search_rol."'";	
}
if($search_area != ''){ 
	$query_RsListadoPersona = $query_RsListadoPersona. " AND AREAID = '".$search_area."'";	
}
//echo($query_RsListadoPersona);echo("<br>");
	$query_limit_RsListadoPersona = sprintf("%s LIMIT %d, %d", $query_RsListadoPersona, $startRow_RsListadoPersona, $maxRows_RsListadoPersona);	
    $RsListadoPersona = mysqli_query($conexion, $query_limit_RsListadoPersona) or die(mysqli_error($conexion));
    $row_RsListadoPersona = mysqli_fetch_array($RsListadoPersona);	
	

if (isset($_GET['totalRows_RsListadoPersona'])) {
  $totalRows_RsListadoPersona = $_GET['totalRows_RsListadoPersona'];
} else {
  $all_RsListadoPersona = mysqli_query($conexion,$query_RsListadoPersona);
  $totalRows_RsListadoPersona = mysqli_num_rows($all_RsListadoPersona);
}

//if ($maxRows_RsProducto != 0)
$totalPages_RsListadoPersona = ceil($totalRows_RsListadoPersona/$maxRows_RsListadoPersona)-1;
//else
//$totalPages_RsProducto = ceil($totalRows_RsProducto/1)-1;

$queryString_RsListadoPersona = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_RsListadoPersona") == false &&
        stristr($param, "totalRows_RsListadoPersona") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_RsListadoPersona = "&" . htmlentities(implode("&", $newParams));
  }
}

$queryString_RsListadoPersona = sprintf("&totalRows_RsListadoPersona=%d%s", $totalRows_RsListadoPersona, $queryString_RsListadoPersona);

$paginaHasta = 0;
if ($pageNum_RsListadoPersona == $totalPages_RsListadoPersona)
{
	$paginaHasta = $totalRows_RsListadoPersona;
}
else
{
	$paginaHasta = ($pageNum_RsListadoPersona+1)*$maxRows_RsListadoPersona;
}

$query_RsRolesLista = "SELECT R.ROLCODI CODIGO,
															R.ROLNOMB NOMBRE
											FROM ROLES R where ROLCODI != 7";
$RsRolesLista = mysqli_query($conexion,$query_RsRolesLista) or die(mysqli_error($conexion));
$row_RsRolesLista = mysqli_fetch_array($RsRolesLista);
$totalRows_RsRolesLista = mysqli_num_rows($RsRolesLista);

$query_RsAreaLista = "SELECT R.AREAID CODIGO,
															R.AREANOMB NOMBRE
											FROM AREA R";
$RsAreaLista = mysqli_query($conexion,$query_RsAreaLista) or die(mysqli_error($conexion));
$row_RsAreaLista = mysqli_fetch_array($RsAreaLista);
$totalRows_RsAreaLista = mysqli_num_rows($RsAreaLista);

?>

<!DOCTYPE html>

<html>
<!-- inicio del html -->
<head>

<title>	Gestion Usuarios</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


 <script type="text/javascript">
   function f_abrir_link(v_link)
	{
	  document.form1.action=v_link;
	  document.form1.submit();

    }
	
	  function Feliminar(cod,id){
	  if(confirm('esta seguro de eliminar esta informacion?')){
       document.form1.action='home.php?page=administrar_guardar&tipoGuardar=Eliminar&cod_eliminar='+cod+'&cod_usuario='+id;
	   document.form1.submit();
	   }
	  }
	  
	   function Feditar(cod){
      
       document.form1.action='home.php?page=administrar&tipoGuardar=Editar&cod_editar='+cod;
	   document.form1.submit();
	  }

		function BuscarUsuario(){
			document.form1.action='home.php?page=listar_usuarios';
	   document.form1.submit();			
		}

  $(function() {
    $('.onoff').change(function() {
			id = $(this).attr('id');
			//id = id.split('_');
     //console.log($('#onff_'+id[1]).html('Toggle: ' + $(this).prop('checked')));
		 console.log($(this).prop('checked'));

    var date = new Date();
	  var timestamp = date.getTime();
	 	 $.ajax({
			type: "POST",
			url: "tipo_guardar.php?tipoGuardar=usuarioOnOff&user="+$(this).attr('data-user')+"&status="+$(this).prop('checked'),
			success : function(r){
				if(r != ''){

				}
			},
			error   : callback_error
		});			

    })
  })
	function callback_error(XMLHttpRequest, textStatus, errorThrown)
{
    alert("Respuesta del servidor "+XMLHttpRequest.responseText);
    alert("Error "+textStatus);
    alert(errorThrown);
} 

       $(document).ready(function()
        {
        //Handles menu drop down`enter code here`
        $('.dropdown-menu').click(function (e) {
           e.stopPropagation();
            });
          });
         </script>
</head>

<body>
<form name="form1" method="post" action="">
<table class="tablalistado" width="800">
    <!--<tr>
	  <input type="button" name="crearnuevo" onclick="location.href='home.php?page=administrar&tipoGuardar=Guardar';" value="Nuevo Usuario" class="button2">
	</tr>-->
	<div class="col-md-12 col-lg-12">
			<div class="row">
				<div class="col-md-6">
				
				<div class="col-md-8" style="padding:0">
				
					<input type="text" class="form-control" placeholder="Busqueda" aria-label="Recipient's username" name="search_usuario" id="search_usuario" value="<?php echo($search_usuario);?>" aria-describedby="basic-addon2">		
					<span class="dropdown-toggle caret_search" data-toggle="dropdown">
								 <i class="fa fa-caret-down fa fa-2x labelgray"></i>
							</span>
							<div class="dropdown-menu col-md-12" role="menu" style="padding:5px;">
								<div class="row" style="padding:5px">
							  <div class="col-md-2 labelgray text-right">Rol</div>
								<div class="col-md-10">

									<select class="form-control" class="form-control" name="search_rol" id="search_rol" >
										<option value="">Seleccione...</option>
										<?php 
										do{
											?>
										<option value="<?php echo($row_RsRolesLista['CODIGO']);?>" <?php if($row_RsRolesLista['CODIGO']==$search_rol){ echo('selected');} ?> ><?php echo($row_RsRolesLista['NOMBRE']);?></option>	
											<?php
										}while($row_RsRolesLista = mysqli_fetch_array($RsRolesLista));
								
										?>
									</select>
								</div>
								</div>
								<div class="row" style="padding:5px">
							  <div class="col-md-2 labelgray text-right">Area</div>
								<div class="col-md-10">

									<select class="form-control" class="form-control" name="search_area" id="search_area" >
										<option value="">Seleccione...</option>
										<?php 
										do{
											?>
										<option value="<?php echo($row_RsAreaLista['CODIGO']);?>" <?php if($row_RsAreaLista['CODIGO']==$search_area){ echo('selected');} ?> ><?php echo($row_RsAreaLista['NOMBRE']);?></option>	
											<?php
										}while($row_RsAreaLista = mysqli_fetch_array($RsAreaLista));
								
										?>
									</select>
								</div>
								</div>
								<div class="text-right">
								<button class="button2 btn-lg " >Buscar</button>
								</div>	
								
							</div>								
				</div>
					<div class="col-md-4 text-left" style="padding:0 0 0 3px">
						<button class="btn btn-outline-secondary" type="button" onclick="BuscarUsuario();"><i class="fa fa-search" ></i></button>
						
					</div>
				</div>				
				<div class="col-md-6 text-right">
				<input type="button" name="crearnuevo" onclick="location.href='home.php?page=administrar&tipoGuardar=Guardar';" value="Nuevo Usuario" class="button2">
				</div>
			</div>
	</div>
<table class="tablalistado" width="100%" style="min-width:800px;">
	<tr>
	 <td colspan="8" class="SLAB" align="right">
	 	<?php
		if ($totalRows_RsListadoPersona > 0)
		{
					?>
		Mostrando <b><?php echo($startRow_RsListadoPersona+1); ?></b> a <b><?php echo($paginaHasta); ?></b> de <b><?php echo($totalRows_RsListadoPersona);
		 ?></b> Registros
		<?php
					}
		else
		{
		?>
		Mostrando <b>0</b> a <b>0</b> de <b>0</b> Registros
		<?php
		}
		?>
	 </td>
	</tr>
    <tr class="SLAB trtitle" align="center">
    <td height="30">Nombres</td>
	<td>Usuario</td>
	<td>Rol</td>
	<td>Area</td>
	<td>Poa Asignado</td>
	<td width="110">Acciones</td>
   </tr>
   <?php
    if($totalRows_RsListadoPersona >0){
	 $t=0;
      do{
	    $t++;
		 $estilo="SB2";
		 if($t%2==0){
		 $estilo="SB";
		 }
	  ?>
	  <tr class="<?php echo($estilo);?>">
	   <td class=""><?php echo($row_RsListadoPersona['APELLIDO']." ".$row_RsListadoPersona['NOMBRE']);?></td>
	   <td><?php echo($row_RsListadoPersona['USUARIO']);?></td>
	    <td><?php echo($row_RsListadoPersona['ROL_NOMBRE']);?></td>
		 <td><?php echo($row_RsListadoPersona['AREA']);?></td>
		  <td><?php echo($row_RsListadoPersona['POA']);?></td>
	  <td>
		<input class="onoff"   id="onoff_<?php echo($row_RsListadoPersona['CODIGO']);?>" type="checkbox" data-toggle="toggle" data-on="On" data-off="Off" onClick="OnOffUser()" data-user="<?php echo($row_RsListadoPersona['CODIGO']);?>" <?php if($row_RsListadoPersona['ESTADO_USER']==1){ echo('checked');} ?> data-size="mini" >
		<a title="Editar"  onclick="Feditar('<?php echo($row_RsListadoPersona['CODIGO']);?>');"><i class="fa fa-pencil-square" style="font-size:1.2em;"></i><a>
			<?php /* <a title="Eliminar" onclick="Feliminar('<?php echo($row_RsListadoPersona['CODIGO']);?>','<?php echo($row_RsListadoPersona['ID_AREA_POA']);?>');"><i class="fa fa-close"></i><a>*/?>
	  </td>
	  </tr>
	  <?php
	   }while($row_RsListadoPersona = mysqli_fetch_array($RsListadoPersona));
	}
   ?>
  </table>
		<table border="0" align="left" class="datagrid">
		 <tr>
		  <td colspan="4">&nbsp;</td>
		 </tr>
		  <tr class="texto_gral">
			<td>
			 <ul>
			   <?php if ($pageNum_RsListadoPersona > 0) { // Show if not first page ?>
			   <li>
				  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListadoPersona=%d%s", $currentPage, 0, $queryString_RsListadoPersona); ?>')" class="submenus">Primero</a>
               </li>
				  <?php } // Show if not first page ?>
			   <?php if ($pageNum_RsListadoPersona > 0) { // Show if not first page ?>
			    <li>
				  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListadoPersona=%d%s", $currentPage, max(0, $pageNum_RsListadoPersona - 1), $queryString_RsListadoPersona); ?>')" class="submenus">Anterior</a>
				 </li>
				  <?php } // Show if not first page ?>
			<?php if ($pageNum_RsListadoPersona < $totalPages_RsListadoPersona) { // Show if not last page ?>
			     <li>
                  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListadoPersona=%d%s", $currentPage, min($totalPages_RsListadoPersona, $pageNum_RsListadoPersona + 1), $queryString_RsListadoPersona); ?>')" class="submenus">Siguiente</a>
				 </li>
				  <?php } // Show if not last page ?>
			<?php if ($pageNum_RsListadoPersona < $totalPages_RsListadoPersona) { // Show if not last page ?>
			      <li>
                  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListadoPersona=%d%s", $currentPage, $totalPages_RsListadoPersona, $queryString_RsListadoPersona); ?>')" class="submenus">&Uacute;ltimo</a>
				  </li>
				  <?php } // Show if not last page ?>
				</ul>
			</td>
		  </tr>
		</table>
  </form>
  
  </body>

</html>