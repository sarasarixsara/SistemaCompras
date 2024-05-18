<html lang="en" >
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Angular Material style sheet -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
  <link rel="stylesheet" href="includes/angular-material/1.1.7/angular-material.min.css">
	<link rel="stylesheet" href="includes/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="includes/angular-1.5.8/angularjs-datetime-picker.css">
	<link rel="stylesheet" href="includes/angular-1.5.8/themes/default.date.css"/>
	<link rel="stylesheet" href="includes/angular-1.5.8/themes/default.time.css"/>	
  <!-- Angular Material requires Angular.js Libraries -->
  <script src="js/moment_2.14.1.js"></script>
  <script src="includes/angular-1.5.8/angular.min.js"></script>
  <script src="includes/angular-1.5.8/angular-animate.min.js"></script>
  <script src="includes/angular-1.5.8/angular-aria.min.js"></script>
  <script src="includes/angular-1.5.8/angular-messages.min.js"></script>	
  <script src="includes/angular-1.5.8/angular-route.min.js"></script>	
  <script src="includes/angular-1.5.8/angular-resource.min.js"></script>	
  <script src="includes/angular-1.5.8/angularjs-datetime-picker.min.js"></script>	
  <script src="includes/angular-1.5.8/angular-datepicker.js"></script>	
  <!-- Angular Material Library -->
  <script src="includes/angular-material/1.1.7/angular-material.min.js"></script>
  <script src="includes/angularbootstrap/ui-bootstrap-tpls-2.5.0.min.js"></script>
  <script src="app.js"></script>
  <script src="src/detalle_compra/services/utils.js"></script>
  <script src="src/detalle_compra/services/filtros.js"></script>
  <script src="src/detalle_compra/controllers/detallelistacontroller.js"></script>
  <script src="src/detalle_compra/services/detalleservice.js"></script>
  <script src="src/detalle_compra/directives/directives.js"></script>

  
  <!-- Your application bootstrap  -->
  <script type="text/javascript">    
$(document).ready(function() {
jQuery(function($){
   $.datepicker.regional['es'] = {
      closeText: 'Cerrar',
      prevText: '<Ant',
      nextText: 'Sig>',
      currentText: 'Hoy',
      monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
      dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
      dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
      dayNamesMin: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
      weekHeader: 'Sm',
      dateFormat: 'dd/mm/yy',
      firstDay: 1,
      isRTL: false,
      showMonthAfterYear: false,
      yearSuffix: ''};
   $.datepicker.setDefaults($.datepicker.regional['es']);
});		
	
			
$("#fecha_desde, #fecha_hasta, #fecha_env_req").datepicker({
   showOn: 'both',
   buttonImage: 'images/calendar.png',
   buttonImageOnly: true,
   changeYear: true,
   dateFormat: 'dd/mm/yy',
   //regional:'es',
   //numberOfMonths: 2,
   onSelect: function(fech, objDatepicker){
      //alert("fecha seleccionada: " + fech);
   }
});			

});
	
  </script>	
	<style type="text/css">
.toolbarapp{
	position:absolute;
	top:0;
	left:0;
}
.contentapp{
	margin-top:0px;
}
.paginacionpdf{
	padding-left: 0;
}
.paginacionpdf>li{
	display: inline;
}
.paginacionpdf>li:hover{
	cursor:pointer;
}
.paginacionpdf>li>a,
.paginacionpdf>li>span{
	position:relative;
	float:left;
	margin-left:2px;
	margin-top:2px;
}
.paginacionpdf a{
   margin-left:2px;
   padding:8px;
   background: #CDEFC6;
   border:solid 1px #76966F;
   font-weight: 600;
   padding: 8px;
   text-decoration: none
}
.paginacionpdf span{
	background: #eeebeb ;
	border: 1px solid #e3dbdb;
    border-radius: 5px;
    color: #3b5c18;
    padding: 8px;
}
.size8{
	font-size: 0.8em;
}
.iconfilter{
	padding-left:0; color:#777;
}
.iconfilter:hover{
	color:#565151;
	cursor:pointer;
}
.caret_searchfilter {
    position: absolute;
    top: 0.2em;
    right: -9.5em;
    /*width: 20px;*/
    text-align: center;
}
.message{
border-top: solid 1px #ccc; 
border-bottom: solid 1px #ccc	
}
	</style>
</head>


<body ng-app="app" ng-cloak layout="column">

<div ng-controller="AppCtrl" layout="column" style="height:500px;" ng-cloak>

<section  >


			<div class="contentapp" ng-view>
				
			</div>








</section>

</div>
</body>


</html>