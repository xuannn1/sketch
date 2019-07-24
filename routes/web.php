<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


{//以下是用户注册与验证模块
   Auth::routes();

   Route::get('/test', 'PagesController@test')->name('test');

   Route::post('login', 'Auth\LoginController@login')->name('login');

   Route::get('register/confirm/{token}', 'Auth\RegisterController@confirmEmail')->name('confirm_email');//确认邮箱正确 ??
}

{ // 关联马甲

    Route::get('/linkedaccounts','LinkedAccountsController@index')->name('linkedaccounts.index');
    Route::get('/linkedaccounts/create','LinkedAccountsController@create')->name('linkedaccounts.create');
    Route::post('/linkedaccounts/store','LinkedAccountsController@store')->name('linkedaccounts.store');
    Route::get('/linkedaccounts/switch/{id}','LinkedAccountsController@switch')->name('linkedaccounts.switch');
    Route::delete('/linkedaccounts/destroy','LinkedAccountsController@destroy')->name('linkedaccounts.destroy');
}

{//以下是静态页面模块

    Route::get('/', 'PagesController@home')->name('home');
    Route::get('about', 'PagesController@about')->name('about');

    // TODO:  Contact写成动态情况，提供当前编辑管理列表和往期人员。这个完全不急，慢慢做。
    Route::get('contacts', 'PagesController@contacts')->name('contacts');

    Route::get('/search','SearchController@search')->name('search');
    Route::get('/search_user','SearchController@search_user')->name('search.search_user');
    Route::get('/search_thread','SearchController@search_thread')->name('search.search_thread');
    Route::get('/search_tag','SearchController@search_tag')->name('search.search_tag');

    Route::get('/administrationrecords', 'PagesController@administrationrecords')->name('administrationrecords');
    Route::get('/qiandao', 'UsersController@qiandao')->name('qiandao');//签到
    Route::get('/recommend_records', 'PagesController@recommend_records')->name('recommend_records');//普通用户查看推荐书籍历史
    Route::get('/create_thread_entry', 'PagesController@create_thread_entry')->name('create_thread_entry');//绝对复杂筛选所有thread？？
    Route::get('/tags', 'PagesController@all_tags')->name('all.tags');//全站标签列表
}

{//题头部分
    Route::resource('quote', 'QuoteController', ['only' => [
        'index', 'create', 'store', 'show'
    ]]); //
    Route::get('/quote_mine', 'QuoteController@mine')->name('quote.mine');//我提交的题头

    Route::get('/admin/quote_review', 'QuoteController@review_index')->name('quote.review_index');//批量审核题头

    Route::get('/quote/{quote}/review','QuoteController@review')->name('quote.review');//审核单独题头
}

{//以下是用户信息展示模块
   Route::get('/users/{id}', 'UsersController@show')->name('user.show');//展示某用户的个人页面(这里只展示书籍)

   Route::get('users/{id}/threads','UsersController@threads')->name('user.threads');//展示某用户的全部主题贴

   Route::get('users/{id}/lists','UsersController@lists')->name('user.lists');//展示某用户的全部清单 19.7.11

   Route::get('users/{id}/statuses','UsersController@statuses')->name('user.statuses');//展示某用户的全部动态 19.7.11

   Route::get('users/{id}/comments','UsersController@comments')->name('user.comments');//展示某用户的全部评论 19.7.11

   Route::get('/users/{id}/followings', 'UsersController@followings')->name('user.followings');// 关注的人列表 19.7.11

   Route::get('/users/{id}/followers', 'UsersController@followers')->name('user.followers'); // 粉丝列表 19.7.11

   Route::get('/users', 'UsersController@index')->name('user.index');//展示所有用户，按最后签到时间排序 19.7.11

}

{ // title
    Route::resource('title', 'TitleController', ['only' => [
        'index', 'create', 'store', 'edit', 'update', 'destroy'
    ]]); //

    Route::get('/mytitles', 'TitleController@mytitles')->name('title.mytitles');
    Route::get('/wearTitle/{title}', 'TitleController@wear')->name('title.wear');
}

