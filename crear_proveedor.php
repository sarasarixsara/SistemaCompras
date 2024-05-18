<?php
require_once('conexion/db.php');
	if (!isset($_SESSION)) {
  session_start();
}
if(!isset($_SESSION['MM_AccesoCorrectoApp']) || $_SESSION['MM_AccesoCorrectoApp'] != 'ACTIVO'){
  header("location: index.php");
}

$get_codi='';
if(isset($_GET['cod_prov']) && $_GET['cod_prov'] !=''){
$get_codi=$_GET['cod_prov'];
}



//crear_proveedor&tipoGuardar=Editar&cod_prov=79515327-1
$tipoGuardar = '';
$totalRows_RsCategProv=0;
$sololecturanit='';
$totalRows_RsArchivosLista = 0;

if(isset($_SESSION['MM_RolID']) && $_SESSION['MM_RolID'] == '7'){

  $get_codi =$_SESSION['MM_ProveedorID'];
  if(isset($_GET['page']) && $_GET['page'] == 'crear_proveedor'){
  	?>
  	<script type="text/javascript">
  		location.href="crear_proveedor.php";
  	</script>
  	<?php
  }
}

  $query_RsLista_adjuntos="SELECT * FROM CONF_PROVEEDOR WHERE COPRTITU = 'adjuntos'
            and COPRESTA = 1
          ";
  $RsLista_adjuntos = mysqli_query($conexion,$query_RsLista_adjuntos) or die(mysqli_error($conexion));
  $row_RsLista_adjuntos = mysqli_fetch_array($RsLista_adjuntos);
  $totalRows_RsLista_adjuntos = mysqli_num_rows($RsLista_adjuntos);


