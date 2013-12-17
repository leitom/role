<?php namespace Leitom\Role\Facades;

use \Illuminate\Support\Facades\Facade;

class HTML extends Facade
{
	protected static function getFacadeAccessor()
    {
    	return 'leitom.role.html';
    }
}