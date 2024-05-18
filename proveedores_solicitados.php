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



// function procesarArchivosCategorias($codigo_prov_temporal,$codigo_proveedor_oficial, $dir_origen, $dir_destino, $conexion){
	
	// $query_RsArchivosTemp = "SELECT * FROM  PROVEEDOR_DATATEMP WHERE PDTEPROV = '".$codigo_prov_temporal."'";
	// $RsArchivosTemp = mysqli_query($conexion,$query_RsArchivosTemp) or die(mysqli_error($conexion));
	// $row_RsArchivosTemp = mysqli_fetch_array($RsArchivosTemp);
	// $totalRows_RsArchivosTemp = mysqli_num_rows($RsArchivosTemp);	
	// $status = 'failed';
	// if($totalRows_RsArchivosTemp > 0){
		// do{
			// //echo($row_RsArchivosTemp['PDTECATE']."<br></br>");
			// $categorias_prov = json_decode($row_RsArchivosTemp['PDTECATE']);
			// $archivos_prov    = json_decode($row_RsArchivosTemp['PDTEARCH']);
			// foreach($categorias_prov as $categoria){
				// echo($categoria.'<br>');
			// }		
			// foreach($archivos_prov as $proveedor){
				// echo($proveedor->archivo.'<br>');
				// if($proveedor->archivo){
					// try {
						// $pasa = copy("$dir_origen/$proveedor->archivo", "$dir_destino/$proveedor->archivo");
					// } catch (Exception $e) {
						
					// }
				// }
			// }
		// }while($row_RsArchivosTemp = mysqli_fetch_array($RsArchivosTemp));
		// $status = 'ok';
	// }	
// }

// function VerificarExisteDirectorio($carpeta_prov){
	// $existe = 'failed'; /* inicia no existe directorio */
		// if (file_exists($carpeta_prov)) {
			// echo "El fichero existe";
			// $existe = 'ok'; /* directorio existe*/
		// } else {
			// if(!mkdir($carpeta_prov, 0777, true)) {
				// die('Fallo al crear las carpetas...');
				// $existe = 'failed';
			// }else{
				// $existe = 'ok';
			// }
		// }
	// return $existe;	
// }


// $codigo_proveedor_oficial = '11409';
// $codigo_prov_temporal = '2';

// $dir_destino = 'archivos_compras/PROVEEDORES/'.$codigo_proveedor_oficial;
// $dir_origen  = 'proveedores/publico/php/temporalfiles/';
// $carpeta_prov = "archivos_compras/PROVEEDORES/".$codigo_proveedor_oficial;	

// $verificar_directorio = VerificarExisteDirectorio($dir_destino);
// if($verificar_directorio == 'ok'){
	// /*directorio creado*/
	// /*procesar archivos*/
	// $proveedores_data_relacionada = procesarArchivosCategorias($codigo_prov_temporal,$codigo_proveedor_oficial, $dir_origen, $dir_destino, $conexion);
	// if($proveedores_data_relacionada == 'ok'){
		// /*Archivos y categorias insertadas correctamente*/	
		
	// }
// }



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
	$query_RsListaProveedores="SELECT ".$textdistinct." P.PROVCODI CODIGO,
                                      P.PROVNOMB NOMBRE,
									  P.PROVTELE TELEFONO,
									  P.PROVPWEB WEB,
									  P.PROVDIRE DIRECCION,
									  P.PROVCON1 CONTACTO1,
									  P.PROVTEC1 TEL_CONTACTO1,
									  P.PROVCON2 CONTACTO2,
									  P.PROVTEC2 TEL_CONTACTO2,
									  P.PROVCORR CORREO,
									  (SELECT C.CONVCONS 
									     FROM convenios C
									   where C.CONVIDPR = P.PROVCODI
									    AND  '".date('Y-m-d')."' >= C.CONVFEIN 
									    AND  '".date('Y-m-d')."' <= C.CONVFEFI
									   LIMIT 1
									  ) CONVENIO,
									  P.PROVCONV TIENE_CONVENIO,
									  p.PROVESSO ESTADO,
									  CASE PROVESSO
									   WHEN 0 THEN 'SIN REVIZAR'
									   WHEN 1 THEN 'APROBADO'
									   WHEN 2 THEN 'RECHAZADO'
									  ELSE ''
									  END ESTADO_DES
									  
							    FROM PROVEEDORES_TEMPORAL P INNER JOIN proveedor_clasificacion C ON P.PROVCODI = C.PRCLPROV 
							 WHERE  1";
	
	if($estado_filtro!='' && $estado_filtro != '3'){
		
	  $query_RsListaProveedores = $query_RsListaProveedores." AND P.PROVESSO = '".$estado_filtro."'";
	}	
	
	if($nombre_filtro!=''){
	  $query_RsListaProveedores = $query_RsListaProveedores." AND P.PROVNOMB LIKE '%".$nombre_filtro."%'";
	}

