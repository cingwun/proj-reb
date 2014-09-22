<?php
namespace spa;

class KettanController extends \BaseController {

	public function kettan(){
		return \View::make('spa.iop');
	}
}