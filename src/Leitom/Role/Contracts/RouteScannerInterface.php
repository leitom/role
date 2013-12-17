<?php namespace Leitom\Role\Contracts;

interface RouteScannerInterface
{
	/**
	 * We will call the getRoutes method on an implementation
	 * of this interface to get all available routes for the system
	 * 
	 * @return array
	 */
	public function getRoutes();
}