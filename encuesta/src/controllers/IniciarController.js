/**
* IniciarController
* Controlador del listado de beneficiarios
*/
function IniciarController($scope, $routeParams, $http, Datas, Utils, $location) {
//console.log("dasd")
//$scope.preguntas = {};
if($scope.preguntas == undefined){
				//setTimeout(function(){
					$("#IdEncuesta").val($routeParams.IdEncuesta);
					Utils.goTo('iniciar/'+$routeParams.IdEncuesta)
				//}, 50)
$http.get('beneficiarios/consultar_preguntas.php?CodEnc='+$routeParams.IdEncuesta).success(function(data) {
	$scope.preguntas    = data;
	console.log("en IniciarController");
	console.log($scope.preguntas);
	if($scope.preguntas.pregs.length>0){
$scope.index = 0;
$scope.pregs= {};
$scope.pregunta= $scope.preguntas[0];
$scope.respuesta = 3;
$quest = $('.left')
		$scope.minResponses = $scope.preguntas.pregs.length;
		$scope.goTo = Utils.goTo
		//$scope.votes = Data.votes
		$scope.votesCount = 0
		$scope.progress = '0%'
//console.log("el index es "+$scope.preguntas.index);
$scope.pregunta = $scope.preguntas[0];
//console.log("asfasdfadfas"+$scope.preguntas.total_rtas);
//console.log("minResponses"+$scope.minResponses);
$scope.doEncuest = function( val ){
	console.log("en do encuest "+val);
	setTimeout(function(){
		$('.choice').blur()
	}, 100)
	
	$scope.pregunta.choice = true
	$scope.pregunta.RESPUESTA = val
	$scope.siguiente()
}
console.log($scope.pregunta);

		$scope.$watch('index', function( newVal, oldVal ){
			 //console.log("asdf".$scope.preguntas.total_rtas);
			if( typeof newVal == 'number' ){
				$scope.pregunta = $scope.preguntas.pregs[ newVal ]
				$scope.preguntas.pregs.RESPUESTA = $scope.respuesta;
				console.log("nuevo index "+newVal);
				console.log($scope.pregunta.OPCIONES);
				
				//$scope.vote = $scope.votes[ newVal ]
				//console.log($scope.preguntas.pregs[ newVal ]);
				$scope.preguntas.pregs.RESPUESTA = $scope.respuesta;
			}
		})
		
		$scope.jumpTo = function( index ){ console.log("en el jump to");
			if( index >= 0 && index < $scope.preguntas.pregs.length ){
				$scope.preguntas.index = index
				$scope.index = index;
				console.log("esto es index dentro de jump to" +index);
			}
			
			else if( index == $scope.preguntas.pregs.length ){
				if( _.filter( $scope.preguntas.pregs, function( v ){ return v.choice !== null } ).length < $scope.minResponses ){
					return
				}
				
				setTimeout(function(){
					/*console.log("uno");
					console.log($scope.preguntas);
					console.log("dos");*/
					Datas.dataenv = $scope.preguntas;
					Utils.goTo('resultado/'+$routeParams.IdEncuesta)
				}, 50)
				
			}
			
			$scope.$$phase || $scope.$apply()
		}
		

		$scope.animateTo = function( index ){ //console.log(index)
			if( index > $scope.preguntas.index ){
				console.log('siguiente',index)
				$scope.siguiente( index )
			}else if( index < $scope.preguntas.index ){
				console.log('anterior',index)
				$scope.anterior( index )
			}
		}
		$scope.siguiente = function( index ){
			index = typeof index == 'undefined' ? $scope.preguntas.index + 1 : index
			//console.log("que es index"+index);
			//console.log("tamano"+$scope.preguntas.pregs.length);
			//console.log("cual es el tamano "+$scope.preguntas.pregs.length);
			if( index <= $scope.preguntas.pregs.length - 1 ){
				$quest.animate({
						opacity: 0,
						//left: -100
					},function(){
						$scope.jumpTo( index )
						$quest.css({
								left: 100
							})
							.animate({
								opacity: 1,
								left: 0
							})
					})
			}else{
				$scope.jumpTo( index )
			}			
		}		

		$scope.anterior = function( index ){
			index = typeof index == 'undefined' ? $scope.preguntas[index] - 1 : index
			console.log(index);
			if( index >= 0 ){
				$quest.animate({
						opacity: 0,
						left: 100
					},function(){
						$scope.jumpTo( index )
						$quest.css({
								left: -100
							})
							.animate({
								opacity: 1,
								left: 0
							})
					})
			}
		}		
	}else{
     Utils.goTo('#/')		
	}	
	
  });
  
}
console.log("antes");
console.log($scope.preguntas);
console.log("despues");
/*
$scope.index = 0;
$scope.pregs= {};
$scope.pregunta= $scope.preguntas[0];
$scope.respuesta = 3;
$quest = $('.left')
		$scope.minResponses = $scope.preguntas.pregs.length;
		$scope.goTo = Utils.goTo
		//$scope.votes = Data.votes
		$scope.votesCount = 0
		$scope.progress = '0%'
//console.log("el index es "+$scope.preguntas.index);
$scope.pregunta = $scope.preguntas[0];
//console.log("asfasdfadfas"+$scope.preguntas.total_rtas);
//console.log("minResponses"+$scope.minResponses);
$scope.doEncuest = function( val ){
	console.log("en do encuest "+val);
	setTimeout(function(){
		$('.choice').blur()
	}, 100)
	
	$scope.pregunta.choice = true
	$scope.pregunta.RESPUESTA = val
	$scope.siguiente()
}
console.log($scope.pregunta);

		$scope.$watch('index', function( newVal, oldVal ){
			 //console.log("asdf".$scope.preguntas.total_rtas);
			if( typeof newVal == 'number' ){
				$scope.pregunta = $scope.preguntas.pregs[ newVal ]
				$scope.preguntas.pregs.RESPUESTA = $scope.respuesta;
				console.log("nuevo index "+newVal);
				console.log($scope.pregunta.OPCIONES);
				
				//$scope.vote = $scope.votes[ newVal ]
				//console.log($scope.preguntas.pregs[ newVal ]);
				$scope.preguntas.pregs.RESPUESTA = $scope.respuesta;
			}
		})
		
		$scope.jumpTo = function( index ){ console.log("en el jump to");
			if( index >= 0 && index < $scope.preguntas.pregs.length ){
				$scope.preguntas.index = index
				$scope.index = index;
				console.log("esto es index dentro de jump to" +index);
			}
			
			else if( index == $scope.preguntas.pregs.length ){
				if( _.filter( $scope.preguntas.pregs, function( v ){ return v.choice !== null } ).length < $scope.minResponses ){
					return
				}
				
				setTimeout(function(){
					Utils.goTo('resultado')
				}, 50)
			}
			
			$scope.$$phase || $scope.$apply()
		}
		

		$scope.animateTo = function( index ){ //console.log(index)
			if( index > $scope.preguntas.index ){
				console.log('siguiente',index)
				$scope.siguiente( index )
			}else if( index < $scope.preguntas.index ){
				console.log('anterior',index)
				$scope.anterior( index )
			}
		}
		$scope.siguiente = function( index ){
			index = typeof index == 'undefined' ? $scope.preguntas.index + 1 : index
			//console.log("que es index"+index);
			//console.log("tamano"+$scope.preguntas.pregs.length);
			//console.log("cual es el tamano "+$scope.preguntas.pregs.length);
			if( index <= $scope.preguntas.pregs.length - 1 ){
				$quest.animate({
						opacity: 0,
						//left: -100
					},function(){
						$scope.jumpTo( index )
						$quest.css({
								left: 100
							})
							.animate({
								opacity: 1,
								left: 0
							})
					})
			}else{
				$scope.jumpTo( index )
			}			
		}		

		$scope.anterior = function( index ){
			index = typeof index == 'undefined' ? $scope.preguntas[index] - 1 : index
			console.log(index);
			if( index >= 0 ){
				$quest.animate({
						opacity: 0,
						left: 100
					},function(){
						$scope.jumpTo( index )
						$quest.css({
								left: -100
							})
							.animate({
								opacity: 1,
								left: 0
							})
					})
			}
		}
*/		
}