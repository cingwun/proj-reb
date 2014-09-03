<?php
/*
 * this component is used to create/verify/delete captcha image
 * it will create a file to store two values, one is timestamp, the other is
 */
class Captcha{

	/*
	 * @var (string) font
	 * @see http://www.1001freefonts.com/boycott.font
	 * default: app/storage/fonts/boycott.ttf
	 */
	private $font = '';

	/*
	 * @var (array) bgColors
	 */
	public $bgColors = array(
		145,
		204,
		177,
		184,
		199,
		255
	);

	/*
	 * @var (string) chars
	 * default: ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz;
	 */
	public $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

	/*
	 * @var (int) height
	 * default: 25;
	 */
	public $height = 25;

	/*
	 * @var (int) letters
	 * default: 4
	 */
	public $letters = 4;

	/*
	 * @var (int) sessionKey
	 * default: rbcaptcha
	 */
	public $sessionKey = 'rbcaptcha';

	/*
	 * @var (int) width
	 * default: 120;
	 */
	public $width = 100;

	/*
	 * get instance of Captcha
	 * @return (object) Captcha
	 */
	public static function getInstance($className=__CLASS__){
		return new $className;
	}

	/*
	 * construct
	 * @params (int) $w
	 * @params (int) $h
	 * @params (int) $letters
	 * @params (int) $valid_time
	 */
	public function __construct($w=null, $h=null, $letters=4, $valid_time=null){
		if ($w!==null && ($w=$this->valid_integer($w))!==false)
			$this->width = $w;

		if ($h!==null && ($h=$this->valid_integer($h))!==false)
			$this->height = $h;

		if ($letters!==null && ($letters=$this->valid_integer($letters))!==false)
			$this->letters = $letters;

		if ($valid_time!==null && ($valid_time=$this->valid_integer($valid_time))!==false)
			$this->valid_time = $valid_time;

		//check session start
		if (session_id()=='')
    		session_start();

    	// set font path
    	$this->font = storage_path() . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'PixelSplitter-Bold.ttf';
	}

	/*
	 * create image
	 * @see http://www.codeproject.com/Articles/26595/CAPTCHA-Image-in-PHP
	 * @return (object) image
	 */
	public function createImage(){
		// create background
		$image = imagecreatetruecolor($this->width, $this->height);
		$bg = imagecolorallocate($image, 255, 255, 255);
		imagefill($image, 10, 10, $bg);
		for ($x=0; $x<$this->width; $x++){
		    for ($y=0; $y<$this->height; $y++){
		        $random = mt_rand(0 , 5);
		        $temp_color = imagecolorallocate($image, $this->bgColors[$random], $this->bgColors[$random], $this->bgColors[$random]);
		        imagesetpixel($image, $x, $y, $temp_color);
		    }
		}

		// set charset
		$validCode = '';
		$fontSize = round($this->height*0.5);
		$charLen = strlen($this->chars) + 1;
		$gap = round($this->width/$this->letters);
		$minY = round($this->height*0.8);
		$maxY = round($this->height*1);
		for($i=0; $i<$this->letters; $i++){
			$ck = mt_rand(1, 1000) % $charLen;
			$char = $this->chars[$ck];
			$minX = $i*$gap;
			$maxX = $minX + $gap - 10;
			$posX = mt_rand($minX, $maxX);
			$posY = mt_rand($minY, $maxY);
			$angle = mt_rand(-10, 10);
			$ccNum = mt_rand(20, 50);
			$charColor = imagecolorallocate($image, $ccNum, $ccNum, $ccNum);
			imagettftext($image, $fontSize, $angle, $posX, $posY, $charColor, $this->font, $char);
			$validCode .= $char;
		}

		$this->setValidCode($validCode);
		return imagepng($image);
		imagedestroy($image);
	}

	/*
	 * valid value
	 * @params (string) $valid
	 * @return (bool) $bool
	 */
	public function valid($value){
		/*
		$origValue = (isset($_SESSION[$this->sessionKey])) ? $_SESSION[$this->sessionKey] : '';*/
		$origValue = (Session::has($this->sessionKey)) ? Session::get($this->sessionKey) : '';
		Session::forget($this->sessionKey);
		return (md5($origValue)===md5($value));
	}

	/*
	 * set valid code
	 * @params (string) $validCode
	 */
	private function setValidCode($validCode){
		//$_SESSION[$this->sessionKey] = $validCode;
		Session::put($this->sessionKey, $validCode);
	}

	/*
	 * valid value is integer
	 * @params (int) $value
	 * @return (false|int) $value
	 */
	private function valid_integer($value){
		$value = (int) $value;
		return ($value==0) ? false : $value;
	}
}
?>