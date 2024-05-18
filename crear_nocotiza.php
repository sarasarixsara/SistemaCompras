<?php 
require_once('conexion/db.php');

	$query_RsArchivosLista="SELECT DERECONS,
								  `DEREDESC`,
								  `REQUCORE`,
								   DERECANT ,
								   DEREJUST,
								   DEREOBSE
							FROM `detalle_requ`,
   							      requerimientos 
							WHERE dereapro=11
							 AND `DEREREQU`=REQUCODI 
							 AND `DERENCOT` <> ''
							 order by DERECONS DESC
							 ";
							 //echo ($query_RsArchivosLista);
	$RsArchivosLista = mysqli_query($conexion,$query_RsArchivosLista) or die(mysqli_error($conexion));
	$row_RsArchivosLista = mysqli_fetch_array($RsArchivosLista);
    $totalRows_RsArchivosLista = mysqli_num_rows($RsArchivosLista);

	$query_RsConsulta="SELECT (ORNCCONS+1) CODIGO_ORDEN FROM `orden_compra_ncotiza` order by ORNCCONS desc limit 1";
	$RsConsulta = mysqli_query($conexion,$query_RsConsulta) or die(mysqli_error($conexion));
	$row_RsConsulta = mysqli_fetch_array($RsConsulta);
    $totalRows_RsConsulta = mysqli_num_rows($RsConsulta);
?>

<style type="text/css">

.TB_overlayBGcot {
  height: 100%;
  left: 0;
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 1500;
  background-color: #FFFFFF;
  opacity: 0.55;
}  
.no_visible {
  display: none;
}
#tbl_orden {
	font-size:11px;
}
.btncerrarpop{
  float: right;
  margin-right: 5px;
  color: black;
  font-weight: 700;
  font-size: 26px;
  padding: 3px 7px;
  background-color: #ccc;
  border-radius: 12px;
  cursor:pointer;
}

.input_cant2{
 background:inherit;
 text-align:right;
 }
 
 .Titulo2{
 font-weight:500;
 color:#000000;
 font-size:21px;
 background:#d9d3ff;
 padding-left:12px;
 padding-right:12px;
 }
.coddeta{
	color:#555;
	font-size:9px;
	padding:2px 5px;
}
 .input_cant2{
 background:inherit;
 text-align:right;
 }
 .valorivapre{
	 background:inherit;
	 border:0;
	 box-shadow:1px 1px 5px #E0DDDD;
 }
 .valoriva{
	 border-radius:10px;
 }
 .addfila{
	 border-radius:5px;
	 solid 1px #ccc;
 }
</style>

<head>
		<title>No Cotiza-Compras</title>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<form role="form">
  <div class="panel-body">
    <div class="row">
	 <div class="col-lg-1"   >
		<label for="id_prov" class="">Proveedore:</label></div>								
		  <div class="col-lg-11"><label for="id_prov" class=""><input  type="text" name="proveedor" id="proveedor" value=""/><?php ?>	</label></div>
				</div>
					<div class="row">
						<div class="col-lg-1"   ><label for="id_nit" class="">Nit :</label></div>								
						<div class="col-lg-11"	><label for="id_nit" class=""><input  onChange="validarSiNumero(this.value);" type="text" name="nit" id="nit" value=""/> <?php ?>	</label></div>
					</div> 
					<div class="row">
						<div class="col-lg-1"   ><label for="id_email" class="">Email:</label></div>								
						<div class="col-lg-11"	><label for="id_email" class=""><input  type="text" name="email" id="email" value=""/><?php ?>	</label></div>
					</div> 
					<div class="row">
						<div class="col-lg-1"   ><label for="id_telef" class="">Telefono:			</label></div>								
						<div class="col-lg-11"	><label for="id_telef" class=""><input  type="text" name="telefono" id="telefono"  value=""/><?php ?>	</label></div>
					</div> 	
					<div class="row">
						<div class="col-lg-1"   ><label for="id_direc" class="">Direcci&oacute;n:			</label></div>								
						<div class="col-lg-11"	><label for="id_direc" class=""><input  type="text" name="direccion" id="direccion" value=""/> <?php ?>	</label></div>
					</div> 
					<div class="row">
						<div class="col-lg-1"   ><label for="id_obser" class="">Observaci&oacute;n:			</label></div>								
						<div class="col-lg-11"	><label for="id_obser" class=""><textarea class="form-control" name="observacion" id="observacion"  rows="3"></textarea></div>
					</div>
		            <input type="hidden" name="codigorden" id="codigorden" value="<?php  echo($row_RsConsulta['CODIGO_ORDEN']) ?>" />
													
				<!-- <input title="" type="button" id="btnnocotiza" class="buttonazul" value="Guardar" onclick="f_Crear_orden_ncotiza();"/>-->
					<input title="" type="button" id="btnnocotiza" class="buttonazul" value="Guardar" onclick="f_Crear_MenorCuantia();"/>
				
 </div>	
