<?php

class ManagerTest extends TestCase
{
	public function testThatWeCanValidateUser()
	{
		// Get a user to test with
		// this is the super admin
		$user = User::find(1);
		$this->be($user);

		$manager = $this->app->make('leitom.role.manager');

		$this->call('GET', '/');

		// Get the check if user is validated
		$access = $manager->hasAccess();

		$this->assertTrue($access);

		$this->assertEquals($manager->accessLevel(), 10);
	}

	public function testThatWeCanNotValidateUser()
	{
		// Get a user to test with
		$user = User::find(2);
		$this->be($user);

		$manager = $this->app->make('leitom.role.manager');

		$this->call('GET', '/');

		// Get the check if user is validated
		$access = $manager->hasAccess();
		
		$this->assertFalse($access);

		$this->assertEquals($manager->accessLevel(), 0);
	}

	public function testAccessChecks()
	{
		$user = User::find(2);
		$this->be($user);

		$manager = $this->app->make('leitom.role.manager');

		$this->call('GET', '/');

		$access = $manager->hasAccess($user);

		// url tests
		$this->assertTrue($manager->hasUrlAccess('GET', 'login'));
		$this->assertFalse($manager->hasUrlAccess('GET', '/'));

		// route tests
		$this->assertTrue($manager->hasRouteAccess('GET', 'session.create'));
		$this->assertFalse($manager->hasRouteAccess('GET', 'routes.create'));

		// action tests
		$this->assertTrue($manager->hasActionAccess('POST', 'SessionController@store'));
		$this->assertFalse($manager->hasActionAccess('GET', 'RoutesController@show'));
	}
}