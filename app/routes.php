<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

//get locale
$languages = array('tw', 'cn');
$locale = Request::segment(1);
if (in_array($locale, $languages)) {
    App::setLocale($locale);
} else {
    $locale = null;
}

/**
 * using multi domains for development enviroment
 */
$www = Host::get('rebeauty', false);
$spa = Host::get('spa', false);

//for admin login/logout
Route::get('admin/logout', array('as'=>'admin.logout', 'uses'=>'AuthController@getLogout'));
Route::get('admin/login', array('as'=>'admin.login', 'uses'=>'AuthController@getLogin'));
Route::post('admin/login', array('as'=>'admin.login.post', 'uses'=>'AuthController@postLogin'));

// for fps image
Route::get('image/{filename}', array('as'=>'imageUrl', 'uses'=>'FpsController@getFile'));
// for fps file
Route::get('file/{filename}', array('as'=>'fileUrl', 'uses'=>'FpsController@getFile'));
// for captcha
Route::get('captcha', array('as'=>'captcha.get', 'uses'=>'CaptchaController@getCaptcha'));
// for social login
Route::get('login/{social}', array('as'=>'frontend.login', 'uses'=>'aesthetics\\MemberController@getLogin'))
     ->where(array('social'=>'(facebook|google)'));
Route::post('login', array('as'=>'frontend.login.post', 'uses'=>'aesthetics\\MemberController@postLogin'));

Route::group(array('prefix'=>$locale, 'domain'=>$www), function(){

    //首頁
    Route::get('/', array('uses'=>'aesthetics\\IndexController@getIndex', 'as'=>'frontend.index'));

    //Google搜尋結果頁
    Route::get('search/', function(){
        return View::make('aesthetics.google_search.index');
    });

    //文章
    Route::get('articles/{id}',array('uses'=>'ArticlesController@article', 'as'=>'frontend.article'))->where('id', '[0-9]+'); //單文

    //最新消息列表
    Route::get('news',array('uses'=>'ArticlesController@news')); //列表

    // wintness
    Route::get('wintness', array('uses'=>'aesthetics\\WintnessController@getIndex', 'as'=>'frontend.wintness.index'));
    Route::get('wintness/article/{id}', array('uses'=>'aesthetics\\WintnessController@getArticle', 'as'=>'frontend.wintness.article'))
         ->where(array('id'=>'([0-9]+)'));
    Route::get('wintness/load/articles', array('uses'=>'aesthetics\\WintnessController@getAjaxArticles', 'as'=>'frontend.wintness.ajax.load.articles'));

    //post form of reservation
    Route::post('reservation', array('uses'=>'aesthetics\\ReservationController@postForm', 'as'=>'frontend.reservation.post'));

    // service and faq
    Route::get('{type}/article/{id}', array('uses'=>'aesthetics\\ServiceFaqController@getArticle', 'as'=>'frontend.service_faq.article'))
             ->where(array('type'=>'(service|faq)', 'id'=>'([0-9]+)'));

    // board
    // list
    Route::get('board', array('uses'=>'aesthetics\\BoardController@getList', 'as'=>'frontend.board.list'));
    // ask
    Route::get('board/ask', array('uses'=>'aesthetics\\BoardController@getAsk', 'as'=>'frontend.board.ask'));
    // post
    Route::get('board/{postId}', array('uses'=>'aesthetics\\BoardController@getPost', 'as'=>'frontend.board.post'))
         ->where(array('postId'=>'([0-9]+)'));
    Route::post('board/ask', array('uses'=>'aesthetics\\BoardController@postAsk', 'as'=>'frontend.board.post.ask'));

    // beauty news
    Route::get('beautynews/{page?}', array('uses'=>'aesthetics\\BeautyNewsController@getIndex', 'as'=>'frontend.beautynews.index'))
         ->where(array('page'=>'([0-9]+)'));
    Route::get('beautynews/article/{id}', array('uses'=>'aesthetics\\BeautyNewsController@getArticle', 'as'=>'frontend.beautynews.article'))
         ->where(array('id'=>'([0-9]+)'));

    // garbage collect
    Route::get('gc', array('uses'=>'aesthetics\\TestController@getGarbageCollect'));
});

