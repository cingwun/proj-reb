<?php
/*
 * This controller is used to handle FILE request, includes get/upload image...
 */
class FpsController extends BaseController {

	/*
	 * this action is used to handle specific request for getting file, it includes two types, follows:
	 *   1. image: this type has some parameters like YAM FPS, ex: $_GET[w], $_GET[h] and return image content to request
	 *   2. file: this type has a parameter name, it's used to rename file name, $_GET[name] and return file content and download header to request
	 *
	 * @return (mixed) iamge/file
	 */
	public function getFile($filename){
		$file = fps::getInstance()->getRealFilePath($filename) . DIRECTORY_SEPARATOR . basename($filename);
		//$filename = basename($filename);
		//$file = $this->storePath . DIRECTORY_SEPARATOR . $filename;

		if (!file_exists($file))
			throw new Exception('File is not exists');

        $mimeType = exif_imagetype($file);
        $contentType =  image_type_to_mime_type($mimeType);

		if ($mimeType){
			$image = $this->handleImageRequest($file);
            return Response::make($image, 200, array('Content-Type'=>$contentType, 'Cache-control'=> 'max-age='.(60*60*24*365), 'Expires'=> date(DATE_RFC822,strtotime(" 2 day"))));
		}

		$name = Input::get('name', strstr($filename, '.', true));
		return Response::download($file, $name);
	}

    /*
     * delete file
     *
     * @params (string) $file
     * @return (object) $res, format: json((string)status[ok/error], (array)files)
     */
    public function postDelete(){
        try{
            if (!isset($_POST))
                throw new Exception("Error request [10]");

            if (!isset($_POST['file']))
                throw new Exception("Error Request [11]");

            $res = fps::getInstance()->delete($_POST['file']);
            return Response::json($res);
        }catch(Exception $e){
            return Response::json(array(
                    'status' => 'error',
                    'message' => $e->getMessage()
                ));
        }
    }

	/*
	 * upload file
	 *
	 * @params (string) $type, default: ajax
	 * @return (mixed) $res, format: json((string)status[ok/error], (array)files) | jsonp4ckeditor
	 */
	public function postUpload($type='ajax'){
		if (!isset($_FILES) || count($_FILES)==0)
			return Response::json(array('status' => 'error', 'message'=>'request error'));
		$res = fps::getInstance()->upload();

		if ($type=='editor'){
			$callback = Input::get('CKEditorFuncNum', 'callback');
			$url = (isset($res['files'][0])) ? $res['files'][0] : '';
            $output='<script type="text/javascript">';
            $output.="window.parent.CKEDITOR.tools.callFunction('{$callback}','{$url}','');";
            $output.='</script>';

            return $output;
		}else
			return Response::json($res);
	}

	/*
	 * get gr position
	 *
	 * @params (string) $gr
	 * @return (string) $new_gr
	 */
	private function getGR($gr='center'){
		if ($gr=='center' || $gr==false)
			return 'center';

		$gr = str_replace('north', 'top-', $gr);
		$gr = str_replace('south', 'bottom-', $gr);
		$gr = str_replace('west', '-left', $gr);
		$gr = str_replace('east', '-right', $gr);
		$gr = str_replace('--', '-', $gr);
		return trim($gr, '-');
	}

	/*
	 * get crop image
	 *
	 * @params (gd resource) $im
	 * @params (string) $gr
     * @params (int) $src_w
     * @params (int) $src_h
	 * @params (int) $new_w
	 * @params (int) $new_h
     * @params (string) $createFun
	 * @return (gd resource) $im
	 */
    private function getCropImage(&$im, $gr, $src_w, $src_h, $new_w, $new_h, $createFun){
        if ($gr=='center'){
            $x = round(($src_w - $new_w) / 2);
            $y = round(($src_h - $new_h) / 2);
        }else{
            $y = (stristr($gr, 'top')===false) ?
                    abs($src_h-$new_h) :
                    0;
            $x = (stristr($gr, 'left')===false) ?
                    abs($src_w-$new_w) :
                    0;
        }

        $_im = $createFun($new_w, $new_h);
        imagecopy($_im, $im, 0, 0, $x, $y, $new_w, $new_h);
        imagedestroy($im);
        return $_im;
    }

