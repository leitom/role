<?php namespace Leitom\Role\Facades;

use \Illuminate\Support\Facades\Facade;

class Form extends Facade
{
	protected static function getFacadeAccessor()
    {
    	return 'leitom.role.form';
    }
}