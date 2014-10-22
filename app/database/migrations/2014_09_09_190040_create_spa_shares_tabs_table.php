<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpaSharesTabsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('spa_shares_tabs', function($table)
		{
			$table->increments('id', 11);
			$table->enum('type', array('shares'));
			$table->integer('item_id');
			$table->string('title');
			$table->text('content');
			$table->integer('sort');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('spa_shares_tabs');
	}

}