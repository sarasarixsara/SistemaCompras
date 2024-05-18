/**
* PostNewController
* Controlador de agregar nuevo post
*/
function PostNewController($scope, $http, $location, Utils) {
  $scope.ButtonGuardar = " Publicar ";
  $scope.ButtonVolver = " Volver ";
  $scope.post = {};
mayorid = $scope.posts.length+1;
if(mayorid!='0'){
//$scope.post.id = parseInt(mayorid)+1;
$scope.post.id = Utils.newuuid();
$scope.post.image="imagenes/img4.jpg";
$scope.post.votes = 0;
}
 
  	$scope.NewPost = function(){

		$scope.posts.push($scope.post);
		$location.url("/");
	} 
	
}