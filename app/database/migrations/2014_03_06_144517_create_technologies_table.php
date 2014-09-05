<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechnologiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
                Schema::create('technologies', function($table)
                {
                        $table->dropIfExists();
                        $table->increments('id');
                        $table->string('title');
                        $table->string('image');
                        $table->string('link');
                        $table->enum('target', array('_self','_blank'));
                        $table->smallInteger('sort');
                        $table->timestamps();
                        $table->enum('status', array('Y','N'));
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
                Schema::drop('technologies');
	}

}
