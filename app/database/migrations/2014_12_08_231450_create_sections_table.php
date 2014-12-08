<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSectionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sections', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('title', 24);
			$table->integer('created_at');
			$table->integer('updated_at');
			$table->text('data', 65535);
			$table->integer('upvotes');
			$table->integer('downvotes');
			$table->integer('markdown');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sections');
	}

}
