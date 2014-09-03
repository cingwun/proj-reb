<?php
/*
 * This component is used to handle some thing of File Provider System,
 * These are three major methods in this object.
 * if want to use these method must construct it by using "fpg::getInstance()" (@see line: 72),
 * these methods includes:
 *   1. upload: @see line: 112
 *	 2. delete: @see line: 95
 *   3. getFileUploadURL: @see line: 82
 */
class fps{
	/*
	 * this variable is used to set store path of file for this FPS
	 *
	 * @ var (string)
	 */
	public $storePath = '';

	/*
	 * this variable is used to set store path of image cache file for this FPS
	 *
	 * @var (string)
	 */
	public $cachePath = '';

	/*
	 * this variable is used to set route table of file type for using creating route URL
	 *
	 * @var (array)
	 */
	private $routeTable = array('image'=>'imageUrl', 'file'=>'fileUrl');

	/*
	 * construct
	 */
 	public function __construct(){
 		$this->init();
 	}

	/*
	 * this method is used to generate a identity filename using uniqid function.
	 * it will append fps be extension name when didn't use parameter of ext
	 *
	 * @params (string) $ext, default: fps
	 */
	protected function genFilename($ext='fps'){
		return uniqid() . '.' . $ext;
	}

	/*
	 * get real file path for origin or cache
	 *
	 * @params (string) $filename
	 * @params (string) $type, default: origin
	 * @return (string) $realFilePath
	 */
	public function getRealFilePath($filename=null, $type='origin'){
		$filename = basename($filename);
		if (empty($filename))
			return $filename;

		if ($type=='cache'){
			$cachePath = $this->cachePath . DIRECTORY_SEPARATOR . $filename;
			$this->checkDirectory($cachePath);
			return $cachePath;
		}

		return $this->storePath;
	}

	/*
	 * get fps instance
	 *
	 * @params (string) $className, default: fps
	 * @return (object) $fbs
	 */
	public static function getInstance($className=__CLASS__){
		return new $className;
	}

    /*
     * it's a static method for delete a upload URL
     * @return (string) deleteURL
     */
    public static function getDeleteURL(){
        return URL::route('admin.fps.delete');
    }

	/*
	 * it's a static method for create a upload URL according parameter of type
	 * this Component support two upload types, follows:
	 * 	 1. ajax: return object of JSON
	 *	 2. editor: return ckEditor code
	 *
	 * @params (string) $type, default: ajax
	 */
	public static function getUploadURL($type='ajax'){
		if (preg_match('/^(ajax|editor)$/i', $type)==false)
			return '';
		return URL::route('fileUploadUrl', array(strtolower($type)));
	}

	/*
	 * Delete file, if the file type is image, it will delete all related cache files.
	 *
	 * @params (string) $file
	 * @return (array) $res, (status: (string)[ok/error], message: (string)[(optional)])
	 */
	public function delete($file=null){
		if ($file==null)
			return array('status'=>'error', 'message'=>'detect file error');

		$cachePath = $this->getRealFilePath($file, 'cache');
		if (is_dir($cachePath))
			File::deleteDirectory($cachePath);

		@unlink($this->getRealFilePath($file) . DIRECTORY_SEPARATOR . basename($file));
		return array('status'=>'ok', 'message'=>'delete file "'.$file.'" finished');
	}

	/*
	 * this method is used to handle upload request according its source file type,
	 * furthermore this source file must has extension name
	 * when the type follows:
	 * 	 1. string: it will be consider a upload input file's name of $_FILES
	 * 	 2. file path: it will be consider a physical file
	 *
	 * @params (string) $srcFile
	 * @return (array) $res, (status: (string)[ok/error], files: (array), message: (string)[optional])
	 */
	public function upload($srcFile=null){
		$files = array();
		if(strpos($srcFile, '/')===false || $srcFile==null){
			foreach($_FILES as $name=>$file){
				$fs= Input::file($name);
				if (!is_object($fs)){
					foreach($fs as $f){
						$filename = $this->moveUploadFile($f);
						$files[] = $this->getFileURL($filename);
					}
				}else{
					$filename = $this->moveUploadFile($fs);
					$files[] = $this->getFileURL($filename);
				}
			}
		}else{
			$filename = $this->moveUploadFile(new File($srcFile));
			$files[] = $this->getFileURL($filename);
		}

		$res = array('status'=>'ok', 'files'=>&$files);
		return $res;
	}

	/*
	 * it's used to check directory is exists and write able
	 *
	 * @params (string) $path
	 * @return (bool) $bool
	 */
	private function checkDirectory($path=null){
		if (!is_dir($path))
			if (!mkdir($path)) throw new Exception(sprintf('Make %s folder %s error', $name, $path));

		if (!is_writable($path))
			if (!chmod($path, 0777)) throw new Exception(sprintf('Change %s folder\'s "%s" mode error', $name, $path));
	}

	/*
	 * get file URL, this method has to use route table
	 *
	 * @params (string) $filename
	 * @return (string) $URL
	 */
	private function getFileURL($filename=null){
		if ($filename==null)
			return '';
		else{
			$url = (exif_imagetype($this->storePath.DIRECTORY_SEPARATOR.$filename)) ?
						URL::route($this->routeTable['image'], array($filename)) :
						URL::route($this->routeTable['file'], array($filename));
            return parse_url($url, PHP_URL_PATH);
        }
	}

	/*
	 * this is a private method for initialize,
	 * it could check the store path is exists, write able
	 * and auto create that it will throw exception when error happened
	 */
	private function init(){
		$this->storePath = storage_path() . DIRECTORY_SEPARATOR . 'files';
		$this->cachePath = $this->storePath . DIRECTORY_SEPARATOR . 'cache';

		// prepare path
		$this->checkDirectory($this->storePath);
		$this->checkDirectory($this->cachePath);
	}

	/*
	 * move upload file to storage of fps
	 * @params (File/UploadedFile) $file
	 * @return (string) $destFilename:
	 */
	private function moveUploadFile($file){
		$ext = ($file instanceof Symfony\Component\HttpFoundation\File\UploadedFile) ?
			$file->getClientOriginalExtension() :
			$file->getExtension();
		$destFilename = $this->genFilename($ext);
		$file->move($this->storePath, $destFilename);
		return $destFilename;
	}
}
?>