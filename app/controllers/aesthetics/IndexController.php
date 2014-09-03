<?php
namespace aesthetics;

class IndexController extends \BaseController {

	/*
     * display index page
     */
    public function getIndex(){
    	$boards = \Board::where('status', '=', '1')
    				   ->orderBy('created_at', 'desc')
    				   ->skip(0)
    				   ->take(10)
    				   ->get(array('id', 'name', 'topic', 'count_num', \DB::raw("date_format(created_at,'%Y-%m-%d') as d")));
        if ($boards===null)
        	$boards = array();

        $newses = \Article::where('status', '=', '1')
                          ->where('category', '=', '3')
                          ->where('open_at', '<=', date('Y-m-d'))
                          ->orderBy('sort', 'desc')
                          ->orderBy('open_at', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->skip(0)
                          ->take(10)
                          ->get(array('id', 'title', 'open_at'));

        if ($newses===null)
            $newses = array();

        return \View::make('aesthetics.index.index', array(
        	   'boards' => &$boards,
            'newses' => &$newses
        ));
    }

}