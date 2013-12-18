<?php

class RouteScannerTest extends TestCase
{
	public function testThatWeCanGetAnArrayOfRoutesFromImplementation()
	{
		// Register a test route
		Route::get('test', function(){});

		$routeScanner = $this->app->make('Leitom\Role\RouteScannerInterface');

		$routes = $routeScanner->getRoutes();

		$this->assertTrue(count($routes) > 0);
	}
}
