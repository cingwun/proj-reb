<?php
namespace aesthetics;

class NewsController extends \BaseController {

    /*
     * display index page
     */
    public function getIndex($page=1){
        $newses = \Article::where('status', '=', '1')
                          ->where('category', '=', '3')
                          ->where('open_at', '<=', date('Y-m-d'))
                          ->orderBy('sort', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->skip(0)
                          ->take(10)
                          ->get(array('id', 'title', 'open_at'));

        if ($newses===null)
            $newses = array();

        return \View::make('aesthetics.index.index', array(
            'newses' => &$newses,
        ));
    }

}