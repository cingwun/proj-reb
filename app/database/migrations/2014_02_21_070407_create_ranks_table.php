<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRanksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
                Schema::create('ranks', function($table)
                {
                        $table->increments('id');
                        $table->string('title');
                        $table->string('link');
                        $table->enum('target', array('_self','_blank'));
                        $table->smallInteger('sort');
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
                Schema::drop('ranks');
	}

}
