<?php
namespace spa;

class IndexController extends \BaseController{

	public function getIndex() {
		$news = \SpaArticles::where('category', 'news')
							->where('lang', $this->getLocale())
							->where('status', '1')
							->orderBy('open_at', 'desc')
							->orderBy('sort', 'desc')
							->take(6)
							->get()
							->toArray();
		$newsCount = count($news);
		$cover = '';
		if($news)
			$cover = json_decode($news[0]['cover']);

		$service = \SpaService::where('_parent', 'N')
							  ->where('lang', $this->getLocale())
							  ->where('display', 'yes')
							  ->orderBy('sort', 'desc')
							  ->take(4)
							  ->get()
							  ->toArray();

		return \View::make('spa.index.view_index', array(
			'news'=>$news,
			'service'=>$service,
			'newsCount'=>$newsCount,
			'cover'=>$cover
			));

	}
}

?>