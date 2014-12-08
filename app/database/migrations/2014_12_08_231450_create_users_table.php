<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('username', 24);
			$table->string('password', 64);
			$table->integer('created_at')->unsigned();
			$table->integer('updated_at')->unsigned();
			$table->string('remember_token', 100);
			$table->integer('points');
			$table->integer('upvotes')->unsigned();
			$table->integer('downvotes')->unsigned();
			$table->boolean('anonymous')->default(0);
			$table->integer('votes')->default(0);
			$table->boolean('show_nsfw')->default(1);
			$table->boolean('show_nsfl')->default(1);
			$table->text('frontpage_ignore_sections', 65535);
			$table->text('frontpage_show_sections', 65535);
			$table->text('profile_data', 65535);
			$table->text('profile_css', 65535);
			$table->text('profile_markdown', 65535);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