//admin
Route::group(array('prefix'=>'admin', 'before'=>'auth.admin'), function()
{
        //admin index
        Route::any('/', array('as'=>'admin.index', 'uses'=>'AuthController@index'));

        //switch to spa backgroupd
        Route::get('switch_to_spa', array('as'=>'switch.to.admin.spa', 'uses'=>'BackendSwitchController@getSpa'));

        // admin delete fps url
        Route::post('fps/delete', array('as'=>'admin.fps.delete', 'uses'=>'FpsController@postDelete'));

        //admin module groups
        Route::resource('users','UsersController');

        //admin module permission
        Route::resource('permissions','PermissionsController');
        Route::post('permissions/sort',array('as'=>'admin.permissions.sort', 'uses'=>'PermissionsController@sort'));

         //admin module groups
        Route::resource('groups','GroupsController');

        //admin module ranks (美麗排行榜)
        Route::resource('ranks','RanksController');
        Route::post('ranks/sort',array('as'=>'admin.ranks.sort', 'uses'=>'RanksController@sort'));

        //admin module Technology (美麗新技術)
        Route::resource('technologies','TechnologiesController');
        Route::post('technologies/sort',array('as'=>'admin.technologies.sort', 'uses'=>'TechnologiesController@sort'));

        // file upload
        Route::post('file/upload/{type}', array('as'=>'fileUploadUrl', 'uses'=>'FpsController@postUpload'))->where('type', '^(ajax|editor)$');

        //文章
        Route::resource('articles','ArticlesController');
        Route::post('articles/sort',array('as'=>'admin.articles.sort', 'uses'=>'ArticlesController@sort'));

        // banners
        Route::get('banners/action/{size}/{id?}', array('as'=>'admin.banners.action', 'uses'=>'BannersController@getAction'))
               ->where(array('size'=>'(large|medium|small)', 'id'=>'([0-9]+)'));

        Route::get('banners/delete/{size}/{id?}', array('as'=>'admin.banners.delete', 'uses'=>'BannersController@getDelete'))
               ->where(array('size'=>'(large|medium|small)', 'id'=>'([0-9]+)'));

        // banners action
        Route::post('banners/action', array('as'=>'admin.banners.enAction', 'uses'=>'BannersController@postAction'));

        // banners list
        Route::get('banners/{size}', array('as'=>'admin.banners.list', 'uses'=>'BannersController@getList'));

        // test
        //Route::controller('test', 'TestController');

        //預約
        Route::resource('reservations','ReservationsController');

        // admin module Service and faq
        Route::get('{type}/category/list/{page?}', array('as'=>'admin.service_faq.category.list', 'uses'=>'ServiceFaqController@getCategoryList'))
             ->where(array('type'=>'(service|faq)', 'page'=>'([0-9]+)'));

        Route::get('{type}/category/action/{id?}', array('as'=>'admin.service_faq.category.action', 'uses'=>'ServiceFaqController@getCategoryAction'))
             ->where(array('type'=>'(service|faq)', 'id'=>'([0-9]+)'));

        Route::post('{type}/category/update', array('as'=>'admin.service_faq.category.update', 'uses'=>'ServiceFaqController@postUpdateCategory'))
             ->where(array('type'=>'(service|faq)'));

        Route::post('{type}/delete', array('as'=>'admin.service_faq.delete', 'uses'=>'ServiceFaqController@postDelete'))
             ->where(array('type'=>'(service|faq)'));

        Route::post('{type}/sort/update', array('as'=>'admin.service_faq.sort.update', 'uses'=>'ServiceFaqController@postUpdateSort'))
             ->where(array('type'=>'(service|faq)'));

        Route::get('{type}/article/list/', array('as'=>'admin.service_faq.article.list', 'uses'=>'ServiceFaqController@getArticleList'))
             ->where(array('type'=>'(service|faq)'));

        Route::get('{type}/article/action/{id?}', array('as'=>'admin.service_faq.article.action', 'uses'=>'ServiceFaqController@getArticleAction'))
             ->where(array('type'=>'(service|faq)', 'id'=>'([0-9]+)'));

        Route::post('{type}/article/action', array('as'=>'admin.service_faq.article.write', 'uses'=>'ServiceFaqController@postWriteArticle'))
             ->where(array('type'=>'(service|faq)'));


        // admin board
        //  list
        Route::get('board/list', array('as'=>'admin.board.list', 'uses'=>'BoardController@getList'));
        //  reply
        Route::get('board/reply/{boardId}', array('as'=>'admin.board.reply', 'uses'=>'BoardController@getReply'))
                ->where(array('id'=>'([0-9]+)'));
        Route::post('board/reply', array('as'=>'admin.board.reply.store', 'before'=>'csrf', 'uses'=>'BoardController@postReply'));
        Route::post('board/status', array('as'=>'admin.board.status', 'before'=>'csrf', 'uses'=>'BoardController@postStatus'));

        //member
        Route::get('member/list', array('as'=>'admin.member.list', 'uses'=>'MemberController@getList'));
        Route::get('member/action/{member_id}', array('as'=>'admin.member.action', 'uses'=>'MemberController@getAction'))
                ->where(array('member_id'=>'([0-9]+)'));
        Route::post('member/action', array('as'=>'admin.member.action.post', 'uses'=>'MemberController@postAction'));

        // wintness
        //   gallery
        Route::get('wintness/gallery/{page?}', array('as'=>'admin.wintness.gallery', 'uses'=>'WintnessController@getGallery'))
             ->where(array('page'=>'([0-9]+)'));
        Route::get('wintness/gallery/action/{id?}', array('as'=>'admin.wintness.gallery.action', 'uses'=>'WintnessController@getGalleryAction'))
             ->where(array('id'=>'([0-9]+)'));
        Route::get('wintness/gallery/delete', array('as'=>'admin.wintness.gallery.delete', 'uses'=>'WintnessController@getGalleryDelete'));
        Route::post('wintness/gallery/write', array('as'=>'admin.wintness.gallery.write', 'uses'=>'WintnessController@postGalleryAction'));

        //   article
        Route::get('wintness/article/list/{page?}', array('as'=>'admin.wintness.article.list', 'uses'=>'WintnessController@getArticleList'))
             ->where(array('page'=>'([0-9]+)'));
        Route::get('wintness/article/action/{id?}', array('as'=>'admin.wintness.article.action', 'uses'=>'WintnessController@getArticleAction'))
             ->where(array('id'=>'([0-9]+)'));
        Route::post('wintness/article/action', array('as'=>'admin.wintness.article.write', 'uses'=>'WintnessController@postArticleAction'));
        Route::post('wintness/article/delete', array('as'=>'admin.wintness.article.delete', 'uses'=>'WintnessController@postArticleDelete'));

        //   sort
        Route::post('wintness/{type}/sort/update', array('as'=>'admin.wintness.sort.update', 'uses'=>'WintnessController@postUpdateSort'))
             ->where(array('type'=>'(gallery|article)'));

        // beauty news
        //   article
        Route::get('beautynews/list/{page?}', array('as'=>'admin.beautynews.list', 'uses'=>'BeautyNewsController@getList'))
             ->where(array('page'=>'([0-9]+)'));
        Route::get('beautynews/action/{id?}', array('as'=>'admin.beautynews.action', 'uses'=>'BeautyNewsController@getAction'))
             ->where(array('id'=>'([0-9]+)'));
        Route::post('beautynews/action', array('as'=>'admin.beautynews.write', 'uses'=>'BeautyNewsController@postAction'));
        Route::post('beautynews/delete', array('as'=>'admin.beautynews.delete', 'uses'=>'BeautyNewsController@postDelete'));

        //   sort
        Route::post('beautynews/sort/update', array('as'=>'admin.beautynews.sort.update', 'uses'=>'BeautyNewsController@postUpdateSort'));

        /*
         * Display modify user page
         */
        Route::get('user/modify', array('as'=>'admin.user.modify', 'uses'=>'UsersModifyController@getModify'))
             ->where(array('id'=>'([0-9]+)'));

        /*
         * write modify user
         */
        Route::post('user/write/{id?}', array('as'=>'admin.user.write', 'uses'=>'UsersModifyController@postWrite'));
});

