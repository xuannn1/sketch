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

// 测试用api
Route::middleware('auth:api')->group( function(){
    // Route::resource('thread', 'API\ThreadController');
});

// 注册相关
Route::post('register', 'API\PassportController@register');
Route::post('register_by_invitation', 'API\PassportController@register_by_invitation');
Route::post('login', 'API\PassportController@login')->name('login');
Route::post('logout', 'API\PassportController@logout')->name('logout');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('password/reset_via_email', 'API\PassportController@reset_via_email');
Route::post('password/reset_via_password', 'API\PassportController@reset_via_password');

// 输入邮箱申请测试答题
Route::post('register/by_invitation_email/submit_email', 'API\RegAppController@submit_email'); // 输入邮箱尝试注册
Route::post('register/by_invitation_email/submit_quiz', 'API\RegAppController@submit_quiz'); // 尝试答题
Route::get('register/by_invitation_email/resend_email_verification', 'API\RegAppController@resend_email_verification'); // 重新发送邮箱确认邮件
Route::post('register/by_invitation_email/submit_essay', 'API\RegAppController@submit_essay'); // 提交小论文
Route::get('register/by_invitation_email/resend_invitation_email', 'API\RegAppController@resend_invitation_email'); // 重发邀请邮件

// 关联账户相关
Route::get('/linkaccount','LinkAccountController@index');
Route::post('/linkaccount/store','LinkAccountController@store');
Route::get('/linkaccount/switch/{id}','LinkAccountController@switch');
Route::delete('/linkaccount/destroy','LinkAccountController@destroy');


// 默认页面
Route::get('/', 'API\PageController@home')->name('home');// 网站首页

// 固定信息
Route::get('config/allTags', 'API\PageController@allTags');
Route::get('config/allChannels', 'API\PageController@allChannels');
Route::get('config/allTitles', 'API\PageController@allTitles');

// 讨论串/讨论楼/讨论帖
Route::get('/channel/{channel}', 'API\ThreadController@channel_index')->middleware('filter_channel');//某个版面的讨论贴
Route::apiResource('thread', 'API\ThreadController');
Route::apiResource('/thread/{thread}/post', 'API\PostController');
Route::get('/thread/{thread}/recommendation', 'API\ThreadController@recommendation');// 展示这个书籍名下recommendation的index
Route::patch('/thread/{thread}/synctags', 'API\ThreadController@synctags')->name('synctags');// 用户给自己的thread修改对应的tag信息
Route::patch('/thread/{thread}/post/{post}/turnToPost', 'API\PostController@turnToPost');// 把任意的component转化成post

// 书评清单部分
Route::resource('/thread/{thread}/review', 'API\ReviewController')->only(['store', 'update']);// 书评增改
Route::get('review', 'API\ReviewController@index');// 展示所有评论

// 问题箱部分
Route::patch('/thread/{thread}/post/{post}/turnToAnswer', 'API\QAController@turnToAnswer');// 把box楼里的某个回复转变成问答
Route::get('answer', 'API\QAController@index');// 展示所有回答的问答

// 书籍
Route::get('/book', 'API\BookController@index');//文库筛选主页
Route::get('/book/{thread}', 'API\BookController@show');// 显示书籍主页
Route::get('/book/{thread}/chapterindex', 'API\BookController@chapterindex');// 显示书籍所有chapter的列表
// 章节
Route::resource('/thread/{thread}/chapter', 'API\ChapterController')->only(['store', 'update']);

// 用户
Route::apiResource('user', 'API\UserController');
Route::get('user/{user}/thread', 'API\UserController@showthread');// 展示某用户的全部thread，当本人或管理查询时，允许出现私密thread
Route::patch('user/{user}/profile', 'API\UserController@updateProfile');//
Route::get('user/{user}/follower', 'API\FollowerController@follower');//展示该用户的所有粉丝
Route::get('user/{user}/following', 'API\FollowerController@following');//展示该用户的所有关注
Route::get('user/{user}/followingStatuses', 'API\FollowerController@followingStatuses');//展示该用户的所有关注，附带关注信息更新状态
Route::post('user/{user}/follow','API\FollowerController@store');//关注某人
Route::delete('user/{user}/follow','API\FollowerController@destroy');//取关某人
Route::patch('user/{user}/follow','API\FollowerController@update');//切换是否跟踪动态
Route::get('user/{user}/follow','API\FollowerController@show');//返回与该关注相关的信息（是否跟踪动态，是否已阅更新）

//收藏部分
Route::post('/thread/{thread}/collect', 'API\CollectionController@store');//收藏某个thread
Route::patch('/collection/{collection}', 'API\CollectionController@update');//修改某个收藏
Route::delete('/collection/{collection}', 'API\CollectionController@destroy');//删除某个收藏
Route::get('user/{user}/collection', 'API\CollectionController@index');//查看收藏夹


// 动态部分
Route::apiResource('status', 'API\StatusController');

// 题头部分
Route::post('quote', 'API\QuoteController@store');

// 私信部分
Route::get('/user/{user}/message', 'API\MessageController@index');// 展示某用户的信箱，仅允许本人和管理员查询
Route::post('message', 'API\MessageController@store');
Route::post('groupmessage', 'API\MessageController@groupmessage');//管理员群发私信
Route::post('publicnotice', 'API\MessageController@publicnotice');//管理员发系统消息

// 消息部分
Route::get('/user/{user}/activity', 'API\ActivityController@index');// 展示某用户的站内提醒，仅允许本人和管理员查询
Route::post('/clearupdates', 'API\ActivityController@clearupdates');// 清除未读提醒

// 阅读历史保存

// 投票
Route::apiResource('vote', 'API\VoteController')->only(['index', 'store', 'destroy']);

Route::get('/user/{user}/vote_sent','API\VoteController@sent');//我给出的评票
Route::get('/user/{user}/vote_received','API\VoteController@received');//我收到的评票

// 打赏
Route::apiResource('reward', 'API\RewardController')->only(['index', 'store', 'destroy']);

Route::get('/user/{user}/reward_sent','API\RewardController@sent');//我给出的评票
Route::get('/user/{user}/reward_received','API\RewardController@received');//我收到的评票

// 头衔
Route::get('user/{user}/mytitle', 'API\TitleController@mytitle');
Route::post('wearTitle/{title}', 'API\TitleController@wear');
Route::post('redeemTitle', 'API\TitleController@redeem_title');
