<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpaarticlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('spa_articles', function($table)
		{
			//$table->dropIfExists();
			$table->increments('id');
			$table->string('title');
			$table->text('content');
			$table->enum('category', array('1','2','3')); //1=關於煥麗 2=最新消息 3=美麗分享
			$table->date('open_at');
			$table->integer('sort');
			$table->tinyInteger('status');
			$table->integer('views'); //瀏覽數
			$table->enum('lan', array('zh','cn'));
			$table->integer('ref_id');
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