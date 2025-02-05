<?php
namespace spa;

class AboutController extends \BaseController {
    public function getArticle($id = null){
        try {
            $articles = array();
            if($id==null) {
                $article = \SpaArticles::where('category', '=', 'about')
                                       ->where('lang', $this->getLocale())
                                       ->where('status', '1')
                                       ->orderBy('sort', 'desc')
                                       ->first();
                $cover = '';
                if($article){
                  $id = $article->id;
                  $cover = json_decode($article->cover);
                }
            }
            else
                $article = \SpaArticles::where('status', '1')
                                       ->find($id);
            if($article && $this->getLocale()!=$article->lang){
              $refId = $article->ref_id;
              $article = \SpaArticles::find($refId);
            }

            if($article)
              $cover = json_decode($article->cover);

            if($article&&\ViewsAdder::views_cookie('about', $id)) {
              $article->views = $article->views + 1;
              $article->save();
            }

            $articleList = \SpaArticles::where('category', '=', 'about')
                                       ->where('lang', $this->getLocale())
                                       ->where('status', '1')
                                       ->orderBy('sort', 'desc')
                                       ->get();

            return \View::make('spa.about.view_about', array('article'=>$article,
                                                             'articleList'=>$articleList,
                                                             'publish'=>array_get($article, 'open_at'),
                                                             'views'=>array_get($article, 'views'),
                                                             'cover'=>$cover));
        }catch(Exception $e) {
            return \View::make('spa.about.view_about');
        }
    }

}
