<?php
/*
 * This controller is used to handle FILE request, includes get/upload image...
 */
class TestController extends BaseController {

	public function getIndex(){

		var_dump(fps::getInstance()->delete('http://eric.www.rebeauty.com.tw/image/5313ea80699a1.jpg'));
		return View::make('admin.test.index');
	}
}
?>