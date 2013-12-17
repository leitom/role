<?php namespace Leitom\Role\Contracts;

use \Illuminate\Auth\UserInterface;

interface ManagerInterface
{
	/**
	 * Validate a current route/action against a user on top level
	 * this function is used in the filter
	 *
	 * @return boolean
	 */
	public function hasAccess();

	/**
	 * Check if a user has access to a url
	 *
	 * @param  string  $method
	 * @param  string  $url
	 * @param  string  $host
	 * @return boolean
	 */
	public function hasUrlAccess($method, $url, $host = '');

	/**
	 * Check if a user has access to a route
	 *
	 * @param  string  $method
	 * @param  string  $route
	 * @param  string  $host
	 * @return boolean
	 */
	public function hasRouteAccess($method, $route, $host = '');

	/**
	 * Check if a user has access to a action
	 *
	 * @param  string  $method
	 * @param  string  $action
	 * @param  string  $host
	 * @return boolean
	 */
	public function hasActionAccess($method, $action, $host = '');

	/**
	 * The access level given for the current
	 * route with the current user
	 *
	 * @return integer
	 */
	public function accessLevel();
}
