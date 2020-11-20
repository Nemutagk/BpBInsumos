<?php
namespace Nemutagk\BpBInsumos;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

use Fruitcake\Cors\CorsServiceProvider;
use Nemutagk\BpBInsumos\Providers\{AccountServiceProvider, AuthServiceProvider};
use Jenssegers\Mongodb\MongodbServiceProvider;

class InsumoServiceProvider extends ServiceProvider
{
	public function register() {
		//Registramos otros services providers
		$this->app->register(MongodbServiceProvider::class);
		$this->app->register(CorsServiceProvider::class);
		$this->app->register(AuthServiceProvider::class);
		$this->app->register(AccountServiceProvider::class);
	}

	public function boot() {
		//Registramos los middleware globales
		$this->app->middleware([
			\Fruitcake\Cors\HandleCors::class,
			\Nemutagk\BpBInsumos\Middleware\RequestMiddleware::class,
		]);

		//Registramos route middleware
		$this->app->routeMiddleware([
			'auth' => \Nemutagk\BpBInsumos\Middleware\AuthMiddleware::class
		]);

		$this->loadViewsFrom(__DIR__.'/Resources/views', 'BpBInsumos');
	}
}