function PostDetailController($scope, $routeParams, Utils) {
$scope.id = $routeParams.postId;
if($scope.posts.length>0){
 for(i=0; i< $scope.posts.length; i++){
    if($routeParams.postId==$scope.posts[i].id){
	 $scope.post = $scope.posts[i];  
	}
 }
}
		$scope.goTo = function( dest ){
		//console.log("destino "+dest );
		Utils.goTo( dest )
	}
			$scope.Votar = function( action, val, index ){
				if(action=='up'){
				  val.votes =parseInt(val.votes)+1; /*primera forma de aplicar el cambio de acuerdo a el objeto*/
				  //$scope.posts[index].votes = parseInt($scope.posts[index].votes)+1; /*segunda forma de acuerdo al indice posicion del objeto*/
				}
				if(action=='down'){
				val.votes =parseInt(val.votes)-1;
				//$scope.posts[index].votes = parseInt($scope.posts[index].votes)+1; 
				}
		}	
}