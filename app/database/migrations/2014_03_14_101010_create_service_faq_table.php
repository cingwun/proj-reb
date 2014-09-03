<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceFaqTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('service_faq', function($table)
        {
        		$table->engine = 'InnoDB';
        		
                $table->increments('id');
                $table->enum('type', array('service','faq'));
                $table->string('title',100);
                $table->string('image',100);
                $table->text('content');
                $table->string('labels',128);
                $table->text('tabs');
                $table->string('_parent');
                $table->integer('views');
                $table->integer('sort');
                $table->timestamps();
                $table->enum('status', array('Y','N'));

        });

		Schema::create('service_faq_images', function($table)
        {
        	$table->engine = 'InnoDB';

                $table->increments('id');
        	$table->integer('sid')->unsigned();
        	$table->string('image',100);
        	$table->string('text');
        	$table->integer('sort');

        	$table->index('sid');
        	
        	
        });

        Schema::table('service_faq_images', function($table)
        {
        	$table->foreign('sid')->references('id')->on('service_faq')->onDelete('cascade');
        });
        
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropForeign('posts_service_faq_images_sid_foreign');
		Schema::drop('service_faq_images');

		Schema::drop('service_faq');
		
	}

}
