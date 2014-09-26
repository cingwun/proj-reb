<?php
namespace spa;

class NewsContoller extends \BaseController {

	public function getNewsList() {

		try {
			$news = \SpaArticles::where('category', 'news')
								->where('status', '1')
								->orderBy('open_at', 'desc')
								->orderBy('sort', 'desc')
								->get(array('id', 'title', 'content', 'open_at'))
								->toArray();
			if($news)
				return \View::make('spa.news.view_news_list', array('news'=>$news));
		}catch(Exception $e) {
			throw new Exception ('Error request [11]');
		}

	}

	public function getArticle($id = null) {

		try {
			if($id==null)
				return Redirect::route('spa.news');

			$article = \SpaArticles::where('status', '1')
								   ->find($id);
			if(empty($article))
				throw new \Exception('Error request [11]');

			$prevArticle = \SpaArticles::where('category', 'news')
									   ->where('status', '1')
									   ->where('sort', '>=', $article->sort)
									   ->where('updated_at', '>', $article->updated_at)
									   ->first(array('id', 'title'));
			$nextArticle = \SpaArticles::where('category', 'news')
									   ->where('status', '1')
									   ->where('sort', '<=', $article->sort)
									   ->where('updated_at', '<', $article->updated_at)
									   ->first(array('id', 'title'));

			return \View::make('spa.news.view_news_detail', array(
															 'article'=>$article,
															 'prevArticle'=>$prevArticle,
															 'nextArticle'=>$nextArticle,
															 'publish'=>$article->open_at,
															 'views'=>$article->views
															 ));
		}catch(Exception $e) {
			throw new Exception ('Error request [11]');
		}
	}


}