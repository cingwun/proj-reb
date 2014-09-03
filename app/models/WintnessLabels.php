<?php
/*
 * This class represent a model of wintness_labels and it extends Eloquent be a active record.
 */
class WintnessLabels extends Eloquent {

    /*
     * table name
     */
    protected $table = 'wintness_labels';

	/*
	 * @var wid, label_id
	 */
	protected $fillable = array('wid', 'label_id');

    /*
     * primary key
     */
    protected $primaryKey = 'id';

}
?>