if($categoria_filtro!=''){
	  $query_RsListaProveedores = $query_RsListaProveedores." AND C.PRCLCLAS = '".$categoria_filtro."'  ";
	}

	if($con_conveniofiltro != ''){
	  $query_RsListaProveedores = $query_RsListaProveedores." AND P.PROVCONV = '".$con_conveniofiltro."'";
	}

	$query_RsListaProveedores = $query_RsListaProveedores." ORDER BY P.PROVNOMB";
	
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
			<div id="divfiltros" style="display:none; border:solid 1px #ccc; width:100%;">
 				<table width="100%" class="table">
   					<tr>
     					<td class="SLAB trtitle" colspan="9" align="center">Filtros de Busqueda</td>
   					</tr>
						<tr>
							<td class="SB">Nombre Proveedor</td>
							<td>
	 							<input type="text" name="nombre_filtro" id="nombre_filtro" value="<?php echo($nombre_filtro);?>" class="form-control">
							</td>
							<td>
								<button type="button" class="btn btn-default "  name="fcategoria" id="fcategoria"  onclick="limpiarfiltros('nombre_filtro');"><i class="fa fa-close"></i></button>
							</td>
							<td class="SB">Estado</td>
							<td>
								<select name="estado_filtro" id="estado_filtro" class="form-control">
									<option value="">Seleccione...</option>
									<option value="3" <?php if($estado_filtro == 3)echo("selected");?>>Todos</option>
									<option value="0" <?php if($estado_filtro == 0)echo("selected");?>>Sin Revizar</option>
									<option value="1" <?php if($estado_filtro == 1)echo("selected");?>>Aprobado</option>
									<option value="2" <?php if($estado_filtro == 2)echo("selected");?>>Rechazado</option>
								</select>
							</td>
							<td>
								<button type="button" class="btn btn-default "  name="fcategoria" id="fcategoria"  onclick="limpiarfiltros('estado_filtro');"><i class="fa fa-close"></i></button>
							</td>
   						</tr>

  			 			<tr>
    					<td colspan="6" align="left">
	 <button type="submit" name="butonfiltro" id="butonfiltro" class="button2" value="Buscar"><i class="fa fa-search"></i> Buscar</button>
	</td>
   </tr>
 </table>
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
	<td>Telefono</td>
	<td>Correo</td>	
	<td>Contacto</td>
	<td>Estado</td>
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
				<li><a  title="Ver" onclick="location.href='proveedores/publico/registro.php?cod_prov=<?php echo($row_RsListaProveedores['CODIGO']);?>'"> <i class="fa fa-eye"></i> Ver</a></li>
			  </ul>
			</div>		  
		  </div>
	   </td>
	   <td><?php echo($row_RsListaProveedores['NOMBRE']);?></td>
	   <td><?php echo($row_RsListaProveedores['TELEFONO']);?></td>
	   <td><?php echo($row_RsListaProveedores['CORREO']);?></td>	   
	   <td><?php echo($row_RsListaProveedores['CONTACTO1']);?></td>
	   <td><?php echo($row_RsListaProveedores['ESTADO_DES']);?></td>
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
	function getDataServer(url, vars){
		 var xml = null;
		 try{
			 xml = new ActiveXObject("Microsoft.XMLHTTP");
		 }catch(expeption){
			 xml = new XMLHttpRequest();
		 }
		 xml.open("GET",url + vars, false);
		 xml.send(null);
		 if(xml.status == 404) alert("Url no valida");
		 return xml.responseText;
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