{ // ajax修改关注情况
    Route::post('/users/followers/{id}', 'FollowersController@store')->name('follower.store');// 关注某人 19.7.10

    Route::delete('/users/followers/{id}', 'FollowersController@destroy')->name('follower.destroy');// 取关某人 19.7.10

    Route::post('/followers/togglekeepupdate', 'FollowersController@togglekeepupdate')->name('follower.togglekeepupdate');//是否订阅动态更新提醒 ？？
}

{ // 以下是用户个人信息自己更改
    Route::get('/usercenter', 'UsersController@center')->name('user.center');//展示某用户的个人中心 19.7.11

    Route::get('/user/edit', 'UsersController@edit')->name('user.edit');//修改用户的个人信息整体界面 19.7.9

    Route::get('/user/send_email_confirmation', 'Auth\RegisterController@resend_email_confirmation')->name('email_confirmation.send'); // 重新发送当前用户的注册邮箱激活

    // 邮箱更新
    Route::get('/user/edit_email', 'UsersController@edit_email')->name('user.edit_email');//更改用户的邮箱信息 19.7.9
    Route::patch('/user/update_email', 'UsersController@update_email')->name('user.update_email');//更新用户的邮箱信息  19.7.9

    // 密码更新
    Route::get('/user/edit_password', 'UsersController@edit_password')->name('user.edit_password');//更改用户的密码信息  19.7.9
    Route::patch('/user/update_password', 'UsersController@update_password')->name('user.update_password');//更新用户的密码信息  19.7.9

    // 简介更新
    Route::get('/user/edit_introduction', 'UsersController@edit_introduction')->name('user.edit_introduction');//更改用户的个人简介
    Route::patch('/user/update_introduction', 'UsersController@update_introduction')->name('user.update_introduction');//更改用户的个人简介

    // 偏好更新
    Route::get('/user/edit_preference', 'UsersController@edit_preference')->name('user.edit_preference');//更改用户的使用偏好
    Route::patch('/user/update_preference', 'UsersController@update_preference')->name('user.update_preference');//更改用户的使用偏好
}

{//以下展示论坛贴按标签（label）与板块（channel）分布的视图
   Route::get('/channels/{channel}', 'ThreadsController@channel_index')->name('channel.show')->middleware('filter_channel');//展示某个板块的帖子目录 //7.9
}


{//以下是论坛主题目录模块
    Route::resource('threads', 'ThreadsController', ['only' => [
        'index', 'create', 'store', 'edit', 'update', 'destroy'
    ]]); //

     Route::get('/threads', 'ThreadsController@index')->name('threads.index');//查看全部的包含隐藏内容的thread

   Route::get('/thread_index', 'ThreadsController@thread_index')->name('threads.thread_index');//论坛全部帖子 19.7.9

   Route::get('/thread_jinghua', 'ThreadsController@thread_jinghua')->name('threads.thread_jinghua');//论坛精华帖列表 19.7.9
}

{ // 具体某个帖子：
    Route::get('/threads/{thread}/profile', 'ThreadsController@show_profile')->name('thread.show_profile')->middleware('filter_thread');//目录版首页，比如书籍首页。全部缓存 19.7.9

    Route::get('/threads/{thread}', 'ThreadsController@show')->name('thread.show')->middleware('filter_thread');//论坛版首页，条件过滤Post，没有则显示首页 19.7.9

    Route::get('/threads/{thread}/index', 'ThreadsController@component_index')->name('thread.component_index')->middleware('filter_thread');//看某个主题的内部目录 // 19.7.9？？

    Route::get('/threads/{thread}/chapter_index', 'ThreadsController@chapter_index')->name('thread.chapter_index')->middleware('filter_thread');//看某个主题的内部章节目录 // 19.7.9

     Route::get('/threads/{thread}/review_index', 'ThreadsController@review_index')->name('thread.review_index')->middleware('filter_thread');//看某个主题的内部书评目录 // 19.7.9
}

