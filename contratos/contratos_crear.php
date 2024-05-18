<?php
include('../conexion/db.php');

$tipoGuardar = ''; 

if(isset($_GET['cod']) && $_GET['cod'] != '')
{	
	$query_RsEditar="SELECT `CONTID`  ID,
									  `CONTNUME` NUMERO,
									  `CONTCLAS` CLASE,
									  `CONTOBJE` OBJETO,									  
									  `CONTCOID` ID_CONTRATISTA,
									  (SELECT P.PROVNOMB 
									   FROM  proveedores P 
									   WHERE P.PROVCODI=C.CONTCOID) CONTRATISTA,
									  `CONTFEIN` FECHA_INICIO,
									  `CONTFEFI` FECHA_FIN,
									  `CONTFETR` FECHA_DEFINITIVA,
									  `CONTNHOR` NUMHORAS,
									  `CONTFOPA` FORMA_PAGO,
									  `CONTVACU` VALOR
							 FROM CONTRATOS C
						     WHERE CONTID= '".$_GET['cod']."'";
				 //echo($query_RsEditar);echo("<br>");
   	$RsEditar = mysqli_query($conexion,$query_RsEditar) or die(mysqli_error($conexion));
	$row_RsEditar = mysqli_fetch_assoc($RsEditar);
    $totalRows_RsEditar = mysqli_num_rows($RsEditar);
	
	$contrato_codigo		= $_GET['cod'];
	$n_contrato        		= $row_RsEditar['NUMERO'];
	$contrato_objeto   		= $row_RsEditar['OBJETO'];
	$contrato_clase			= $row_RsEditar['CLASE'];
	$idcontratista			= $row_RsEditar['ID_CONTRATISTA'];
	$contrato_contratista   = $row_RsEditar['CONTRATISTA'];
	$fecha_inicio 			= $row_RsEditar['FECHA_INICIO'];
	$fecha_fin  			= $row_RsEditar['FECHA_FIN'];
	$contrato_nhoras		= $row_RsEditar['NUMHORAS'];
	$contrato_fpago  		= $row_RsEditar['FORMA_PAGO'];
	$contrato_valor 		= $row_RsEditar['VALOR'];
	
	$tipoGuardar= 'Editar';


    $query_RsListaAnexos="SELECT COANCODI CODIGO,
							  COANCONT CONTRATO,
                              COANDESC DESCRIPCION,
							  COANARCH ARCHIVO_RUTA,
							  DATE_FORMAT(COANFECH, '%d/%m/%Y') FECHA
						FROM  contratos_anexos,
							  contratos
						WHERE CONTID=COANCONT
						AND	  CONTID='".$_GET['cod']."'";
				 // echo($query_RsListaAnexos);
	$RsListaAnexos = mysqli_query($conexion,$query_RsListaAnexos) or die(mysqli_error($conexion));
	$row_RsListaAnexos = mysqli_fetch_array($RsListaAnexos);
    $totalRows_RsListaAnexos = mysqli_num_rows($RsListaAnexos);
    
	$query_RsListaOtrosi="SELECT COOTID ID,
							     COOTNUME NUMERO,
								 COOTCLAS ClASE,
								 COOTOBJE OBJETO,
								 COOTCOID,
								 (SELECT P.PROVNOMB 
									   FROM  proveedores P 
									   WHERE P.PROVCODI=COOTCOID) CONTRATISTA,								 								 
								 date_format(COOTFEFI,'%d/%m/%Y') FECHA_FIN,
								 COOTNHOR,
								 COOTFOPA,
								 COOTVACU								  
						   FROM  CONTRATOS_OTROSI
						   WHERE COOTIDCO='".$_GET['cod']."'";
				 // echo($query_RsListaAnexos);
	$RsListaOtrosi = mysqli_query($conexion,$query_RsListaOtrosi) or die(mysqli_error($conexion));
	$row_RsListaOtrosi = mysqli_fetch_array($RsListaOtrosi);
    $totalRows_RsListaOtrosi = mysqli_num_rows($RsListaOtrosi);


}else{
		$contrato_codigo		= "";
		$n_contrato        		= "";
		$contrato_objeto   		= "";
		$contrato_clase			= "";
		$idcontratista			= "";
		$contrato_contratista   = "";
		$fecha_inicio 			= "";
		$fecha_fin  			= "";
		$contrato_nhoras		= "";
		$contrato_fpago  		= "";
		$contrato_valor 		= "";
		
		$tipoGuardar='Guardar'; 
}

 
?>

