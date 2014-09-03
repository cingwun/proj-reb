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


Route::group(array('prefix'=>$locale), function(){

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

//admin
Route::group(array('prefix'=>'admin', 'before'=>'auth.admin'), function()
{
        //admin index
        Route::any('/', array('as'=>'admin.index', 'uses'=>'AuthController@index'));

        // admin delete fps url
        Route::post('fps/delete', array('as'=>'admin.fps.delete', 'uses'=>'FpsController@postDelete'));

        //admin module users
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
        Route::controller('test', 'TestController');

        //預約
        Route::resource('reservations','ReservationsController');

        // admin module Service and faq
        Route::get('{type}/category/list/{page?}', array('as'=>'admin.service_faq.category.list', 'uses'=>'ServiceFaqController@getCategoryList'))
             ->where(array('type'=>'(service|faq)', 'page'=>'([0-9]+)'));

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

});
