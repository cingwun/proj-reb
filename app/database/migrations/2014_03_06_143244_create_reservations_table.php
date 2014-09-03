<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
                Schema::create('reservations', function($table)
                {
                        $table->increments('id');
                        $table->string('name');
                        $table->enum('sex', array('male', 'female'));
                        $table->string('phone');
                        $table->string('email');
                        $table->string('note');
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
		//
                Schema::drop('reservations');
	}

}
