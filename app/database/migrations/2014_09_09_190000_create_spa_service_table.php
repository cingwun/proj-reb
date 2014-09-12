<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpaServiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(!Schema::hasTable('spa_service')){
			Schema::create('spa_service',function($table){
				$table->increments('id');
				$table->string('title');
				$table->string('image');
				$table->string('image_desc');
				$table->text('content');
				$table->integer('views');
				$table->string('_parent')->default('N');
				$table->integer('sort');
				$table->enum('display', array('yes', 'no'))->default('yes');
				$table->enum('lan', array('zh', 'ch'));
				$table->integer('ref_id'); //service reference id
				$table->timestamps();
			});
		}
		if(!Schema::hasTable('spa_service_images')){
			Schema::create('spa_service_images',function($table){
				$table->increments('id');
				$table->integer('ser_id')->unsigned(); //service id
				$table->string('image_path');
				$table->string('description');
				$table->integer('sort');
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('spa_service');
		Schema::drop('spa_service_images');
	}

}