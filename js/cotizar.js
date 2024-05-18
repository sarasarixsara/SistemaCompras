var proveedoresAgregados = {};
var liactiva=0;
var detallesAgregados = {};
     $(document).ready(function() {
	   $(".ficha").click(function(evento){
	     /*alert($(this).attr('id'));*/
		   data = $(this).attr('id').split('_');
		   codigo_req = data['1'];
		     var SADDREQ = document.getElementById('lireq_'+codigo_req);
			  if(SADDREQ==null){
			   var SLOAD   = document.getElementById('loading_'+codigo_req);
			    if(SLOAD==null){
			     $(this).prepend("<div class='loading' id='loading_"+codigo_req+"'></div>");
				  $("#temporalreq").load("cargarreq.php?req="+codigo_req, function(){
				   var li      = '<li class="litab"><a href="#lireq_'+codigo_req+'" data-toggle="tab">'+$("#numreq_"+codigo_req).text()+'</a></li>';
				   var tabini  = '<div class="tab-pane" id="lireq_'+codigo_req+'"><h4>'+$("#span_"+codigo_req).text()+'</h4>';
				   var fin     = '</div>';
 				    $("#requerimientos_menu").append(li); 
				    $("#requerimiento_detalle").append(tabini+$("#temporalreq").html()+fin);
				    $("#temporalreq").html('');
				    $("#requerimiento_"+codigo_req).addClass("cotizado")
				    $("#loading_"+codigo_req).remove();					
					//liactiva = codigo_req; //hacemos el id del li codgo requerimiento al actual
				  });		   
		         }
		        } 
	   });
	   
	   $(".chkprov").click(function(evento){
			datas = $(this).attr("id").split('_');
			codigo_prov = datas[1];
			proveedoresAgregados[codigo_prov] = codigo_prov;
				if(!isEmptyObject(proveedoresAgregados))
				{
				  
				}
		   // var divprov='<div class="proveedorview"><h3>'+$("#addprov_"+codigo_prov).text()+'</h3></div>';
			
			 /* $.each(proveedoresAgregados, function(key, val)
			 {
			  alert(val);
			 })
			 */
	   });
	   
		 $('.addchkdet').click(function(index){ 

		 })

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
 
 function poblarDetalle(prov){
	 $.each(detallesAgregados, function(id, valor)
	 {
	    div = document.getElementById('detview_'+valor+'_'+prov);
		//alert(div);
         if(div==null){		
		   var divdet = '<div class="detalleview" id="detview_'+valor+'_'+prov+'">'+$("#tdcanti_"+valor).text()+'&nbsp;&nbsp;'+$("#tddescrip_"+valor).text()+'</div>';	  
		   $("#provview_"+prov).append(divdet);
		  }
	 })
 
 }
	 
function ResetArray(){
if(!isEmptyObject(proveedoresAgregados) && !isEmptyObject(detallesAgregados))
{
	  $.each(proveedoresAgregados, function(key, val)
	 {
	  divprod = document.getElementById('provview_'+val);
	  button  = '<input id="btn_'+val+'" type="button" class="btn btn-sm btn-warning" onclick="GuardarDetalles('+val+')" value="Guardar Cotizacion"></button>';
	  //alert(divprod);
	  if(divprod==null){
		  var divprov='<div class="proveedorview" id="provview_'+val+'"><h3>'+$("#addprov_"+val).text()+'</h3></div>';
			  $("#container_cotizacion").append(divprov);
			  $("#provview_"+val).append(button);
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
	 $('.chkprov').each(function(index){
	   var checks = $(this).attr('id');
	   document.getElementById(''+checks).checked=false; 
	 })
	
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
		  alert(v_dato);
          if(v_dato!=''){
		    if(v_dato!='none'){
			 $("#btn_"+prov).remove();
			 alert('se ha generado correctamente la cotizacion numero '+v_dato);
			}
		  }
		 
	}	
 }	
}