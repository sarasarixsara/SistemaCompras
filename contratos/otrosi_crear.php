<?php
if(isset($_GET['cod']) && $_GET['cod'] != '')
{	

	
?>
<!-- Modal -->
<div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalScrollableTitle">Ingresar Otrosí</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="document.getElementById('form_otrosi').reset();">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
	<div class="modal-body">
		<div class="container">
			<form name="form_otrosi" id="form_otrosi" action="" method="post"  enctype="multipart/form-data">
				<div class="row">
					<div class="col col-sm-1">
						N° Otrosí:
					</div>
					<div class="col col-sm-2">
						<input class="form-control form-control-sm mt-2" type="text" name="otrosi_numero" value="" placeholder="Numero de otrosí" id="otrosi_numero">
					</div>
				</div>
				<div class="row">
					<div class="col col-sm-1">
						Objeto:
					</div>
					<div class="col col-sm-5">		  
						<textarea class="form-control" name="otrosi_objeto" id="otrosi_objeto"  placeholder="Objeto del otrosí" rows="3"></textarea>   
					</div>	
				</div>
				<div class="row">
					<div class="col col-sm-1">
						Clase:
					</div>
					<div class="col col-sm-3">		  
						<input class="form-control form-control-sm" type="text" name="otrosi_clase" value="" placeholder="Clase de otrosí" id="otrosi_clase">	  
					</div>	
				</div>
				<div class="row">
					<div class="col col-sm-1">
						Proveedor:
					</div>
					<div id="select_otrosi"class="col col-sm-5">		  
						<!-- inicio boton combo de proveedor contratos -->
							<select name="otrosi_contratista" id="otrosi_contratista" class="form-control chzn-select"> 
								<option value=""></option>
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
				</div>
				<div class="row">
					<div class="col col-sm-1">
						Fecha:
					</div>
					<div class="col col-sm-2">		  
						<input class="form-control form-control-sm" type="text" name="otrosi_fecha" id="otrosi_fecha" value=""  readonly placeholder="Fecha Fin Otrosí" >     
					</div>
				</div>				
				<div class="row">
					<div class="col col-sm-1">
						N° Horas:
					</div>
					<div class="col col-sm-3">		  
						<input class="form-control form-control-sm" type="text" name="otrosi_nhoras" value="" placeholder="N° Horas otrosi" id="otrosi_nhoras">  
					</div>
				</div>
				<div class="row">
					<div class="col col-sm-1">
					 F. pago:
					</div>
					<div class="col col-sm-3">		  
						<input class="form-control form-control-sm" type="text" name="otrosi_fpago" value="" placeholder="Forma de Pago" id="otrosi_fpago">
					</div>
				</div>
				<div class="row">
					<div class="col col-sm-1">
						Valor:
					</div>
					<div class="col col-sm-3">		  
						<input class="form-control form-control-sm" type="text" name="otrosi_valor" value="" placeholder="Valor de la cuantia" id="otrosi_valor"> 
					</div>
				</div>			
		</div>        
	</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="document.getElementById('form_otrosi').reset();">Close</button>
		<input  type="hidden"  name="contrato_codigo" value="<?php echo($contrato_codigo); ?>" >
		<input  type="hidden"  name="otrosi_codigo" id="otrosi_codigo" value="" >
        <button type="button" id="boton_otrosi" name="boton_otrosi" class="btn btn-primary" onclick="validarCampos_otrosi(); " >Guardar</button>
      </div>
	  </form>	
    </div>
  </div>


<script type="text/javascript">

function validarCampos_otrosi(){	
	
    var otrosi_codigo       =document.form_otrosi.otrosi_codigo.value;
	var otrosi_numero       =document.form_otrosi.otrosi_numero.value;
	var otrosi_objeto		=document.form_otrosi.otrosi_objeto.value;
	var otrosi_clase		=document.form_otrosi.otrosi_clase.value;
	var otrosi_contratista	=document.form_otrosi.otrosi_contratista.value;
	var otrosi_fecha		=document.form_otrosi.otrosi_fecha.value;
	var otrosi_nhoras		=document.form_otrosi.otrosi_nhoras.value;
	var otrosi_fpago		=document.form_otrosi.otrosi_fpago.value;
	var otrosi_valor		=document.form_otrosi.otrosi_valor.value;	
	

	// Validacion campos mensajes 
    		
	if(otrosi_codigo == ""){
		if(confirm('¿Esta seguro de guardar estos datos?')){			  
								document.form_otrosi.action="contratos/otrosi_guardar.php?tipoGuardar_otrosi=Guardar";
								document.form_otrosi.submit(); 
								}
	}else{
		
		if(confirm('¿Esta seguro de editar estos datos?')){	
			
						document.form_otrosi.action="contratos/otrosi_guardar.php?tipoGuardar_otrosi=Editar";
						document.form_otrosi.submit(); 
				}
	}
  
  
}




$("#otrosi_fecha").datepicker({
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

</script>
</div>

<?php 
}

?>