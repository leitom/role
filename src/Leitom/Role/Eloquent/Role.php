<?php namespace Leitom\Role\Eloquent;

class Role extends \Eloquent
{
	protected $table = 'roles';

	protected $fillable = array(
		'name', 'description'
	);

	/**
	 * A role can belong to many users
	 * default we use the User model shipped with laravel
	 *
	 * @return Eloquent\Relationship
	 */
	public function users()
	{
		return $this->belongsToMany('User', 'user_role');
	}

	/**
	 * A role can have many routes
	 *
	 * @return Eloquent\Relationship
	 */
	public function routes()
	{
		return $this->belongsToMany('Leitom\Role\Eloquent\Route')->withPivot('access_level')
																 ->orderBy('role_route.access_level', 'desc')
																 ->withTimestamps();
	}
}
