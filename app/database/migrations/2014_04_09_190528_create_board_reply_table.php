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
	        $table->engine = 'InnoDB';
	        $table->increments('id')->unsigned();
	        $table->string('tag', 128);
	        $table->text('content');
	        $table->integer('creator')->unsigned();
	        $table->dateTime('created_at');
	        $table->integer('board_id')->unsigned();
	        $table->primary('id');
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
