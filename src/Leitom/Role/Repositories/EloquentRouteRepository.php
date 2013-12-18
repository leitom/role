<?php namespace Leitom\Role\Repositories;

use \Leitom\Role\Exceptions\NoRoutesAreSyncronizedToPersistantStorage;
use \Leitom\Role\Exceptions\CouldNotFindRouteById;
use \Leitom\Role\Contracts\RouteRepositoryInterface;
use \Leitom\Role\Contracts\RoleRepositoryInterface;
use \Leitom\Role\Contracts\RouteScannerInterface;
use \Leitom\Role\Eloquent\Route;
use \Illuminate\Config\Repository;

class EloquentRouteRepository implements RouteRepositoryInterface
{
	/**
	 * Instance of the eloquent model Route
	 *
	 * @var \Leitom\Role\Repositories\Route $route
	 */
	protected $routes;

	/**
	 * Instance of Config repository
	 *
	 * @var \Illuminate\Config\Repository $config
	 */
	protected $config;

	/**
	 * If we should sync the routes to a default role
	 * ex. super admin
	 *
	 * @var boolean $roleSync
	 */
	protected $roleSync = true;

	/**
	 * Generate a new instance of the EloquentRouteRepository instance
	 *
	 * @param  \Leitom\Role\Eloquent\Route 	  $route
	 * @param  \Illuminate\Config\repository  $config
	 * @return void
	 */
	public function __construct(Route $routes, Repository $config)
	{
		$this->routes = $routes;
		$this->config = $config;

		// Set role sync
		$this->roleSync = $this->config->get('role::super.admin.sync');
	}

	/**
	 * Get all available routes from storage
	 *
	 * @return array
	 */
	public function all()
	{
		$routes = $this->routes->all();

		if ($routes) return $routes->toArray();

		throw new NoRoutesAreSyncronizedToPersistantStorage(
			"No Routes are syncronized to persisant storage",
			1
		);
	}

	/**
	 * Find one single route from storage by id
	 *
	 * @param  integer $id
	 * @return array
	 */
	public function findById($id)
	{
		$route = $this->routes->find($id);

		if ($route) return $route->toArray();
		
		throw new CouldNotFindRouteById("Could not find a route by the id: $id", 2);
	}

	/**
	 * Sync all routes to a persistant storage
	 *
	 * @param  Leitom\Role\Contracts\RouteScannerInterface $routeScanner
	 * @param  Leitom\Role\Contracts\RoleRepositoryInterface $roleRepository
	 * @return boolean
	 */
	public function sync(RouteScannerInterface $routeScanner, RoleRepositoryInterface $roleRepository)
	{
		$routes = $routeScanner->getRoutes();

		$this->storeRoutes($routes, $roleRepository);

		return true;
	}

	/**
	 * Store routes to persisant storage
	 *
	 * @param  array $routes
	 * @param  Leitom\Role\Contracts\RoleRepositoryInterface $roleRepository
	 * @return void
	 */
	protected function storeRoutes(array $routes = array(), RoleRepositoryInterface $roleRepository)
	{
		// If there are routes registered in the system
		// we import if not we dont return nothing
		if (count($routes) > 0) {
			// Deactivate all routes before sync
			$this->deactivateRoutes();
			
			// Super admin role sync
			$roleSync = array();

			foreach ($routes as $route) {
				// Assign the access level also for admin role
				$roleSync[(int) $this->storeRoute($route)->id] = array(
					'access_level' => $roleRepository->getAccessLevelForSuperAdmin()
				);
			}

			// Sync routes to super admin role(if enabled)
			if ($this->roleSync) {
				$roleRepository->attachRoutesToSuperAdmin($roleSync);
			}
			
			// Clean up all routes that are outdated
			$this->cleanup();
		}
	}

	/**
	 * Store a route to persisant storage
	 * We will handle Create / Update here
	 *
	 * @param  array $routeData
	 * @return void
	 */
	protected function storeRoute(array $routeData = array())
	{
		// Append active to the route we know is valid
		$routeData['active'] = 1;

		// Check if the route allready exists then we will update that instance
		$route = $this->routes->where('host', $routeData['host'])
							  ->where('method', $routeData['method'])
							  ->where('uri', $routeData['uri'])
							  ->first();
		
		// If the route does not exists we create a new route instance
		if ( ! $route) {
			$route = $this->routes->create($routeData);
		} else {
			$route->fill($routeData)->save();
		}
		
		return $route;
	}

	/**
	 * Deactivate all routes based on the active flag
	 *
	 * @return void
	 */
	protected function deactivateRoutes()
	{
		return $this->routes->where('active', 1)->update(array('active' => 0));
	}

	/**
	 * Clean up the routes that still not active
	 *
	 * @todo Clear query cache also when it's implemented
	 *
	 * @return void
	 */
	protected function cleanup()
	{
		return $this->routes->where('active', 0)->delete();
	}
}
