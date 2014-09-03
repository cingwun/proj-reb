<?php
/*
 * This controller is used to handle request of banner
 */
class BannersController extends BaseController {
	/*
	 * @var $size
	 */
	private $size = array(
		array('text'=>'尺寸700x300', 'value'=>'small'),
		array('text'=>'尺寸960x250', 'value'=>'medium'),
		array('text'=>'尺寸960x430', 'value'=>'large')
	);

    protected $filterExcept = array();

    public function __construct(){
        parent::permissionFilter();
    }

    /*
     * banner list for show
     * @params (string) $size
     * @return (array) $banners;
     */
	public static function bannerShow($size){
		$sql = 'select image, link, target, title from banners ' .
			   'where size = ? and status = ? and ' .
			         '(on_time = 0 or on_time <= ?) and (off_time = 0 or off_time >= ?)' .
			   'order by updated_at desc';
		$t = time();
		$rows = \DB::select($sql, array($size, 1, $t, $t));
		return $rows;
    }

	/*
	 * display all banners according to size
	 *
	 * @params (string) $size, values: large[960x430], medium[960x250], small[700x300]
	 */
	public function getList($size='large'){
		$page = Input::get('page', 1);
		$limit = 10;
		$offset = ((int)($page-1)) * $limit;
		$rowsNum = Banners::where('size', '=', $size)->count();
		$data = Banners::take($limit)->skip($offset)->where('size', '=', $size)->get();
		$widgetParam = array(
			'currPage' => $page,
			'total' => $rowsNum,
			'perPage' => $limit,
			'URL' => URL::route('admin.banners.list', array($size)),
		);
		return View::make('admin.banners.view_list', array(
				'size' => $this->_getSize($size),
				'wp' => &$widgetParam,
				'data' => $data
			));
	}

	/*
	 * create/update action display interface
	 */
	public function getAction($size, $bid=null){
		$m = Banners::find($bid);
		$data = ($m==null) ? array() : $m->toArray();

		$data['on_time'] = (isset($data['on_time']) && $data['on_time']>0) ? date('Y-m-d', $data['on_time']) : '';
		$data['off_time'] = (isset($data['off_time']) && $data['off_time']>0) ? date('Y-m-d', $data['off_time']) : '';

		return View::make('admin.banners.view_action', array(
				'size' => $this->_getSize($size),
				'data' => $data
			));
	}

	/*
	 * delete specific banner
	 */
	public function getDelete($size, $bid=null){
		$m = Banners::find($bid);
		if ($m==null)
			return Redirect::route('admin.banners.list', array($size));
		fps::getInstance()->delete($m->image);
		$m->delete();
		return Redirect::route('admin.banners.list', array($size));
	}

	/*
	 * post action
	 */
	public function postAction(){
		$bid = Arr::get($_POST, 'bid');
		$image = Arr::get($_POST, 'image', null);
		$imgList = Arr::get($_POST, 'imglist', array());
		$size = Arr::get($_POST, 'size', 'small');
		$title = Arr::get($_POST, 'title', 'unknown');
		$link = Arr::get($_POST, 'link', '#');
		$target = Arr::get($_POST, 'target', '_self');
		$start = Arr::get($_POST, 'on_time', 0);
		$end = Arr::get($_POST, 'off_time', 0);
		$status = Arr::get($_POST, 'status', '1');

		if ($image==null)
			return Redirect::route('admin.banners.list', array($size, 'message'=>'error'));

		$m = ($bid!='null') ? $m = Banners::find($bid) : null;

		if ($m==null){
			$m = new Banners;
			$m->created_at = date('Y-m-d H:i:s');
		}

		$start = (empty($start)) ? 0 : strtotime($start);
		$end = (empty($end)) ? 0 : strtotime($end);

		$m->title = $title;
		$m->image = $image;
		$m->size = $size;
		$m->link = $link;
		$m->target = $target;
		$m->on_time = $start;
		$m->off_time = $end;
		$m->status = $status;
		$m->updated_at = date('Y-m-d H:i:s');
		$bool = $m->save();

		$list = explode($imgList, '=sep=');
		foreach($list as $item)
			if (md5($item)!=md5($image)) fps::getInstance()->delete($item);

		return Redirect::route('admin.banners.list', array($size, 'message'=>'success'));
	}

	/*
	 * get banner
	 *
	 * @params (string) $size
	 * @return (array) $banner, array(text, value) | array(array(),...)
	 */
	private function _getSize($size=null){
		if ($size==null)
			return $this->size;
		else{
			foreach($this->size as &$s){
				if ($s['value']==$size)
					return $s;
			}
		}
	}
}
?>