<div id="TB_overlaycot" class="" onclick="close_popup();">
<span onclick="close_popup();" title="click para cerrar" id="spancerrarpop" ></span>
</div>
 <div id="popup_ncot" 
 style="
 float:left;
 border: solid 3px #780002;
 width:70%;
 position:absolute;
 z-index:9999;
 background:#f2f2f2
 margin: 0 13%; 
 padding:10px; 
 border-radius:8px; " 
 
 class="no_visible">
	   Alina me
</div>
 <div id="popup_2" 
 style="
 float:left;
 border: solid 3px #780002;
 width:70%;
 position:absolute;
 z-index:9999;
 background:#f2f2f2
 margin: 0 13%; 
 padding:10px; 
 border-radius:8px; " 
 
 class="no_visible">
	   Alina me
</div>
</form>							
<div class="row SLAB trtitle" >
<div class="col-lg-4"   ><label for="id_prov" class="">Descripcion			</label></div>
<div class="col-lg-4"   ><label for="id_prov" class="">Justificación - Observación	</label></div>								
<div class="col-lg-2"   ><label for="id_prov" class="">Cantidad			</label></div>								
<div class="col-lg-2"	><label for="id_prov" class="">Requerimiento	</label></div>
</div> 
<div id="det_nocotiza">
  <?php 
  if($totalRows_RsArchivosLista>0){
  $k=0;
  $estilo = "S2";
  do{
	$k++;
	if($k%2==0){
		$estilo = "SB";
	}else{
		$estilo ="SB2";
	}
	
  ?>
    							<div class="row <?php  echo($estilo);?>" id="fila_<?php  echo($row_RsArchivosLista['DERECONS']) ?>">
								<div class="col-lg-4"	>
	                            <div>
								<label>	<input type="checkbox" data-detnocot="<?php  echo($row_RsArchivosLista['DERECONS']) ?>" class="adddet" name="no_cotiza_<?php  echo($row_RsArchivosLista['DERECONS']) ?>" id="no_cotiza_<?php  echo($row_RsArchivosLista['DERECONS']) ?>" /><span class="coddeta">(<?php  echo($row_RsArchivosLista['DERECONS']) ?>)</span><span id="descnocot_<?php  echo($row_RsArchivosLista['DERECONS']) ?>"><?php  echo($row_RsArchivosLista['DEREDESC']) ?></span> </label>	
								</label>								
								</div>
								
								</div>
                                <div class="col-lg-4">
								<?php  echo($row_RsArchivosLista['DEREJUST'].'<BR>') ?>
								<?php  echo($row_RsArchivosLista['DEREOBSE']) ?>
								
								</div>								
								<div class="col-lg-2"	>
									<div id="cant_<?php  echo($row_RsArchivosLista['DERECONS']);?>" ><?php  echo($row_RsArchivosLista['DERECANT']) ?></div>	
								</div>
								<div class="col-lg-2"	>
									<div id="reqnocot_<?php  echo($row_RsArchivosLista['DERECONS']);?>" ><?php  echo($row_RsArchivosLista['REQUCORE']) ?></div>	
								</div>						
							    </div>
							



 
  <?php 
  }while($row_RsArchivosLista = mysqli_fetch_array($RsArchivosLista)); 
 }
  ?>
  </div>
  
  <script type="text/template" id='ordenprod-template'>
  
  <table class="table" align="center" border="1" style="width:70%; !important">
  <tbody>
 <%
        // repeat items 
        _.each(itemsdetalles,function(item,key,list){
      %>
	    <%
	   if(key==0){
		  
		  
	  %>
  <tr  ></tr>
  <tr><td class="SLAB trtitle" align="center" colspan="9">ORDEN NO COTIZA #<span id="t_codord"></span>	</td> </tr>
  <tr><td class="active">Proveedor:				</td> <td class="info" colspan="5"><span id="t_prov"></span>		</td></tr>
  <tr><td class="active">Nit:					</td> <td class="info" colspan="5"><span id="t_nit"></span>			</td></tr>
  <tr><td class="active">Email:					</td> <td class="info" colspan="5"><span id="t_email"></span>		</td></tr>
  <tr><td class="active">Telefono:				</td> <td class="info" colspan="5"><span id="t_telef"></span>		</td></tr>
  <tr><td class="active" >Direccion:				</td> <td class="info" colspan="5"><span id="t_direc"></span>		</td></tr>

  <tr >
    <td class="SLAB trtitle" align="center" colspan="6" >Listado Detalles No Cotizan</td>
  </tr>
  <tr style="font-weight:bold;">
    <td class="active" >Requerimiento</td>
    <td class="active" >Descripción</td>
    <td class="active">Cantidad</td>
    <td class="active">V/unit</td>
    <td class="active">iva</td>
    <td class="active" >V/total</td>
  </tr>  
	  <% } %>
 
  <!-- Aplicadas en las celdas (<td> o <th>) -->

  <tr class="<% if(key%2==0){%>SB2<%}else{%>SB <%} %>">
    <td class=""><%- item.req %>																			 </td>
	<td class="" >
     <textarea name="descripcion_<%- item.det %>" id="descripcion_<%- item.det %>" cols="26" rows="3"></textarea><br>
	 <%- item.desc %>
	 </td>
   	<td class="" ><div id="cant_<%- item.det %>"><%- item.cant %></div>										 </td>    
    <td class="" ><input class="addfila" type="text" name="v_unit_<%- item.det %>" id="v_unit_<%- item.det %>" size="7" value=""  onKeyPress="return acceptNum(event);" value="" onchange="unitario('<%- item.det %>');"/> </td>
	<td>%
    <input size="2" title="porcentaje de iva" class="valoriva" type="text" name="valoriva_<%- item.det %>" id="valoriva_<%- item.det %>" onkeypress="return acceptNum(event);" value="" maxlength="2" onchange="Fiva('<%- item.det %>');"><br>
	<input type="text" class="valorivapre" name="valorivapre_<%- item.det %>" id="valorivapre_<%- item.det %>" value="" size="14" readonly="">
	</td>
	<td class="" ><span class="total2">$</span>&nbsp;<input class="input_cant2" readonly type="text" size="17" name="valortotal_<%- item.det %>" id="valortotal_<%- item.det %>" value="">
												
												
	</td>
  </tr>
  <tr>
  
  
  </tr>
  
  
			 <%
		});
      %>
	  <tr><td colspan="6">
	   <table border="0" align="right" width="39%">
                        <tr>						 
						                         <tr>						 
						 <td align="right" class="Titulo2">Subtotal: <span class="total">$</span></td>
						  <td>
						  <input class="total" type="text" name="subtotal" id="subtotal" size="12" readonly></td>
					    </tr>
						<tr>
						  <td align="right" class="Titulo2">Iva: <span class="total">$</span></td>
						  <td>
						  <input class="total" type="text" name="iva" id="iva" size="12" readonly></td>
					    </tr>
						<tr>
						   <td colspan="" align="right" CLASS="Titulo2" align="center">Total: <span class="total">$</span></td>
						   <td  >
						   <input class="total" type="text" name="total" id="total" size="12" readonly ></td>
						</tr>
						<tr>
						<td align="right" class="Titulo2">Flete: <span class="total">$</span></td>
						  <td><input class="total" type="text" name="flete" id="flete" size="12" onKeyPress="return acceptNum(event);" value="" ></td>
					    </tr>
						</tr>
						</td>
					  </table>
					  </td></tr>
	   <tr class="active">	 <td colspan="1" class="active">Observaciones:	</td> <td class="info" colspan="4">	
