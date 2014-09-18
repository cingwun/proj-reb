<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpaReservationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(!Schema::hasTable('spa_reservation')){
			Schema::create('spa_reservation', function($table){
				$table->increments('id')->unsigned();
				$table->string('name');
				$table->string('country');
				$table->enum('contact_style', array('phone', 'line', 'wechat', 'qq'));
				$table->string('contact_content');
				$table->enum('contact_time', array('morning', 'noon', 'afternoon', 'night'));
				$table->date('birthday');
				$table->string('email');
				$table->dateTime('stay_start_date');
				$table->dateTime('stay_exit_date');
				$table->dateTime('service_date');
				$table->string('improve_item');
				$table->string('other_notes');
				$table->timestamps();
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
		Schema::drop('spa_reservation');
	}

}
