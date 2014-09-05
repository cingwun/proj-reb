<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('board', function($table){
			$table->dropIfExists();
			$table->engine = 'InnoDB';
	        $table->increments('id');
	        $table->string('name', 16);
	        $table->enum('gender', array('m', 'f'))->default('f');
	        $table->string('email', 255);
	        $table->string('topic', 64);
	        $table->text('content');
	        $table->enum('isPrivate', array('0', '1'))->default('0');
	        $table->enum('isReply', array('0', '1'))->default('0');
	        $table->enum('status', array('0', '1'))->default('1');
	        $table->integer('count_num', false, true)->default(0);
	        $table->dateTime('created_at');
	        $table->dateTime('updated_at');
	        $table->integer('user_id', false, true);
	        $table->index('user_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::drop('board');
	}

}
