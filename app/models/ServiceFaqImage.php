<?php
class ServiceFaqImage extends Eloquent {

	protected $table = 'service_faq_images';

	/*
	 * primary key
	 */
	//protected $primaryKey = 'id';

	/*
	 * @var fillable, define fillable columns
	 */
	protected $fillable = array('sid', 'image', 'text', 'sort');

	/*
	 * @var timestamps, define the model is using timestamps
	 */
	public $timestamps = false;

}