{ //发文发帖模块
    Route::post('/threads/{thread}/posts', 'PostsController@store')->name('post.store');//在某个主题发表回帖
}

//书评相关
{
    Route::get('/threads/{thread}/review/create', 'ReviewController@create')->name('review.create');
    Route::post('/threads/{thread}/review', 'ReviewController@store')->name('review.store');//存储书评
    Route::patch('/review/{review}', 'ReviewController@update')->name('review.update');//更新书评
    Route::get('/posts/{post}/turn_to_review', 'ReviewController@turn_to_review')->name('post.turn_to_review');
}


{ // 打赏
    Route::resource('reward', 'RewardController', ['only' => [
        'index', 'store', 'destroy'
    ]]); //
    Route::get('/posts/{post}/reward', 'PostsController@reward')->name('post.reward');
    Route::get('/reward_sent','RewardController@sent')->name('reward.sent');//我给出的打赏
    Route::get('/reward_received','RewardController@received')->name('reward.received');//我给出的打赏
}


{ // 打赏
    Route::resource('vote', 'VoteController', ['only' => [
        'index', 'destroy'
    ]]); //
    Route::get('storevote', 'VoteController@store')->name('vote.store');

    Route::get('/vote_sent','VoteController@sent')->name('vote.sent');//我给出的评票
    Route::get('/vote_received','VoteController@received')->name('vote.received');//我收到的评票
}


{//作业模块 TODO 全部需要重做
   Route::get('/homework/create', 'HomeworksController@create')->name('homework.create')->middleware('admin');//创建新作业活动
   Route::get('/homework/index', 'HomeworksController@index')->name('homework.index')->middleware('admin');//创建新作业活动
   Route::get('/homework/submit', 'HomeworksController@submit')->name('homework.submit');//交作业
   Route::post('/homework/store', 'HomeworksController@store')->name('homework.store')->middleware('admin');//储存新建立的作业活动

   Route::get('/homeworks/{homework}/sendreminderform', 'HomeworksController@sendreminderform')->name('homework.sendreminderform')->middleware('admin');//发送提醒通知表格
   Route::post('/homeworks/{homework}/sendreminder', 'HomeworksController@sendreminder')->name('homework.sendreminder')->middleware('admin');//发送提醒通知

   Route::get('/homeworks/{homework}/rewardsform', 'HomeworksController@rewardsform')->name('homework.rewardsform')->middleware('admin');//发送奖励表格
   Route::post('/homeworks/{homework}/rewards', 'HomeworksController@rewards')->name('homework.rewards')->middleware('admin');//发送奖励

   Route::get('/homeworks/{homework}', 'HomeworksController@show')->name('homework.show')->middleware('admin');//查看作业信息
   Route::post('/homeworks/{homework}/register', 'HomeworksController@register')->name('homework.register');//注册参加作业活动
   Route::get('/homeworks/{homework}/deactivate', 'HomeworksController@deactivate')->name('homework.deactivate')->middleware('admin');//结束作业活动
}

{//以下是图书／文章模块

    Route::resource('books', 'BooksController', ['only' => [
        'index', 'create', 'store', 'edit', 'update', 'show'
    ]]); //

    //修改书籍标签
    Route::get('books/{book}/edit_tag', 'BooksController@edit_tag')->name('books.edit_tag');
    Route::patch('books/{book}/edit_tag', 'BooksController@update_tag')->name('books.update_tag');

    //修改书籍文案信息
    Route::get('books/{book}/edit_profile', 'BooksController@edit_profile')->name('books.edit_profile');
    Route::patch('books/{book}/edit_profile', 'BooksController@update_profile')->name('books.update_profile');

    //修改书籍同人标记
    Route::get('books/{book}/edit_tongren', 'BooksController@edit_tongren')->name('books.edit_tongren');
    Route::patch('books/{book}/edit_tongren', 'BooksController@update_tongren')->name('books.update_tongren');

    // 章节
    Route::get('books/{book}/edit_chapter_index', 'BooksController@edit_chapter_index')->name('books.edit_chapter_index');
    Route::patch('books/{book}/update_chapter_index', 'BooksController@update_chapter_index')->name('books.update_chapter_index');

    Route::patch('chapter/{chapter}', 'ChapterController@update')->name('chapter.update');

    Route::resource('/thread/{thread}/chapter', 'ChapterController', ['only' => [
         'create', 'store'
    ]]); //
    Route::get('/posts/{post}/turn_to_chapter', 'ChapterController@turn_to_chapter')->name('post.turn_to_chapter');



}

