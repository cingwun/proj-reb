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
			$selectedArticles[1] = \SpaArticles::where('category',$category)->orderBy('sort', 'desc')->get();

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
			$nosort = 0;   
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
			}else{
				$article = \SpaArticles::find($id);
				$nosort = 1;
			}

			$article->title = \Input::get('title');
			$article->content = \Input::get('content');
			$article->category = \Input::get('category');
			$article->open_at = \Input::get('open_at');
			$article->status = \Input::get('status');
			$article->lan = \Input::get('lan');
			($nosort = 0) ? $article->sort = \SpaArticles::max('sort')+1 : $nosort = 1;
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
			$category = $article->category;
			if($article->ref_id != 0){
				$refArticle = \SpaArticles::where('id',"=",$article->ref_id)->first();
				$refArticle->ref_id = 0;
				$refArticle->save();
			}
			$article->delete();
			return \Redirect::route('spa.admin.articles.list', array('category'=>$category));
		}catch(Exception $e){
			return Redirect::route('spa.admin.articles.list', array('errorMessage'=>$e->getMessage()));
		}
	}

	/*public function postSort()
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
    }*/

     public function postSort(){
        try{
            if (!isset($_POST) || !isset($_POST['id']) || !isset($_POST['sort']) || !isset($_POST['role']))
                throw new Exception('Error request [10]');	

            $id = (int) \Input::get('id');
            $role = \Input::get('role');
            $sort = (int) \Input::get('sort');
            $isUpdatedTime = \Input::get('isUpdatedTime', false);
            $lastUpdatedId = \Input::get('lastUpdatedId', false);

            $model = \SpaArticles::find($id);
            if (empty($model))
                throw new Exception("Error request [11]");

            $model->sort = $sort;

            if (!$model->save())
                throw new Exception("更新排序失敗，請通知工程師");

            if ($isUpdatedTime){
                $cmd = \SpaArticles::where('id', '<>', $id)
                                 ->where('sort', '=', $sort)
                                 ->where('id', '>=', $lastUpdatedId)
                                 ->orderBy('sort', 'desc')
                                 ->orderBy('updated_at', 'desc');
                /*if ($role=='category')
                    $cmd->where('_parent', '=', 'N');
                else
                    $cmd->where('_parent', '=', $model->_parent);*/

                $items = $cmd->get();
                if (sizeof($items)>0){
                    $t = time();
                    foreach($items as $key=>$item){
                        $t = $t+$key;
                        $item->updated_at = $t;
                        $item->save();
                    }
                }
            }

            return \Response::json(array(
                    'status' => 'ok',
                    'message' => '更新排序完成',
                ));

        }catch(Exception $e){
            return Response::json(array(
                    'status' => 'error',
                    'message' => $e->getMessage()
                ));
        }
    }


}
