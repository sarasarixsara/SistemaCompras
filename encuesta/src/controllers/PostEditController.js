function PostEditController($scope, $routeParams, $location) {
  $scope.post = {};
$scope.id = $routeParams.postId;
position="";
if($scope.posts.length>0){
 for(i=0; i< $scope.posts.length; i++){
    if($routeParams.postId==$scope.posts[i].id){
	 $scope.post = $scope.posts[i];  
	}
 }
   	$scope.EditPost = function(){
		$scope.posts[position]=$scope.post;
		$location.url("/");
	} 
}  
}