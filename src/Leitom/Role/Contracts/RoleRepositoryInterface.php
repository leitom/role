<?php namespace Leitom\Role\Contracts;

interface RoleRepositoryInterface
{
	/**
	 * Get all available roles from storage
	 *
	 * @return array
	 */
	public function all();

	/**
	 * Find one single role from storage by id
	 *
	 * @param  integer $id
	 * @return array
	 */
	public function findById($id);

	/**
	 * A convenient way to attach a role to
	 * a super admin role for easier development
	 *
	 * @param  array $routes
	 * @return void
	 */
	public function attachRoutesToSuperAdmin(array $routes = array());

	/**
	 * Get the access level that super admin should have
	 *
	 * @return integer
	 */
	public function getAccessLevelForSuperAdmin();
}