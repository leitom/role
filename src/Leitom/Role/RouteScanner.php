<?php namespace Leitom\Role;

use \Leitom\Role\Contracts\RouteScannerInterface;
use \Illuminate\Http\Request;
use \Illuminate\Routing\Router;
use \Illuminate\Console\Command;
use \Illuminate\Routing\Route;
use \Symfony\Component\Routing\RouteCollection;

class RouteScanner implements RouteScannerInterface
{
	/**
 	 * This class is basicly a copy of the php artisan routes command
 	 * We have our own implementation in this package to get all
 	 * available routes and make it posible to sync them to a persisant storage
 	 */

	/**
	 * The router instance.
	 *
	 * @var \Illuminate\Routing\Router $router
	 */
	protected $router;

	/**
	 * An array of all the registered routes.
	 *
	 * @var \Symfony\Component\Routing\RouteCollection $routes
	 */
	protected $routes;

	/**
	 * The Request instance
	 *
	 * @var \Illuminate\Http\Request $request
	 */
	protected $request;

	/**
	 * Create a new routes instance instance.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function __construct(Router $router, Request $request)
	{
		$this->router = $router;
		$this->request = $request;
		$this->routes = $router->getRoutes();
	}

	/**
	 * Compile the routes into a displayable format.
	 *
	 * @return array
	 */
	public function getRoutes()
	{
		$results = array();

		foreach ($this->routes as $name => $route) {
			$results[] = $this->getRouteInformation($name, $route);
		}

		return array_filter($results);
	}

	/**
	 * Get the route information for a given route.
	 *
	 * @param  string  $name
	 * @param  \Symfony\Component\Routing\Route  $route
	 * @return array
	 */
	protected function getRouteInformation($name, Route $route)
	{
		$uri = head($route->methods()).' '.$route->uri();
		
		return array(
			'host'   => (string) $route->domain(),
			'method' => (string) $this->getMethod($uri),
			'uri'    => (string) $uri,
			'name'   => (string) $route->getName(),
			'action' => (string) $route->getActionName(),
			'before' => (string) $this->getBeforeFilters($route),
			'after'  => (string) $this->getAfterFilters($route)
		);
	}

	/**
	 * Get the method from the uri and then
	 * clean the uri for better seperation in storage by referance
	 *
	 * @param  string $uri
	 * @return string $method
	 */
	protected function getMethod(&$uri)
	{
		list ($method, $uri) = explode(' ', $uri);

		return $method;
	}

	/**
	 * Get before filters
	 *
	 * @param  \Illuminate\Routing\Route  $route
	 * @return string
	 */
	protected function getBeforeFilters($route)
	{
		$before = array_keys($route->beforeFilters());

		$before = array_unique(array_merge($before, $this->getPatternFilters($route)));

		return implode(', ', $before);
	}

	/**
	 * Get all of the pattern filters matching the route.
	 *
	 * @param  \Illuminate\Routing\Route  $route
	 * @return array
	 */
	protected function getPatternFilters($route)
	{
		$patterns = array();

		foreach ($route->methods() as $method) {
			// For each method supported by the route we will need to gather up the patterned
			// filters for that method. We will then merge these in with the other filters
			// we have already gathered up then return them back out to these consumers.
			$inner = $this->getMethodPatterns($route->uri(), $method);

			$patterns = array_merge($patterns, $inner);
		}

		return $patterns;
	}

	/**
	 * Get the pattern filters for a given URI and method.
	 *
	 * @param  string  $uri
	 * @param  string  $method
	 * @return array
	 */
	protected function getMethodPatterns($uri, $method)
	{
		return $this->router->findPatternFilters($this->request->create($uri, $method));
	}

	/**
	 * Get after filters
	 *
	 * @param  Route  $route
	 * @return string
	 */
	protected function getAfterFilters($route)
	{
		return implode(', ', array_keys($route->afterFilters()));
	}
}
