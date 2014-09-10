<?php
namespace spaAdmin;

class ArticleController extends \BaseController {

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

			$selectedArticles = \SpaArticles::where('category',$category)->get();
			return \View::make('spa_admin.articles.view_list', array('category'=>$category, 'selectedArticles'=>$selectedArticles));
		}catch(Exception $e){
			return Redirect::route('spa.admin.articles.list', array('errorMessage'=>$e->getMessage()));
		}
	}


	/**
	 * Get the form for create new article.
	 *
	 * 
	 */
	public function getCreate()
	{
		return \View::make('spa_admin.articles.view_create');
	}


	/**
	 * Post a newly created article into table.
	 *
	 * 
	 */
	public function postCreate()
	{
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
	public function edit($id)
	{
		//
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
	}


}
