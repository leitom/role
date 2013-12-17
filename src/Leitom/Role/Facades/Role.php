<?php namespace Leitom\Role\Facades;

use \Illuminate\Support\Facades\Facade;

class Role extends Facade
{
    protected static function getFacadeAccessor()
    {
    	return 'leitom.role.manager';
    }
}
