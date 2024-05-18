<?php

$query_RsPoa = "SELECT P.POACODI CODIGO,
					   P.POANOMB DESCRIPCION,
					   P.POARESP PERSONA_RESPONSABLE,
                       concat(PER.PERSNOMB,' ',PER.PERSAPEL) PERSONA_RESPONSABLE_DES,
					   case P.POAESTA 
                        when 0 then 'Inactivo'
                        when 1 then 'Activo'
                        else ''
                        end ESTADO
			    FROM   POA	P left join PERSONAS PER 
                 ON P.POARESP = PER.PERSID 
			    WHERE  1
          order by P.POACODI DESC	
				";
	$RsPoa = mysqli_query($conexion,$query_RsPoa) or die(mysqli_error($conexion));
	$row_RsPoa = mysqli_fetch_assoc($RsPoa);
  $totalRows_RsPoa = mysqli_num_rows($RsPoa);
  
  $query_RsCentroCosto = "SELECT P.PODECODI CODIGO,
                                 P.PODENOMB NOMBRE,
                                 case P.PODEESTA 
                                  WHEN 1 THEN 'Activo'
                                  WHEN 2 THEN 'Inactivo'
                                    else ''
                                 end ESTADO
                            FROM POADETA P
                            WHERE P.PODEESTA = '1'
                            ORDER BY P.PODECODI DESC
                          ";
  $RsCentroCosto = mysqli_query($conexion,$query_RsCentroCosto) or die(mysqli_error($conexion));
	$row_RsCentroCosto = mysqli_fetch_assoc($RsCentroCosto);
  $totalRows_RsCentroCosto = mysqli_num_rows($RsCentroCosto);

  $query_RsArea = "SELECT AREAID CODIGO,
                                 AREANOMB NOMBRE,
                                 case A.AREAESTA
                                  WHEN 1 THEN 'Activo'
                                  WHEN 2 THEN 'Inactivo'
                                    else 'Inactivo'
                                 end ESTADO
                            FROM AREA A
                            WHERE A.AREAESTA = '1'
                            ORDER BY A.AREAID DESC";
  $RsArea = mysqli_query($conexion,$query_RsArea) or die(mysqli_error($conexion));
	$row_RsArea = mysqli_fetch_assoc($RsArea);
  $totalRows_RsArea = mysqli_num_rows($RsArea);  

/*$query_RsResponsable = "SELECT 1 CODIGO,
                               'Diego' NOMBRE
                         union 
                         select 2 CODIGO,
                                'Juan' NOMBRE
                         union  
                         SELECT 3 CODIGO,
                               'Lina' Nombre
                ";*/
$query_RsResponsable = "SELECT P.PERSID CODIGO, CONCAT(P.PERSNOMB,' ',P.PERSAPEL) NOMBRE FROM PERSONAS P, USUARIOS U WHERE P.PERSUSUA = U.USUALOG AND USUAESTA = 0
                ";                
    $RsResponsable = mysqli_query($conexion,$query_RsResponsable) or die(mysqli_error($conexion));
    $row_RsResponsable = mysqli_fetch_assoc($RsResponsable);
    $totalRows_RsResponsable = mysqli_num_rows($RsResponsable); 
    $arrayresponsables = array();
    $pers_responsablearray = 0; /* 0 si no 1 si es array*/
    $total_responsables = '';
//var_dump($row_RsResponsable);
    //'{"0": "Inactivo", "1": "Activo"}'
    if($totalRows_RsResponsable > 0){
         $it=0;
         $ini = '{';
         $end = '}';
        do{
           $arrayresponsables[$it] = array( 'CODIGO'    =>  $row_RsResponsable['CODIGO'],
                                            'NOMBRE'    =>  $row_RsResponsable['NOMBRE']
           );
           $total_responsables = $total_responsables. '  "'.$row_RsResponsable['CODIGO'].'": "'.$row_RsResponsable['NOMBRE'].'"';
            if($it < $totalRows_RsResponsable-1 ){
                $total_responsables = $total_responsables.',';
            }
           $it++;
        }while($row_RsResponsable = mysqli_fetch_assoc($RsResponsable));
    }
	
//echo($total_responsables);
//echo(json_encode($arrayresponsables));

