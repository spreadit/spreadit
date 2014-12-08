<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('posts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('created_at');
			$table->integer('type')->unsigned();
			$table->text('data', 65535);
			$table->integer('updated_at');
			$table->integer('section_id')->unsigned();
			$table->string('title', 128);
			$table->integer('upvotes');
			$table->integer('downvotes');
			$table->string('url', 256);
			$table->integer('comment_count')->unsigned();
			$table->text('markdown', 65535);
			$table->string('thumbnail', 32);
			$table->integer('deleted_at')->default(0);
			$table->integer('nsfw');
			$table->integer('nsfl');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('posts');
	}

}
