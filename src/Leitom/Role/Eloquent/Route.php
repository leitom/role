<?php namespace Leitom\Role\Eloquent;

class Route extends \Eloquent
{
	/**
	 * The table to handle
	 *
	 * @var string $table
	 */
	protected $table = 'routes';

	/**
	 * The columns that are posible to 
	 * be populated or mass assigned
	 *
	 * @var array $fillable
	 */
	protected $fillable = array(
		'host', 'method', 'uri', 'name',
		'action', 'before', 'after', 'active'
	);

	/**
	 * On route can belong to many different roles
	 *
	 * @return Eloquent\Relationship
	 */
	public function roles()
	{
		return $this->belongsToMany('Leitom\Role\Eloquent\Role')->withPivot('access_level')
																->orderBy('role_route.access_level', 'desc')
																->withTimestamps();
	}
}
