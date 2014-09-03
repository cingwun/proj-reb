<?php
namespace aesthetics;

/*
 * This controller is used to handle request of Services
 */
class BoardController extends \BaseController {

	/*
	 * dispaly board list
	 */
	public function getList(){
		$page = \Input::get('page', 1);
		$limit = 10;
		$offset = ($page-1)*$limit;
		$count = \Board::where('status', '=', '1')->count();
		$columns = array(
			'id',
			'name',
			'topic',
			'count_num',
			\DB::raw("date_format(created_at,'%Y-%m-%d') as d")
		);
		$boards = \Board::where('status', '=', '1')
						->skip($offset)
						->take($limit)
						->orderBy('created_at', 'desc')
						->get($columns);
		$widgetParam = array(
			'currPage' => $page,
			'total' => $count,
			'length' => $limit,
			'URL' => route('frontend.board.list'),
			'qs' => ''
		);
		return \View::make('aesthetics.board.view_list', array(
			'boards' => &$boards,
			'wp' => &$widgetParam
		));
	}

	/*
	 * display ask page
	 */
	public function getAsk(){
		$user = array();
		if (\Auth::check()){
			$u = \Auth::user();
			$user['id'] = $u->id;
			$user['name'] = $u->name;
			$user['email'] = $u->email;
		}
		return \View::make('aesthetics.board.view_ask', array(
			'user' => &$user
		));
	}

	/*
	 * display specific question which means post
	 * @params (int) $postId
	 */
	public function getPost($postId){
		// board
		$b = \Board::find($postId);
		if ($b===NULL)
			return \Redirect::route('frontend.board.list');
		$b->count_num = $b->count_num+1;
		$b->save();
		$b->d = str_replace('-', '/', substr($b->created_at, 0, 10));

		$isShowPost = false;
		if ($b->isPrivate=='0')
			$isShowPost = true;
		else{
			$user_id = (\Auth::check()) ? \Auth::user()->id : null;
			$isShowPost = ($user_id===$b->user_id && $user_id!==null);
		}

		// fetch reply
		$br = \BoardReply::where('board_id', '=', $postId)->orderBy('created_at', 'desc')->first(array('tags', 'content', \DB::raw("date_format(created_at,'%Y/%m/%d') as d")));
		$tags = array();
		if ($br!==null){
			$tagsId = unserialize($br->tags);
			$tags = \ServiceFaq::find($tagsId, array('id', 'title'));
		}

		// get next and previous
		$sql = 'select * from ' .
		       '(select id, topic, created_at from board where created_at < ? and status = "1" order by created_at desc limit 0, 1) as T ' .
		       'union select * from ' .
		       '(select id, topic, created_at from board where created_at > ? and status = "1" order by created_at asc limit 0, 1) as T order by id asc';
		$rows = \DB::select($sql, array($b->created_at, $b->created_at));
		$list = array('next'=>null, 'prev'=>null);
		foreach($rows as &$r){
			if ($r->created_at > $b->created_at) $list['next'] = $r;
			if ($r->created_at < $b->created_at) $list['prev'] = $r;
		}

		return \View::make('aesthetics.board.view_post', array(
			'board' => &$b,
			'list' => &$list,
			'reply' => &$br,
			'tags' => &$tags,
			'isShowPost' => $isShowPost
		));
	}

	/*
	 * handle post request of ask
	 */
	public function postAsk(){
		try{
			if (!isset($_POST))
				throw new \Exception('Request error!');
			$keys = array('name', 'sex', 'email', 'ask', 'content', 'code', 'isPrivate', 'user_id');
			$values = array();
			$bool = true;
			foreach($keys as $key){
				$values[$key] = \Arr::get($_POST, $key, false);
				if ($values[$key]===false)
					$bool = false;
			}

			if (!$bool)
				throw new \Exception('Request error!');

			if (!(\Captcha::getInstance()->valid($values['code'])))
				throw new \Exception('驗証碼錯誤');

			$values['isPrivate'] = ($values['isPrivate']=='n') ? '0' : '1';

			$b = new \Board;
			$b->name = $values['name'];
			$b->gender = $values['sex'];
			$b->email = $values['email'];
			$b->topic = $values['ask'];
			$b->content = $values['content'];
			$b->isPrivate = $values['isPrivate'];
			$b->user_id = (empty($values['user_id'])) ? '' : (int) $values['user_id'];
			$b->save();
			return \Response::json(array('status'=>'ok'));
		}catch(\Exception $e){
			return \Response::json(array('status'=>'error', 'message'=>$e->getMessage()));
		}
	}
}
?>
