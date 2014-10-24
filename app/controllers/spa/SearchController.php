<?php
namespace spa;

class SearchController extends \BaseController {

	public function postSearch() {

		try {
			$hotService = \SpaService::where('_parent', '!=', 'news')
									 ->where('lang', $this->getLocale())
									 ->where('display', 'yes')
									 ->orderBy('views', 'desc')
									 ->take(4)
									 ->get();
			if(!$hotService)
				$hotService = null;
			return \View::make('spa.google_search.view_search', array('hotService'=>$hotService));

		}catch(Exception $e) {
			throw new Exception ('Error request');
		}
	}
}