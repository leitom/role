<?php namespace Leitom\Role\Contracts;

interface RoleRepositoryInterface
{
	/**
	 * Get all available roles from storage
	 *
	 * @param  array $columns
	 * @return array
	 */
	public function all($columns = array('*'));

	/**
	 * Get all with pagination attached
	 *
	 * @param  int 	   $perPage
	 * @param  string  $search
	 * @param  array   $columns
	 * @return object
	 */
	public function paginate($perPage = 10, $search = null, $columns = array('*'));

	/**
	 * Find one single role from storage by id
	 *
	 * @param  integer 		$id
	 * @return array|object
	 */
	public function findById($id, $columns = array('*'));

	/**
	 * Create a new role
	 *
	 * @param  string  $name
	 * @param  string  $description
	 * @return integer
	 */
	public function create($name, $description);

	/**
	 * Update an existing role
	 *
	 * @param  integer 	$id
	 * @param  string 	$name
	 * @param  string 	$description
	 * @return boolean
	 */
	public function update($id, $name, $description);

	/**
	 * Delete a role from storage
	 *
	 * @param  int 		$id
	 * @return boolean
	 */
	public function delete($id);

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