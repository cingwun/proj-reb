<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
        Schema::create('permissions', function($table)
        {
                $table->dropIfExists();
                $table->increments('id');
                $table->string('name')->unique();
                $table->string('title');
                $table->tinyInteger('status')->default(1);
                $table->integer('sort');
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
                Schema::drop('permissions');
	}

}