<!DOCTYPE html>
	<html lang="es">
		<head>
		<title>Contratos Sanboni</title>
		<link rel="stylesheet" type="text/css" href="css/contratos/contratos.css"/>
		</head>
			<body>
			<div class="container">
				<form name="form1" id="form1" action="" method="post"  enctype="multipart/form-data">
					<div class="row">
						<div class="col col-sm-3">
							<b>Información General Del Contrato</b> 
						</div>
					</div>
					
					<?php if(isset($_GET['cod']) && $_GET['cod'] != ''){ ?>
					
					<div class="row">
						<div class="col col-sm-1">
							<b><?php echo($n_contrato);?></b> 
					</div>
					</div>	
					
					<?php } ?>  
     
					<div class="row">
						<div class="col col-sm-2">
							<b>Contrato:</b>
							<input class="form-control form-control-sm" type="text" name="contrato_numero" value="<?php echo($n_contrato);?>" placeholder="Numero de Contrato" id="contrato_numero">
						</div>
						<div class="col col-sm-2">
							<b>Fecha Inicio:</b>
							<input class="form-control form-control-sm" type="text" name="fecha_inicio" id="fecha_inicio" value="<?php echo($fecha_inicio);?>"  readonly placeholder="Fecha Inicio" >
						</div>
						<div class="col col-sm-2">
							<b>Fecha Fin:</b>
							<input class="form-control form-control-sm" type="text" name="fecha_fin" id="fecha_fin"  value="<?php echo($fecha_fin);?>" readonly   placeholder="Fecha Fin" >
						</div>
						<div class="col col-sm-6">
							<b>Clase:</b>
							<input class="form-control form-control-sm" type="text" name="contrato_clase" value="<?php echo($contrato_clase);?>" placeholder="Clase de Contrato" id="contrato_clase">
						</div>
					</div>	     
					<div class="row">
						<div class="col col-sm-12">
							<b>Objeto del contrato:</b>
							<textarea class="form-control" name="contrato_objeto" id="contrato_objeto"  placeholder="Objeto del contrato" rows="3"><?php echo($contrato_objeto);?></textarea>
						</div>
					</div>
					<div class="row">				
						<div class="col col-sm-8">
							<b>Contratista:</b>
							<!-- inicio boton combo de proveedor contratos -->
								<select name="contrato_contratista" id="contrato_contratista" class="form-control form-control-sm chzn-select"> 
									<option value="<?php echo($idcontratista);?>"><?php echo($contrato_contratista);?></option>
									<?php
										require_once("scripts/funcionescombo.php");		
										$proveedores = dameProveedorContratos();
										foreach($proveedores as $indice => $registro)
										{
									?>
											<option value="<?php echo($registro['CODIGO'])?>"><?php echo($registro['NOMBRE']);?></option>
									<?php
										}
									?>
								</select>    
							<!-- fin -->	 
						</div>	
						<div class="col col-sm-4">
							<b>Forma de Pago:</b>
							<input class="form-control form-control-sm" type="text" name="contrato_fpago" value="<?php echo($contrato_fpago);?>" placeholder="Forma de Pago" id="contrato_fpago">
						</div>			
					</div>	
					<div class="row">	
						<div class="col col-sm-2">
							<b>N° horas Contratada:</b>
							<input class="form-control form-control-sm" type="text" name="contrato_nhoras" value="<?php echo($contrato_nhoras);?>" placeholder="Horas" id="contrato_nhoras">
						</div>			
						<div class="col col-sm-3">
							<b>Valor Cuantia:</b>
							<input class="form-control form-control-sm" type="text" name="contrato_valor" value="<?php echo($contrato_valor);?>" placeholder="Valor de la cuantia" id="contrato_valor">
						</div>
					</div>
					
					<?php if(isset($_GET['cod']) && $_GET['cod'] != ''){ ?>						
					
					<div class="row">
						<div class="col col-sm-12">
							<input type="file"  name="archivo1" id="archivo1">	
							<input class="button2" type="text"  value="" name="descripcion_archivo" placeholder="Descripción del Archivo" id="descripcion_archivo"  />
							<input class="button2" type="submit"  value="Cargar Archivo" onclick="return subirarchivo('<?php echo($n_contrato);?>');" />
			    		</div>
					</div>
					<div class="row">
						<div class="col col-sm-12">
					
							<?php  
								if($totalRows_RsListaAnexos >0)
								{
								  $k=0;
								  do{
										$k++;
										$estilo="SB";
										if($k%2==0){
										  $estilo="SB2";
										}
							?>
								 
										 <table>
											<tr class="<?php echo($estilo);?>">
												<td> 
													<input type="submit" class="buttonrojo" value="Eliminar" onclick="return DeleteAnexo('<?php echo($row_RsListaAnexos['CODIGO']);?>');">
													<a href="downloadfile.php?doc=<?php echo($n_contrato."/".$row_RsListaAnexos['ARCHIVO_RUTA']);?>&tipopath=acont" target="_back"><?php echo($row_RsListaAnexos['DESCRIPCION']);?></a>
													<?php echo($row_RsListaAnexos['FECHA']);?>
												</td>
											</tr>
										</table>		
								
							<?php
									}while($row_RsListaAnexos = mysqli_fetch_array($RsListaAnexos));
								}
							?>
	
						</div>
					</div>
					<div class="row">
						<div class="col col-sm-12">
							<!-- Button trigger modal -->
								<button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#exampleModalScrollable">
									<i class="fa fa-plus-circle"></i> 
									<md-tooltip md-direction="right">Otrosí</md-tooltip>			
								</button>	
								<table class="table table-bordered">
									<thead>
										<tr>
											<th scope="col">#</th>
											<th scope="col">Descripción</th>
											<th scope="col">Clase</th>
											<th scope="col">Proveedor</th>
											<th scope="col">Fecha</th>
										</tr>
									 </thead>
									 <tbody>
										 <?php  
											if($totalRows_RsListaOtrosi >0)
											{
											  do{
													
													
										 ?>
													<tr>
														<th scope="row">
															<a  class="btn btn-link" onclick="update_otrosi('<?php echo($row_RsListaOtrosi['ID']);?>');"  data-toggle="modal" data-target="#exampleModalScrollable" role="button"><?php echo($row_RsListaOtrosi['NUMERO']);?></a>		
														</th>
														<td class="otrosi_o"><?php echo($row_RsListaOtrosi['OBJETO']);?></td>
														<td class="otrosi_o"><?php echo($row_RsListaOtrosi['ClASE']);?></td>														
														<td class="otrosi_p"><?php echo($row_RsListaOtrosi['CONTRATISTA']);?></td>
														<td class="otrosi_f"><?php echo($row_RsListaOtrosi['FECHA_FIN']);?></td>
													</tr>				
										<?php
												}while($row_RsListaOtrosi = mysqli_fetch_array($RsListaOtrosi));
											}
										?>	
									</tbody>
								</table>		
						</div>
					</div>		
					<?php } ?>	
					<div class="row">
						<div class="col col-sm-12">
							<input  type="hidden"  name="contrato_codigo" value="<?php echo($contrato_codigo); ?>" >
                          							
							<input class="button2" type="submit"  name="guardarprov" value="<?php echo($tipoGuardar); ?>"  onclick=" validarCampos('<?php echo($tipoGuardar); ?>'); " />
						</div>
					</div>
					</div>
				</form>
					<?php  include('otrosi_crear.php'); ?>
			</body>
