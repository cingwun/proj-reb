<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('members', function($table){
			$table->engine = 'InnoDB';
	        $table->increments('id')->unsigned();
	        $table->char('uid', 32);
	        $table->enum('social', array('facebook', 'google'))->default('facebook');
	        $table->string('password', 32)->nullable();
	        $table->enum('gender', array('m', 'f'))->default('f');
	        $table->string('name', 16);
	        $table->string('email', 255);
	        $table->char('birthday', 10)->nullable();
	        $table->string('phone', 32)->nullable();
	        $table->string('address', 128)->nullable();
	        $table->string('interest', 64)->nullable();
	        $table->dateTime('created_at');
	        $table->dateTime('updated_at');
	        $table->primary('id');
	        $table->unique(array('uid', 'social'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::drop('members');
	}
}
