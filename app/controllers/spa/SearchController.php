<?php
namespace spa;

class SearchController extends \BaseController {

	public function postSearch() {

		/*
		 * prepare recommended lesson for search page
		 */ 
		try {
			$hotService = array();
			$hotService = \SpaService::where('_parent', '!=', 'news')
									 ->where('lang', $this->getLocale())
									 ->where('display', 'yes')
									 ->orderBy('views', 'desc')
									 ->take(4)
									 ->get();
			return \View::make('spa.google_search.view_search', array('hotService'=>$hotService));
		}catch(Exception $e) {
			throw new Exception ('Error request');
		}
	}
}