<?php

use \Mockery as m;

class EloquentRoleRepositoryTest extends TestCase
{
	public function setUp()
	{
		parent::setUp();

		$this->repository = $this->app->make('Leitom\Role\RoleRepositoryInterface');
	}

	public function testThatWeCanAddRouteToSuperAdmin()
	{
		/*
		$route = m::mock('Leitom\RoleManager\Eloquent\Route');
		$route->shouldReceive('')->once()->andReturn()*/
	}
}