?><style type="text/css">
.table{
  font-size: .9em;
}
</style>
<div class="modal fade" id="modal-createpoa">
    <div class="modal-dialog modal-lg">
        <form name="nuevo_poa" id="nuevo_poa" method="post" action="">
            <div class="modal-content">
                <div class="modal-header well">
                 <a class="close" data-dismiss="modal">Ã—</a>
                 <h3 class="text-center">Nuevo Poa</h3>
             </div>
             <div class="modal-body">
                 <div class="row">
                    <div class="col-md-6">
                         <div class="col-md-4 text-right">Poa Nombre</div>
                         <div class="col-md-8">
                             <input type="text" name="poa_nombre" id="poa_nombre" value="" class="form-control">
                         </div>                        
                    </div>
                    <div class="col-md-6">
                         <div class="col-md-4 text-right">Poa Estado</div>
                         <div class="col-md-8">
                             <select  name="poa_estado" id="poa_estado" value="" class="form-control">
                                <option value="">Seleccione...</option>
                                <option value="0">Inactivo</option>    
                                <option value="1">Activo</option>    
                             </select>
                         </div>                        
                    </div>                    
                 </div>
                <div class="row" style="margin-top: .5em">
                 <div class="col-md-6">
                     <div class="col-md-4 text-right">Responsable</div>
                         <div class="col-md-8">
                             <select name="responsable" id="responsable" value="" class="form-control">
                             <option value="">Seleccione ...</option>
                            <?php 
                               for($i=0; $i < count($arrayresponsables); $i++){
                            ?>
                                <option value="<?php echo($arrayresponsables[$i]['CODIGO']);?>"><?php echo($arrayresponsables[$i]['NOMBRE']);?></option>
                            <?php
                                }
                            ?>
                            </select>
                         </div>                      
                 </div>
             </div>
             </div>
             <div class="modal-footer">
                 <button type="button" id="btnguardarpoa" class="btn btn-success" onclick="SaveNewPoa();">Guardar</button>
                 <a href="#" class="btn" data-dismiss="modal">Close</a>
             </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal-createcentrocosto">
    <div class="modal-dialog modal-lg">
        <form name="nuevo_centrocosto" id="nuevo_centrocosto" method="post" action="">
            <div class="modal-content">
                <div class="modal-header well">
                 <a class="close" data-dismiss="modal">Ã—</a>
                 <h3 class="text-center">Nuevo Centro de Costo</h3>
             </div>
             <div class="modal-body">
                 <div class="row">
                    <div class="col-md-6">
                         <div class="col-md-4 text-right">Centro de costo</div>
                         <div class="col-md-8">
                             <input type="text" name="centro_nombre" id="centro_nombre" value="" class="form-control">
                         </div>                        
                    </div>
                    <div class="col-md-6">
                         <div class="col-md-4 text-right">Estado</div>
                         <div class="col-md-8">
                             <select  name="centro_estado" id="centro_estado" value="" class="form-control">
                                <option value="">Seleccione...</option>
                                <option value="1">Activo</option>                                    
                                <option value="2">Inactivo</option>    
                             </select>
                         </div>                        
                    </div>                    
                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" id="btnguardarcentro" class="btn btn-success" onclick="SaveNewCentro();">Guardar</button>
                 <a href="#" class="btn" data-dismiss="modal">Close</a>
             </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal-createarea">
    <div class="modal-dialog modal-lg">
        <form name="nuevo_area" id="nuevo_area" method="post" action="">
            <div class="modal-content">
                <div class="modal-header well">
                 <a class="close" data-dismiss="modal">Ã—</a>
                 <h3 class="text-center">Nueva Area</h3>
             </div>
             <div class="modal-body">
                 <div class="row">
                    <div class="col-md-6">
                         <div class="col-md-4 text-right">Area</div>
                         <div class="col-md-8">
                             <input type="text" name="area_nombre" id="area_nombre" value="" class="form-control">
                         </div>                        
                    </div>
                    <div class="col-md-6">
                         <div class="col-md-4 text-right">Estado</div>
                         <div class="col-md-8">
                             <select  name="area_estado" id="area_estado" value="" class="form-control">
                                <option value="">Seleccione...</option>
                                <option value="1">Activo</option>                                    
                                <option value="2">Inactivo</option>    
                             </select>
                         </div>                        
                    </div>                    
                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" id="btnguardararea" class="btn btn-success" onclick="SaveNewArea();">Guardar</button>
                 <a href="#" class="btn" data-dismiss="modal">Close</a>
             </div>
            </div>
        </form>
    </div>
</div>



<div id="exTab2" class="container">	
<ul class="nav nav-tabs customawidth">
			<li class="active">
			<a  href="#poacontent" data-toggle="tab">Poa</a>
			</li>
			<li><a href="#centrocostoscontent" data-toggle="tab">Centros de Costos</a>
			</li>
			<li><a href="#areacontent" data-toggle="tab">Area</a>
			</li>
