<?php
namespace spaAdmin;
class TestController extends \BaseController{
	

	public function test(){
		return \View::make("spa_admin.viewA.test");
	}
}
?>