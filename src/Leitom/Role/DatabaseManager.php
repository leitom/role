<?php namespace Leitom\Role;

use \Leitom\Role\Contracts\ManagerInterface;
use \Leitom\Role\Exceptions\RouteTypeNotValid;
use \Illuminate\Database\DatabaseManager as IlluminateDatabaseManager;
use \Illuminate\Database\Query\Builder;
use \Illuminate\Auth\UserInterface;
use \Illuminate\Routing\Route;
use \Illuminate\Routing\Router;
use \Illuminate\Auth\AuthManager;

class DatabaseManager implements ManagerInterface
{
	/**
	 * Route instance
	 *
	 * @var \Illuminate\Routing\Router $router
	 */
	protected $router;

	/**
	 * The access level for the current route
	 *
	 * @var integer $accessLevel
	 */
	protected $accessLevel = 0;

	/**
	 * In laravel there are tree valid types of generating
	 * an url:
	 * url -> uri
	 * route -> name
	 * action
	 *
	 * @var array $validTypes
	 */
	protected $validTypes = array(
		'uri',
		'name',
		'action'
	);

	/**
	 * When a user is given access we store the user
	 * object in this class for further referencing
	 *
	 * @var \Illuminate\Auth\UserInterface $user
	 */
	protected $user;

	/**
	 * Instance of AuthManager
	 *
	 * @var \Illuminate\Auth\AuthManager
	 */
	protected $auth;

	/**
	 * The identifier for role control
	 * 
	 * @var string $roleControlIdentifier
	 */
	protected $roleControlIdentifier = 'role-control';

	/**
	 * Create an instance of the Manager
	 *
	 * @param  \Illuminate\Routing\Router 			$router
	 * @param  \Illuminate\Database\DatabaseManager $db
	 * @return void
	 */
	public function __construct(Router $router, IlluminateDatabaseManager $db, AuthManager $auth)
	{
		$this->db = $db;
		$this->router = $router;
		$this->auth = $auth;

		// Set the user object
		$this->user = $this->auth->user();
	}

	/**
	 * Make the database instance with the correct setup
	 *
	 * @return \Illuminate\Database\Query\Builder
	 */
	protected function makeQueryInstance()
	{
		return $this->db->table('role_route')->select('role_route.access_level as acl')
						->join('routes', 'role_route.route_id', '=', 'routes.id')
						->join('roles', 'role_route.role_id', '=', 'roles.id')
						->join('user_role', 'roles.id', '=', 'user_role.role_id');
	}

	/**
	 * Make the route check for the query
	 *
	 * @param  string 	$host
	 * @param  string 	$method
	 * @param  string 	$type
	 * @param  string  	$value
	 * @return \Illuminate\Database\Query\Builder
	 */
	protected function makeRouteCheck($host = '', $method = '', $type = 'uri', $value = null)
	{
		if (in_array($type, $this->validTypes)) {
			return $this->makeQueryInstance()->where('routes.host', $host)
						 				     ->where('routes.method', strtoupper($method))
										     ->where("routes.$type", $value);
		}

		// If the type provided is not valid we cant continue
		throw new RouteTypeNotValid("The type provided ($type) is not valid", 1);
	}

	/**
	 * Check if a route has role control enabled(via before filter)
	 * We cache the result of the query so that we dont have to run the
	 * query every time someone hits this route
	 *
	 * @param  \Illuminate\Database\Query\Builder $query
	 * @return boolean
	 */
	protected function hasRoleControl(Builder $query)
	{
		return (bool) $query->where('routes.before', 'LIKE', "%$this->roleControlIdentifier%")
							->rememberForever()
							->first();
	}

	/**
	 * Check route access against a user
	 * Again we cache the query so that we dont have to trigger it again
	 *
	 * @param  \Illuminate\Database\Query\Builder $query
	 * @return integer $acl
	 */
	protected function checkRouteAgainstUser(Builder $query)
	{
		return $query->where('user_role.user_id', (int) $this->user->getAuthIdentifier())
					 ->rememberForever()
					 ->first();
	}

	/**
	 * Validate a current route/action against a user on top level
	 * this function is used in the filter to validate
	 * therefore we dont have to check if the route has role control
	 *
	 * @return boolean
	 */
	public function hasAccess()
	{
		// If a user is not logged in then we quit
		if ($this->auth->guest()) return false;
		
		// Get an instance of the current route
		$route = $this->router->current();

		if ($access = $this->checkRouteAgainstUser(
				$this->makeRouteCheck(
					(string) $route->domain(),
					(string) head($route->methods()),
					'uri',
					(string) $route->uri()
				)
			)
		) {
			// Set access level on the current route
			$this->accessLevel = (int) $access->acl;
			return true;
		}

		return false;
	}

	/**
	 * Check if a route has role control enabled(via filter)
	 * If the route has role control enabled we check against
	 * the provided user
	 *
	 * @param  \Illuminate\Database\Query\Builder $query
	 * @return boolean
	 */
	protected function validateRouteUser($query)
	{
		if ($this->hasRoleControl($query)) {
			if ($this->auth->guest() || ! $this->checkRouteAgainstUser($query)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Check if a user has access to a url
	 *
	 * @param  string  $method
	 * @param  string  $url
	 * @param  string  $host
	 * @return boolean
	 */
	public function hasUrlAccess($method, $url, $host = '')
	{
		return $this->validateRouteUser($this->makeRouteCheck($host, $method, 'uri', $url));
	}

	/**
	 * Check if a user has access to a route
	 *
	 * @param  string  $method
	 * @param  string  $route
	 * @param  string  $host
	 * @return boolean
	 */
	public function hasRouteAccess($method, $route, $host = '')
	{
		return $this->validateRouteUser($this->makeRouteCheck($host, $method, 'name', $route));
	}

	/**
	 * Check if a user has access to a action
	 *
	 * @param  string  $method
	 * @param  string  $action
	 * @param  string  $host
	 * @return boolean
	 */
	public function hasActionAccess($method, $action, $host = '')
	{
		return $this->validateRouteUser($this->makeRouteCheck($host, $method, 'action', $action));
	}

	/**
	 * The access level given for the current
	 * route with the current user
	 *
	 * @return integer
	 */
	public function accessLevel()
	{
		return (int) $this->accessLevel;
	}
}
