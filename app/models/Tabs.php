<?php
/*
 * This class represent a model of tabs and it extends Eloquent be a active record.
 */
class Tabs extends Eloquent {

    /*
     * table name
     */
    protected $table = 'tabs';

	/*
	 * @var type, item_id, title, content, sort
	 */
	protected $fillable = array('type', 'item_id', 'title', 'content', 'sort');

    /*
     * primary key
     */
    protected $primaryKey = 'id';

}
?>