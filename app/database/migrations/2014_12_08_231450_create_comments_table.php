<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('created_at')->unsigned();
			$table->integer('post_id')->unsigned();
			$table->text('data', 65535);
			$table->integer('updated_at')->unsigned();
			$table->integer('parent_id')->unsigned()->index('parent_id');
			$table->integer('upvotes');
			$table->integer('downvotes');
			$table->text('markdown', 65535);
			$table->integer('deleted_at')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('comments');
	}

}