/*
 *  rebeauty spa admin
 */
Route::group(array('prefix'=>'admin/spa', 'before'=>'auth.admin'), function()
{

    Route::get('/',array('as'=>'spa.admin.index', 'uses'=>function(){
        return \View::make('spa_admin._layouts.default');
    }));

    // Spa Articles
    Route::get('articles/list/{category?}/{lang?}', array('as'=>'spa.admin.articles.list', 'uses'=>'spaAdmin\\ArticleController@getList'))
         ->where(array('category'=>'(about|news)'));

    Route::get('articles/action/{id?}/{changeLan?}/{category?}', array('as'=>'spa.admin.articles.action', 'uses'=>'spaAdmin\\ArticleController@getAction'))
         ->where(array('id'=>'([0-9]+)', 'changeLan'=>'(modifyLanguage|0)', 'category'=>'(about|news|oversea)'));

    Route::post('articles/action/{id?}/{changeLan?}', array('as'=>'spa.admin.articles.store', 'uses'=>'spaAdmin\\ArticleController@postAction'))
         ->where(array('id'=>'([0-9]+)', 'changeLan'=>'(modifyLanguage)'));

    Route::get('articles/kickout/{id?}', array('as'=>'spa.admin.articles.delete', 'uses'=>'spaAdmin\\ArticleController@postDelete'))
         ->where(array('id'=>'([0-9]+)'));

    Route::post('articles/sort', array('as'=>'spa.admin.articles.sort', 'uses'=>'spaAdmin\\ArticleController@postSort'));

    // Spa Shares
    // Article
    Route::get('share/article/list/{page?}/{lang?}', array('as'=>'spa.admin.share.article.list', 'uses'=>'spaAdmin\\ShareController@getArticleList'))
         ->where(array('page'=>'([0-9]+)'));

    Route::get('share/article/action/{id?}/{changeLang?}', array('as'=>'spa.admin.share.article.action', 'uses'=>'spaAdmin\\ShareController@getArticleAction'))
         ->where(array('id'=>'([0-9]+)', 'changeLang'=>'(tw|cn)'));

    Route::post('share/article/action', array('as'=>'spa.admin.share.article.write', 'uses'=>'spaAdmin\\ShareController@postArticleAction'));
    Route::post('share/article/delete', array('as'=>'spa.admin.share.article.delete', 'uses'=>'spaAdmin\\ShareController@postArticleDelete'));
    Route::post('share/{type}/sort/update', array('as'=>'spa.admin.share.sort.update', 'uses'=>'spaAdmin\\ShareController@postUpdateSort'));
    // Gallery
    Route::get('share/gallery/{page?}/{lang?}', array('as'=>'spa.admin.share.gallery', 'uses'=>'spaAdmin\\ShareController@getGallery'))
         ->where(array('page'=>'([0-9]+)'));
    Route::get('share/gallery/action/{id?}', array('as'=>'spa.admin.share.gallery.action', 'uses'=>'spaAdmin\\ShareController@getGalleryAction'))
         ->where(array('type'=>'(shares|gallery)'));
    Route::get('share/gallery/delete', array('as'=>'spa.admin.share.gallery.delete', 'uses'=>'spaAdmin\\ShareController@getGalleryDelete'));
    Route::post('share/gallery/write', array('as'=>'spa.admin.share.gallery.write', 'uses'=>'spaAdmin\\ShareController@postGalleryAction'));

    //switch to rebeauty backgroupd
    Route::get('switch_to_rebeauty', array('as'=>'switch.to.admin.rebeauty', 'uses'=>'BackendSwitchController@getRebeauty'));

    /*----------Service----------*/
    /*
     * Display service list page
     */
    Route::get('service/article/list/', array('as'=>'spa.admin.service.article.list', 'uses'=>'spaAdmin\\ServiceController@getServiceList'));

    /*
     * Display service create/edit page
     * @params (string) $id
    * @params (string) $lang
     */
    Route::get('service/article/action/{id?}', array('as'=>'spa.admin.service.article.action', 'uses'=>'spaAdmin\\ServiceController@getServiceAction'))
             ->where(array('id'=>'([0-9]+)'));

    /*
     * Write(create/edit action) service data.
     * @params (string) $id
     */
    Route::post('service/article/write/{id?}', array('as'=>'spa.admin.service.article.write', 'uses'=>'spaAdmin\\ServiceController@postWriteService'))
             ->where(array('id'=>'([0-9]+)'));

    /*
     * Delete Service.
     * @params (string) $id
     */
    Route::post('service/article/delete', array('as'=>'spa.admin.service.article.delete', 'uses'=>'spaAdmin\\ServiceController@postDeleteService'));

    /*
     * Display category list page
     */
    Route::get('service/category/list', array('as'=>'spa.admin.service.category.list', 'uses'=>'spaAdmin\\ServiceController@getCategoryList'));

    /*
     * hadnle AJAX request for delete category
     */
    Route::post('service/category/delete', array('as'=>'spa.admin.service.category.delete', 'uses'=>'spaAdmin\\ServiceController@postDeleteCategory'));

    /*
     * handle AJAX request of change sort
     */
    Route::post('service/sort/update', array('as'=>'spa.admin.service.sort.update', 'uses'=>'spaAdmin\\ServiceController@postUpdateSort'));

    /*
     * Display category action page
     */
    Route::get('service/category/action/{id?}', array('as'=>'spa.admin.service.category.action', 'uses'=>'spaAdmin\\ServiceController@getCategoryAction'));

    /*
     * Write(create/edit action) category data.
     * @params (int) $id
     */
    Route::post('service/category/write/{id?}', array('as'=>'spa.admin.service.category.write', 'uses'=>'spaAdmin\\ServiceController@postWriteCategory'));

    /*----------product----------*/
    /*
     * Display product list.
     */
    Route::get('product/article/list/', array('as'=>'spa.admin.product.article.list', 'uses'=>'spaAdmin\\ProductController@getProductList'));

    /*
     * Display product create/edit page.
     * @params (string) $id
     * @params (string) $lang
     */
    Route::get('product/article/action/{id?}', array('as'=>'spa.admin.product.article.action', 'uses'=>'spaAdmin\\ProductController@getProductAction'))
             ->where(array('id'=>'([0-9]+)'));

    /*
     * Write(create/edit action) product data.
     * @params (string) $id
     */
    Route::post('product/article/write/{id?}', array('as'=>'spa.admin.product.article.write', 'uses'=>'spaAdmin\\ProductController@postWriteProduct'))
             ->where(array('id'=>'([0-9]+)'));

    /*
     * Delete product.
     * @param (string) $id
     */
    Route::post('product/article/delete', array('as'=>'spa.admin.product.article.delete', 'uses'=>'spaAdmin\\ProductController@postDeleteProduct'));

    /*
     * Display category list page
     */
    Route::get('product/category/list', array('as'=>'spa.admin.product.category.list', 'uses'=>'spaAdmin\\ProductController@getCategoryList'));

    /*
     * hadnle AJAX request for delete category
     */
    Route::post('product/category/delete', array('as'=>'spa.admin.product.category.delete', 'uses'=>'spaAdmin\\ProductController@postDeleteCategory'));

    /*
     * handle AJAX request of change sort
     */
    Route::post('product/sort/update', array('as'=>'spa.admin.product.sort.update', 'uses'=>'spaAdmin\\ProductController@postUpdateSort'));

    /*
     * Display category action page
     */
    Route::get('product/category/action/{id?}', array('as'=>'spa.admin.product.category.action', 'uses'=>'spaAdmin\\ProductController@getCategoryAction'));

    /*
     * Write(create/edit action) category data.
     * @params (int) $id
     */
    Route::post('product/category/write/{id?}', array('as'=>'spa.admin.product.category.write', 'uses'=>'spaAdmin\\ProductController@postWriteCategory'));
    /*----------reservation----------*/

    /*
     * Display reservation list page
     */
    Route::get('reservation/list', array('as'=>'spa.admin.reservation.list', 'uses'=>'spaAdmin\\ReservationController@getReservationList'));

    /*
     * AJAX request for reservation details
     */
    Route::post('reservation/details', array('as'=>'spa.admin.reservation.details', 'uses'=>'spaAdmin\\ReservationController@postReservationDetails'));

    /*
     * AJAX request for delete reservation
     */
    Route::post('reservation/delete', array('as'=>'spa.admin.reservation.delete','uses'=>'spaAdmin\\ReservationController@postDeleteReservation'));

    /*
     * Display reservation action page
     * @params (string) $id
     */
    Route::get('reservation/action/{id?}', array('as'=>'spa.admin.reservation.action', 'uses'=>'spaAdmin\\ReservationController@getReservationAction'))
             ->where(array('id'=>'([0-9]+)'));

    /*
     * Write(create/edit action) reservation data.
     * @params (string) $id
     */
    Route::post('reservation/write/{id?}', array('as'=>'spa.admin.reservation.write', 'uses'=>'spaAdmin\\ReservationController@postReservationWrite'))
             ->where(array('id'=>'([0-9]+)'));
});