<textarea name="t_obser" id="t_obser" cols="70" rows="3"></textarea>
	   </td></tr>
	    <tr class="active">	 <td colspan="1"  class="active">Fecha Entrega:	</td> <td class="info" colspan="4" ><input  type="date"  name="t_fechent" id="t_fechent" size="10" value=""/> 		</td></tr>
  <tr>
  <td colspan="5" align="center">
			 <input type="button" class="buttonazul" name="savedeta" id="savedeta" value="Generar" onclick="SaveDeta();">
			 
			</td>
			</tr>
</tbody>
</table>
  </script> 


<script type="text/template" id='menorcuantia-template'>


  <table class="table" align="center" border="1" style="width:70%; !important">
  <tbody>
 <%
        // repeat items 
        _.each(itemsdetalles,function(item,key,list)
        {
      %>
	  <%
	   		if(key==0)
	   		{
	  %>
  				  <tr  ></tr>
				  <tr><td class="SLAB trtitle" align="center" colspan="9">ORDEN MENOR CUANTIA #<span id="t_codord"></span>	</td> </tr>
				  <tr><td class="active">Proveedor:				</td> <td class="info" colspan="5"><span id="t_prov"></span>		</td></tr>
				  <tr><td class="active">Nit:					</td> <td class="info" colspan="5"><span id="t_nit"></span>			</td></tr>
				  <tr><td class="active">Email:					</td> <td class="info" colspan="5"><span id="t_email"></span>		</td></tr>
				  <tr><td class="active">Telefono:				</td> <td class="info" colspan="5"><span id="t_telef"></span>		</td></tr>
				  <tr><td class="active" >Direccion:				</td> <td class="info" colspan="5"><span id="t_direc"></span>		</td></tr>
				  <tr >
				    <td class="SLAB trtitle" align="center" colspan="6" >Listado Detalles Menor Cuantia</td>
				  </tr>
				  <tr style="font-weight:bold;">
				    <td class="active" >Requerimiento</td>
				    <td class="active" >Descripción</td>
				    <td class="active">Cantidad</td>    
				  </tr>  
	  <% 	} 
	  %>
				 
				  <!-- Aplicadas en las celdas (<td> o <th>) -->

				  <tr class="<% if(key%2==0){%>SB2<%}else{%>SB <%} %>">
					  	<td class="">	<%- item.req %>			</td>
						<td class="">   <%- item.desc %>		</td>
				   		<td class="" >	<%- item.cant %>		</td>    
				  </tr>
				    
	  <%
		});
      %>
	  	<tr>
	  		<td colspan="6">
	   			<table border="0" align="right" width="39%">
                        <tr>						 
						   	<tr>						 
						 		<td align="right" class="Titulo2">Subtotal: <span class="total">$</span></td>
						  		<td><input class="total" type="text" name="MCsubtotal" id="MCsubtotal" size="12" value="" onKeyPress="return acceptNum(event);" onchange="MCcalculartotal();" /></td>
					    	</tr>
							<tr>
						  		<td align="right" class="Titulo2">Iva: <span class="total">$</span></td>
						  		<td><input class="total" type="text" name="MCiva" id="MCiva" value="" size="12" onKeyPress="return acceptNum(event);" onchange="MCcalculartotal();"></td>
					   		</tr>
							<tr>
						   		<td colspan="" align="right" CLASS="Titulo2" align="center">Total: <span class="total">$</span></td>
						   		<td><input class="total" type="text" name="MCtotal" value="" id="MCtotal" size="12" readonly ></td>
							</tr>
							<tr>
								<td align="right" class="Titulo2">Flete: <span class="total">$</span></td>
						  		<td><input class="total" type="text" name="MCflete" id="MCflete" size="12" onKeyPress="return acceptNum(event);" value="" ></td>
					    	</tr>
						</tr>
						
				</table>
		    </td>
		</tr>
	   	<tr class="active">	 
	   		<td colspan="1" class="active">Observaciones:	</td> 
	   		<td class="info" colspan="4"><textarea name="MCt_obser" id="MCt_obser" cols="70" rows="3"></textarea></td>
	   	</tr>
	    <tr class="active">	 
	    	<td colspan="1"  class="active">Fecha Entrega:	</td> 
	    	<td class="info" colspan="4" ><input  type="date"  name="MCt_fechent" id="MCt_fechent" size="10" value=""/> </td>
	    </tr>
  		<tr>
  			<td colspan="5" align="center"><input type="button" class="buttonazul" name="MCsavedeta" id="MCsavedeta" value="Generar" onclick="MCSaveDeta();"></td>
		</tr>
	</tbody>
