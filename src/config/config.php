<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Super admin
	|--------------------------------------------------------------------------
	|
	| A boolean to check if we are going to sync all new routes to a default
	| super admin role (very handy for developing)
	| Default: true
	|
	*/

	'super.admin.sync' => true,

	/*
	|--------------------------------------------------------------------------
	| Super admin identifier
	|--------------------------------------------------------------------------
	|
	| The default role identifier for the super admin role
	| Default: 1
	|
	*/

	'super.admin.id' => 1,

	/*
	|--------------------------------------------------------------------------
	| Role control identifier
	|--------------------------------------------------------------------------
	|
	| The role control identifier is used as the filter name for role control
	| Every role synced to a persisant storage get's this in the filter column
	| When changing this remember to update your routes file
	|
	*/

	'role.control.identifier' => 'role',

	/*
	|--------------------------------------------------------------------------
	| Access levels
	|--------------------------------------------------------------------------
	|
	| An array containing default access levels
	| There are no connection to this in the package but can be used in an
	| interface to show a dropdown etc.
	|
	*/

	'access.levels' => array(
		1 => 'read',
		2 => 'write',
		3 => 'update',
		4 => 'delete',
		5 => 'all'
	)

);