<?php
namespace spa;

class IndexController extends \BaseController{

	public function getIndex(){
		return \View::make('spa._layouts.default');
	}
}
?>