</table>
  </script> 
<script>
	function callback_error(XMLHttpRequest, textStatus, errorThrown)
	{
	    $("#btnordencotizar").css("display","block");
	    alert("Respuesta del servidor "+XMLHttpRequest.responseText);
	    alert("Error "+textStatus);
	    alert(errorThrown);
	}

	function f_Crear_orden_ncotiza(){
	
	if($('#proveedor').val() == ''){
		alert('Falta el Proveedor');
		return;
	}

	if($('#nit').val() == ''){
		alert('Falta el Nit');
		return;
	}


	var newArrayD = new Array();
	//saber que checkbox estan seleccionados
	marcados =0;
		$("#det_nocotiza .adddet").each(function(index){
		  if($(this).is(':checked')){
			  marcados = 1;
			  d		= $(this).attr('data-detnocot');
			  desc	= $('#descnocot_'+d).text();
			  cant 	= $('#cant_'+d).text();
			  /*console.log("after cant");
			  console.log(cant);
			  console.log("before cant");*/
              req 	= $('#reqnocot_'+d).text();			  
		  
		  var odeta = { 'det': d, 'desc':desc, 'req':req, 'cant':cant };
		 
			//alert(ideta);
			newArrayD.push(odeta);
			
		  }			
	});	
	
if(marcados==0){
	alert("debe marcar algun detalle de no cotiza para agregar");
	return;
}	
	 //validar si este proveedor ya existe base de datos
      		if($('#proveedor').val() != '' && $('#nit').val() != '' )
      		{
				prov =$('#proveedor').val();
				nit  =$('#nit').val();
				$.ajax({
				 			type: "POST",
				 			url: "tipo_guardar.php?tipoGuardar=ValidarProveedor&proveedor="+prov+"&nit="+nit,
				 			dataType: 'json',
				 			success : function(t)
				 			{  
				 				if(t != '' && t == '1')
								{
							 		alert("Este proveedor ya existe en base de datos");
							 		return false;
							 		
								}else{

										document.getElementById('TB_overlaycot').className="TB_overlayBGcot";	
	$('#spancerrarpop').text(" X ");	
	document.getElementById('spancerrarpop').className="btncerrarpop";	
	document.getElementById('popup_ncot').className="";
	$('html, body').animate({ scrollTop: $('#popup_ncot').offset().top }, 'slow');
 procesarplantilla(newArrayD);
								}
				 		 	},	
							error   : callback_error,
						});
			}


	
}