	/*
	 * get image file by specific URL and parameters
	 *
	 * @params (string) $filename
	 * @return (object) $image
	 */
	private function handleImageRequest($filename){
		$pKey = array('w'=>null, 'h'=>null, 'ar'=>'sl', 'gr'=>false, 'bg'=>'ffffff', 'q'=>100);
		$p = array();
		foreach($pKey as $k=>$value)
			$p[$k] = Input::get($k, $value);

		$gr = $this->getGR($p['gr']);

		//$filename = $this->storePath . DIRECTORY_SEPARATOR . $filename;
        list($file, $ext) = explode('.', $filename);
		$cacheKey = md5(implode('_', $p));
        $ck = md5($filename . '/' .$cacheKey);
        $IM = null;
        $bool = Cache::has($ck);
        //if ($bool) $bool = false;
        if ($bool){
            return $result = Cache::get($ck);
        }else{
            ob_start();
    		$cacheFile = ($p['w']==null && $p['h']==null) ?
    			$filename :
    			fps::getInstance()->getRealFilePath($filename, 'cache') . DIRECTORY_SEPARATOR . sprintf('%s_%s', $cacheKey, basename($filename));

    		if (!file_exists($cacheFile)){
                $info = getimagesize($filename);
                $width = $info[0];
                $height = $info[1];
                $createFuncion = '';
                $q = (int) $p['q'];
                switch(strtolower($info['mime'])){
                    case 'image/jpeg':
                    case 'image/jpg':
                        $im = imagecreatefromjpeg($filename);
                        $createFuncion = 'imagejpeg';
                        break;
                    case 'image/gif':
                        $im =  imagecreatefromgif($filename);
                        $createFuncion = 'imagegif';
                        break;
                    case 'image/png':
                        $im = imagecreatefrompng($filename);
                        $createFuncion = 'imagepng';
                        $q = ceil(abs(100-$q) / 10);
                        break;
                    case "image/bmp":
                        $im = imagecreatefromwbmp($filename);
                        $createFuncion = 'image2wbmp';
                        break;
                }

                $newCreateImage = ($createFuncion=='imagepng' || $createFuncion=='imagegif') ? 'imagecreate' : 'imagecreatetruecolor';

                if ($p['w']!=null && $p['h']!=null){
                        $ratio = round($p['w']/$p['h'], 3);
                        if (($h=$width/$ratio)<=$height){
                            $w = $width;
                        }else{
                            $w = $height * $ratio;
                            $h = $height;
                        }

                        $corpIm = $this->getCropImage($im, $gr, $width, $height, (int)$w, (int)$h, $newCreateImage);
                        $newIm = $newCreateImage($p['w'], $p['h']);

                        imagecopyresampled($newIm, $corpIm, 0, 0, 0, 0, $p['w'], $p['h'], $w, $h);
                        $createFuncion($newIm, $cacheFile, $q);
                        imagedestroy($corpIm);
                }else{
                    if (!empty($p['w']))
                        $p['h'] = (int) round($p['w']*$height/$width);
                    else
                        $p['w'] = (int) round($p['h']*$width/$height);

                    $newIm = $newCreateImage($p['w'], $p['h']);
                    imagecopyresampled($newIm, $im, 0, 0, 0, 0, $p['w'], $p['h'], $width, $height);
                    $createFuncion($newIm, $cacheFile, $q);
                    imagedestroy($im);
                }

                $createFuncion($newIm);
                imagedestroy($newIm);
    		}else{
                $info = getimagesize($cacheFile);
                $createFuncion = '';
                $q = (int) $p['q'];
                switch(strtolower($info['mime'])){
                    case 'image/jpeg':
                    case 'image/jpg':
                        $im = imagecreatefromjpeg($cacheFile);
                        $createFuncion = 'imagejpeg';
                        break;
                    case 'image/gif':
                        $im =  imagecreatefromgif($cacheFile);
                        $createFuncion = 'imagegif';
                        break;
                    case 'image/png':
                        $im = imagecreatefrompng($cacheFile);
                        $createFuncion = 'imagepng';
                        $q = ceil(abs(100-$q) / 10);
                        break;
                    case "image/bmp":
                        $im = imagecreatefromwbmp($cacheFile);
                        $createFuncion = 'image2wbmp';
                        break;
                }
                $createFuncion($im, null, $q);
                imagedestroy($im);
            }

            $result = ob_get_contents();

            Cache::put($ck, $result, 10);

            ob_end_clean();

            return $result;
        }
	}

}
?>