</ul>

			<div class="tab-content ">
			  <div class="tab-pane active" id="poacontent">
			  <div style="position:relative;">
				<div class="" style="height:40px;border-left: 1px solid #ccc;border-right:  1px solid #ccc;"><a class="btn btn-large" style="right:0; position:absolute;" data-toggle="modal" title="Nuevo Poa" data-target="#modal-createpoa"><i class="fa fa-plus-circle fa-2x"></i></a></div>
				</div>		  
          <form class="form-inline" name="form2" id="form2" method="post" action="">
          <table class="table table-bordered table-responsive table-striped" id="tablepoas">
          <thead class="table-dark">
              <tr>
              <th scope="col-sm-1">#</th>	  
                <th scope="col-sm-5">Descripci&oacute;n</th>
                <th scope="col-sm-3">Asignaci&oacute;n </th>
              <th scope="col-sm-3">Estado</th><?php // Esta fecha hace referencia al campo fecha de envio del requerimiento ?>       
              </tr>
            </thead>
            <tbody id="tableBody">
          <?php  
            do{
            ?>		 
              <tr>	   
                <td  scope="row"><?php echo($row_RsPoa['CODIGO']); ?></td>
              <td data-field="descripcion"><?php echo($row_RsPoa['DESCRIPCION']); ?></td>
                <td data-field="persona_responsable"><?php echo($row_RsPoa['PERSONA_RESPONSABLE_DES']); ?></td>	  
              <td data-field="estado"><?php echo($row_RsPoa['ESTADO']); ?></td>
                </tr>    
          
            <?php
            }while($row_RsPoa = mysqli_fetch_array($RsPoa));
            ?>
            </tbody>

          </table>
          </form>					
				</div>
				<div class="tab-pane" id="centrocostoscontent">
          <div style="position:relative;">
				<div class="" style="height:40px;border-left: 1px solid #ccc;border-right:  1px solid #ccc;"><a class="btn btn-large" style="right:0; position:absolute;" data-toggle="modal" title="Nuevo Centro de Costo" data-target="#modal-createcentrocosto"><i class="fa fa-plus-circle fa-2x"></i></a></div>
				</div>		  
          <form class="form-inline" name="formcentrocosto" id="formcentrocosto" method="post" action="">
          <table class="table table-bordered table-responsive table-striped" id="tablecentrocosto">
          <thead class="table-dark">
              <tr>
              <th scope="col-sm-1">#</th>	  
                <th scope="col-sm-5">Descripci&oacute;n</th>
              <th scope="col-sm-3">Estado</th><?php // Esta fecha hace referencia al campo fecha de envio del requerimiento ?>       
              </tr>
            </thead>
            <tbody id="tablebodycentrocostos">
          <?php  
            do{
            ?>		 
              <tr>	   
                <td  scope="row"><?php echo($row_RsCentroCosto['CODIGO']); ?></td>
                <td data-field="nombre"><?php echo($row_RsCentroCosto['NOMBRE']); ?></td> 
                <td data-field="estado"><?php echo($row_RsCentroCosto['ESTADO']); ?></td>
              </tr>    
          
            <?php
            }while($row_RsCentroCosto = mysqli_fetch_assoc($RsCentroCosto));
            ?>
            </tbody>

          </table>
          </form>	            
				</div>
        <div class="tab-pane" id="areacontent">
          <div style="position:relative;">
          <div class="" style="height:40px;border-left: 1px solid #ccc;border-right:  1px solid #ccc;"><a class="btn btn-large" style="right:0; position:absolute;" data-toggle="modal" title="Nuevo Area" data-target="#modal-createarea"><i class="fa fa-plus-circle fa-2x"></i></a></div>
          </div>		  
            <form class="form-inline" name="formarea" id="formarea" method="post" action="">
            <table class="table table-bordered table-responsive table-striped" id="tablearea">
            <thead class="table-dark">
                <tr>
                <th scope="col-sm-1">#</th>	  
                  <th scope="col-sm-5">Descripci&oacute;n</th>
                <th scope="col-sm-3">Estado</th><?php // Esta fecha hace referencia al campo fecha de envio del requerimiento ?>       
                </tr>
              </thead>
              <tbody id="tablebodyareas">
            <?php  
              do{
              ?>		 
                <tr>	   
                  <td  scope="row"><?php echo($row_RsArea['CODIGO']); ?></td>
                  <td data-field="nombre"><?php echo($row_RsArea['NOMBRE']); ?></td> 
                  <td data-field="estado"><?php echo($row_RsArea['ESTADO']); ?></td>
                </tr>    
            
              <?php
              }while($row_RsArea = mysqli_fetch_assoc($RsArea));
              ?>
              </tbody>

            </table>
            </form>
				</div>
			</div>
  </div>

