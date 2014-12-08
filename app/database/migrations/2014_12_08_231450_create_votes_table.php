<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('votes', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id')->unsigned()->index('user_id');
			$table->integer('type')->unsigned();
			$table->integer('item_id')->unsigned()->index('item_id');
			$table->integer('updown');
			$table->integer('created_at')->unsigned();
			$table->integer('updated_at')->unsigned();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('votes');
	}

}
