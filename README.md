Easy but scalable role manager for laravel 4.1
====

This package makes it very easy to add role capabilities to laravel 4.1
It uses routes as access points. That means that all routes are synced to a
persisant storage(default: database) and then routes connect to different roles that can be connected to users etc.
This package does not handle any controllers/logic for creating or maintaining roles and users.
But it comes with two eloquent models used for persisant storage(Role and Route) that you can connect to your user model.
The logic that handles queries uses the database/query builder.

The package integrates with laravel auth right out of the box.

## Installation
Begin by installing this package through Composer. Edit your project's `composer.json` file to require `leitom/role`

```
"require": {
	"laravel/framework": "4.1.*",
	"leitom/role": "1.0.*@dev"
},
"minimum-stability": "dev"
```

Next, update composer from the Terminal:
`composer update`

Once this operation completes, the final step is to add the service provider. Open `app/config/app.php`, and add a new item
to the provider array.
`'Leitom\Role\RoleServiceProvider'`

## Setup

#### Migrations and Configs
The package provides an artisan command for doing all this by it's self just type `php artisan role:install`

Or you can check out: [http://laravel.com/docs/packages](http://laravel.com/docs/packages) on how to publish the packages
migrations and config files.

#### Seed
Generate a seed file to populate the super admin role with id 1, it will lokk something like this:

```php
class RolesTableSeeder extends Seeder
{
	public function run()
	{
		$role = new Leitom\Role\Eloquent\Role;
		$role->name = 'Super admin';
		$role->description = 'A super admin has access to all routes';
		$role->save();
	}
}
```

#### Add filter
Add the filter with the name from config `role.control.identifier` to your route/group

#### Integrating with your eloquent auth model
Add the following line to your auth model default it's: `app/models/User.php`

```php
public function roles()
{
	return $this->belongsToMany('Leitom\Role\Eloquent\Role', 'user_role');
}
```

## Usage

#### Sync routes
The package comes with an artisan command for automatic syncing the routes to persisant storage
it also comes with an option in config `super.admin.sync` for syncing all new routes to an super admin role 
identified by option `super.admin.id` in config.

Run the following command each time you add a new route to your application: `php artisan routes:sync` this will 
update all routes to the db table and clear the laravel cache.

### Access check functions

Check if the current logged in user has access to a url
`Role::hasUrlAccess($method, $url, $host = '')`

Check if the current logged in user has access to a route
`Role::hasRouteAccess($method, $route, $host = '')`

Check if the current logged in user has access to a action
`hasActionAccess($method, $action, $host = '')`
        
### Access level
In your views you can use `Role::accessLevel()` to check for additional access level. That means if the user have access
to the current route/uri but to see a part of the page the access level have to be larger than 4:

```php
if (Role::accessLevel() > 4) {
	echo "Level 5 acceess granted.";
}
```

### Laravel extensions
The package have extended the HtmlBuilder and the FormBuilder.
This allows us to check for role access on forms and when generating links.

When using `HTML::linkRoute()` then the package check's if the current logged in user have access to the route 
if not the link dont show. same for `HTML::linkAction()`
The package includes an function `HTML::roleCheckLink()` wich are the same as `HTML::link()` except that the third parameter 
excepts an array of parameters like `HTML::linkRoute() and HTML::linkAction()` does.
