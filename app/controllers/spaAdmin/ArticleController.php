<?php
namespace spaAdmin;

/*
 * Manage AboutSpa/ News/ Oversea articles.
 */
class ArticleController extends \BaseController {

	protected function beforeAction($actionType){
        $actionType .= 's';
        parent::permissionFilter($actionType);
    }

	/**
	 * Get specific categroy articles' list.
	 * @params (string) $category about / news / oversea
	 * 
	 */
	public function getList($category = 'about') {
		try{
			$Articles = array();
			$Articles = \SpaArticles::where('category', $category)->orderBy('sort', 'desc')->get();

			if($Articles)
				return \View::make('spa_admin.articles.view_list', array('category'=>$category, 'selectedArticles'=>$Articles));
		}catch(Exception $e){
			return Redirect::route('spa.admin.articles.list', array('errorMessage'=>$e->getMessage()));
		}catch (Exception $e) {
			return Redirect::route('spa.admin.index');
		}
	}


	/*
	 * Get the form for create new article or modify a specific article.
	 * @params (int) $id, (string) $changeLang, (string) $Category
	 * 
	 */
	public function getAction($id = null, $changeLang = null, $Category = null) {
		if(empty($id)){
			$specArticle = array(
							'id'=>0,
							'status'=>1,
							'lang'=>'tw'
							);
			return \View::make('spa_admin.articles.view_articles_action', array('specArticle'=>$specArticle, 'createCategory'=>$Category));
		}else {
			$specArticle = \SpaArticles::find($id)->toArray();
			return \View::make('spa_admin.articles.view_articles_action', array('specArticle'=>$specArticle, 'changeLang'=>$changeLang, 'createCategory'=>$Category));
		}
	}

	/**
	 * Save the modidied article into database.
	 * @params (int) $id, (string) $changeLang
	 * 
	 */
	public function postAction($id = null, $changeLang = null) {

		try
		{   
			if(empty($id)){
				$article = new \SpaArticles;
				$dosort = 1;
			}
			elseif($changeLang=="modifyLanguage"){
				$refArticle = \SpaArticles::find($id);

				$newArticle = new \SpaArticles;
				$newArticle->title = \Input::get('title');
				$newArticle->content = \Input::get('content');
				$newArticle->category = \Input::get('category');
				$newArticle->open_at = \Input::get('open_at');   
				$newArticle->status = \Input::get('status');
				$newArticle->lang = \Input::get('lang');
				$newArticle->sort = \SpaArticles::max('sort')+1;

				$newArticle->ref_id = $refArticle->id;
				$newArticle->save();

				$refArticle->ref_id = $newArticle->id;
				$refArticle->save();
				return \Redirect::route('spa.admin.articles.list', array('category'=>$newArticle->category));
			}else{
				$article = \SpaArticles::find($id);
				$dosort = 0;
			}

			$article->title = \Input::get('title');
			$article->content = \Input::get('content');
			$article->category = \Input::get('category');
			$article->open_at = \Input::get('open_at');
			$article->status = \Input::get('status');
			$article->lang = \Input::get('lang');
			($dosort = 1) ? $article->sort = \SpaArticles::max('sort')+1 : $dosort = 1;
			$article->save();
			return \Redirect::route('spa.admin.articles.list', array('category'=>$article->category));
		}catch(Exception $e)
		{
			return Redirect::route('spa.admin.articles.list', array('errorMessage'=>$e->getMessage()));
		}
		
	}

	/**
	 * Delete a specific article.
	 * @params (int) $id
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

	/*
	 * Sort articles.
	 * @params (int) $id, (int) $sort, (string) $role
	 *
	 */
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
