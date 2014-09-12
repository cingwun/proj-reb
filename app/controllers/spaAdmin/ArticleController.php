<?php
namespace spaAdmin;

class ArticleController extends \BaseController {

	
	protected function beforeAction($actionType){
        $actionType .= 's';
        parent::permissionFilter($actionType);
    }

    protected $filterExcept = array('sidebarData');


	/**
	 * Get article management index.
	 * @param (enum) $category 1=about 2=news 3=share
	 * 'as'=>'spa.admin.articles.list' [GET]
	 * 
	 */
	public function getList($category = 1)
	{
		try{
			if(!($category == 1 || $category == 2 || $category ==3)) //If insert category does not exist, redirect to category1.
				return \Redirect::route('spa.admin.articles.list');

			if(isset($_POST['category']))
				$category = Input::get('category');
			$selectedArticles[0] = $category;
			$selectedArticles[1] = \SpaArticles::where('category',$category)->get();

			return \View::make('spa_admin.articles.view_list', array('category'=>$category, 'selectedArticles'=>$selectedArticles));
		}catch(Exception $e){
			return Redirect::route('spa.admin.articles.list', array('errorMessage'=>$e->getMessage()));
		}
	}


	/*
	 * Get the form for create new article.
	 * 'as'=>'spa.admin.articles.action' [GET]
	 * 
	 */
	public function getAction($id = null, $changeLan = null, $createCategory = null)
	{
		if(empty($id)){
			$createCategory = $createCategory;
			return \View::make('spa_admin.articles.view_articles_action', array('action'=>"create", 'id'=>'null', 'specArticle'=>'null', 'createCategory'=>$createCategory));
		}else {
			$specArticle = \SpaArticles::find($id);
			return \View::make('spa_admin.articles.view_articles_action', array('action'=>"update", 'id'=>$specArticle->id, 'specArticle'=>$specArticle, 'changeLan'=>$changeLan));
		}
	}

	/**
	 * Post a newly created article into table.
	 * 'as'=>'spa.admin.articles.store' [POST]
	 * 
	 */
	public function postAction($id = null, $changeLan = null)
	{
		
		try
		{
			if(empty($id))
				$article = new \SpaArticles;
			elseif($changeLan=="modifyLanguage"){
				$refArticle = \SpaArticles::find($id);

				$newArticle = new \SpaArticles;
				$newArticle->title = \Input::get('title');
				$newArticle->content = \Input::get('content');
				$newArticle->category = \Input::get('category');
				$newArticle->open_at = \Input::get('open_at');   //Should this column been modified with reference-article?
				$newArticle->status = \Input::get('status');
				$newArticle->lan = \Input::get('lan');
				$newArticle->sort = \SpaArticles::max('sort')+1;

				$newArticle->ref_id = $refArticle->id;
				$newArticle->save();

				$refArticle->ref_id = $newArticle->id;
				$refArticle->save();
				return \Redirect::route('spa.admin.articles.list', array('category'=>$newArticle->category));
			}else
				$article = \SpaArticles::find($id);

			$article->title = \Input::get('title');
			$article->content = \Input::get('content');
			$article->category = \Input::get('category');
			$article->open_at = \Input::get('open_at');
			$article->status = \Input::get('status');
			$article->lan = \Input::get('lan');
			$article->sort = \SpaArticles::max('sort')+1;
			$article->save();
			return \Redirect::route('spa.admin.articles.list', array('category'=>$article->category));
		}catch(Exception $e)
		{
			return Redirect::route('spa.admin.articles.list', array('errorMessage'=>$e->getMessage()));
		}
		
	}

	/**
	 * Remove the specified resource from storage.
	 * 'as'=>'spa.admin.articles.delete' [POST]
	 * @param  int  $id
	 * 
	 */
	public function postDelete($id)
	{
		try{
			if(empty($id))
				return \Redirect::route('spa.admin.articles.list');

			$article = \SpaArticles::find($id);
			$article->delete();
			return \Redirect::route('spa.admin.articles.list');
		}catch(Exception $e){
			return Redirect::route('spa.admin.articles.list', array('errorMessage'=>$e->getMessage()));
		}
	}

	public function postSort()
	{
                $sort = explode(',',Input::get('sort'));

                if($sort){
                	foreach($sort as $key=>$id){
                		$rankArticle = \SpaArticles::find($id);
                		$rankArticle->sort = $key+1;
                        $rankArticle->save();
                    }
                }
    }

    public static function sidebarData ($limit=10)
    {
    	$key = 'sidebar_rank';
        $data = Cache::get($key);

        if(!$data){
        	$data = \SpaArticles::all()->sortBy('sort')->take(10);
            Cache::put($key, $data, 2);
        }
        return $data;
    }


}