{//以下是回帖模块
   Route::get('/thread-posts/{post}', 'ThreadsController@showpost')->name('thread.showpost');//展示某个主题贴下的特定回帖

   Route::get('/posts/{post}/edit', 'PostsController@edit')->name('post.edit');//更改已回复post，必须有权限

   Route::get('/posts/{post}/turn_to_post', 'PostsController@turn_to_post')->name('post.turn_to_post');//更改post成普通回帖

    Route::get('/posts/{post}/fold_by_owner', 'PostsController@fold_by_owner')->name('post.fold_by_owner');//更改post成普通回帖

   Route::get('/posts/{post}/delete_by_owner', 'PostsController@delete_by_owner')->name('post.delete_by_owner');

   Route::post('/posts/{post}/update', 'PostsController@update')->name('post.update');//更改帖子，必须有权限

   Route::delete('/posts/{post}', 'PostsController@destroy')->name('post.destroy');//删除已回复帖子，必须有权限

   Route::get('/posts/{post}/', 'PostsController@show')->name('post.show');//单独的回帖的主页 //7.9.19
}

{//以下是admin
   Route::get('/admin', 'AdminsController@index')->name('admin.index');//管理员管理界面

   Route::post('/admin/threadmanagement/{thread}','AdminsController@threadmanagement')->name('admin.threadmanagement');//管理员管理主题贴
    Route::get('/admin/threadform/{thread}','AdminsController@threadform')->name('admin.threadform');//进入管理主题贴页面

   Route::post('/admin/postmanagement/{post}','AdminsController@postmanagement')->name('admin.postmanagement');//管理员管理回帖
    Route::get('/admin/postform/{post}','AdminsController@postform')->name('admin.postform');//进入管理帖子页面

   Route::post('/admin/usermanagement/{user}','AdminsController@usermanagement')->name('admin.usermanagement');//管理员管理用户
   Route::get('/admin/userform/{user}','AdminsController@userform')->name('admin.userform');//进入管理用户页面

   Route::post('/admin/statusmanagement/{status}','AdminsController@statusmanagement')->name('admin.statusmanagement');//管理员管理动态

   Route::get('/admin/sendpublicnoticeform', 'AdminsController@sendpublicnoticeform')->name('admin.sendpublicnoticeform');//发送提醒通知表格
   Route::post('/admin/sendpublicnotice', 'AdminsController@sendpublicnotice')->name('admin.sendpublicnotice')->middleware('admin');//发送提醒通知

   Route::get('/admin/createtag', 'AdminsController@create_tag_form')->name('admin.createtag')->middleware('admin');//显示新建tag表格
   Route::post('/admin/createtag', 'AdminsController@store_tag')->name('admin.store_tag')->middleware('admin');//存储新tag


   Route::get('/admin/searchusersform', 'AdminsController@searchusersform')->name('admin.searchusersform');//审核是否存在某用户
   Route::get('/admin/searchusers', 'AdminsController@searchusers')->name('admin.searchusers');//审核是否存在某用户
}

