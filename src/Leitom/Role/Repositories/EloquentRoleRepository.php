<?php namespace Leitom\Role\Repositories;

use \Leitom\Role\Contracts\RoleRepositoryInterface;
use \Leitom\Role\Eloquent\Role;
use \Illuminate\Config\Repository;

class EloquentRoleRepository  implements RoleRepositoryInterface
{
	/**
	 * An instance of the eloquent model Role
	 *
	 * @var \Leitom\Role\Eloquent\Role $roles
	 */
	protected $roles;

	/**
	 * The access level a auto synced route should
	 * have for a super admin
	 *
	 * @var integer $superAdminAccessLevel
	 */
	protected $superAdminAccessLevel = 10;

	/**
	 * The identifier for the super admin role
	 *
	 * @var integer $superAdminIdentifier
	 */
	protected $superAdminIdentifier = 1;

	/**
	 * Instance of Config repository
	 *
	 * @var \Illuminate\Config\Repository $config
	 */
	protected $config;

	/**
	 * Create an instance of EloquentRoleRepository
	 *
	 * @param  \Leitom\Role\Eloquent\Role 	  $roles
	 * @param  \Illuminate\Config\repository  $config
	 * @return void
	 */
	public function __construct(Role $roles, Repository $config)
	{
		$this->roles = $roles;
		$this->config = $config;

		// Set the super admin identifier
		$this->superAdminIdentifier = $this->config->get('role::super.admin.id');
	}

	/**
	 * Get all available roles from storage
	 *
	 * @return array
	 */
	public function all()
	{
		return $this->roles->all();
	}

	/**
	 * Find one single role from storage by id
	 *
	 * @param  integer $id
	 * @return array
	 */
	public function findById($id)
	{
		return $this->roles->find($id);
	}

	/**
	 * Create a new role
	 *
	 * @param  string  $name
	 * @param  string  $description
	 * @return integer
	 */
	public function create($name, $description)
	{
		$role = $this->roles->create(array('name' => $name, 'description' => $description));
		
		return $role->id;
	}

	/**
	 * A convenient way to attach a role to
	 * a super admin role for easier development
	 *
	 * @param  array $routes
	 * @return void
	 */
	public function attachRoutesToSuperAdmin(array $routes = array())
	{
		// Get the super admin role
		$role = $this->findById($this->superAdminIdentifier);

		// Sync all routes
		$role->routes()->sync($routes);
	}

	/**
	 * Get the access level that super admin should have
	 *
	 * @return integer
	 */
	public function getAccessLevelForSuperAdmin()
	{
		return $this->superAdminAccessLevel;
	}
}
