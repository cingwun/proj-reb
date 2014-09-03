<?php
/*
 * This controller is used to handle request of captcha
 */
class CaptchaController extends BaseController {

	/*
	 * get captcha image
	 * @params (string) $size
	 * @params ()
	 * @return (image)
	 */
	public function getCaptcha(){
		$width = Input::get('w', 100);
		$height = Input::get('h', 25);
		$letters = Input::get('letters', 4);
		$cpa = new Captcha($width, $height, $letters);
		header("Content-Type: image/png");
		return $cpa->createImage();
	}
}