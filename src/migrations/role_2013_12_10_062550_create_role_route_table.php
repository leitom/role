<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRoleRouteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('role_route', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('route_id')->unsigned();
			$table->integer('role_id')->unsigned();
			// Access level - can be used in views
			$table->integer('access_level')->default(1);
			$table->timestamps();

			// Foreign keys

			// Route
			$table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');

			// Role
			$table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('role_route');
	}

}