{
    Route::get('/admin/invitation_token', 'InvitationTokensController@index')->name('invitation_tokens.index');//管理员查看邀请码列表
    Route::get('/admin/invitation_token/create', 'InvitationTokensController@create')->name('invitation_token.create');//管理员新建邀请码
    Route::post('/admin/invitation_token/store', 'InvitationTokensController@store')->name('invitation_token.store');//管理员储存邀请码
}

{//收藏模块

    Route::resource('collection', 'CollectionController', ['only' => [
        'index', 'update', 'destroy'
    ]]); //

    Route::get('/collection_clearupdates', 'CollectionController@clearupdates')->name('collection.clearupdates');//清零更新提醒

    Route::get('/threads/{thread}/collect', 'CollectionController@store')->name('collection.store')->middleware('filter_thread');//收藏某个主题帖


    Route::resource('collection_group', 'CollectionGroupController', ['only' => [
        'create', 'store', 'edit', 'update', 'destroy'
    ]]); //

}

{//消息提醒模块

    Route::get('/activity','ActivityController@index')->name('activity.index');//显示站内提醒列表

    Route::get('/message','MessageController@index')->name('message.index');//显示信箱

    Route::get('/message/sent','MessageController@sent')->name('message.sent');//显示已发送信件

    Route::get('/message/public_notice','MessageController@public_notice')->name('message.public_notice');//显示全部往期公共通知

    Route::get('/message/dialogue/{speaker}','MessageController@dialogue')->name('message.dialogue');//显示所有对话

    Route::get('/message/clearupdates','MessageController@clearupdates')->name('message.clearupdates');//清空所有提醒，都标记为已读

    Route::post('/message','MessageController@store')->name('message.store');

}
//动态微博模块
{
   Route::resource('status', 'StatusController', ['only' => [
       'index', 'show', 'store', 'destroy'
   ]]); //
   Route::get('/status_collection', 'StatusController@collection')->name('status.collection');//显示关注对象的所有动态

}

{//缓存模块
    Route::post('/cache/save', 'CachesController@save')->name('cache.save');
    Route::get('/cache/retrieve', 'CachesController@retrieve')->name('cache.retrieve');
    Route::get('/cache/initcache','CachesController@initcache')->name('cache.initcache');
}

//动态下载模块
{
    Route::get('/downloads/index/{thread}', 'DownloadsController@index')->name('download.index')->middleware('filter_thread');//要下载某个主题,必须首先有权限看这个帖子

}


// 答题测试模块 quiz
{
    Route::get('admin/quiz/review', 'QuizController@review')->name('quiz.review');//管理员查看所有的题目
    Route::get('admin/quiz/create', 'QuizController@create')->name('quiz.create');//管理员增加题目
    Route::post('admin/quiz/store', 'QuizController@store')->name('quiz.store');//管理员存储新题目
    Route::get('admin/quiz/{quiz}/edit','QuizController@edit')->name('quiz.edit');//管理员修改某题目
    Route::get('admin/quiz/{quiz}','QuizController@show')->name('quiz.show');//管理员修改某题目
    Route::post('admin/quiz/{quiz}/update','QuizController@update')->name('quiz.update');//管理员更新某题目
    Route::get('quiz/quiz_entry','QuizController@quiz_entry')->name('quiz.quiz_entry');//测试入口
    Route::get('quiz/taketest','QuizController@taketest')->name('quiz.taketest');//测试
    Route::post('quiz/submittest','QuizController@submittest')->name('quiz.submittest');//测试
}

{// faq 相关
    Route::get('help', 'FAQController@index')->name('help');//显示全站帮助(这个路径名字就不改了，涉及版面很多)
    Route::get('/admin/faq/create', 'FAQController@create')->name('faq.create');//管理员添加faq
    Route::post('/admin/faq', 'FAQController@store')->name('faq.store');//管理员存储新faq
    Route::get('/admin/faq/{faq}/edit', 'FAQController@edit')->name('faq.edit');//管理员修改aq
    Route::patch('/admin/faq/{faq}', 'FAQController@update')->name('faq.update');//管理员保存修改的faq

}
