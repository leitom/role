<?php namespace Leitom\Role;

use \Illuminate\Support\ServiceProvider;
use \Illuminate\Foundation\AliasLoader;

class RoleServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Register the role manager filter
		// This filter is used to trigger the protection of routes.
		// It will also contain the auth before filter so that it does not have to
		// be implemented at the same time.
		\Route::filter('role-control', 'Leitom\Role\Filters\Role');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// Register the eloquent models used by the package
		$this->registerEloquent();

		// Bind the acutal implementation of the route scanner to the interface
		$this->app['Leitom\Role\RouteScannerInterface'] = $this->app->share(function($app) {
			return new RouteScanner($app['router'], $app['request']);
		});

		// Register the repositories used by the package
		$this->registerRepositories();

		// Register the manager instance to the ioc container
		$this->app['leitom.role.manager'] = $this->app->share(function($app) {
			return new DatabaseManager($app['router'], $app['db'], $app['auth']);
		});

		// Register all commands provided by the package
		$this->registerCommands();

		// Register facades
		$this->registerFacades();

		// Register laravel extension of the form builder
		$this->registerExtensions();
	}

	/**
	 * Register console command
	 *
	 * @return void
	 */
	protected function registerCommands()
	{
		// Sync routes and clear cache command
		$this->app['command.leitom.role.sync'] = $this->app->share(function($app) {
			return new Console\SyncRoutesCommand(
				$app['Leitom\Role\RouteRepositoryInterface'],
				$app['Leitom\Role\RouteScannerInterface'],
				$app['Leitom\Role\RoleRepositoryInterface']
			);
		});

		$this->commands('command.leitom.role.sync');
	}

	/**
	 * Register eloquent models provided by the package
	 *
	 * @return void
	 */
	protected function registerEloquent()
	{
		// Bind the route eloquent model to ioc container for separation posibilities
		$this->app['leitom.role.route'] = $this->app->share(function($app) {
			return new Eloquent\Route;
		});

		// Role model
		$this->app['leitom.role.role'] = $this->app->share(function($app) {
			return new Eloquent\Role;
		});
	}

	/**
	 * Register repositories provided by the package
	 *
	 * @return void
	 */
	protected function registerRepositories()
	{
		// Bind the implementation of RoleRepositoryInterface to the ioc container
		$this->app['Leitom\Role\RoleRepositoryInterface'] = $this->app->share(function($app) {
			return new Repositories\EloquentRoleRepository($app['leitom.role.role']);
		});

		// Bind the Eloquent Route Repository to the interface implementation
		$this->app['Leitom\Role\RouteRepositoryInterface'] = $this->app->share(function($app) {
			return new Repositories\EloquentRouteRepository($app['leitom.role.route']);
		});
	}

	/**
	 * Register facades provided by the package
	 *
	 * @return void
	 */
	protected function registerFacades()
	{
		// Connect parts to the laravel application boot
		$this->app->booting(function() {
			// Load facades aliases
			$loader = AliasLoader::getInstance();

			// Register main class for checking role access
			// This gives us a nice facade to use within our main application
			$loader->alias('Role', 'Leitom\Role\Facades\Role');

			// Override laravel's own form library with role control
			// Every functionality in laravel is supported
			$loader->alias('Form', 'Leitom\Role\Facades\Form');
			
			// Override laravel's own HTML library and add role control
			// Every functionality in laravel is supported
			// We have also our own implementation of roleCheckLink()
			// that's equal to link() but we support parameters as the secound option
			// for matching our routes uri
			$loader->alias('HTML', 'Leitom\Role\Facades\HTML');
		});
	}

	/**
	 * Register laravel extensions
	 * Here we overide laravels own classes
	 *
	 * @return void
	 */
	protected function registerExtensions()
	{
		// Form role control
		$this->app['leitom.role.form'] = $this->app->share(function($app) {
			$form = new Extensions\FormBuilder(
				$app['html'], 
				$app['url'], 
				$app['session.store']->getToken(),
				$app['leitom.role.manager']
			);

			return $form->setSessionStore($app['session.store']);	
		});

		// Html role control
		$this->app['leitom.role.html'] = $this->app->share(function($app) {
			return new Extensions\HtmlBuilder($app['url'], $app['leitom.role.manager']);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array(
			'command.leitom.role',
			'leitom.role.route',
			'leitom.role.manager',
			'Leitom\Role\RouteScannerInterface',
			'Leitom\Role\RouteRepositoryInterface',
			'leitom.role.form',
			'leitom.role.html'
		);
	}

}
