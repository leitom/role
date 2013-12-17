<?php namespace Leitom\Role\Filters;

class Role
{
	/**
	 * Main filter function triggered by laravel
	 * Here we will trigger our role/route check also do the 
	 * standard auth check here so that we dont need to add 
	 * more filter parameters to our routes
	 * @todo find where we should go when we dont have access
	 * @return Response
	 */
	public function filter()
	{
		// Heres the implementation of the default auth filter
		// All routes using the Role Manager needs the auth object instance
		// to look up rights for the users

		// In the redirect page we can do a Auth::guest() check to see if the user
		// are logged in or not. If the user are logged in we can show a warning that he/she
		// does not have the rights required to view the page / take the action
		if ( ! \Role::hasAccess()) return \Redirect::guest('login');
	}
}
