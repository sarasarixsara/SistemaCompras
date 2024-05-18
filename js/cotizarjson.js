var proveedoresAgregados = {};
var liactiva=0;
var detallesAgregados = {};
     $(document).ready(function() {
	 

$('#btnordencotizar').click(function() {	
  //var tmp = JSON.stringify($('.proveedorview'));
  // tmp value: [{"id":21,"children":[{"id":196},{"id":195},{"id":49},{"id":194}]},{"id":29,"children":[{"id":184},{"id":152}]},...]
//alert(tmp);
test();
});	 

$(".add_cotizacion").on("click", ".deleteitem", function(){
		 if(confirm("seguro que desea eliminar este detalle?")){ 
		  delediv = $(this).parent().attr("id");
		  $("#"+delediv).remove();
		  code=delediv.split("_");
			 var proveedorescreados = $('#provview_'+code[2]).find('.detalleview');
		     if(proveedorescreados.length==0){
         		$("#provview_"+code[2]).remove();
		     }
		 }
});	 
	 
	   //$(".ficha").click(function(evento){
	   $("#ofertas").on("click", ".closereq", function(){
        data = $(this).attr('id').split('_');
		 $("#litabli_"+data[1]).remove();
		 $("#lireq_"+data[1]).remove();
		 $("#requerimiento_"+data[1]).removeClass('cotizado');
		 $(this).css("display","none");
	   });
	   
	   $("#ofertas").on("click", ".ficha", function(){
	     /*alert($(this).attr('id'));*/
		   data = $(this).attr('id').split('_');
		   codigo_req = data['1'];
		     var SADDREQ = document.getElementById('lireq_'+codigo_req);
		     var SADDREQLI = document.getElementById('litabli_'+codigo_req);
			  if(SADDREQ==null && SADDREQLI==null){
			   var SLOAD   = document.getElementById('loading_'+codigo_req);
			    if(SLOAD==null){
			     $(this).prepend("<div class='loading' id='loading_"+codigo_req+"'></div>");
				  $("#temporalreq").load("cargarreq.php?req="+codigo_req, function(){
				   var li      = '<li class="litab" id="litabli_'+codigo_req+'"><a href="#lireq_'+codigo_req+'" data-toggle="tab">'+$("#numreq_"+codigo_req).text()+'</a></li>';
				   var tabini  = '<div class="tab-pane" id="lireq_'+codigo_req+'"><h4>'+$("#span_"+codigo_req).text()+'</h4>';
				   var fin     = '</div>';
 				    $("#requerimientos_menu").append(li); 
				    $("#requerimiento_detalle").append(tabini+$("#temporalreq").html()+fin);
				    $("#temporalreq").html('');
				    $("#requerimiento_"+codigo_req).addClass("cotizado");
				    $("#closer_"+codigo_req).css("display","block");
				    $("#loading_"+codigo_req).remove();					
					//liactiva = codigo_req; //hacemos el id del li codgo requerimiento al actual
				  });		   
		         }
		        } 
	   });
	   $("#mkt").on("click", ".chkprov", function(evento){
			id = $(this).attr("id");
			datas = $(this).attr("id").split('_');
			codigo_prov = datas[1];
			//if(document.getElementById(""+id).checked==true){
				if($(this).is(':checked')){
				proveedoresAgregados[codigo_prov] = codigo_prov;
					if(!isEmptyObject(proveedoresAgregados))
					{
					  
					}
			}else{
               delete proveedoresAgregados[codigo_prov]; 			
			}

	   });
	   /*
		 $('.addchkdet').click(function(index){ 

		 }) */

		$("#requerimiento_detalle").on("click", ".addchkdet", function(){
		       id           = $(this).attr('id')
			   var checks   = $(this).attr('id').split("_");
				   cod_adddetalle = checks[1];  
				  if(document.getElementById(""+id).checked==true){
				    detallesAgregados[cod_adddetalle]=cod_adddetalle;
				    //$('#tddescrip_'+cod_adddetalle).addClass('cotizado');
					if(!isEmptyObject(detallesAgregados))
					{
 
					}
				}else{
				  delete detallesAgregados[cod_adddetalle]; 
                  //$('#tddescrip_'+cod_adddetalle).removeClass('cotizado');				  
				}
		});		 
	   
 });
 
 function cleaner(){ 
 	 $('#requerimientos_menu > .litab').each(function(index){
	   $(this).remove();
	 })
	 $("#requerimiento_detalle > .tab-pane").each(function(index){
	   $(this).remove();
	 })
	 $("#container_cotizacion > .proveedorview").each(function(index){
	   $(this).remove();
	 })
	 $("#btnlimpiar").css("display","none");
	  $("#ofertas .cotizado").each(function(index){
	  data = $(this).attr('id').split('_');
	  id=data[1];
	   $("#princ_"+id).remove();
	 })
 }
 
 function poblarDetalle(prov){
	 $.each(detallesAgregados, function(id, valor)
	 {
	    div = document.getElementById('detview_'+valor+'_'+prov);
		//alert(div);
         if(div==null){		
		  if($("#tddescrip_"+valor).text()!=''){
		   var divdet = '<div class="detalleview" id="detview_'+valor+'_'+prov+'"><div class="deleteitem"></div>'+$("#tdcanti_"+valor).text()+'&nbsp;&nbsp;'+$("#tddescrip_"+valor).text()+'</div>';	  
		   $("#provview_"+prov).append(divdet);
		   }
		  }
	 })
	 var proveedorescreados = $('#container_cotizacion').find('.proveedorview');
     proveedorescreados.each(function(){
	    var id = $(this).attr('id');
        var val = id.split("_");
		var value = val[1];
        var selectsdetalle = $('#'+id).find('.detalleview');
        if(selectsdetalle.length==0){
		$('#'+id).remove()
		}     
	 });
 }
	 
