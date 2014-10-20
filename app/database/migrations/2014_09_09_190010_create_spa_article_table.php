<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpaArticleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('spa_articles', function($table)
		{
			$table->increments('id', 11);
			$table->string('title');
			$table->string('cover');
			$table->text('content');
			$table->enum('category', array('about', 'news', 'oversea'));
			$table->date('open_at');
			$table->integer('sort');
			$table->integer('status', array('1', '0'));
			$table->integer('views');
			$table->enum('lang', array('tw', 'cn'));
			$table->integer('ref_id');
			$table->text('meta_name');
			$table->text('meta_content');
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
		Schema::drop('spa_articles');
	}

}