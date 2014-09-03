<?php
/*
 * This class represent a model of Banners and it extends Eloquent be a active record.
 */
class Banners extends Eloquent {

	/*
	 * table name
	 */
	protected $table = 'banners';

	/*
	 * primary key
	 */
	protected $primaryKey = 'bid';

        public function scopeOfSize($query, $name)
        {
                return $query->whereSize($name);
        }
}
?>
