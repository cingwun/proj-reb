<?php
/*
 * This class represent a model of Board's reply and it extends Eloquent be a active record.
 */
class BoardReply extends Eloquent {

	/*
	 * @var timestamps
	 * set the variable to false means disabled the
	 * auto insert created_at/updated_at timestamp value
	 */
	public $timestamps = false;

	/*
	 * table name
	 */
	protected $table = 'board_replys';

	/*
	 * primary key
	 */
	protected $primaryKey = 'id';
}
?>