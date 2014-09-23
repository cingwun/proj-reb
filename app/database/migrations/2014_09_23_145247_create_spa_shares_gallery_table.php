<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpaSharesGalleryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('spa_shares_gallery', function($table)
		{
			$table->increments('id', 11)->unsigned();
			$table->string('title');
			$table->string('link');
			$table->enum('target', array('_self', '_blank'));
			$table->string('imageURL');
            $table->integer('sort');
            $table->enum('status', array('0', '1'));
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
		Schema::drop('spa_shares_gallery');
	}

}
