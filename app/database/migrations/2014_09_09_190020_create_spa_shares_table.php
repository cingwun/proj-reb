<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpaSharesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('spa_shares', function($table)
		{
			$table->increments('id', 11);
			$table->string('title');
			$table->string('cover');
			$table->string('background_color');
			$table->string('image');
			$table->text('description');
			$table->string('label_service');
			$table->string('label_product');
			$table->text('tabs');
			$table->text('gallery');
			$table->integer('views');
			$table->integer('sort');
			$table->enum('status', array('1', '0'));
			$table->enum('isInSiderbar', array('1', '0'));
			$table->enum('language', array('tw', 'cn'));
			$table->integer('reference');
			$table->text('meta_name');
			$table->text('meta_content');
			$table->text('meta_title');
			$table->text('h1');
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
		Schema::drop('spa_shares');
	}

}