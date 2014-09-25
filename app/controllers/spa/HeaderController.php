<?php
namespace spa;

class HeaderController extends \BaseController {

	protected $filterExcept = array('getDirections');

	public function getDirections() {

		$aboutArticle = \SpaArticles::where('category', '=', 'about')
								   ->orderBy('sort', 'desc')
								   ->get();

		$serviceParent = array();
		$serviceParent = \SpaSerivce::where('_parent', '=', 'N')
									->orderBy('sort', 'desc')
									->get();

		$serviceArticle = array();
		foreach($serviceParent as $parent) {
			$serviceArticle = \SpaSerivce::where('_parent', '=', $parent->id)
										 ->orderBy('sort', 'desc')
									 	 ->get();
		}

		$productParent = \SpaProduct::where('_parent', '=', 'N')
									->orderBy('sort', 'desc')
									->get();

		return $aboutArticle, $serviceParent, $serviceArticle, $productParent;
	}
}