var app = angular.module("app", [
		'ngResource',
		'ngRoute',
		'ngAnimate',
]);

app.config(function($routeProvider){
	$routeProvider.
      when('/', {
		  templateUrl: 'src/views/nopr.html'
		  }).
		  when('/enc/:IdEncuesta', {
		  templateUrl: 'src/views/welcome.html',
		  controller: WelcomeController
		  }).
		  when('/salida/:IdEncuesta', {
		  templateUrl: 'src/views/salida.html',
		  controller: SalidaController
		  }).
		  when('/iniciar/:IdEncuesta', {
		  templateUrl: 'src/views/iniciar.html',
		  controller: IniciarController
		  }).
		  when('/resultado/:IdEncuesta', {
		  templateUrl: 'src/views/resultado.html',
		  controller: ResultadoController
		  }).
      when('/pagina/:PaginaVars', {
		  templateUrl: 'src/views/beneficiarios_lista.html',
		  controller: BeneficiariosListPageController
		  }).
		  when('/pagina/:PaginaVars/otravar/:Otra', {
		  templateUrl: 'src/views/beneficiarios_lista.html',
		  controller: BeneficiariosListPageController
		  }).		  
	 when('#/', {
		  templateUrl: 'src/views/beneficiarios_lista.html',
		  controller: BeneficiariosListPageController
		  }).
      otherwise({
		  redirectTo: '/'})
})