<hr></hr>
<script src="includes/editinplacetablerows/jquery.tabledit.js"></script>



<script type="text/javascript">
    function SaveNewPoa(){
    img ="<i class='fa fa-spinner fa-spin' id='ispinner' style='color:#ff0000'></i>";
     if($("#poa_nombre").val() == ''){
        alert("debe ingresar el nombre del poa");
        return;
     }
     if($("#poa_estado").val() == ''){
        alert("debe ingresar el estado");
        return;
     }   
       /*  if($("#responsable").val() == ''){
        alert("debe ingresar el responsable");
        return;
     }  */

      $("#btnguardarpoa").attr('disabled','disabled');
      $("#btnguardarpoa").append(img);         
      var form = $('#nuevo_poa');
      var table = $('#tablepoas');
      var body = $('#tableBody');      
      var date = new Date();
      var timestamp = date.getTime();
      //var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=CargarDatos&time="+timestamp+"&codigo_detalle="+det);
         $.ajax({
            type: "POST",
            url: "src/detalle_compra/api.php?tipoGuardar=SaveNewPoa",
            dataType: 'json',
            success : function(r){
                if(r != ''){
                    console.log(r);
                      tradd = '<tr><td>'+r.ultimo_record+'</td><td>'+r.info.poa_nombre+'</td><td>'+r.info.responsable_des+'</td><td>'+r.info.poa_estado_des+'</td></tr>';
                      table.prepend(tradd);
                      table.data('Tabledit').reload(); 
                      cleanformpoavalues();
                      $('#modal-createpoa').modal('hide');
                      $("#btnguardarpoa").removeAttr('disabled');                     
                      toastr.success('<strong>Poa Creado Correctamente </strong>','OK');
                      $("#btnguardarpoa > i").remove(); /*remove la img elemento i*/
                }
            },
            data: form.serialize(),
              error: function (xhr, ajaxOptions, thrownError) {
                $("#btnguardarpoa").removeAttr('disabled');
                toastr.success('<strong>Error al Crear poa </strong>','OK');
              }
        });
    }


    function SaveNewCentro(){
    img ="<i class='fa fa-spinner fa-spin' id='ispinner' style='color:#ff0000'></i>";
     if($("#centro_nombre").val() == ''){
        alert("debe ingresar el nombre del centro de costo");
        return;
     }
     if($("#centro_estado").val() == ''){
        alert("debe ingresar el estado");
        return;
     }   
       /*  if($("#responsable").val() == ''){
        alert("debe ingresar el responsable");
        return;
     }  */
      $("#btnguardarcentro").attr('disabled','disabled');
      $("#btnguardarcentro").append(img);         
      var form = $('#nuevo_centrocosto');
      var table = $('#tablecentrocosto');
      var body = $('#tablebodycentrocostos');      
      var date = new Date();
      var timestamp = date.getTime();
      //var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=CargarDatos&time="+timestamp+"&codigo_detalle="+det);
         $.ajax({
            type: "POST",
            url: "src/detalle_compra/api.php?tipoGuardar=SaveNewCentro",
            dataType: 'json',
            success : function(r){
                if(r != ''){
                    console.log(r);
                      tradd = '<tr><td>'+r.ultimo_record+'</td><td>'+r.info.centro_nombre+'</td><td>'+r.info.centro_estado_des+'</td></tr>';
                      table.prepend(tradd);
                      table.data('Tabledit').reload(); 
                      cleanformcentrocosto();
                      $('#modal-createcentrocosto').modal('hide');
                      $("#btnguardarcentro").removeAttr('disabled');                     
                      toastr.success('<strong>Centro de costo Creado Correctamente </strong>','OK');
                      $("#btnguardarcentro > i").remove(); /*remove la img elemento i*/
                }
            },
            data: form.serialize(),
              error: function (xhr, ajaxOptions, thrownError) {
                $("#btnguardarcentro").removeAttr('disabled');
                $("#btnguardarcentro > i").remove();
                toastr.error('<strong>Error al Crear centro de costo </strong>','OK');
              }
        });
    }

    function SaveNewArea(){
    img ="<i class='fa fa-spinner fa-spin' id='ispinner' style='color:#ff0000'></i>";
     if($("#area_nombre").val() == ''){
        alert("debe ingresar el nombre del Ã¡rea");
        return;
     }
     if($("#area_estado").val() == ''){
        alert("debe ingresar el estado");
        return;
     }   
       /*  if($("#responsable").val() == ''){
        alert("debe ingresar el responsable");
        return;
     }  */
      $("#btnguardararea").attr('disabled','disabled');
      $("#btnguardararea").append(img);         
      var form = $('#nuevo_area');
      var table = $('#tablearea');
      var body = $('#tablebodyareas');      
      var date = new Date();
      var timestamp = date.getTime();
      //var v_dato = getDataServer("tipo_guardar.php","?tipoGuardar=CargarDatos&time="+timestamp+"&codigo_detalle="+det);
         $.ajax({
            type: "POST",
            url: "src/detalle_compra/api.php?tipoGuardar=SaveNewArea",
            dataType: 'json',
            success : function(r){
                if(r != ''){
                    console.log(r);
                      tradd = '<tr><td>'+r.ultimo_record+'</td><td>'+r.info.area_nombre+'</td><td>'+r.info.area_estado_des+'</td></tr>';
                      table.prepend(tradd);
                      table.data('Tabledit').reload(); 
                      cleanformarea();
                      $('#modal-createarea').modal('hide');
                      $("#btnguardararea").removeAttr('disabled');                     
                      toastr.success('<strong>Area Creado Correctamente </strong>','OK');
                      $("#btnguardararea > i").remove(); /*remove la img elemento i*/
                }
            },
            data: form.serialize(),
              error: function (xhr, ajaxOptions, thrownError) {
                $("#btnguardararea").removeAttr('disabled');
                $("#btnguardararea > i").remove();
                toastr.error('<strong>Error al Crear Area</strong>','OK');
              }
        });
    }

 function cleanformarea(){
    $("#area_nombre").val('');
    $("#area_estado").val('');   
 }
    
  function cleanformcentrocosto(){
        $("#centro_nombre").val('');
        $("#centro_estado").val('');
  }    

    function cleanformpoavalues(){
        $("#poa_nombre").val('');
        $("#poa_estado").val('');
        $("#responsable").val('');
    }

    function callback_error(XMLHttpRequest, textStatus, errorThrown)
    {
        alert("Respuesta del servidor "+XMLHttpRequest.responseText);
        alert("Error "+textStatus);
        alert(errorThrown);
    }     

            $('#tablepoas').Tabledit({
                url: 'src/detalle_compra/api.php?tipoGuardar=SavePoaEditinPlace',                
                inputClass: 'form-control input-sm',
                toolbarClass: 'btn-toolbar',
                groupClass: 'btn-group btn-group-sm',
                dangerClass: 'danger',
                warningClass: 'warning',
                mutedClass: 'text-muted',
                restoreButton: false,
                buttons: {
                  edit: {
                    class: 'btn btn-sm btn-default',
                    html: '<span class="fa fa-pencil"></span>',
                    action: 'Editar'
                  },
                  delete: {
                    class: 'btn btn-sm btn-default',
                    html: '<span class="fa fa-trash"></span>',
                    action: 'Eliminar'
                  },
                  save: {
                    class: 'btn btn-sm btn-success',
                    html: 'Guardar'
                  },
                  restore: {
                    class: 'btn btn-sm btn-warning',
                    html: 'Restaurar',
                    action: 'restore'
                  },
                  confirm: {
                    class: 'btn btn-sm btn-danger',
                    html: 'Confirmar'
                  }
                },  

                onDraw: function() { return; },

                // executed when the ajax request is completed
                // onSuccess(data, textStatus, jqXHR)
                onSuccess: function(data, textStatus, jqXHR) { 
                    if(data.action == 'Editar'){
                        if(data.afectado == 1){
                            toastr.success('<strong>Registro Editado Correctamente </strong>','OK');
                        }
                    }
                    return; 
                },

                // executed when occurred an error on ajax request
                // onFail(jqXHR, textStatus, errorThrown)
                onFail: function() { 
                          setTimeout(function() {
                            location.reload();
                          }, 1000);
                    return; 
                },

                // executed whenever there is an ajax request
                onAlways: function() { return; },

                // executed before the ajax request
                // onAjax(action, serialize)
                onAjax: function() { 
                          return; 
                } ,                            

                columns: {
                    identifier: [0, 'id'],
                    editable: [[1, 'descripcion'],[2, 'persona_responsable','{<?php echo($total_responsables);?>}'],[3, 'estado', '{"0": "Inactivo", "1": "Activo"}']]
                }
            });

            $('#tablecentrocosto').Tabledit({
                url: 'src/detalle_compra/api.php?tipoGuardar=SaveSubPoaEditinPlace',
                inputClass: 'form-control input-sm',
                toolbarClass: 'btn-toolbar',
                groupClass: 'btn-group btn-group-sm',
                dangerClass: 'danger',
                warningClass: 'warning',
                mutedClass: 'text-muted',
                restoreButton: false,
                buttons: {
                  edit: {
                    class: 'btn btn-sm btn-default',
                    html: '<span class="fa fa-pencil"></span>',
                    action: 'Editar'
                  },
                  delete: {
                    class: 'btn btn-sm btn-default',
                    html: '<span class="fa fa-trash"></span>',
                    action: 'Eliminar'
                  },
                  save: {
                    class: 'btn btn-sm btn-success',
                    html: 'Guardar'
                  },
                  restore: {
                    class: 'btn btn-sm btn-warning',
                    html: 'Restaurar',
                    action: 'restore'
                  },
                  confirm: {
                    class: 'btn btn-sm btn-danger',
                    html: 'Confirmar'
                  }
                },  

                onDraw: function() { return; },

                // executed when the ajax request is completed
                // onSuccess(data, textStatus, jqXHR)
                onSuccess: function(data, textStatus, jqXHR) { 
                    if(data.action == 'Editar'){
                        if(data.afectado == 1){
                            toastr.success('<strong>Registro Editado Correctamente </strong>','OK');
                        }
                    }
                    return; 
                },

                // executed when occurred an error on ajax request
                // onFail(jqXHR, textStatus, errorThrown)
                onFail: function() { 
                          setTimeout(function() {
                            location.reload();
                          }, 1000);
                    return; 
                },

                // executed whenever there is an ajax request
                onAlways: function() { return; },

                // executed before the ajax request
                // onAjax(action, serialize)
                onAjax: function() { 
                          return; 
                } ,                            

                columns: {
                    identifier: [0, 'id'],
                    editable: [[1, 'nombre'],[2, 'estado', '{"1": "Activo", "2": "Inactivo"}']]
                }
            }); 
            
            
            $('#tablearea').Tabledit({
                url: 'src/detalle_compra/api.php?tipoGuardar=SaveAreaEditinPlace',
                inputClass: 'form-control input-sm',
                toolbarClass: 'btn-toolbar',
                groupClass: 'btn-group btn-group-sm',
                dangerClass: 'danger',
                warningClass: 'warning',
                mutedClass: 'text-muted',
                restoreButton: false,
                buttons: {
                  edit: {
                    class: 'btn btn-sm btn-default',
                    html: '<span class="fa fa-pencil"></span>',
                    action: 'Editar'
                  },
                  delete: {
                    class: 'btn btn-sm btn-default',
                    html: '<span class="fa fa-trash"></span>',
                    action: 'Eliminar'
                  },
                  save: {
                    class: 'btn btn-sm btn-success',
                    html: 'Guardar'
                  },
                  restore: {
                    class: 'btn btn-sm btn-warning',
                    html: 'Restaurar',
                    action: 'restore'
                  },
                  confirm: {
                    class: 'btn btn-sm btn-danger',
                    html: 'Confirmar'
                  }
                },  

                onDraw: function() { return; },

                // executed when the ajax request is completed
                // onSuccess(data, textStatus, jqXHR)
                onSuccess: function(data, textStatus, jqXHR) { 
                    if(data.action == 'Editar'){
                        if(data.afectado == 1){
                            toastr.success('<strong>Registro Editado Correctamente </strong>','OK');
                        }
                    }
                    return; 
                },

                // executed when occurred an error on ajax request
                // onFail(jqXHR, textStatus, errorThrown)
                onFail: function() { 
                          setTimeout(function() {
                            location.reload();
                          }, 1000);
                    return; 
                },

                // executed whenever there is an ajax request
                onAlways: function() { return; },

                // executed before the ajax request
                // onAjax(action, serialize)
                onAjax: function() { 
                          return; 
                } ,                            

                columns: {
                    identifier: [0, 'id'],
                    editable: [[1, 'nombre'],[2, 'estado', '{"1": "Activo", "2": "Inactivo"}']]
                }
            });             
</script>