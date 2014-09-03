<?php
class Article extends Eloquent {

	public function scopeOfCategory($query, $name)
  	{
    		return $query->whereCategory($name);
  	}
	
        public function scopeOpen($query)
    	{
        	return $query->where('open_at', '<=', date('Y-m-d'))->where('status','=',1);
    	}
}
