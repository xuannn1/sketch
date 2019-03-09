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
Route::get('/', 'API\PageController@home')->name('home');//网站首页
Route::get('/homebook', 'API\PageController@homebook')->name('homebook');//文库页面首页
Route::get('/homethread', 'API\PageController@homethread')->name('homethread');//论坛页面首页

//固定信息
Route::get('config/allTags', 'API\PageController@allTags');
Route::get('config/noTongrenTags', 'API\PageController@noTongrenTags');
Route::get('config/allChannels', 'API\PageController@allChannels');

//讨论串/讨论楼/讨论帖
Route::apiResource('thread', 'API\ThreadController');
Route::apiResource('/thread/{thread}/post', 'API\PostController');
Route::get('/thread/{thread}/recommendation', 'API\ThreadController@recommendation');//展示这个书籍名下recommendation的index
Route::post('/thread/{thread}/synctags', 'API\ThreadController@synctags')->name('synctags');//用户给自己的thread修改对应的tag信息
Route::patch('/thread/{thread}/post/{post}/turnToPost', 'API\PostController@turnToPost');//把任意的component转化成post

//书评清单部分
Route::resource('/thread/{thread}/review', 'API\ReviewController')->only(['store', 'update']);//书评增改
Route::get('review', 'API\ReviewController@index');//展示所有评论

//问题箱部分
Route::patch('/thread/{thread}/post/{post}/turnToAnswer', 'API\QAController@turnToAnswer');//把box楼里的某个回复转变成问答
Route::get('answer', 'API\QAController@index');//展示所有回答的问答

//书籍
Route::get('/book/{thread}', 'API\BookController@show');//显示书籍主页
Route::get('/book/{thread}/chapterindex', 'API\BookController@chapterindex');//显示书籍所有chapter的列表
//章节
Route::resource('/thread/{thread}/chapter', 'API\ChapterController')->only(['store', 'update']);

//用户
Route::apiResource('user', 'API\UserController');
Route::get('user/{user}/thread', 'API\UserController@showthread');//展示某用户的全部thread，当本人或管理查询时，允许出现私密thread
Route::get('user/{user}/title', 'API\TitleController@index');//展示某用户的全部title，当本人或管理查询时，允许出现私密title
Route::patch('user/{user}/title/{title}', 'API\TitleController@update');//用户可以控制某个title是否公开

//收藏部分
Route::post('/thread/{thread}/collect', 'API\CollectionController@store');//收藏某个thread
Route::patch('/collection/{collection}', 'API\CollectionController@update');//修改某个收藏
Route::delete('/collection/{collection}', 'API\CollectionController@destroy');//删除某个收藏
Route::get('/collection', 'API\CollectionController@index');//查看收藏夹


//动态部分
Route::apiResource('status', 'API\StatusController');

//题头部分
Route::post('quote', 'API\QuoteController@store');

//私信部分
Route::get('/user/{user}/message', 'API\MessageController@index');//展示某用户的信箱，仅允许本人和管理员查询
Route::post('message', 'API\MessageController@store');

//阅读历史保存

//投票

Route::apiResource('vote', 'API\VoteController')->only(['index', 'store', 'update']);