var itemsdetalles = [];

function procesarplantilla(p){
	
	itemsdetalles=p;
	  var template = $("#ordenprod-template").html();
  //console.log("itemsdelosdetalles");
  //console.log(itemsdetalles);
  $("#popup_ncot").html(_.template(template,{itemsdetalles:p}));  
  
   cabecera();
  // $("#t_prov").text($("#proveedor").val());
	
}

function procesarplantilla2(p)
{
	
	itemsdetalles=p;
	  var template = $("#menorcuantia-template").html();
  	  $("#popup_ncot").html(_.template(template,{itemsdetalles:p}));  
  	   cabecera();
  	
}

function cabecera()
{
	
	 campo="proveedor,nit,telefono,email,direccion,observacion,codigorden";
	 input=campo.split(',');
	 for (i=0 ; i<input.length; i++)
	 {		 
		 if(input[i] == "proveedor"		){$("#t_prov"	).text($("#proveedor"	).val()); }
		 if(input[i] == "nit"			){$("#t_nit"	).text($("#nit"			).val()); }
		 if(input[i] == "telefono"		){$("#t_telef"	).text($("#telefono"	).val()); }
		 if(input[i] == "email"			){$("#t_email"	).text($("#email"		).val());}
		 if(input[i] == "direccion"		){$("#t_direc"	).text($("#direccion"	).val());}
		 if(input[i] == "observacion"	){$("#t_obser"	).text($("#observacion"	).val());}
		 if(input[i] == "codigorden"	){$("#t_codord"	).text($("#codigorden"	).val());}
		 
	 }
	
}

function acceptNum(event)
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
		
