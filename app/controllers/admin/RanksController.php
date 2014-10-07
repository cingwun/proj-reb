<?php

class RanksController extends \BaseController {

	protected $filterExcept = array('sidebarData');

	public function __construct() {
		parent::permissionFilter();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		$ranksLang = array(
			'tw' => array(),
			'cn' => array()
			);
		$rankTWCmd = Rank::where('lang', 'tw')
		->orderBy('sort')
		->get();
		$rankCNCmd = Rank::where('lang', 'cn')
		->orderBy('sort')
		->get();
		if($rankTWCmd && $rankCNCmd) {
			$ranksLang = array(
				'tw' => array(
					'data' => $rankTWCmd,
					'title' => '繁體列表'
					),
				'cn' => array(
					'data' => $rankCNCmd,
					'title' => '簡體列表'
					),
				);
		}

		return View::make('admin.ranks.index', array(
			'ranksLang' => $ranksLang
			));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		return View::make('admin.ranks.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
		try{
			//create tw data
			$rankTW = new Rank;
			$rankTW->title = Input::get('title');
			$rankTW->link = Input::get('link');
			$rankTW->target = Input::get('target');
			$rankTW->lang = 'tw';
			$rankTW->sort = Rank::where('lang','tw')->max('sort')+1;

			$rankTW->save();

			$insertTWId = $rankTW->id; //insert id
			//create cn data
			$rankCN = new Rank;
			$rankCN->title = Input::get('title');
			$rankCN->link = Input::get('link');
			$rankCN->target = Input::get('target');
			$rankCN->lang = 'cn';
			$rankCN->ref = $insertTWId;
			$rankCN->sort = Rank::where('lang','cn')->max('sort')+1;

			$rankCN->save();

			$insertCNId = $rankCN->id; //insert id
			//set tw data ref
			$rankTWCmd = Rank::find($insertTWId);
			$rankTWCmd->ref = $insertCNId;
			$rankTWCmd->save();

			return Redirect::route('admin.ranks.index');
		}catch (Exception $e) {
			return Redirect::back()->withInput()->withErrors('新增失敗');
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id) {
		return View::make('admin.ranks.edit')->with('rank',Rank::find($id));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
		try{
			$rank = Rank::find($id);

			$rank->title = Input::get('title');
			$rank->link = Input::get('link');
			$rank->target = Input::get('target');

			$rank->save();

			return Redirect::route('admin.ranks.index');
		}catch (Exception $e){

			return Redirect::back()->withInput()->withErrors('修改失敗');

		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id) {
		try {
			$deleteCmd = Rank::find($id);
			$deleteCmd->delete();
			Rank::destroy($deleteCmd->ref);
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}

	public function sort() {
		try {
			$sort = explode(',',Input::get('sort'));

			if($sort) {
				foreach($sort as $key=>$id){
					$rank = Rank::find($id);
					$rank->sort = $key+1;
					$rank->save();
				}
			}
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}

    /**
     * fot 側欄套版
     */
    public static function sidebarData ($limit=10){
    	// $key = 'sidebar_rank';
    	// $data = Cache::get($key);

    	$locale = App::getLocale();
    	// if(!$data){
		$data = Rank::where('lang', $locale)
					->orderBy('sort', 'asc')
					->take(10)
					->get();
    	// 	Cache::put($key, $data, 2);
    	// }

    	return $data;
    }
}
