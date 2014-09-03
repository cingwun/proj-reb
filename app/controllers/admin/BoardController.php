<?php
class BoardController extends BaseController{

    protected $filterExcept = array();

    public function __construct(){
        parent::permissionFilter();
    }

	/*
	 * display all question
	 */
	public function getList(){
		$page = Input::get('page', 1);
		$status = Input::get('status', '');
		$keyword = Input::get('keyword', '');
		$limit = 10;
		$offset = ((int)($page-1)) * $limit;

		$model = new Board;

		if (!empty($status))
			$model = $model->where('status', '=', $status);

		if (!empty($keyword))
			$model = $model->where('topic', 'like', '%'.$keyword.'%');

		$rowsNum = $model->count();
		$data = $model->take($limit)->skip($offset)->orderBy('updated_at', 'desc')->get();

		$widgetParam = array(
			'currPage' => $page,
			'total' => $rowsNum,
			'perPage' => $limit,
			'URL' => URL::route('admin.board.list'),
		);

		return View::make('admin.board.view_list', array(
			'wp' => &$widgetParam,
			'data' => $data
		));
	}

	/*
	 * display create reply page for specific board id
	 * @params (int) boardId
	 */
	public function getReply($boardId){
		$b = Board::find($boardId, array('id', 'content'));
		$board = array();
		if ($b!==null)
			$board = $b->getAttributes();

		$r = BoardReply::where('board_id', '=', $boardId)->orderBy('created_at', 'desc')->first(array('tags', 'content'));
		$reply = array();
		if ($r!==null)
			$reply = $r->getAttributes();

		// prepare faq
		$faqs = ServiceFaq::where('type', '=', 'faq')->where('_parent', '<>', 'N')->orderBy('sort', 'desc')->get(array('id', 'title'));
		$items = array();
		foreach($faqs as $m)
			$items[$m->id] = $m->title;

        $tags = Arr::get($reply, 'tags', null);
        if ($tags==null)
            $selected = $tags;
        else
            $selected = unserialize($tags);

		return View::make('admin.board.view_reply', array(
			'board' => $board,
			'reply' => $reply,
			'lblWidgetOptions' => array(
                'elementId' => 'label-panel',
                'fieldName' => 'labels[]',
                'formTitle' => '常見問題標籤',
                'items' => &$items,
                'selected' => $selected
            )
		));
	}

	/*
	 * handle reply form submit
	 */
	public function postReply(){
		try{
 			$board_id = Arr::get($_POST, 'board_id', null);
			$labels = Arr::get($_POST, 'labels', array());
			$content = Arr::get($_POST, 'content', '');

			if ($board_id===null || empty($content))
				throw new Exception('回覆失敗');

			$br = new BoardReply;
			$br->tags = serialize($labels);
			$br->content = $content;
			$br->creator = Sentry::getUser()->getId();
			$br->created_at = date('Y-m-d H:i:s');
			$br->board_id = $board_id;
			$br->save();

			//update status of board
			$b = Board::find($board_id);
			$b->isReply = '1';
			$b->save();
			return Redirect::route('admin.board.list');
		}catch(Exception $e){
			return Redirect::back()->withInput()->withErrors($e->getMessage());
		}
	}

	/*
	 * handle AJAX Request of change board status
	 * @params (string) $_token
	 * @params (int) $board_id
	 * @return (object) json{status, value, message, token}
	 */
	public function postStatus(){
		try{
 			$board_id = Arr::get($_POST, 'board_id', null);

			//update status of board
			$b = Board::find($board_id);
			if ($b===null)
				throw new Exception('Request ID of board error!');

			$b->status = ($b->status=='0') ? '1' : '0';
			$b->save();
			$topic = mb_substr($b->topic, 0, 10);
			$topic .= (mb_strlen($b->topic, 'UTF-8')>10) ? '...' : '';
			$message = sprintf('更新留言「%s」的狀態，成功!', $topic);
			return Response::json(array('status'=>'ok', 'value'=>$b->status, 'message'=>$message, '_token'=>csrf_token()));
		}catch(Exception $e){
			return Response::json(array('status'=>'error', 'value'=>1, 'message'=>$e->getMessage(), '_token'=>csrf_token()));
		}
	}
}
?>