if($get_codi != ''){
	$query_RsArchivosLista="SELECT PRARCODI CODIGO, PRARPROV, PRARARCH ARCHIVO, PRARTICA TIENE_DIR
                        FROM `proveedoresarch` WHERE PRARPROV= '".$get_codi."'";
   	$RsArchivosLista = mysqli_query($conexion,$query_RsArchivosLista) or die(mysqli_error($conexion));
	$row_RsArchivosLista = mysqli_fetch_assoc($RsArchivosLista);
    $totalRows_RsArchivosLista = mysqli_num_rows($RsArchivosLista);

$query_RsEditProveedores="SELECT PROVCODI NIT,
								 PROVREGI REGISTRO,
								 PROVNOMB NOMBRE,
								 PROVTELE TELEFONO,
								 PROVPWEB WEB,
								 PROVDIRE DIRECCION,
								 PROVIDCI CIUDAD,
								 PROVCON1 CONTACTO1,
								 PROVTEC1 TEL_CONTACTO1, 
								 PROVCCO1 CARGO1,
								 PROVCON2 CONTACTO2,
								 PROVTEC2 TEL_CONTACTO2,
								 PROVCCO2 CARGO2,
								 PROVCOME COMENTARIOS,
								 PROVPERE CORREO, 
								 PROVFERE FECHA_REGISTRO,
								 PROVESTA ESTADO,
								 PROVCORR CORREO,
								 PROVIDCA ID_CATEGORIA,
								 PROVCONV CONVENIO,
								 PROVTIPE TIPO_PERSONA,
								 PROVNOCO NOMBRE_COMERCIAL,
								 PROVREGM REGIMEN,
								 PROVAURE AUTORETENEDOR,
								 PROVGRCO GRAN_CONTRIBUYENTE,
								 PROVCICA CONTRIBUYENTE_ICA,
                                 PROVESSO ESTADO_ACT								 
								 
						     FROM PROVEEDORES P
							 WHERE P.PROVCODI= '".$get_codi."'";
				 //echo($query_RsEditProveedores);echo("<br>");
   	$RsEditProveedores = mysqli_query($conexion,$query_RsEditProveedores) or die(mysqli_error($conexion));
	$row_RsEditProveedores = mysqli_fetch_assoc($RsEditProveedores);
    $totalRows_RsEditProveedores = mysqli_num_rows($RsEditProveedores);
	
	
	$nit     	= $row_RsEditProveedores['NIT'];
	$registro2	= $row_RsEditProveedores['REGISTRO'];
	$nombre 	= $row_RsEditProveedores['NOMBRE'];
	$telefono   = $row_RsEditProveedores['TELEFONO'];
	$web 		= $row_RsEditProveedores['WEB'];
	$direccion  = $row_RsEditProveedores['DIRECCION'];
	$ciudad  	= $row_RsEditProveedores['CIUDAD'];
	$contacto1  = $row_RsEditProveedores['CONTACTO1'];
	$telcontac1 = $row_RsEditProveedores['TEL_CONTACTO1'];
	$cargo1     = $row_RsEditProveedores['CARGO1'];
 	$contacto2   = $row_RsEditProveedores['CONTACTO2'];
	$telcontac2 = $row_RsEditProveedores['TEL_CONTACTO2'];
	$cargo2		= $row_RsEditProveedores['CARGO2'];
	$correo 	= $row_RsEditProveedores['CORREO'];
	$comentarios = $row_RsEditProveedores['COMENTARIOS'];
	$categoria   = $row_RsEditProveedores['ID_CATEGORIA'];
	$convenio    =$row_RsEditProveedores ['CONVENIO'];
	$nombre_comercial = $row_RsEditProveedores ['NOMBRE_COMERCIAL'];
	$regimen          = $row_RsEditProveedores ['REGIMEN'];
	$autoretenedor    = $row_RsEditProveedores ['AUTORETENEDOR'];
	$gran_contribuyente    = $row_RsEditProveedores ['GRAN_CONTRIBUYENTE'];
	$contribuyente_ica    = $row_RsEditProveedores ['CONTRIBUYENTE_ICA'];
	$tipo_persona    = $row_RsEditProveedores ['TIPO_PERSONA'];
	
//pendiente implementar algoritmo para manejar corroborar el estado del proveedor si actualizo o no $row_RsEditProveedores ['ESTADO_ACT'].	
	$estado_act    = '1';
	
	
	$query_RsCategProv="SELECT P.PRCLCODI CODIGO,
							   P.PRCLPROV PROVEEDOR, 
							   P.PRCLCLAS CLASIFICACION,
							   C.CLASNOMB CLASIFICACION_DES
						FROM   PROVEEDOR_CLASIFICACION P,
                               CLASIFICACION C						
					    WHERE P.PRCLPROV = '".$get_codi."'
						  AND P.PRCLCLAS = C.CLASCODI";
				 //echo($query_RsCategProv);echo("<br>");
	$RsCategProv = mysqli_query($conexion,$query_RsCategProv) or die(mysqli_error($conexion));
	$row_RsCategProv = mysqli_fetch_assoc($RsCategProv);
    $totalRows_RsCategProv = mysqli_num_rows($RsCategProv); 
	
	
    	
	$tipoGuardar= 'Editar';
	$sololecturanit='readonly';
	 
	
}else{
    $nit        = "";
	$registro2   = "";
	$nombre 	= "";
	$telefono   = "";
	$web 		= "";
	$direccion  = "";
	$ciudad		= "";
	$contacto1  = "";
	$telcontac1 = "";
	$cargo1     = "";
 	$contacto2   = "";
	$telcontac2 = "";
	$cargo2		= "";
	$correo = "";
	$comentarios = "";
	$categoria ="";
	$categoria_des="";
	$convenio="";
	$nombre_comercial = '';
	$regimen          = '';
	$autoretenedor    = '';
	$gran_contribuyente    = '';
	$contribuyente_ica    = '';
	$tipo_persona    = '';
	$estado_act ='1';
	$tipoGuardar='Guardar'; //$_GET['tipoGuardar'];
	
}
	

?>

<!DOCTYPE html>
<html>

<head>
<title>Proveedores</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="css/estilo_solicitud.css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
<link rel="stylesheet" href="includes/font-awesome/css/font-awesome.min.css">	
<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>


