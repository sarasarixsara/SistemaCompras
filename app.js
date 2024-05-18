   /**
     * You must include the dependency on 'ngMaterial' 
     */
var app = angular
  .module('app', ['ngMaterial', 'ngRoute', 'ngResource', 'app.directives', 'ui.bootstrap','ngMessages', 'ngAnimate',
    'angular-datepicker', /**/
  'angularjs-datetime-picker'
  ]);
  

app.config(function($routeProvider) {
  $routeProvider.when('/detail_list',
						{templateUrl: 'src/detalle_compra/views/detalle_lista.html', controller : DetailListController, reloadOnSearch: false});
  $routeProvider.when('/detail_list/page/:cpage',        {templateUrl: 'src/detalle_compra/views/detalle_lista.html',  controller : DetailListController, reloadOnSearch: false}); 						
  $routeProvider.when('/proposal/new',      {template: 'bien bien',  reloadOnSearch: false})
  .
      otherwise({
		  redirectTo: '/detail_list'});
});


  
  app.controller('AppCtrl', function ($scope, $timeout, $mdSidenav, $log, $rootScope, detalleservice) {
	  $rootScope.estados = [];
	  
	  
    $scope.toggleLeft = buildDelayedToggler('left');
    $scope.toggleRight = buildToggler('right');
    $scope.isOpenRight = function(){
      return $mdSidenav('right').isOpen();
    };

    /**
     * Supplies a function that will continue to operate until the
     * time is up.
     */
    function debounce(func, wait, context) {
      var timer;

      return function debounced() {
        var context = $scope,
            args = Array.prototype.slice.call(arguments);
        $timeout.cancel(timer);
        timer = $timeout(function() {
          timer = undefined;
          func.apply(context, args);
        }, wait || 10);
      };
    }

    /**
     * Build handler to open/close a SideNav; when animation finishes
     * report completion in console
     */
    function buildDelayedToggler(navID) {
      return debounce(function() {
        // Component lookup should always be available since we are not using `ng-if`
        $mdSidenav(navID)
          .toggle()
          .then(function () {
            $log.debug("toggle " + navID + " is done");
          });
      }, 200);
    }

    function buildToggler(navID) {
      return function() {
        // Component lookup should always be available since we are not using `ng-if`
        $mdSidenav(navID)
          .toggle()
          .then(function () {
            $log.debug("toggle " + navID + " is done");
          });
      };
    }
	
	function loadEstadosAreas(){
			detalleservice.loadEstadosAreas()
			.then(function( data ){
				$rootScope.estados = data.estados;
				$rootScope.areas = data.areas;
			}).catch(function(err) {
			   alert("error to save employee "+err);
			});		
	}	
	loadEstadosAreas();
  })
  .controller('LeftCtrl', function ($scope, $timeout, $mdSidenav, $log) {
    $scope.close = function () {
      // Component lookup should always be available since we are not using `ng-if`
      $mdSidenav('left').close()
        .then(function () {
          $log.debug("close LEFT is done");
        });

    };
  });