function ResetArray(){
if(isEmptyObject(proveedoresAgregados) || isEmptyObject(detallesAgregados)){
 if(isEmptyObject(detallesAgregados)){
 alert("debe marcar detalles a agregar");
 return;
 }
 if(isEmptyObject(proveedoresAgregados)){
 alert("debe marcar proveedores a agregar");
 return;
 }
}
if(!isEmptyObject(proveedoresAgregados) && !isEmptyObject(detallesAgregados))
{
	  $.each(proveedoresAgregados, function(key, val)
	 {
	  divprod = document.getElementById('provview_'+val);
	 // button  = '<input id="btn_'+val+'" type="button" class="btn btn-sm btn-warning" onclick="GuardarDetalles('+val+')" value="Guardar Cotizacion"></button>';
	  //alert(divprod);
	  if(divprod==null){
	    TextoLabel = $("#addprov_"+val).text().split('Visitar');
	    TextoLabel = TextoLabel[0];
		      var divprov='<div class="proveedorview" id="provview_'+val+'"><h3>'+TextoLabel+'</h3></div>';
			  $("#container_cotizacion").append(divprov);
			  //$("#provview_"+val).append(button);
			  poblarDetalle(val);
	    }else{
		   poblarDetalle(val);		  

		}
	 })

}
/*
  proveedoresAgregados = null;
  detallesAgregados    = null;*/
	  $.each(proveedoresAgregados, function(key, val)
	 {
       delete proveedoresAgregados[val];       
	 })
	 
	 $.each(detallesAgregados, function(key, val)
	 {
       delete detallesAgregados[val];       
	 })	 
  
	 $('.addchkdet').each(function(index){
	   var checks = $(this).attr('id');
          document.getElementById(''+checks).checked=false; 
	 })
	 /*$('.chkprov').each(function(index){
	   var checks = $(this).attr('id');
	   document.getElementById(''+checks).checked=false; 
	 })
	 */
	 $('.chkprov').each(function(index){
	   $(this).attr('checked', false);
	 })
$("#btnordencotizar").css("display","block");	
}	

function isEmptyObject(obj) 
{
    var name;
    for (name in obj) 
    {
        return false;
    }
    return true;
}

function GuardarDetalles(prov){
if(confirm('seguro que desea generar esta cotizacion para este proveedor?')){
detallesEnviar = {};
detallesarr    = new Array();
	 $('#provview_'+prov+' div.detalleview').each(function(index){ 
	   var checks = $(this).attr('id').split("_");
	       id     = checks[1];
           detallesEnviar[id] = id;
	 })
	 

	if(!isEmptyObject(detallesEnviar)){	 
		  $.each(detallesEnviar, function(key, val)
		 {
		  detallesarr.push(val);
		 })
		 str = '';
		 for(i=0; i<detallesarr.length; i++){
		  //alert('dentro del for posicion '+i+' valor '+detallesarr[i]);
		  str = str+detallesarr[i]+',';
		 }
		 var enviardet = str.substring(0, str.length-1);
         
		  var date = new Date();
		  var timestamp = date.getTime();
		  var v_dato = getDataServer("cotizarproveedor.php","?tipoGuardar=DetallesCotizacion&time="+timestamp+"&detalles="+enviardet+"&proveedor="+prov);
          if(v_dato!=''){
		    if(v_dato!='none'){
			 $("#btn_"+prov).remove();
			 alert('se ha generado correctamente la cotizacion numero '+v_dato);
			}
		  }
		 
	}	
 }	
}

function test(){
    var selects = $('#container_cotizacion').find('.proveedorview');
    var newArray = new Array();
	var odeta = {};

    selects.each(function(){
        var id = $(this).attr('id');
        //var val = $(this).val();
        var val = id.split("_");
		var value = val[1];
        var selectsdetalle = $('#'+id).find('.detalleview');
        var newArrayD = new Array();	
             selectsdetalle.each(function(){
              var ideta = $(this).attr('id');
              var valdeta = ideta.split("_");
		      var valuedeta = valdeta[1];			  
			  //var odeta = { 'ideta': ideta, 'valdeta': valuedeta };
			  var odeta = { 'd': valuedeta};
			  //alert(ideta);
			  newArrayD.push(odeta);
			 })		
         newArrayD.sort();			 
        var o = { 'id': id, 'val': value, 'det': newArrayD };

        newArray.push(o);
    });

    if(newArray.length>0){
      if(confirm('seguro que desea guardar esta informacion?')){
		$("#btnordencotizar").css("display","none");
		//$("#btnlimpiar").css("display","block");	
//console.log(newArray);		
	    $.ajax({
	            type: "POST",
	            url: "orden_cotizacion.php?tipoGuardar=CrearOrdenCotizacion",
	            dataType: 'json',
				success : function(r){
				      RemoverOpdDelele();
					 alert('orden de cotizacion creada codigo '+r);
					 cleaner();
					 
				},
				error   : callback_error,
	    
	            data: { json: JSON.stringify(newArray) }
	        });
	    }
	  }else{

	  	alert("no hay datos para guardar");
	  	return;
	  }
}
function RemoverOpdDelele(){
	 $('.deleteitem').each(function(index){
	   $(this).remove();
	 })

}
function calback_success(data){
 alert('orden de cotizacion generada correctamente codigo'+data);
}

function callback_error(XMLHttpRequest, textStatus, errorThrown)
{
    $("#btnordencotizar").css("display","block");
    alert("Respuesta del servidor "+XMLHttpRequest.responseText);
    alert("Error "+textStatus);
    alert(errorThrown);
}