<style type="text/css">
body{
background-image: url("imagenes/Bottom_texture.png");
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
</style>

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



function validarProveedor(TG){
 
 
 
 var nombre=document.form1.nombre.value;
 var nit=document.form1.nit.value
 var codigoprov=document.form1.codigoprov.value;
 var telefono=document.form1.telefono.value;
 var correo=document.form1.correo.value;
 var direccion=document.form1.direccion.value;
 var ciudad=document.form1.ciudad.value;
 var categoria_prov=document.form1.categoria_prov.value;
 var personac1=document.form1.personac1.value;
 var telefonoc1=document.form1.telefonoc1.value;
 var cargo1=document.form1.cargo1.value;
 var tipo_persona=document.form1.tipo_persona.value;
 var regimen=document.form1.regimen.value;
 var autoretenedor=document.form1.autoretenedor.value;
 var gran_contribuyente=document.form1.gran_contribuyente.value;
 var contribuyente_ica=document.form1.contribuyente_ica.value;

 // Mensajes 
 if(nombre == '')
  {
   inlineMsg('nombre','debe digitar la Razon Social .',3);
		return false;
  }
  
   
 if(codigoprov == '')
  {
   inlineMsg('codigoprov','debe digitar el NIT o CEDULA del Proveedor.',3);
		return false;
  }
  
 if(tipo_persona == '')
  {
   inlineMsg('tipo_persona','Olvidaste digitar el tipo de persona.',3);
		return false;
  }   
  if(regimen == '')
  {
   inlineMsg('regimen','Olvidaste ingresar el régimen.',3);
		return false;
  }  
  if(autoretenedor == '')
  {
   inlineMsg('autoretenedor','Olvidaste ingresar el campo autoretenedor.',3);
		return false;
  }   
  if(gran_contribuyente == '')
  {
   inlineMsg('gran_contribuyente','Olvidaste ingresar el campo gran contribuyente.',3);
		return false;
  }   
  if(contribuyente_ica == '')
  {
   inlineMsg('contribuyente_ica','Olvidaste ingresar el campo contribuyente ica.',3);
		return false;
  }     

  if(telefono == '')
  {
   inlineMsg('telefono','debe digitar el Telefono.',3);
		return false;
  }

  //if(correo == '')
  //{
   //inlineMsg('correo','debe digitar el Correo Electronico.',3);
	//	return false;
 // }
 
  
  if(direccion == '')
  {
   inlineMsg('direccion','Olvidaste digitar la Direccion.',3);
		return false;
  }
  
    if(ciudad == '')
  {
   inlineMsg('ciudad','Olvidaste digitar la Ciudad.',3);
		return false;
  }
  
  
<?php 
if($tipoGuardar=='Guardar' || $tipoGuardar==''){
?>
  if(categoria_prov == '')
  {
   inlineMsg('categoria_prov','Olvidaste digitar la categoria al que pertenece el proveedor.',3);
		return false;
  } 
<?php 
}
?>  
  
  if(personac1 == '')
  {
   inlineMsg('personac1','Olvidaste digitar el Nombre de la persona de contacto.',3);
		return false;
  }   

  if(telefonoc1 == '')
  {
   inlineMsg('telefonoc1','Olvidaste digitar el Telefono de la persona de contacto.',3);
		return false;
  }   

  if(cargo1 == '')
  {
   inlineMsg('cargo1','Olvidaste digitar el Cargo de la persona de contacto.',3);
		return false;
  }    
  

  
  if(TG=="Guardar" || TG==""){  
  
	if(confirm('Esta seguro de almacenar estos datos guardar?')){
    document.form1.action="crear_proveedor_guardar.php?tipoGuardar=Guardar";
	document.form1.submit(); }
	}
	
  if(TG=="Editar"){
     	  
      var date = new Date();
	  var timestamp = date.getTime();
	  var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=ConsultarCategoriasProveedor&time="+timestamp+"&proveedor="+nit+"&categoria="+$("#categoria_prov").val());
	//alert(v_dato); 
	 if(v_dato>0){
		alert('la categoria a ingresar ya se encuentra asociada al proveedor');
		return false;
	  }
	  
	  
	  
	if(confirm('Esta seguro de almacenar estos datos?')){
    document.form1.action="crear_proveedor_guardar.php?tipoGuardar=Editar";
	document.form1.submit(); } 
	}	
  
}

function FeliminarCategoria(codigo,cat){
  if(confirm("Seguro que desea eliminar esta categoria?")){
      var date = new Date();
	  var timestamp = date.getTime();
	  var v_dato = getDataServer("crear_proveedor_guardar.php","?tipoGuardar=EliminarCategoriaProv&time="+timestamp+"&codigo="+codigo+"&proveedor="+$("#nit").val()+"&clasificacion="+cat);
	  //alert(v_dato);
      if(v_dato==1){
	    $("#trcat_"+cat).remove();
		alert("registro eliminado exitosamente");
	  }
   }
}

 function volveraListado(){
	<?php if($_SESSION['MM_RolID'] != '7'){ ?>
   	document.form1.action ="home.php?page=proveedores_lista";
	<?php }else{
	?>
	document.form1.action ="proveedores/publico/logout.php";		 
	<?php		 
	 	} 
	?>
 document.form1.submit();
 }
 function FguardarCat(prov){
       var date = new Date();
	  var timestamp = date.getTime();
	  if($("#categoria_prov").val()==''){
		alert('debe seleccionar la categoria a agregar');
		infocus('categoria_prov');
		return false;
	  }

 }

 function infocus(campo){
	try{
		  document.getElementById(campo).focus();
		  document.getElementById(campo).select();
		}catch(e){}
		//return false; 

}

 function subirarchivo(codigo){
	 if(codigo==''){
		alert('Primero Seria Bueno Crear el Proveedor');
   		return false; 
	 }
	 
  if($("#archivo1").val()==''){
   alert('debe seleccionar el archivo que desea subir');
   return false;
  }  
  if($("#tipo_adjunto").val()==''){
   alert('debe seleccionar el tipo de adjunto');
   return false;
  }
  
   if(confirm('seguro que desea subir este archivo')){
	  
     document.form1.action = 'crear_proveedor_guardar.php?tipoGuardar=Archivo&codigo='+codigo;
   }else{
   return false;
   } 
 }
 
  function ElimArchivo(codigo,prov){
	

   if(confirm('seguro que desea eliminar este archivo')){
	 // alert('prueba');
     document.form1.action = 'crear_proveedor_guardar.php?tipoGuardar=Eliminar_Archivo&codigo='+codigo+'&prov='+prov;
     document.form1.submit();
   }else{
   return;
   } 
 }
 
 	 function validarSiNumero(numero)
		{
			if (!/^([0-9])*$/.test(numero))
			alert("No se admiten caracteres - Digite el NIT " + numero + " Sin puntos y sin codigo de verificacion");
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
 
</script>

<!--<link rel="stylesheet" type="text/css" href="js/jquery.ui.css"/>-->
<link rel="stylesheet" type="text/css" href="messages.css"/>
<script src="messages.js" type="text/javascript"></script>
<style type="text/css">
.styled-select {
    background: url("../images/down_arrow_select.jpg") no-repeat scroll right center #DDDDDD;
    height: 34px;
    overflow: hidden;
    width: 240px;
}

.styled-select select {
    background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
    border: 1px solid #CCCCCC;
    font-size: 10px;
    height: 34px;
    padding: 5px;
    width: 268px;
}
.form-control{
 height:32px;
 border:1px solid #ccc;
 border-radius:4px;
 font-size:14px;
 color:#555;
 margin:3px 12px;
 }
.form-control:focus{
background:#EFFFE0;
border-color: #66afe9;
box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(102, 175, 233, 0.6);
outline: 0 none;
}
.labeltext{
font-family:Verdana,Arial,Helvetica,sans-serif;
font-weight:600;
font-size:13px;
color:#333333;

}
.contenttable{
 width:100%;
 min-width:1000px;
 overflow:hidden;
 /*border:solid 1px #ff0000;*/
 min-height:150px;
 /*margin:0 auto;*/
 background:#FFFFFF;
 border-radius:12px;
}
.detalles_contacto{
color:#E13030;
width:100%;
font-weight: 900;
font-size:25px;
}
.botonguardarprov{
padding:7px;
border-radius:7px;
color:#ffffff;
background: #9B0100;
}
form{
 padding-left:15px;
 padding-top:20px;
 padding-right:15px;
}
</style>
</head>

<body>
<div id="pagina">

<body style="background:#ffffff;">

<?php /*<div id="wrapper">*/ ?>

 <div class="contenttable container">
<form name="form1" id="form1" action="" method="post" enctype="multipart/form-data">
	
		<div class="panel panel-default jumbotron">
			<?php 
			if($_SESSION['MM_RolID'] == '7'){
			?>
			<div class="row">
				<div class="col-md-12 col-lg-12 text-right">
					<p class="font-weight-bold "><a href="proveedores/publico/logout.php" class="text-danger"><i class="fa fa-close"></i> Cerrar Sesi&oacute;n </a></p>
				</div>
			</div>
			<?php 
			}
			?>
			
            <div class="panel-heading">
              <h2 class="panel-titlde">Información</h2>
            </div>
            <div class="panel-body">
				<div class="row well">
					<div class="col-md-12 col-lg-12">
						<legend class="detalles_contacto">Detalles generales del proveedor</legend>
						 <table border="0" class="table" >
							<tr>
							  <td width="200" class="labeltext">Razon Social:</td>
							  <td width="280"><input class="form-control" type="text" name="nombre" value="<?php echo($nombre);?>" id="nombre" size="40"></td>
							  <td width="100" class="labeltext">Nit-Cedula:</td>
							  <td width="180"><input pattern="[0-9]*" onkeypress='return acceptNum(event)'; class="unica form-control" type="number" name="codigoprov" onChange="validarSiNumero(this.value);" value="<?php echo($registro2);?>" <?php echo($sololecturanit);?> id="codigoprov" size="40"></td>
							<div id='alert_proveedor' data-dismiss="alert" class="alert alert-danger" style="display:none;">...</div>
							</tr>							 
							<tr>
								<td class="labeltext">Tipo Persona</td>
									<td>
										<select id="tipo_persona" name="tipo_persona" class="form-control">
                      						<option value=""></option>
                      						<option value="1" <?php if($tipo_persona=='1'){ echo("selected");}?> >Persona Natural</option>
                      						<option value="2" <?php if($tipo_persona=='2'){ echo("selected");}?> >Persona Jurídica</option>
                    					</select>									
									</td>
							</tr>							
							<tr>
<td class="labeltext">Nombre Comercial:</td>
							 <td><input type="text" class="form-control" size="45" name="nombre_comercial" id="nombre_comercial" value="<?php echo($nombre_comercial);?>" id="direccion"></td>
							 <td width="100" class="labeltext">Telefono:</td>
							  <td><input type="text" class="form-control" name="telefono" id="telefono" value="<?php echo($telefono);?>" size="40"></td>							  
							</tr>
							<tr>
							 <td class="labeltext">E-mail:</td>
							 <td><input type="text" class="form-control" name="correo" id="correo" value="<?php echo($correo);?>" size="40"></td>
							 <td class="labeltext">Pagina Web:</td>
							 <td> <input type="text" class="form-control" name="pagina" id="pagina" value="<?php echo($web);?>" size="40"></td>
							<?php if ($tipoGuardar == 'Editar'){?>
							<td><a href="http://<?php echo($web);?>" target="_blank" title="visitar Página Web"><i class="fa fa-globe fa-2x"></i></a>
							
							</td>							 							 
							 </tr>
							<tr>

							 <?php }?>
							 </tr>
								 <tr>

							
							</tr>
							 <tr>
							 <td class="labeltext">Direcci&oacute;n:</td>
							 <td><input type="text" class="form-control" size="45" name="direccion" value="<?php echo($direccion);?>" id="direccion"></td>
							 <td class="labeltext">Ciudad:</td>
							 <td><input type="text" class="form-control" size="45" name="ciudad" value="<?php echo($ciudad);?>" id="ciudad"></td>							 
							</tr>
							 <tr>
								<td class="labeltext" >Régimen</td>
									<td width="250">
										<select id="regimen" name="regimen" class="form-control validate">
                      						<option value=""></option>
                      						<option value="1" <?php if($regimen=='1'){ echo("selected");}?> >No responsable de IVA</option>
                      						<option value="2" <?php if($regimen=='2'){ echo("selected");}?> >Responsable de IVA</option>
                      						<option value="3" <?php if($regimen=='3'){ echo("selected");}?> >No Aplica</option>
                    					</select>
									</td>
								<td class="labeltext">Autoretenedor</td>
									<td>
										<select id="autoretenedor" name="autoretenedor" class="form-control validate">
                      						<option value=""></option>
                      						<option value="1" <?php if($autoretenedor=='1'){ echo("selected");}?> >Si</option>
                      						<option value="2" <?php if($autoretenedor=='2'){ echo("selected");}?> >No</option>
                    					</select>
									</td>									
							</tr>	
							<tr>
								<td class="labeltext">Gran Contribuyente</td>
									<td>
										<select id="gran_contribuyente" name="gran_contribuyente" class="form-control validate">
                      						<option value=""></option>
                      						<option value="1" <?php if($gran_contribuyente=='1'){ echo("selected");}?> >Si</option>
                      						<option value="2" <?php if($gran_contribuyente=='2'){ echo("selected");}?> >No</option>
                    					</select>
									</td>
							 <td class="labeltext">Contribuyente Ica</td>
									<td>
										<select id="contribuyente_ica" name="contribuyente_ica" class="form-control validate">
                      						<option value=""></option>
                      						<option value="1" <?php if($contribuyente_ica=='1'){ echo("selected");}?> >Si</option>
                      						<option value="2" <?php if($contribuyente_ica=='2'){ echo("selected");}?> >No</option>
                    					</select>
									</td>									
							</tr>
							</table>					
					</div>
					<div class="col-md-6 col-lg-6">
						<legend class="detalles_contacto">Categorias del Proveedor</legend>
						<table>
							<tr>
								<td class="labeltext" width="110">Categoria:</td>
								<td>
									<select name="categoria_prov" id="categoria_prov" class="form-control" style="margin-left:0">				
									<option value="">- Seleccione una Categoria -</option>
									<?php
									require_once("scripts/funcionescombo.php");		
									$estados = dameCategoria();
										foreach($estados as $indice => $registro){
										?>
											<option value="<?php echo($registro['CLASCODI'])?>"><?php echo($registro['CLASNOMB']);?></option>
										<?php
										}
									
									?>
								</select>	
								</td>
								<?php
								 if((isset($_GET['cod_prov']) && $_GET['cod_prov'] != '') ||  $_SESSION['MM_RolID'] == '7'){
								?>
								<td><input class="buttonrojo" type="button" name="guardarprov" value="Agregar"  onclick="validarProveedor('<?php echo($tipoGuardar)?>');"></td>
								<?php 
								} 
								?>
						   </tr>
						</table>
						<table border="0" id="tablecat_prov">
					   <?php 
						if($totalRows_RsCategProv >0){
						  $j=0;
						  do{
						   $j++;
						   $estilo ="SB";
						   if($j%2==0){
						   $estilo = 'SB2';
						   }
						?>
						 <tr class="<?php echo($estilo);?>" id="trcat_<?php echo($row_RsCategProv['CLASIFICACION']);?>">
						  <td width="110"><input type="button" class="buttonrojo" value="Eliminar" onclick="FeliminarCategoria('<?php echo($row_RsCategProv['CODIGO']);?>','<?php echo($row_RsCategProv['CLASIFICACION']);?>');"></td>
						  <td align="left" style="color:#555; font-size:14px;" ><?php echo($row_RsCategProv['CLASIFICACION_DES']);?></td>
						 </tr>
					   <?php
							}while($row_RsCategProv = mysqli_fetch_assoc($RsCategProv));
						}
					   ?>	
						</table>						
					</div>
				</div>	

			<div class="row well">
				<div class="col-md-12 col-lg-12">
					<legend class="detalles_contacto">Convenios</legend>	
					<table>
						<tr>
						<td><input type="checkbox" name="convenio" class="labeltext" value="1" <?php if($convenio==1){ echo('checked');} ?> /><label>&iquest;Pertenece a un convenio&#63;</label> 
						<br></td>
						</tr>		
					</table>				
				</div>
			</div>
			<div class="row well">
				<div class="col-md-12 col-lg-12">
					<legend class="detalles_contacto">Detalles de Contacto</legend>
					<table class="table borderless ">
					<tr>
					  <td class="labeltext">Nombre: </td>
					  <td><input type="text" class="form-control" name="personac1" value="<?php echo($contacto1)?>" id="personac1"></td>
					  <td class="labeltext">Telefono: </td>
					  <td><input type="text" class="form-control" name="telefonoc1" value="<?php echo($telcontac1)?>" id="telefonoc1"></td>
					  <td class="labeltext">Cargo: </td>
					  <td><input type="text" class="form-control" name="cargo1" value="<?php echo($cargo1)?>" id="cargo1"></td>
					</tr>
					<tr>
					  <td class="labeltext">Nombre: </td>
					  <td><input type="text" class="form-control" name="personac2" value="<?php echo($contacto2)?>" id="personac2"></td>
					  <td class="labeltext">Telefono: </td>
					  <td><input type="text" class="form-control" name="telefonoc2" value="<?php echo($telcontac2)?>" id="telefonoc2"></td>
					  <td class="labeltext">Cargo: </td>
					  <td><input type="text" class="form-control" name="cargo2" value="<?php echo($cargo2)?>"  id="cargo2"></td>
					</tr>
					 <tr>
					  <td class="labeltext">comentarios: </td>
					  <td colspan="4" ><textarea class="form-control" name="comentarios" value="" id="comentarios" style="width:100%; height:60px;"><?php echo($comentarios)?></textarea></td>
					</tr>	
				  </table>				
				</div>
			</div>

			<div class="row well">
				<div class="col-md-12 col-lg-12">
					<legend class="detalles_contacto">Informaci&oacute;n y Anexos</legend>
					<div class="col-md-12 col-lg-12">
						<div class="row jumbotrdon">
							<div class="jumbotdron">
									<div class="col-md-4 col-lg-4">
										<div class="form-group">
										<label for="archivo1">Archivo</label>
										<input type="file" name="archivo1" id="archivo1" />
										</div>
									</div>
							<div class="col-md-4 col-lg-4">
							<div class="form-group">
								<label for="tipo_adjunto">&nbsp;Tipo de adjunto:</label>
								<select name="tipo_adjunto" id="tipo_adjunto" class="form-control">
									<option value="">...Seleccione...</option>
									<?php 
										if($totalRows_RsLista_adjuntos > 0){
											do{
										?>
										<option value="<?php echo($row_RsLista_adjuntos['COPRLABE']);?>"><?php echo($row_RsLista_adjuntos['COPRDESC']);?></option>
										<?php									
											}while($row_RsLista_adjuntos = mysqli_fetch_array($RsLista_adjuntos));
										}
									?>
								</select>
							</div>							
							
							</div>
							<div class="col-md-4 col-lg-4 text-right">
							<label for="archivo1">.</label>
								<button class="btn btn-block btn-danger" type="submit"  value="Subir Archivo" onclick="return subirarchivo('<?php echo($nit);?>');"><i class="fa fa-upload"></i> Subir Archivo</button>
							</div>
							</div>
						</div>
						
					</div>
						<div class="row">
							<div class="col-12 col-lg-12">
								<br>
								<br>
							</div>
						</div>
						<div class="row"> 
					 <?php
							if($totalRows_RsArchivosLista){
							
							 do{
							?>	
								<div class="col-md-4 col-lg-4">
								<li class="list-group-item list-group-item-dark">
								 	<div class="text-right">
			 								<a title="descargar" class="text-danger" href="downloadfile.php?doc=<?php echo($row_RsArchivosLista['ARCHIVO']);?>&tipopath=pr&codigo=<?php echo($get_codi); ?>&have_dir=<?php echo($row_RsArchivosLista['TIENE_DIR']);?>" target="_blank" ><i class="fa fa-download fa-2x"></i> </a>&nbsp;
			 								
										 	<a title="Eliminar" href="javascript:ElimArchivo('<?php echo($row_RsArchivosLista['CODIGO'])?>','<?php echo($nit);?>');" class="text-danger">
										 				<i class="fa fa-close fa-2x "> </i>
										 	</a>
								 	</div>										
							 		 <div class="text-left">
							 		 		<div class="text-danger">
							 		 		<i class="fa fa-dot-circle-o"></i>&nbsp;
							 		 			<?php echo($row_RsArchivosLista['ARCHIVO']);?> 
							 		 		</div>
							 			</div>
									</li>
								</div>



							 
							
							<?php
							   }while($row_RsArchivosLista = mysqli_fetch_array($RsArchivosLista));
							?>						
							</div>							
						
							<?php
							}else{
							
							echo('<p style="margin-left:20px;" class="SLAB2">Este Proveedor no tiene archivos adjuntos</p>');
							}
						?>							
				</div>
				<div class="col-md-6 col-lg-16">
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 col-lg-12 text-center">
						<input  type="hidden" name="nit" value="<?php echo($nit); ?>" />
						<input  type="hidden" name="estado_act" value="<?php echo($estado_act); ?>" />
						<input class="button2" type="button" id="idbtnguardar" name="guardarprov" value="<?php echo($tipoGuardar); ?>"  onclick="validarProveedor('<?php echo($tipoGuardar); ?>');">
						<input class="button2" type="button" name="salir" value="salir" onclick="return volveraListado();">	
				</div>
				
			
			</div>

            </div>
          </div>	
	


	
  </form>
</div>
 
<script type="text/javascript">


	$( ".unica" ).change(function() { 
	ide = $(this).val();
	  if(ide != ''){
       $("#usuario").val(ide);
			var date = new Date();
			var timestamp = date.getTime();
			//var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=CargarDatos&time="+timestamp+"&codigo_detalle="+det);
			 $.ajax({
			  type: "POST",
			  url: "tipo_guardar.php?tipoGuardar=VerificarProveedorExiste&identificacion="+$(this).val()+"&timers="+timestamp,
			  dataType: 'json',
			  success : function(r){ //console.log(r);
				if(r != ''){
					if(r.status == 'erroruser'){
						//console.log('proveedor ya existe');
						$("#alert_proveedor").css("display","block");
						$("#idbtnguardar").hide();
						$("#alert_proveedor").html("Error el proveedor ya existe registrado como "+r.prov);	
						
					}
				
					if(r.status == 'ok'){
						//console.log('proveedor no existe');	
                        $("#alert_proveedor").css("display","none");
                        $("#idbtnguardar").show();     
						
						
					}
				   }
				},
			  error   : callback_error
			});		  
	  }else{
		   $("#mensajeunico").css("display","none");
		   DisabledInput();
	  }
	});
	
	function callback_error(XMLHttpRequest, textStatus, errorThrown)
{
    alert("Respuesta del servidor "+XMLHttpRequest.responseText);
    alert("Error "+textStatus);
    alert(errorThrown);
}


</script>
</body>
</html>