<?php
require_once('conexion/db.php');
	if (!isset($_SESSION)) {
  session_start();
}
if(!isset($_SESSION['MM_AccesoCorrectoApp']) || $_SESSION['MM_AccesoCorrectoApp'] != 'ACTIVO'){
  header("location: index.php");
}
$nombre_filtro="";
if(isset($_POST['nombre_filtro']) && $_POST['nombre_filtro']!=''){
$nombre_filtro = $_POST['nombre_filtro'];
}
$estado_filtro="0";
if(isset($_POST['estado_filtro']) && $_POST['estado_filtro']!=''){
$estado_filtro = $_POST['estado_filtro'];
}
$categoria_filtro="";
if(isset($_POST['categoria_prov']) && $_POST['categoria_prov']!=''){
$categoria_filtro = $_POST['categoria_prov'];
}
$con_conveniofiltro="";
if(isset($_POST['con_conveniofiltro']) && $_POST['con_conveniofiltro']!=''){
$con_conveniofiltro = $_POST['con_conveniofiltro'];
}

$posible_conflicto="";
if(isset($_POST['posible_conflicto']) && $_POST['posible_conflicto']!=''){
$posible_conflicto = $_POST['posible_conflicto'];
}

$numero_caracteres="";
if(isset($_POST['numero_caracteres']) && $_POST['numero_caracteres']!=''){
$numero_caracteres = $_POST['numero_caracteres'];
}

$mayorormenor="";
if(isset($_POST['mayorormenor']) && $_POST['mayorormenor']!=''){
$mayorormenor = $_POST['mayorormenor'];
}

	$display_divcaracteres = 'none';
if($posible_conflicto == '2'){
	$display_divcaracteres = 'block';
}

$currentPage = $_SERVER["PHP_SELF"];
$tamanoPagina = 50;
$maxRows_RsListaProveedores = $tamanoPagina;
$pageNum_RsListaProveedores = 0;
if (isset($_GET['pageNum_RsListaProveedores'])) {
  $pageNum_RsListaProveedores = $_GET['pageNum_RsListaProveedores'];
}
$startRow_RsListaProveedores = $pageNum_RsListaProveedores * $maxRows_RsListaProveedores;


//CONSULTA DE CATEGORIA DE PROVEEDORES     
	$query_RsEstados="SELECT E.ESTACODI CODIGO,
	                         E.ESTANOMB NOMBRE
					   FROM ESTADOS E";
	$RsEstados = mysqli_query($conexion,$query_RsEstados) or die(mysqli_connect_error());
	$row_RsEstados = mysqli_fetch_array($RsEstados);
    $totalRows_RsEstados = mysqli_num_rows($RsEstados);

//echo(date('Y-m-d'));
//$textdistinct = '';
//if($categoria_filtro != ''){
	$textdistinct = 'DISTINCT';
//}
$query_RsListaProveedores = "SELECT P.PROVCODI CODIGO,
									P.PROVREGI,
									P.PROVNOMB NOMBRE,
									P.PROVCORR CORREO,
									'' REPETIDOS,
									P.PROVTELE TELEFONO,
									P.PROVPWEB WEB,
									P.PROVDIRE DIRECCION,
									P.PROVCON1 CONTACTO1,
									P.PROVTEC1 TEL_CONTACTO1,
									P.PROVCON2 CONTACTO2,
									P.PROVTEC2 TEL_CONTACTO2,
									P.PROVCORR CORREO,
									P.PROVCONV TIENE_CONVENIO,
									P.PROVESSO ESTADO,
									''
									FROM PROVEEDORES P
									WHERE 1
									";


if($posible_conflicto == ''){
	$query_RsListaProveedores .=" and PROVCODI = -100";
}