</html>

<script type="text/javascript"> $(".chzn-select").chosen(); $(".chzn-select-deselect").chosen({allow_single_deselect:true}); </script>
<script type="text/javascript">

function validarCampos(TG){
	
	
	var contrato_codigo		=document.form1.contrato_codigo.value;
	var contrato_numero		=document.form1.contrato_numero.value;
	var contrato_objeto		=document.form1.contrato_objeto.value;
	var contrato_clase		=document.form1.contrato_clase.value;
	var contrato_contratista=document.form1.contrato_contratista.value;
	var fecha_inicio		=document.form1.fecha_inicio.value;
	var fecha_fin			=document.form1.fecha_fin.value;
	var contrato_nhoras		=document.form1.contrato_nhoras.value;
	var contrato_fpago		=document.form1.contrato_fpago.value;
	var contrato_valor		=document.form1.contrato_valor.value;
	

	// Validacion campos mensajes 
    		
		if(contrato_numero == '')
		  {
		   inlineMsg('contrato_numero','debe digitar el Numero de Contrato.',3);
				return false;
		  }
		
		 if(contrato_objeto == '')
		  {
		   inlineMsg('contrato_objeto','debe digitar el Objeto del Contrato.',3);
				return false;
		  }	
	
  
    if(TG=="Editar"){ 

				if(confirm('Esta seguro de almacenar estos datos?')){	
			
						document.form1.action="contratos/contratos_guardar.php?tipoGuardar="+TG;
						document.form1.submit(); 
				}
		}else if(TG=="Guardar"){
							if(confirm('Esta seguro de almacenar estos datos?')){			  
								document.form1.action="contratos/contratos_guardar.php?tipoGuardar="+TG;
								document.form1.submit(); 
								}
		
							}else{
									alert("Pongase en contacto con el proveedor Linea 112. contratos_crear");
									}
}

