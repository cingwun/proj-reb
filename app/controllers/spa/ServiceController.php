<?php
namespace spa;

class ServiceController extends \BaseController{

	public function getService(){
		return \View::make('spa.service.view_service');
	}
}

?>