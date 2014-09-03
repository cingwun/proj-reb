<?php
/*
 * This class represent a model of Board and it extends Eloquent be a active record.
 */
class Board extends Eloquent {

	/*
	 * table name
	 */
	protected $table = 'board';

	/*
	 * primary key
	 */
	protected $primaryKey = 'id';
}
?>