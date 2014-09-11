<?php
namespace spaAdmin;

class ArticleController extends \BaseController {

	
	protected function beforeAction($actionType){
        $actionType .= 's';
        parent::permissionFilter($actionType);
    }



	/**
	 * Get article management index.
	 * @param (enum) $category 1=about 2=news 3=share
	 * 
	 */
	public function getList($category = 1)
	{
		try{
			if(!($category == 1 || $category == 2 || $category ==3)) //If insert category does not exist, redirect to category1.
				return \Redirect::route('spa.admin.articles.list');

			if(isset($_POST['category']))
				$category = Input::get('category');

			$selectedArticles = \SpaArticles::where('category',$category)->get();
			return \View::make('spa_admin.articles.view_list', array('category'=>$category, 'selectedArticles'=>$selectedArticles));
		}catch(Exception $e){
			return Redirect::route('spa.admin.articles.list', array('errorMessage'=>$e->getMessage()));
		}
	}


	/*
	 * Get the form for create new article.
	 *
	 * 
	 */
	public function getAction($id = null)
	{
		if(empty($id)){
			return \View::make('spa_admin.articles.view_create', array('action'=>"create", 'id'=>'null', 'specArticle'=>'null'));
		}else {
			$specArticle = \SpaArticles::find($id);
			return \View::make('spa_admin.articles.view_create', array('action'=>"update", 'id'=>$specArticle->id, 'specArticle'=>$specArticle));
		}
	}


	/**
	 * Post a newly created article into table.
	 *
	 * 
	 */
	public function postAction($id = null)
	{
		if(empty($id)){
			try{
				$article = new \SpaArticles;
				$article->title = \Input::get('title');
				$article->content = \Input::get('content');
				$article->category = \Input::get('category');
				$article->open_at = \Input::get('open_at');
				$article->status = \Input::get('status');
				$article->lan = \Input::get('lan');
				$article->save();
				return \Redirect::route('spa.admin.articles.list');
			}catch(Exception $e){
				return Redirect::route('spa.admin.articles.list', array('errorMessage'=>$e->getMessage()));
			}
		}else {
			try{
				$article = \SpaArticles::find($id);
				$article->title = \Input::get('title');
				$article->content = \Input::get('content');
				$article->category = \Input::get('category');
				$article->open_at = \Input::get('open_at');
				$article->status = \Input::get('status');
				$article->lan = \Input::get('lan');
				$article->save();
				return \Redirect::route('spa.admin.articles.list');
			}catch(Exception $e){
				return Redirect::route('spa.admin.articles.list', array('errorMessage'=>$e->getMessage()));
			}
		}
	}


	/**
	 * Display the specified article.
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
	public function getUpdate($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdate($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postDelete($id)
	{
		try{
			$article = \SpaArticles::find($id);
			$article->delete();
			return \Redirect::route('spa.admin.articles.list');
		}catch(Exception $e){
			return Redirect::route('spa.admin.articles.list', array('errorMessage'=>$e->getMessage()));
		}
	}


}
