<?php
namespace spa;

class NewsContoller extends \BaseController {

	public function getNewsList() {

		try {
			$model = \SpaArticles::where('category', 'news')
								 ->where('lang', $this->getLocale())
								 ->where('status', '1')
								 ->where('open_at', '<=', new \DateTime('today'))
								 ->orderBy('open_at', 'desc')
								 ->orderBy('sort', 'desc')
								 ->paginate(5);

			$hotService = \SpaService::where('_parent', '!=', 'N')
									 ->where('lang', $this->getLocale())
									 ->where('display', 'yes')
									 ->orderBy('views', 'desc')
									 ->take(4)
									 ->get();
			if($model)
            	return \View::make('spa.news.view_news_list', array('news'=>$model, 'hotService'=>$hotService));
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

			if($this->getLocale()!=$article->lang){
				$refId = $article->ref_id;
				$article = \SpaArticles::find($refId);
			}

			if(empty($article))
				return \Redirect::route('spa.news');

			if(\ViewsAdder::views_cookie('news', $id)) {
				$article->views = $article->views + 1;
                $article->save();
            }

			$prevArticle = \SpaArticles::where('category', 'news')
									   ->where('lang', $article->lang)
									   ->where('status', '=', '1')
									   ->where('open_at', '<=', new \DateTime('today'))
									   ->orderBy('open_at', 'desc')
									   ->where('open_at', '<=', $article->open_at)
									   ->orderBy('sort', 'desc')
									   ->orderBy('id', 'desc')
									   ->where('id', '<', $article->id)
									   ->first(array('id', 'title'));

            $nextArticle = \SpaArticles::where('category', 'news')
            						   ->where('lang', $article->lang)
									   ->where('status', '=', '1')
									   ->where('open_at', '<=', new \DateTime('today'))
									   ->orderBy('open_at', 'desc')
									   ->where('open_at', '>=', $article->open_at)
									   ->orderBy('sort', 'asc')
									   ->orderBy('id', 'adc')
									   ->where('id', '>', $article->id)
                             		   ->first(array('id', 'title'));

            $hotService = \SpaService::where('_parent', '!=', 'N')
            						 ->where('lang', $this->getLocale())
            						 ->where('display', 'yes')
									 ->orderBy('views', 'desc')
									 ->take(4)
									 ->get();

			$cover = json_decode($article->cover);

			return \View::make('spa.news.view_news_detail', array(
															 'article'=>$article,
															 'prevArticle'=>$prevArticle,
															 'nextArticle'=>$nextArticle,
															 'publish'=>$article->open_at,
															 'views'=>$article->views,
															 'hotService'=>$hotService,
															 'cover'=>$cover
															 ));
		}catch(Exception $e) {
			throw new Exception ('Error request [11]');
		}
	}


}
