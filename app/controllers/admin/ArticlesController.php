<?php
class ArticlesController extends \BaseController
{

    protected $filterExcept = array('getNav', 'article', 'news', 'news_index');

    public function __construct() {
        parent::permissionFilter();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        //
        $category = Input::get('category');
        $lang = Input::get('lang'); //where('lang', $lang)->
        if (!$category) {
            return Redirect::to('admin/articles?category=1');
        }
        $model = new Article;
        $model = $model->ofCategory($category);

        $model = ($lang!='all') ? $model->orderBy('open_at', 'desc')->where('lang', $lang)->paginate(6) : $model = $model->orderBy('open_at', 'desc')->paginate(6);
            
        $page = Input::get('page', 1);
        $limit = 6;
        $offset = ((int)($page-1)) * $limit;
        $rowsNum = ($lang!='all')?Article::where('category', '=', $category)->where('lang', $lang)->count() : Article::where('category', '=', $category)->count();
        $widgetParam = array(
            'currPage' => $page,
            'total' => $rowsNum,
            'perPage' => $limit,
            'URL' => URL::route('admin.articles.index'),
            'category' => $category,
            'lang' => $lang
        );
        
        return View::make('admin.articles.index', array(
            'category'=>$category,
            'articles'=>$model,
            'wp'=>$widgetParam
            ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {

        //
        return View::make('admin.articles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {

        //
        try {
            $article = new Article;
            $article->title = Input::get('title');
            $article->description = Input::get('description');
            $article->category = Input::get('category');
            $article->open_at = Input::get('open_at');
            $article->status = Input::get('status');
            $article->lang = Input::get('lang');
            $article->meta_name = Input::get('meta_name');
            $article->meta_content = Input::get('meta_content');
            $article->meta_title = Input::get('meta_title');

            $article->save();
            //create a corresponding tw/cn article at same time.
            $refLang = (Input::get('lang')=='tw') ? 'cn' : 'tw';
            $refArticle = new Article;
            $refArticle->title = Input::get('title');
            $refArticle->description = Input::get('description');
            $refArticle->category = Input::get('category');
            $refArticle->status = 0;
            $refArticle->lang = $refLang;
            $article->meta_name = Input::get('meta_name');
            $article->meta_content = Input::get('meta_content');
            $article->meta_title = Input::get('meta_title');

            $refArticle->save();

            $refArticle->langRef = $article->id;
            $article->langRef = $refArticle->id;
            $refArticle->save();
            $article->save();

            return Redirect::to('admin/articles?category=' . Input::get('category') . '&lang=' . Input::get('lang'));
        }
        catch(Exception $e) {
            return Redirect::back()->withInput()->withErrors('新增失敗');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {

        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {

        //
        return View::make('admin.articles.edit')->with('article', Article::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {

        //
        try {
            $article = Article::find($id);

            $article->title = Input::get('title');
            $article->description = Input::get('description');
            $article->category = Input::get('category');
            $article->open_at = Input::get('open_at');
            $article->status = Input::get('status');
            $article->meta_name = Input::get('meta_name');
            $article->meta_content = Input::get('meta_content');
            $article->meta_title = Input::get('meta_title');

            $article->save();

            return Redirect::to('admin/articles?category=' . Input::get('category') . '&lang=' . Input::get('lang'));
        }
        catch(Exception $e) {
            return Redirect::back()->withInput()->withErrors('修改失敗');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {

        //
        $model = Article::find($id);
        $refArticle = $model->langRef;
        Article::destroy($id);
        Article::destroy($refArticle);
    }

    /**
     *
     *
     */
    public static function getNav($category) {
        // $key = 'nav_article_' . $category;
        // $data = Cache::get($key);

        // if (!$data) {
        //     $data = Article::ofCategory($category)->open()->get();
        //     Cache::put($key, $data, 2);
        // }
        $data = Article::where('category', $category)
                       ->where('lang', App::getLocale())
                       ->where('status', '1')
                       ->get();

        return $data;
    }

    /**
     *
     * 單文頁
     */
    public function article($id) {
        try {
            $article = Article::open()
                              ->where('id', '=', $id)
                              ->where('status', '1')
                              ->first();
                              
            if(App::getLocale()!=$article->lang){
                $refId = $article->langRef;
                $article = Article::find($refId);
            }

            if ($article) {

                //瀏覽數
                if (ViewsAdder::views_cookie($article->category, $id)) {
                    $article->views = $article->views + 1;
                    $article->save();
                }

                //套用不同版型
                if ($article->category == '1') {
                     //關於煥儷
                    return View::make('aesthetics.about.index')->with('article', $article);
                } elseif ($article->category == '2') {
                     //海外專區
                    return View::make('aesthetics.overseas.index')->with('article', $article);
                } elseif ($article->category == '3') {
                     //最新消息
                    //上一篇 ID
                    $previousId = Article::ofCategory('3')->where('id', '<', $article->id)->where('status', '1')->orderBy('id', 'DESC')->max('id');

                    //下一篇 ID
                    $nextId = Article::ofCategory('3')->where('id', '>', $article->id)->where('status', '1')->orderBy('id', 'DESC')->min('id');
                    return View::make('aesthetics.news.post')->with(array('article' => $article, 'prev' => Article::find($previousId), 'next' => Article::find($nextId)));
                }
            } else {
                return Redirect::to('/');
            }
        }
        catch(Exception $e) {

            return Redirect::to('/');
        }
    }

    public function sort() {
        $sort = explode(',', Input::get('sort'));

        if ($sort) {
            foreach ($sort as $key => $id) {
                $article = Article::find($id);
                $article->sort = $key + 1;
                $article->save();
            }
        }
    }

    /**
     *
     * 最新消息列表
     */
    public function news() {
        try {
            $model = new Article;
            $model = $model->ofCategory('3')
                           ->where('lang', App::getLOcale())
                           ->where('status', '1')
                           ->open()
                           ->orderBy('open_at', 'DESC');

            return View::make('aesthetics.news.index')->with('articles', $model->paginate(5));
        }
        catch(Exception $e) {
        }
    }

    /**
     *
     * 首頁 最新消息
     */
    public static function news_index() {
        try {
            $key = 'news_index';
            $data = Cache::get($key);

            if (!$data) {
                $data = Article::ofCategory('3')->open()->orderBy('open_at', 'DESC')->where('status', '1')->take(10)->get();
                Cache::put($key, $data, 2);
            }
            return $data;
        }
        catch(Exception $e) {
        }
    }
}
