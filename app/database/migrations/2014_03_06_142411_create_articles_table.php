<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
                Schema::create('articles', function($table)
                {
                        $table->dropIfExists();
                        $table->increments('id');
                        $table->string('title');
                        $table->text('description');
                        $table->enum('category', array('1','2','3'));
                        $table->date('open_at');
                        $table->integer('sort');
                        $table->tinyInteger('status');
                        $table->integer('views');
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
                Schema::drop('articles');
	}

}
