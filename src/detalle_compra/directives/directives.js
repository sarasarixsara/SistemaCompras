(function () {

  angular.module('app.directives', [])
    .directive('pokemonName', function () {
      return {
        restrict: 'E',
        templateUrl: 'partials/pokemon-name.html'
      };
    })

    .directive('pagination', function () {
      return {
        restrict: 'E',
        templateUrl: './src/detalle_compra/views/pagination.html'
      };
    });/*
	.directive('myDatepicker', function ($parse) {
	    return function (scope, element, attrs, controller) {
	        var ngModel = $parse(attrs.ngModel);
			//console.log(ngModel);
			//console.log(scope.modelo);
	        $(function(){
	            element.datepicker({
	                showOn:"button",
	                changeYear:true,
	                changeMonth:true,
	                dateFormat: 'mm/dd/yy',
					buttonImage: "calendar.gif",
					buttonImageOnly: true,
	                onSelect:function (dateText, inst) {
	                    scope.$apply(function(scope){
	                        ngModel.assign(scope, dateText);
	                    });
	                }
	            });
	        });
	    }
	});	*/

})();