function unitario(cod){
vtotal   = '';
valorsum = 0;
valoriva = 0;
  if(cod!=''){
    //alert(document.getElementById('valor_'+cod).value);
	valor_unitario = parseInt(document.getElementById('v_unit_'+cod).value);
	cantidad_un    = parseInt(document.getElementById('cant_'+cod).innerHTML);
	vtotal = valor_unitario*cantidad_un|0;
	document.getElementById('valortotal_'+cod).value = vtotal;
		$('.input_cant2').each(function(index){
		  codigo_div = ($(this).attr('id'));
		  part = codigo_div.split("_");
		  cod_det = part[1];
		  //valoriva_469
		  //valorivapre_
		  //console.log('jajajja'+$(this).val());
		  if($(this).val()!='' && $(this).val()>0){
		    //valorsum = 0;
		    valorentrada = parseFloat($(this).val());
			valorsum = valorsum+valorentrada;
			ivatr = $("#valoriva_"+cod_det).val() | 0;
			valorivatr = ((ivatr*valorentrada)/100);
			$("#valorivapre_"+cod_det).val(valorivatr);
			//console.log(valorentrada);
		  }
		}); 
	if(valorsum!='0'){
	   //valorsum = decimal2(valorsum/1.16);
	   $("#subtotal").val(valorsum);
	   //$("#iva").val(decimal2(parseFloat($("#subtotal").val())*0.16));
	   //$("#total").val(decimal2(parseFloat($("#subtotal").val()))+decimal2(parseFloat($("#iva").val())));
	   SumarIva();
	   valor_total = (parseFloat($("#subtotal").val()) + parseFloat($("#iva").val()));
	   valor_total = valor_total | 0;
	   $("#total").val(valor_total); 
	 } 
   }
}	

function Fiva(){
	$('.valoriva').each(function(index){
		campo = ($(this).attr('id'));
		part = campo.split("_");
		cod_det = part[1];
		//console.log(cod_det);
		ivatr    = parseInt($("#valoriva_"+cod_det).val()) | 0;
		vtotaltr = parseFloat($("#valortotal_"+cod_det).val()) | 0;
		resultado= ((vtotaltr*ivatr)/100);
		$("#valorivapre_"+cod_det).val(resultado);
	});	
	SumarIva();
	   valor_total = (parseFloat($("#subtotal").val()) + parseFloat($("#iva").val()));
	   valor_total = valor_total | 0;
	   $("#total").val(valor_total);
	
}


function SumarIva(){
	  sumaivas = 0;
	  $('.valorivapre').each(function(index){
		  codigo_div = ($(this).attr('id'));
		  console.log(codigo_div);
		  valorentrada = parseFloat($(this).val())|0;
		  console.log(valorentrada);
		  sumaivas = sumaivas+valorentrada;
		  /*
		  if($(this).val()!=''){
		    //valorsum = 0;
		    valorentrada = parseInt($(this).val());
			valorsum = valorsum+valorentrada;
		  }
		  */
		});
		$("#iva").val(sumaivas);
}

  function close_popup(){
document.getElementById('TB_overlaycot').className="";
document.getElementById('popup_ncot').className="no_visible";
$("#spancerrarpop").html('');
$("#spancerrarpop").removeClass('btncerrarpop');
}