Route::group(array('prefix'=>$locale, 'domain'=>$spa), function() {

    Route::get('/', array('as'=>'spa.index', 'uses'=>'spa\\IndexController@getIndex'));

    /*----------about----------*/
    Route::get('about/{id?}', array('as'=>'spa.about', 'uses'=>'spa\\AboutController@getArticle'))
         ->where(array('id'=>'([0-9]+)'));

    /*----------share----------*/
    Route::get('share', array('as'=>'spa.share', 'uses'=>'spa\\ShareController@getShareList'));
    Route::get('share/{id?}', array('as'=>'spa.share.detail', 'uses'=>'spa\\ShareController@getArticle'))
         ->where(array('id'=>'([0-9]+)'));

    /*----------news----------*/
    Route::get('news', array('as'=>'spa.news', 'uses'=>'spa\\NewsContoller@getNewsList'));
    Route::get('news/{id?}', array('as'=>'spa.news.detail', 'uses'=>'spa\\NewsContoller@getArticle'))
         ->where(array('id'=>'([0-9]+)'));

    /*----------service----------*/
    /*
     * Display service page
     */
    Route::get('service', array('as'=>'spa.service', 'uses'=>'spa\\ServiceController@getService'));

    /*
     * Display service derail page
     * params (int) $id
     */
    Route::get('service/detail/{id?}', array('as'=>'spa.service.detail', 'uses'=>'spa\\ServiceController@getServiceDetail'))
        ->where(array('id'=>'([0-9]+)'));

    /*----------product----------*/

    /*
     * Display product page
     */
    Route::get('product', array('as'=>'spa.product', 'uses'=>'spa\\ProductController@getProduct'));

    /*
     * Display product derail page
     * params (int) $id
     */
    Route::get('product/detail/{id?}', array('as'=>'spa.product.detail', 'uses'=>'spa\\ProductController@getProductDetail'))
         ->where(array('id'=>'([0-9]+)'));

    /*
     * Display product list page
     * params (int) $cat
     */
    Route::get('product/list/{cat?}', array('as'=>'spa.product.list', 'uses'=>'spa\\ProductController@getProductList'))
         ->where(array('cat'=>'([0-9]+)'));

    /*----------reservation----------*/

    /*
     * Display over sea page
     */
    Route::get('reservation/overSea', array('as'=>'spa.reservation.overSea', 'uses'=>'spa\\ReservationController@getOverSea'));

    /*
     * Display over sea form page
     */
    Route::get('reservation/form', array('as'=>'spa.reservation.form', 'uses'=>'spa\\ReservationController@getForm'));

    /*
     * AJAX request for reservation form
     */
    Route::post('reservation/form/write', array('as'=>'spa.reservation.form.write', 'uses'=>'spa\\ReservationController@postWriteForm'));

    /*
     * Display reservation quick page
     */
    Route::get('reservation/quick', array('as'=>'spa.reservation.quick', 'uses'=>'spa\\ReservationController@getQuick'));
});