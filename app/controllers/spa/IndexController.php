<?php
namespace spa;

class IndexController extends \BaseController{

	public function getIndex() {
		$news = \SpaArticles::where('category', 'news')
							 ->where('status', '1')
							 ->orderBy('open_at', 'desc')
							 ->orderBy('sort', 'desc')
							 ->take(5)
							 ->get()
							 ->toArray();
		$newsCount = count($news);
		$cover = json_decode($news[0]['cover']);

		$service = \SpaService::where('_parent', 'N')
							  ->where('display', 'yes')
							  ->orderBy('sort', 'desc')
							  ->take(4)
							  ->get();

		return \View::make('spa.index.view_index', array(
			'news'=>$news,
			'service'=>$service,
			'newsCount'=>$newsCount,
			'cover'=>$cover
			));
		
	}
}

?>