function ubicarfoco(campo){
	$('html, body').animate({ scrollTop: $('#'+campo).offset().top }, 'slow');
	$( "#"+campo).focus();
	console.log(campo);
}
function SaveDeta(){ 
  if($('#total').val() > '689454'){

  	alert('¡Esta compra no puede superar 1SMLV - $689.454');
  	return;
  }
    


	 var newArrayD = new Array();
	  validacampos = 0;
	  totalfilas   = 0;
	  idfoco       = '';	 
	 //variables de proveedor no cotiza
	 proveedor_desc		=$('#t_prov'	).text();
	 proveedor_nit		=$('#t_nit'		).text();
	 proveedor_email	=$('#t_email'	).text();
	 proveedor_tel		=$('#t_telef'	).text();
	 proveedor_direc	=$('#t_direc'	).text();
	 
	 //variables de orden de compra no cotiza
	 observacion		= $('#t_obser').val();
	 fecha_entrega		= $('#t_fechent').val();
	 flete		        = $('#flete').val();
	 			  
	 var datoest = { 
					'p_d'	: 	proveedor_desc	,
					'p_n'	:	proveedor_nit	,
					'p_e'	:	proveedor_email	,
					'p_t'	:	proveedor_tel	, 
					'p_dir'	:	proveedor_direc ,
					'p_o'	:	observacion     ,
					'p_fe'	:	fecha_entrega ,
					'flete'	:	flete 
				 };

	 $("#popup_ncot .input_cant2").each(function(index){
		 //console.log($(this).val());
		 totalfilas++;
		 if($(this).val()!=''){
			validacampos++; 
		 }else{
			 if(idfoco==''){
				 obt      = $(this).attr("id");
				 part_obt = obt.split("_");
				 codfoco  = part_obt[1];
				 idfoco   = "v_unit_"+codfoco;
			 }
		 }
		 
	 });
     if(validacampos==0 || validacampos < totalfilas){
		 alert("debe ingresar valores para cada uno de los items no cotizados");
		 if(idfoco!=''){
		   ubicarfoco(idfoco);
		 }
		 return;
	 } 
	 //console.log("validacampos "+validacampos);
	 //console.log("totalfilas "+totalfilas);
	  
	 if(confirm("esta seguro de crear esta orden de No Cotiza")){
			
		$("#popup_ncot .addfila").each(function(index){
		      d		    = $(this).attr('id');			  
			  input	    = d.split('_');			  
			  id	    = input[2];			  
			  desc	    = $('#descripcion_'+id).val();
			  cant 	    = $('#cant_'+id).text();
			  vunit     = $('#v_unit_'+id).val();
			  porc_iva  = $('#valoriva_'+id).val()|0;
			  valor_iva = $('#valorivapre_'+id).val()|0;
            		  
		  
		  var datodinamico = { 'det': id, 'desc':desc,  'cant':cant, 'vunit':vunit, 'porc_iva':porc_iva, 'valor_iva':valor_iva };
		 
			//alert(ideta);
			newArrayD.push(datodinamico);
			
		 		
	});	
	
	
	 var datoenvia = { 'estatico': datoest, 'dinamico':newArrayD };
	
	 $.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=GuardarOrdenNoCotiza",
	            dataType: 'json',
				success : function(r){
				for(i=0;i<datoenvia.dinamico.length; i++){
					console.log(datoenvia.dinamico[i].det);
					$('#fila_'+datoenvia.dinamico[i].det).remove();
					close_popup();
				}
				alert("se ha generado la orden de compra no cotiza No. "+r);
				},
				error   : callback_error,
	    
	            data: { json: JSON.stringify(datoenvia) }
	        });
	 }
				
}

function sumarflete(){
	if($("#total").val()==''){
		$("#flete").val('');
		alert("debe ingresar valores ");
		return;
	}
	subtotal = parseFloat($("#subtotal").val())|0;
	iva      = parseFloat($("#iva").val())|0;
	flete    = parseFloat($("#flete").val())|0;
	total    = subtotal+iva;
	nuevo_total = total+flete;

	$("#total").val(nuevo_total);
}

function validarSiNumero(numero)
	{	
		//-inicio validar que sea numero sin caracteres ni puntos	
			if (!/^([0-9])*$/.test(numero))
			alert("Por favor Digite el NIT " + numero + " Sin puntos y sin codigo de verificacion");

       
	}


