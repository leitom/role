<?php namespace Leitom\Role\Contracts;

interface RouteRepositoryInterface
{
	/**
	 * Get all available routes from storage
	 *
	 * @return array
	 */
	public function all();

	/**
	 * Find one single route from storage by id
	 *
	 * @param  integer $id
	 * @return array
	 */
	public function findById($id);

	/**
	 * Sync all routes to a persistant storage
	 *
	 * @param  Leitom\Role\Contracts\RouteScannerInterface $routeScanner
	 * @param  Leitom\Role\Contracts\RoleRepositoryInterface $roleRepository
	 * @return boolean
	 */
	public function sync(RouteScannerInterface $routeScanner, RoleRepositoryInterface $roleRepository);
}
