<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
        Schema::create('banners', function($table)
        {
            $table->dropIfExists();
            $table->increments('bid');
            $table->string('title', 64);
			$table->string('image', 256);
            $table->enum('size', array('large', 'medium', 'small'));
            $table->string('link', 256);
            $table->enum('target', array('_self', '_blank'));
            $table->enum('status', array('0', '1'));
            $table->integer('on_time', false, true);
            $table->integer('off_time', false, true);
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
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
        Schema::drop('banners');
	}

}
