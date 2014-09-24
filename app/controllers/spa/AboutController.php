<?php
namespace spa;

class AboutController extends \BaseController {

        public function getArticle($id = null){

        	try {
        		$articles = array();
        		if($id==null)
        			$article = \SpaArticles::where('category', '=', 'about')
        								   ->firstOrFail();
        		else
        			$article = \SpaArticles::find($id);

        		var_dump($article);

        		if($article)
        			//return \View::make('spa.about.view_about', array('article'=>$article, 'target'=>$target));
        	}catch(Exception $e) {
        		return \View::make('spa.about.view_about');
        	}
        	
        }

}
