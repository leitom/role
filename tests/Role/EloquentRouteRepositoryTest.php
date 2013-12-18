<?php

use \Mockery as m;

class EloquentRouteRepositoryTest extends TestCase
{
	public function setUp()
	{
		parent::setUp();

		// Get repository to test
		$this->repository = $this->app->make('Leitom\Role\RouteRepositoryInterface');
	}

	public function testSyncRoutes()
	{
		// Get the instance of Route Scanner Interface
		$scannerInterface = $this->app->make('Leitom\Role\RouteScannerInterface');

		// Get an instance of the role repository
		$roleRepository = $this->app->make('Leitom\Role\RoleRepositoryInterface');

		// Just to make sure that no php errors are invoked on sync action
		$this->assertTrue($this->repository->sync($scannerInterface, $roleRepository));
	}

	public function testThatWeCanGetAllRoutes()
	{
		$routes = $this->repository->all();

		$this->assertTrue(count($routes) > 0);

		// It's good enough to check the first and last index
		$this->routeStructureAssert(current($routes));
		$this->routeStructureAssert(end($routes));
	}

	/**
	 * @expectedException Leitom\Role\Exceptions\NoRoutesAreSyncronizedToPersistantStorage
	 * @expectedExceptionCode 1
	 */
	public function testThatWhenGetAllFailsItThrowsAnException()
	{
		$mock = m::mock('Leitom\Role\Eloquent\Route');
		$mock->shouldReceive('all')->once()->andReturn(array());

		// Config
		$config = $this->app->make('config');

		// To make it fail we replace the route model with one that does
		// not contain any data
		$repository = new Leitom\Role\Repositories\EloquentRouteRepository($mock, $config);

		$routes = $repository->all();
	}

	public function testThatWeCanFindById()
	{
		// Change this to 1 when switching to in memory db
		$route = $this->repository->findById(1);

		$this->routeStructureAssert($route);
	}

	/**
	 * @expectedException Leitom\Role\Exceptions\CouldNotFindRouteById
	 * @expectedExceptionCode 2
	 */
	public function testThatExceptionIsThrownWhenFindbyIdFails()
	{
		$route = $this->repository->findById(999999999);
	}

	protected function routeStructureAssert($route)
	{
		$this->assertArrayHasKey('id', $route);
		$this->assertArrayHasKey('method', $route);
		$this->assertArrayHasKey('uri', $route);
		$this->assertArrayHasKey('action', $route);
		$this->assertArrayHasKey('name', $route);
		$this->assertArrayHasKey('before', $route);
		$this->assertArrayHasKey('after', $route);
		$this->assertArrayHasKey('active', $route);
	}
}