<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePostTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('post_tags', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('post_id');
			$table->integer('user_id');
			$table->integer('created_at');
			$table->integer('updated_at');
			$table->integer('type');
			$table->integer('updown');
			$table->index(['post_id','user_id'], 'post_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('post_tags');
	}

}
