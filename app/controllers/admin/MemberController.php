<?php
class MemberController extends BaseController{

    protected $filterExcept = array();

    public function __construct(){
        parent::permissionFilter();
    }

	/*
	 * display all members
	 */
	public function getList(){
		$page = Input::get('page', 1);
		$keyword = Input::get('keyword', null);
		$limit = 10;
		$offset = ((int)($page-1)) * $limit;

		$m = new Member;
		$params = array();
		//check keywork
		if ($keyword!=null){
			$m = $m->where('name', 'like', '%' . $keyword . '%');
			$params['keyword'] = $keyword;
		}

		$rowsNum = $m->count();
		$data = $m->take($limit)->skip($offset)->get();

		$widgetParam = array(
			'currPage' => $page,
			'total' => $rowsNum,
			'perPage' => $limit,
			'URL' => URL::route('admin.member.list'),
			'qs' => http_build_query($params)
		);

		return View::make('admin.member.view_list', array(
			'wp' => &$widgetParam,
			'data' => &$data
		));
	}

	/*
	 * display action page for specific member
	 * @params (int) $member_id
	 */
	public function getAction($member_id){
		$r = Member::find($member_id);

		if ($r==null)
			return Response::back();

		return View::make('admin.member.view_action', array(
			'm' => &$r
		));
	}

	/*
	 * store member for editing
	 */
	public function postAction(){
		try{
			$id = Arr::get($_POST, 'id', null);
			$password = Arr::get($_POST, 'password', false);
			$email = Arr::get($_POST, 'email', '');
			$name = Arr::get($_POST, 'name', '');
			$birthday = Arr::get($_POST, 'birthday', '');
			$phone = Arr::get($_POST, 'phone', '');
			$address = Arr::get($_POST, 'address', '');

			if ($id===null)
				throw new Exception('request error.');

			$m = Member::find($id);

			if ($m==null)
				throw new Exception('not found user');

			if (!$password){
				$password = trim($password);
				if (!empty($password))
					$m->password = Hash::make($password);
			}

			$m->email = $email;
			$m->name = $name;
			$m->birthday = $birthday;
			$m->phone = $phone;
			$m->address = $address;
			if (!$m->save())
				throw new Exception('store user error');

			return Redirect::route('admin.member.list');
		}catch(Exception $e){
			return Redirect::route('admin.member.list', array('message'=>$e->getMessage()));
		}
	}
}
?>