if ($posible_conflicto == '1') {
	$query_RsListaProveedores = "SELECT P.PROVCODI CODIGO,
										P.PROVREGI,
										P.PROVNOMB NOMBRE,
										COUNT(P.PROVCORR) REPETIDOS,
										P.PROVTELE TELEFONO,
										P.PROVPWEB WEB,
										P.PROVDIRE DIRECCION,
										P.PROVCON1 CONTACTO1,
										P.PROVTEC1 TEL_CONTACTO1,
										P.PROVCON2 CONTACTO2,
										P.PROVTEC2 TEL_CONTACTO2,
										P.PROVCORR CORREO,
										P.PROVCONV TIENE_CONVENIO,
										P.PROVESSO ESTADO,
										COUNT(P.PROVCORR)
										FROM PROVEEDORES P
										WHERE 1
										GROUP BY PROVCORR
										HAVING COUNT(P.PROVCORR) > 1
										";	
}
if ($posible_conflicto == '2') {
	$simbol = '<';
	if($mayorormenor == '1') $simbol = '>';
	$query_RsListaProveedores = "SELECT P.PROVCODI CODIGO,
										P.PROVREGI,
										P.PROVNOMB NOMBRE,
										'' REPETIDOS,
										P.PROVTELE TELEFONO,
										P.PROVPWEB WEB,
										P.PROVDIRE DIRECCION,
										P.PROVCON1 CONTACTO1,
										P.PROVTEC1 TEL_CONTACTO1,
										P.PROVCON2 CONTACTO2,
										P.PROVTEC2 TEL_CONTACTO2,
										P.PROVCORR CORREO,
										P.PROVCONV TIENE_CONVENIO,
										P.PROVESSO ESTADO
										FROM PROVEEDORES P
										WHERE 1
										HAVING LENGTH(P.PROVREGI) ".$simbol." (".$numero_caracteres.")
										";
}
	//$query_RsListaProveedores = $query_RsListaProveedores." ORDER BY P.PROVNOMB";
	//echo($query_RsListaProveedores);	
    
	$query_limit_RsListaProveedores = sprintf("%s LIMIT %d, %d", $query_RsListaProveedores, $startRow_RsListaProveedores, $maxRows_RsListaProveedores);	
    
    $RsListaProveedores = mysqli_query($conexion, $query_limit_RsListaProveedores) or die(mysqli_error($conexion));
    $row_RsListaProveedores = mysqli_fetch_array($RsListaProveedores);	

if (isset($_GET['totalRows_RsListaProveedores'])) {
  $totalRows_RsListaProveedores = $_GET['totalRows_RsListaProveedores'];
} else {
  $all_RsListaProveedores = mysqli_query($conexion,$query_RsListaProveedores);
  $totalRows_RsListaProveedores = mysqli_num_rows($all_RsListaProveedores);
}

//if ($maxRows_RsProducto != 0)
$totalPages_RsListaProveedores = ceil($totalRows_RsListaProveedores/$maxRows_RsListaProveedores)-1;
//else
//$totalPages_RsProducto = ceil($totalRows_RsProducto/1)-1;
if($posible_conflicto == ''){
	$totalRows_RsListaProveedores= 0;
}

$queryString_RsListaProveedores = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_RsListaProveedores") == false &&
        stristr($param, "totalRows_RsListaProveedores") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_RsListaProveedores = "&" . htmlentities(implode("&", $newParams));
  }
}

$queryString_RsListaProveedores = sprintf("&totalRows_RsListaProveedores=%d%s", $totalRows_RsListaProveedores, $queryString_RsListaProveedores);

$paginaHasta = 0;
if ($pageNum_RsListaProveedores == $totalPages_RsListaProveedores)
{
	$paginaHasta = $totalRows_RsListaProveedores;
}
else
{
	$paginaHasta = ($pageNum_RsListaProveedores+1)*$maxRows_RsListaProveedores;
}
//echo($query_RsListaProveedores);

?><!DOCTYPE html>
<html>

<head>
<style type="text/css">
body{
/*background-image: url("img/Bottom_texture.png");*/
}

#menu{
/*background:#26B826;*/
/*background:#4C954B;*/
height:50px;
font-size:13px;
margin-top:-10px;
border-radius:13px;
}
#menu_proveedores{
 /* background:#26B826; */
 padding-top:15px;
 color:#FDF8F8;
 font-weight:bold;
}
#menu_proveedores li{
display:inline;
padding-left:15px;
padding-right:15px;
padding-top:10px;
padding-bottom:10px;
background:#4C954B;
border-radius:13px;
list-style-type:none;
}

#menu_proveedores a{
width:260px;
/*background:#ff0000;*/
text-decoration:none;
color:#ffffff;
}

#menu_proveedores a:hover{
color:#000000;
}

#menu_proveedores li:hover{
background:#99F199;
font-size:13px;
border-radius:13px;
}

form{
 padding-left:15px;
 padding-top:20px;
 padding-right:15px;
}
.toolstable{
	min-height:25px;
}
.toolstable a:hover{
	color:#ff0000;
	cursor:pointer;
	font-size:1.2em;
}
</style>
<title>Proveedores Solicitados</title>
<link rel="stylesheet" type="text/css" href="css/estilo_solicitud.css" />
<link rel="stylesheet" type="text/css" href="messages.css"/>
<script src="messages.js" type="text/javascript"></script>
</head>

