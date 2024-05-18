var DetailListController = ['$rootScope','$scope','$routeParams', '$timeout', '$location', '$q',  'Utils', 'Filtros', 'detalleservice', function($rootScope, $scope, $routeParams, $timeout, $location, $q, Utils, Filtros, detalleservice) {
	$scope.viewtoolsmsg = false;
	$scope.detallesvalidacion = [];
	$scope.currentPath = $location.path();
	$scope.filtrobuscar = {estado:'', area:'', nombre: '', fecha_env_req: ''};
	$scope.paginacioninfo = {start:'', end:''}
	$scope.toolbarvisible = false;
	$scope.detallesadd = [];
	var envio = {};
		$scope.cleanfilterform = function(){
			document.getElementById('festado').value='';	
			document.getElementById('farea').value='';
			document.getElementById('fnombre').value='';
			Filtros.filtro= {};
			Filtros.fuente = '';
		}	

	    if(Filtros.filtro){
			if(Filtros.fuente == 'DetailListController'){
				envio = Filtros.filtro;
				$scope.filtrobuscar = envio;
			}else{
				$scope.cleanfilterform();
			}
		}		

	function getDetallesLista(envio){
		detalleservice.getDetallesLista(envio)
		.then(function( proposals ){ 
			$scope.detalleslista   = ( proposals[0].Detalles);
			$scope.currentpage = proposals[0].Paginate.current;
			$scope.totalpage   = proposals[0].Paginate.total;
			$scope.totalrecords   = proposals[0].Paginate.totalrecords;
			$scope.recordsPerPage   = proposals[0].Paginate.recordsPerPage;
			$scope.paginacion = Utils.paginate($scope.currentpage,$scope.totalpage,$scope.recordsPerPage,'detail_list');	
			//getDataAdicionalProposalList();
			var calcstart = (($scope.currentpage-1)*$scope.recordsPerPage)+1;
			var calcend   = $scope.currentpage*$scope.recordsPerPage;
			if(calcstart > $scope.totalrecords){
				calcstart = 0;
			} 
			if(calcend >= $scope.totalrecords){
			   calcend  = $scope.totalrecords;
			}
			$scope.paginacioninfo = {start: calcstart, end: calcend};
		})			
	}	
	getDetallesLista(envio);

	$scope.goTo = function(url){
			Utils.goTo( url )			
	}


	$scope.BuscarDetalles = function(){
					Filtros.filtro = { 'estado' : document.getElementById('festado').value, 
								'area'  : document.getElementById('farea').value,
								'nombre'  : document.getElementById('fnombre').value,
								'fecha_env_req'  : $scope.filtrobuscar.fecha_env_req								
								}
		Filtros.fuente = 'DetailListController';								
		getDetallesLista(Filtros.filtro);
		Utils.goTo('detail_list')		
	}
	
	$scope.adddetalle = function(){
		codigo_chk = this.detalle.CODIGO;
		if(document.getElementById('multiplefile_'+codigo_chk).checked==true){
			/* add to array detalles scope*/
			$scope.detallesadd = $scope.detallesadd.concat({codigo:codigo_chk});
			console.log($scope.detallesadd);
		}else{
			/*delete to array if false*/
			t=0;
			angular.forEach($scope.detallesadd, function(objeto) {
				if(objeto.codigo == codigo_chk){
					$scope.detallesadd.splice(t, 1); /* borrar el elmento del objeto */
				}
				t++;
			});		
					 
		}
	}

	$scope.changeStatusDetail = function(estado){
			var detallesvalidacion = []; /*detalles que no se pueden validar estado diferente a 6*/
			t=0;
			angular.forEach($scope.detallesadd, function(objeto) {
				for(i=0; i < $scope.detalleslista.length; i++){
					if(objeto.codigo == $scope.detalleslista[i]['CODIGO']){
						if($scope.detalleslista[i]['CODIGO_ESTADO'] != 6){
							detallesvalidacion.push({'CODIGO': $scope.detalleslista[i]['CODIGO']});
						}
					}
				}
				t++;
			});	
			console.log('asdf');
			console.log(detallesvalidacion);
			if(detallesvalidacion.length >0 ){
				$scope.viewtoolsmsg = true;
				$scope.detallesvalidacion = detallesvalidacion;
				clearcheckboxselected(detallesvalidacion);
			}
			
			
		/*
		detalleservice.ChangeStatusDetail($scope.detallesadd, estado)
		.then(function( details ){ 
				console.log($scope.detallesadd);
		})		*/
	}
	function clearcheckboxselected(array){
		/*for(j=0; j < $scope.detallesvalidacion.length; j++) {
			console.log($scope.detallesvalidacion[j]['CODIGO']);
		}
		console.log('separatos');
		for(j=0; j < $scope.detalleslista.length; j++) {
			console.log($scope.detalleslista[j]['CODIGO']);
		}*/		
		if($scope.detallesvalidacion.length > 0){ 
			for(i=0; i < $scope.detallesvalidacion.length; i++){
				for(j=0; j < $scope.detalleslista.length; j++) {
					console.log("segund if");
					if($scope.detallesvalidacion[i]['CODIGO'] == $scope.detalleslista[j]['CODIGO']){
						console.log("equal");
						if($scope.detallesvalidacion.length > 0){
							$scope.detallesvalidacion.splice(i, 1);
							console.log(i+'before '+$scope.detallesvalidacion[i]['CODIGO']);
						}
					}
				}
			}
		}
		console.log('agunasdf');
		console.log($scope.detallesvalidacion);
		console.log("end");
	}	

	$scope.options = {
  format: 'mm/dd/yyyy', // ISO formatted date
  onClose: function(e) {
    // do something when the picker closes   
  }
}

}]		