function f_Autoriza_viaticos(){
		if($('#proveedor').val() == ''){
		alert('Falta el Proveedor');
		return;
	}

	if($('#nit').val() == ''){
		alert('Falta el Nit');
		return;
	}

alert('autorizacion ve viaticos');
}	

	function f_Crear_MenorCuantia()
	{
		//validacion de variables
		if($('#proveedor').val() 	== '')	{	alert('Falta el Proveedor'); 	return;	}
		if($('#nit').val() 			== '')	{   alert('Falta el Nit');			return;	}
		
		//declaracion de array y varibles
		var newArrayD 	= 	new Array();
		marcados 		=	0;

       	//saber que checkbox estan seleccionados
		$("#det_nocotiza .adddet").each(function(index)
		{
			if($(this).is(':checked'))
			{
					marcados 	= 1;
			  		d			= $(this).attr('data-detnocot');
			  		desc		= $('#descnocot_'+d).text();
			  		cant 		= $('#cant_'+d).text();
					req 		= $('#reqnocot_'+d).text();			  
		  		  	
		  		  	var odeta = { 'det': d, 'desc':desc, 'req':req, 'cant':cant };
		 
					newArrayD.push(odeta);
			}			
		});	

		// validacion de checkbox seleccionados
		if(marcados==0)
		{
			alert("debe marcar algun detalle de menor cuantia para agregar");
			return;
		}
	    
			
	 	//validar si este proveedor ya existe base de datos sino realiza la carga del popup
      	if($('#proveedor').val() != '' && $('#nit').val() != '' )
      		{
				prov =$('#proveedor').val();
				nit  =$('#nit').val();
				
				$.ajax({
				 			type: "POST",
				 			url: "tipo_guardar.php?tipoGuardar=ValidarProveedor&proveedor="+prov+"&nit="+nit,
				 			dataType: 'json',
				 			success : function(t)
				 			{  
				 				if(t != '' && t == '1')
								{
							 		alert("Este proveedor ya existe en base de datos");
							 		return false;
							 		
								}else{

											document.getElementById('TB_overlaycot').className="TB_overlayBGcot";	
											$('#spancerrarpop').text(" X ");	
											document.getElementById('spancerrarpop').className="btncerrarpop";	
											document.getElementById('popup_ncot').className="";
											$('html, body').animate({ scrollTop: $('#popup_ncot').offset().top }, 'slow');
										 	procesarplantilla2(newArrayD);
									}
				 		 	},	
							error   : callback_error,
						});
			}
	}

	function MCcalculartotal()
	{
		vtotal   = '';
		valorsum = 0;
		valoriva = 0;
//.innerHTML
  		
			
			MCsubtotal 	= parseInt(document.getElementById('MCsubtotal').value);
			MCiva   	= parseInt(document.getElementById('MCiva').value) | 0;
			vtotal = MCsubtotal+MCiva;
			document.getElementById('MCtotal').value = vtotal;
		
		 
	 
   }

   function MCSaveDeta(){

   	 if($('#MCtotal').val() > '689454'){

	  	alert('¡Esta compra no puede superar 1SMLV - $689.454!');
	  	return;
	  }
	    


	 var newArrayD = new Array();
	  validacampos = 0;	  
	  idfoco       = '';	 
	 //variables de proveedor menor cuantia
	 proveedor_desc		=$('#t_prov'	).text();
	 proveedor_nit		=$('#t_nit'		).text();
	 proveedor_email	=$('#t_email'	).text();
	 proveedor_tel		=$('#t_telef'	).text();
	 proveedor_direc	=$('#t_direc'	).text();

	 //variables de compra de menor cuantia
	 mcvalor_total		=	$('#MCtotal').val();
	 
	 //variables de orden de compra menor cuantia
	 observacion		= $('#MCt_obser').val();
	 fecha_entrega		= $('#MCt_fechent').val();
	 flete		        = $('#MCflete').val();
	 			  
	 var datoest = { 
					'p_d'	: 	proveedor_desc	,
					'p_n'	:	proveedor_nit	,
					'p_e'	:	proveedor_email	,
					'p_t'	:	proveedor_tel	, 
					'p_dir'	:	proveedor_direc ,
					'p_o'	:	observacion     ,
					'p_fe'	:	fecha_entrega ,
					'c_t'	:	mcvalor_total,
					'flete'	:	flete 
				 };

	  
	 if(confirm("esta seguro de crear esta orden de Menor Cuantia")){
			
		
	 var datoenvia = { 'estatico': datoest };
	
	 $.ajax({
	            type: "POST",
	            url: "tipo_guardar.php?tipoGuardar=GuardarMenorCuantia",
	            dataType: 'json',
				success : function(r){
				for(i=0;i<datoenvia.dinamico.length; i++){
					console.log(datoenvia.dinamico[i].det);
					$('#fila_'+datoenvia.dinamico[i].det).remove();
					close_popup();
				}
				alert("se ha generado la orden de compra no cotiza No. "+r);
				},
				error   : callback_error,
	    
	            data: { json: JSON.stringify(datoenvia) }
	        });
	 }
	
   }

</script>