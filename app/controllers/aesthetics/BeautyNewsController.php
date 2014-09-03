<?php
namespace aesthetics;

/*
 * This controller is used to handle request of beauty news
 */
class BeautyNewsController extends \BaseController {

    /*
     * index page which the page is used to list all aticle
     * @params (int) $page
     */
    public function getIndex($page=1){

        $limit = 5;
        $offset = ($page-1) * $limit;
        $cmd = \BeautyNews::where('status', '=', '1');
        $total = $cmd->count();
        $articles = $cmd->skip($offset)
                        ->take($limit)
                        ->orderBy('sort', 'desc')
                        ->orderBy('updated_at', 'desc')
                        ->get();
        if ($articles==null)
            $articles = array();

        $pagerParams = array(
            'currPage' => $page,
            'total' => $total,
            'length' => 5,
            'URL' => \URL::route('frontend.beautynews.index'),
            'qs' => ''
        );

        return \View::make('aesthetics.beautyNews.view_index', array(
            'articles' => &$articles,
            'bodyId' => 'beautyNews',
            'pagerParams' => &$pagerParams,
        ));
    }

    /*
     * display aritcle by specific id for facebook sharing.
     * @params (int) $id
     */
    public function getArticle($id=null){
        try{
            if (empty($id))
                throw new Exception("Error request [10]");

            $articles = \BeautyNews::where('status', '=', '1')
                                   ->where('id', '=', $id)
                                   ->get();
            if ($articles==null)
                throw new Exception("Error request [11]");

            return \View::make('aesthetics.beautyNews.view_index', array(
                'articles' => &$articles,
                'bodyId' => 'beautyNews',
            ));

        }catch(Exception $e){
            return \Response::route('frontend.beautynes.list');
        }
    }
}