<body>
<div id="mensajero" class="alert"></div>
	<div id="pagina">
		<form name="form1" id="form1" action="" method="post">
			<div id="divfiltros" style="display:block; border:solid 1px #ccc; width:100%;">
				<div class="form-group">
					<h5 class="SLAB trtitle text-center">Filtros de Búsqueda</h5>
				</div>
			
			<div class="" style="padding:5px;">
			
				<div class="row">
					<div class="col-md-3 col-lg-3">
						<div class="form-group">
							<label for="posible_conflicto">Posible Conflicto</label>
							<select name="posible_conflicto" id="posible_conflicto" class="form-control" onchange="ShowOptCaracteres(this)">
									<option value="">Seleccione...</option>
									<option value="1" <?php if($posible_conflicto == '1'){ echo('selected'); }?> >Correo Repetido</option>
									<option value="2" <?php if($posible_conflicto == '2'){ echo('selected'); }?> >Por Tama&ntilde;o de carácteres del Nit</option>
								</select>
						</div>
					</div>
					<div class="col-md-9 col-lg-9" id="divoptcaracteres" style="display:<?php echo($display_divcaracteres);?>;">
						<div class="row">
							<div class="col-md-3 col-lg-3">
								<label for="numero_caracteres">Número de Carácteres</label>
								<input style="width:120px;" pattern="[0-9]*" onkeypress='return acceptNum(event)'; type="number" id="numero_caracteres" name="numero_caracteres" value="<?php echo($numero_caracteres);?>" class="form-control">
							</div>
							<div class="col-md-3 col-lg-3 text-left">
								<label for="mayorormenor">Mayor o Menor</label>
								<select name="mayorormenor" id="mayorormenor" class="form-control">
									<option value="">Seleccione...</option>
									<option value="1" <?php if($mayorormenor == '1'){ echo('selected'); }?> >Mayor</option>
									<option value="2" <?php if($mayorormenor == '2'){ echo('selected'); }?>>Menor</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-lg-6">
						<button type="button" name="butonfiltro" id="butonfiltro" class="button2" value="Buscar" onclick="BuscarResultados()"><i class="fa fa-search"></i> Buscar</button>
					</div>
				</div>					
			</div>



 </div>
  <table class="tablalistado table" cellspacing="2" border="0">
   <tr>
  <td colspan="12">
   <button type="button" name="consultar" id="consultar" value="consultar" class="button2" onclick="mostrarfiltros();"><i class="fa fa-filter"></i>  Consultar</button>
  </td>
 </tr>
     
	  
	
	<tr>
	 <td colspan="12" class="" >
	  <?php
			if ($totalRows_RsListaProveedores > 0)
		{
					?>
		Mostrando <b><?php echo($startRow_RsListaProveedores+1); ?></b> a <b><?php echo($paginaHasta); ?></b> de <b><?php echo($totalRows_RsListaProveedores);
		 ?></b> Registros de proveedores solicitados
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
   <tr class="SLAB trtitle">
    <td width="30"></td>
	<td>Proveedor</td>
	<td>Correo</td>
	<td>Telefono</td>	
	<td>Contacto</td>
   </tr>
   <?php
    if($totalRows_RsListaProveedores >0){
	  $j=0;
      do{
	   $j++;
	    $estilo="SB";
	    if($j%2==0){
		$estilo="SB2";
		}
	  ?>
	  <tr class="<?php echo($estilo);?> small text-justify">
	   	<td>
		<div style="width:30px" class="toolstable">
	      
	   	  
		  
		<!-- Single button -->
			<div class="btn-group">
			  <a class=" dropdown-toggle" data-toggle="dropdown">
				 &nbsp;<span class="fa fa-ellipsis-v text-danger"></span> 
			  </a>
			  <ul class="dropdown-menu" role="menu" style="min-width:104px;"> 
				<?php 
				/*
				if($row_RsListaProveedores['ESTADO'] != 1){
				?>
				<li><a  title="aprobar" onclick="FaccionesSolicitado('<?php echo($row_RsListaProveedores['CODIGO']);?>','1');"><i class="fa fa-check-circle"></i> Aprobar</a></li>
				<?php 
				if($row_RsListaProveedores['ESTADO'] != 2){
				?>				
				<li><a  title="aprobar" onclick="location.href='proveedores/publico/registro.php?cod_prov=<?php echo($row_RsListaProveedores['CODIGO']);?>'"><i class="fa fa-close"></i> Rechazar</a></li>
				<?php 
				} 
				}*/
				?>
				<li><a  title="Ver" onclick="location.href='home.php?page=proveedores_lista&correo_filtro=<?php echo($row_RsListaProveedores['CORREO']);?>'"> <i class="fa fa-eye"></i> Ver</a></li>
			  </ul>
			</div>		  
		  </div>
	   </td>
	   <td><?php echo($posible_conflicto == '2' ? '('.$row_RsListaProveedores['PROVREGI'].')' : '');?>&nbsp;&nbsp;&nbsp; <?php echo($row_RsListaProveedores['NOMBRE']);?></td>
	   <td><?php echo($posible_conflicto == '1' ? '('.$row_RsListaProveedores['REPETIDOS'].')' : '');?>  <?php echo($row_RsListaProveedores['CORREO']);?> </td>	   
	   <td><?php echo($row_RsListaProveedores['TELEFONO']);?></td>	   
	   <td><?php echo($row_RsListaProveedores['CONTACTO1']);?></td>
	  </tr>
	  <?php
	   }while($row_RsListaProveedores = mysqli_fetch_array($RsListaProveedores));
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
			   <?php if ($pageNum_RsListaProveedores > 0) { // Show if not first page ?>
			   <li>
				  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaProveedores=%d%s", $currentPage, 0, $queryString_RsListaProveedores); ?>')" class="submenus">Primero</a>
               </li>
				  <?php } // Show if not first page ?>
			   <?php if ($pageNum_RsListaProveedores > 0) { // Show if not first page ?>
			    <li>
				  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaProveedores=%d%s", $currentPage, max(0, $pageNum_RsListaProveedores - 1), $queryString_RsListaProveedores); ?>')" class="submenus">Anterior</a>
				 </li>
				  <?php } // Show if not first page ?>
			<?php if ($pageNum_RsListaProveedores < $totalPages_RsListaProveedores) { // Show if not last page ?>
			     <li>
                  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaProveedores=%d%s", $currentPage, min($totalPages_RsListaProveedores, $pageNum_RsListaProveedores + 1), $queryString_RsListaProveedores); ?>')" class="submenus">Siguiente</a>
				 </li>
				  <?php } // Show if not last page ?>
			<?php if ($pageNum_RsListaProveedores < $totalPages_RsListaProveedores) { // Show if not last page ?>
			      <li>
                  <a href="javascript:f_abrir_link('<?php printf("%s?pageNum_RsListaProveedores=%d%s", $currentPage, $totalPages_RsListaProveedores, $queryString_RsListaProveedores); ?>')" class="submenus">&Uacute;ltimo</a>
				  </li>
				  <?php } // Show if not last page ?>
				</ul>
			</td>
		  </tr>
		</table>  
  </form>
</body>
</html>


<script type="text/javascript">
function ShowOptCaracteres(obj){
	if(obj.value == ''){
		$("#divoptcaracteres").css("display","none");
	}
	if(obj.value == '2'){
		$("#divoptcaracteres").css("display","block");
	}
}
function BuscarResultados(){
	if($("#posible_conflicto").val() == ''){
		alert("debe ingresar un valor de posible conflicto");
		return;
	}
	if($("#posible_conflicto").val() == '1'){
		$("#numero_caracteres").val('');
		$("#mayorormenor").val('');
	}
	if($("#posible_conflicto").val() == '2'){
		if($("#numero_caracteres").val() == ''){
			alert("debe ingresar el número de carácteres");
			return;
		}
		if($("#mayorormenor").val() == ''){
			alert("debe indicar si es mayor o menor");
			return;
		}
	}
	document.form1.submit();
}

function acceptNum(evt)
  {
    var key;
    if(window.event)
    {
      key = event.keyCode;
    }
    else if(event.which)
    {
      key = event.which;
    }
    //return (key == 45 || key == 13 || key == 8 || key == 9 || key == 189 || (key >= 48 && key <= 58) )
    //var nav4 = window.Event ? true : false;
    //var key = nav4 ? evt.which : evt.keyCode;
    return (key <= 13 || (key >= 48 && key <= 57));
  }	


	   function f_abrir_link(v_link)
	{
	  document.form1.action=v_link;
	  document.form1.submit();

    }
  function mostrarfiltros(){
   $( "#divfiltros" ).toggle();
  }	
  
  function Feliminarprov(cod){
      
	  if(confirm('esta seguro de eliminar este proveedor?')){
       document.form1.action='crear_proveedor_guardar.php?tipoGuardar=Eliminar_Prov&cod_prov='+cod;
	   document.form1.submit();
	   }
	  }
	  
  function Feditarprov(cod){
      
       document.form1.action='home.php?page=crear_proveedor&tipoGuardar=Editar&cod_prov='+cod;
	   document.form1.submit();
	  } 
	  
function Fverprov(cod){
      
       document.form1.action='home.php?page=proveedor&cod_prov='+cod;
	   document.form1.submit();
	  }	  

	  function limpiarfiltros (campo){
  document.getElementById(''+campo).value=""; 
  }
  
function msgerror(msg){
  alert(msg);
}  
  
  function FaccionesSolicitado(codigo, accion){
	  
	if(codigo == '') return false;
	label = '';
	if(accion == '1') label = " Aprobar ";
	if(accion == '2') label = " Rechazar ";
	if(accion == '3') label = " Ver ";
	if(accion <= '2'){
		if(confirm("Seguro que desea "+label+" este proveedor?")){
			   var date = new Date();
			   var timestamp = date.getTime();
			   $.ajax({
				type: "POST",
				url: "tipo_guardar.php?tipoGuardar=ProveedorTemporalaOficial&codigo_prov="+codigo+"&accion="+ accion +"&timers="+timestamp,
				dataType: 'json',
				success : function(r){
							if(r.status == 'erroruser'){
								msgerror('error en la accion del proveedor, el proveedor ya existe en la tabla oficial proveedores');
							}  							
							if(r.status == 'errorusertemporal'){
								if(r.data_temp == '0'){
									msgerror('ya se ha ingresado un registro previo con la identificación ingresada, que se encuentra en proceso de aprobación');
								}else{
										msgerror('Esta identificación ya se encuentra registrada, por favor inicie sesión, si no recuerda su contraseña acceda mediante el recurso recordar contraseña, de la pantalla inicial');
									}
								}
														
							if(r.status == 'failed'){
								msgerror('Se ha presentado un error al guardar la información, Intente más tarde.');
							}							
							if(r.status == 'errprovnocreado'){
								msgerror('Se ha presentado un error al guardar la información, Proveedor no ha sido creado en la tabla oficial.');
							}							
							if(r.status == 'rechazado'){
								msgerror('Se ha rechazado correctamente el proveedor.');
								setTimeout(function(){ location.reload(); }, 1000);
							}
							if(r.status == 'norechazado'){
								msgerror('Se ha presentado un error al rechazar el proveedor, intente más tarde, contacte con soporte');
								setTimeout(function(){ location.reload(); }, 1000);
							}							
							if(r.status == 'creado'){
								$("#mensajero").html("Creando directorio proveedor ...");
								crearDirectorio(r);								
							}
							
				  },
				data: {},
				error   : error_call
			  });			
		}
	}
	if(accion == '3'){
		
	}
  }
  
 function crearDirectorio(data){
	 		   var date = new Date();
			   var timestamp = date.getTime();
			   $.ajax({
				type: "POST",
				url: "tipo_guardar.php?tipoGuardar=CrearDirectorioProveedor&cod_prov_oficial="+data.cod_prov_oficial+"&cod_prov_temp="+ data.cod_prov_temporal +"&timers="+timestamp,
				dataType: 'json',
				success : function(r){
							if(r.status == 'ok'){
								$("#mensajero").html("Directorio de proveedor creado correctamente");
								copiarArchivos(r);
							}  							
														
							if(r.status == 'failed'){
								$("#mensajero").html("Error al crear Directorio de proveedor, contacte con soporte");
							}							
							
				  },
				data: {},
				error   : error_call
			  });
 }
 function copiarArchivos(data){
	 		   var date = new Date();
			   var timestamp = date.getTime();
			   $.ajax({
				type: "POST",
				url: "tipo_guardar.php?tipoGuardar=CopiarArchivosProveedor&cod_prov_oficial="+data.cod_prov_oficial+"&cod_prov_temp="+ data.cod_prov_temporal +"&timers="+timestamp,
				dataType: 'json',
				success : function(r){
							if(r.status == 'ok'){
								$("#mensajero").html("categorias y Archivos copiados a directorio proveedor");
								alert("se ha realizado correctamente la gestión de proveedor, copiado de temporal a definido");
							}  							
														
							if(r.status == 'failed'){
								$("#mensajero").html("Error al copiar categorias y copiar archivos de proveedor");
							}							
							if(r.status == 'creado'){
								$("#mensajero").html("proceso finalizado correctamente, proveedor aprobado correctamente");	
								alert("registro de proveedor aprobado correctamente");
								setTimeout(function(){ location.reload(); }, 3000);
							}
							
				  },
				data: {},
				error   : error_call
			  });
 }  
 
function error_call(XMLHttpRequest, textStatus, errorThrown)
{
    alert("Respuesta del servidor "+XMLHttpRequest.responseText);
    alert("Error "+textStatus);
    alert(errorThrown);
}
  
	  
</script>