function update_otrosi(id){
	
	    var date = new Date();
		var timestamp = date.getTime();
		$.ajax({
			type: "POST",
			url: "contratos/otrosi_guardar.php?tipoGuardar_otrosi=cargar_otrosi&id="+id+"&timestamp="+timestamp,
			dataType: 'json',
			success : function(r){
					if(r.status == 'failed'){
						console.log("error");
					}
					if(r.status == 'ok'){
						$("#otrosi_codigo").val(r.data.ID);
						$("#otrosi_numero").val(r.data.NUMERO);						
						$("#otrosi_objeto").val(r.data.OBJETO);
						$("#otrosi_clase").val(r.data.CLASE_OTROSI);
						$("#otrosi_contratista").val(r.data.ID_CONTRATISTA);						
						$("#otrosi_fecha").val(r.data.FECHA_FIN_OTROSI);
						$("#otrosi_nhoras").val(r.data.N_HORAS);
						$("#otrosi_fpago").val(r.data.FORMA_PAGO_OTROSI);
						$("#otrosi_valor").val(r.data.VALOR_CUANTIA_OTROSI);
						
						
					}
					
				},
			data: {},
			error   : function(XMLHttpRequest, textStatus, errorThrown){
			alert("Respuesta del servidor "+XMLHttpRequest.responseText);
			alert("Error "+textStatus);
			alert(errorThrown);
			}
		});	
	
}



$("#fecha_inicio").datepicker({
   showOn: 'both',
   buttonImage: 'images/calendar.png',
   buttonImageOnly: true,
   changeYear: true,
   changeMonth:true,
   showWeek: true,
   dateFormat: 'dd/mm/yy',
   //minDate: 0,
   //regional:'es',
   //numberOfMonths: 2,
   onSelect: function(fech, objDatepicker){
	   
   }
});


$("#fecha_fin").datepicker({
   showOn: 'both',
   buttonImage: 'images/calendar.png',
   buttonImageOnly: true,
   changeYear: true,
   changeMonth:true,
   showWeek: true,
   dateFormat: 'dd/mm/yy',
   //minDate: 0,
   //regional:'es',
   //numberOfMonths: 2,
   onSelect: function(fech, objDatepicker){
	   
   }
});


 function subirarchivo(numero)
 {	 
	if($("#archivo1").val()=='')
	{
		alert('debe seleccionar el archivo que desea subir');
		return false;
	}
   if(confirm('seguro que desea subir este archivo'))
   {	   
		document.getElementById("form1").action = "contratos/contratos_guardar.php?tipoGuardar=Archivo_Cargar&carpeta="+numero;
		document.form1.submit(); 
   }else	
		{
		return false;
		} 
 }
 
 //funciones de manipulacion  de datos
 
	
	function DeleteAnexo(cod){
   if(confirm('seguro que desea eliminar este anexo')){
     document.form1.action = 'contratos/contratos_guardar.php?tipoGuardar=EliminarAnexo&codanexo='+cod;
   }else{
   return false;
   }	
} 

</script>
