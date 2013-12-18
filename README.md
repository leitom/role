role
====

# H1 This package are not done yet and not ready for use!

# H2 Easy but scalable role manager for laravel 4.1
This package makes it very easy to add role capabilities to laravel 4.1
It uses routes as access points. That means that all routes are synced to a
persisant storage(default: database) and then routes connect to different roles that can be connected to users etc.
This package does not handle any controllers/logic for creating or maintaining roles and users.
But it comes with two eloquent models used for persisant storage(Role and Route) that you can connect to your user model.
The logic that handles queries uses the database/query builder.

# H2 Installation
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

* Inital commit, dont use this package yet!
