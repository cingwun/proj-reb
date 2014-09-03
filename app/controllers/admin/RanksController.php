<?php

class RanksController extends \BaseController {

        protected $filterExcept = array('sidebarData');

	public function __construct()
        {
                parent::permissionFilter();
        }

        /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
               return View::make('admin.ranks.index')->with('ranks',Rank::all()->sortBy('sort'));
                
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
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
                	$rank = new Rank;
                        $rank->title = Input::get('title');
                        $rank->link = Input::get('link');
                        $rank->target = Input::get('target');
                        $rank->sort = Rank::max('sort')+1;

                        $rank->save();
                        
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
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
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
	public function destroy($id)
	{
		//
                Rank::destroy($id);
	}

        public function sort(){
                $sort = explode(',',Input::get('sort'));

                if($sort){
                	foreach($sort as $key=>$id){
				$rank = Rank::find($id);
                                $rank->sort = $key+1;
                                $rank->save();
			}
                }

        }

        /**
         * fot 側欄套版
         */
        public static function sidebarData ($limit=10){
        	$key = 'sidebar_rank';
                $data = Cache::get($key);

                if(!$data){
                        $data = Rank::all()->sortBy('sort')->take(10);
                        Cache::put($key, $data, 2);
                }

                return $data;
        }

}
