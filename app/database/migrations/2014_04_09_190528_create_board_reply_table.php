<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardReplyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('board_replys', function($table){
			$table->dropIfExists();
	        $table->engine = 'InnoDB';
	        $table->increments('id');
	        $table->string('tag', 128);
	        $table->text('content');
	        $table->integer('creator', false, true);
	        $table->dateTime('created_at');
	        $table->integer('board_id', false, true);
	        $table->index('board_id');
	        $table->foreign('board_id')
			      ->references('id')->on('board')
			      ->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::drop('board_replys');
	}

}
