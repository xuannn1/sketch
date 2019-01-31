<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//测试用api
Route::middleware('auth:api')->group( function(){
    //Route::resource('thread', 'API\ThreadController');
});

//注册相关
Route::post('register', 'API\PassportController@register');
Route::post('login', 'API\PassportController@login')->name('login');

//默认页面
Route::get('/', 'API\PagesController@home')->name('home');//网站首页
Route::get('/homebook', 'API\PagesController@homebook')->name('homebook');//文库页面首页
Route::get('/homethread', 'API\PagesController@homethread')->name('homethread');//论坛页面首页

//固定信息
Route::get('config/allTags', 'API\PagesController@allTags');

//讨论串/讨论楼/讨论帖
Route::apiResource('thread', 'API\ThreadController');
Route::apiResource('/thread/{thread}/post', 'API\PostController');
Route::post('/thread/{thread}/synctags', 'API\ThreadController@synctags')->name('synctags');//用户给自己的thread修改对应的tag信息

//书籍
Route::apiResource('book', 'API\BookController');

Route::apiResource('/thread/{thread}/chapter', 'API\ChapterController');
Route::apiResource('recommendation', 'API\RecommendationController');

//用户
Route::apiResource('user', 'API\UserController');

//动态部分
Route::apiResource('status', 'API\StatusController');


//题头部分
Route::post('quote', 'API\QuoteController@store');

//私信部分
Route::post('/message/store/{user}', 'API\MessageController@store');//有点问题
