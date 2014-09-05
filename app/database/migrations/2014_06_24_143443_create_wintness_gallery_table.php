<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWintnessGalleryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
        Schema::create('wintness_gallery', function($table)
        {
            $table->dropIfExists();
            $table->increments('id');
            $table->string('title', 128);
            $table->string('link', 255);
            $table->enum('target', array('_blank', '_self'))->default('_self');
            $table->string('imageURL', 255);
            $table->integer('sort', false, true)->default(1);
            $table->enum('status', array('0', '1'));
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->index(array('sort', 'status', 'updated_at'));
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
        Schema::drop('wintness_gallery');
	}

}
