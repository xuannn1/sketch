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
    // TODO 下面这些管理系列都还需要做
    Route::get('/linkedaccounts/create','LinkedAccountsController@create')->name('linkedaccounts.create');
    Route::post('/linkedaccounts/store','LinkedAccountsController@store')->name('linkedaccounts.store');
    Route::get('/linkedaccounts/switch/{id}','LinkedAccountsController@switch')->name('linkedaccounts.switch');
    Route::delete('/linkedaccounts/destroy/{id}','LinkedAccountsController@destroy')->name('linkedaccounts.destroy');
}

{//以下是静态页面模块

    Route::get('/', 'PagesController@home')->name('home');
    Route::get('about', 'PagesController@about')->name('about');
    // TODO Help写成FAQ形式，允许筛选和搜索
    Route::get('help', 'PagesController@help')->name('help');

    // TODO:  Contact写成动态情况，提供当前编辑管理列表和往期人员。这个完全不急，慢慢做。
    Route::get('contacts', 'PagesController@contacts')->name('contacts');

    // TODO 搜索重做
    Route::get('/search','PagesController@search')->name('search');

    // TODO 检查这个route是不是没有必要（是否只要使用abort就可以了？）
    // Route::get('error/{error_code}', 'PagesController@error')->name('error');

    Route::get('/administrationrecords', 'PagesController@administrationrecords')->name('administrationrecords');
    Route::get('/qiandao', 'UsersController@qiandao')->name('qiandao');//签到
    Route::get('/recommend_records', 'PagesController@recommend_records')->name('recommend_records');//普通用户查看推荐书籍历史
}

{//提头部分 TODO 待做
   Route::get('/quote/create', 'QuotesController@create')->name('quote.create');//贡献题头
   Route::post('/quote/create', 'QuotesController@store')->name('quote.store');//贡献题头
   Route::get('/quotes/review', 'AdminsController@quotesreview')->name('quotes.review');//审核题头
   Route::get('/quotes/{quote}/toggle_review/{quote_method}','AdminsController@toggle_review_quote')->name('quote.toggle_review');//通过题头
   Route::get('/quotes/{quote}/xianyu','QuotesController@xianyu')->name('quote.vote');//给题头投喂咸鱼
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
    Route::post('/user/update_email', 'UsersController@update_email')->name('user.update_email');//更新用户的邮箱信息  19.7.9

    // 密码更新
    Route::get('/user/edit_password', 'UsersController@edit_password')->name('user.edit_password');//更改用户的密码信息  19.7.9
    Route::post('/user/update_password', 'UsersController@update_password')->name('user.update_password');//更新用户的密码信息  19.7.9

    // 简介更新
    Route::get('/user/edit_introduction', 'UsersController@edit_introduction')->name('user.edit_introduction');//更改用户的个人简介
    Route::post('/user/update_introduction', 'UsersController@update_introduction')->name('user.update_introduction');//更改用户的个人简介
}

{//以下展示论坛贴按标签（label）与板块（channel）分布的视图
   Route::get('/channels/{channel}', 'ThreadsController@channel_index')->name('channel.show')->middleware('filter_channel');//展示某个板块的帖子目录 //7.9
}


{//以下是论坛主题目录模块
   Route::get('/threads', 'ThreadsController@index')->name('threads.index');//绝对复杂筛选所有thread？？

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


{ // 打赏
    Route::get('reward/create','RewardController@create')->name('reward.create');//新增打赏，还没具体做
}

{ // 评票
    Route::get('vote/create','VoteController@create')->name('vote.create');//新增评票，还没具体做
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
   Route::get('/book/create', 'BooksController@create')->name('book.create');//发表新的文章
   Route::post('/book/create', 'BooksController@store')->name('book.store');//发表新的文章
   Route::get('/books/{book}/edit', 'BooksController@edit')->name('book.edit');//修改文章
   Route::post('/books/{book}/update', 'BooksController@update')->name('book.update');//更新文章修改

   Route::get('/books/{book}', 'BooksController@show')->name('book.show');//查看某本书的目录 这个主要是用来做

   Route::get('/books', 'BooksController@index')->name('books.index');//看全部书 20190711

   Route::get('/tags', 'PagesController@tags')->name('all.tags');//全站标签列表 还没做
}

{//以下是回帖模块
   Route::get('/thread-posts/{post}', 'ThreadsController@showpost')->name('thread.showpost');//展示某个主题贴下的特定回帖

   Route::get('/posts/{post}/edit', 'PostsController@edit')->name('post.edit');//更改已回复post，必须有权限

   Route::post('/posts/{post}/update', 'PostsController@update')->name('post.update');//更改帖子，必须有权限

   Route::delete('/posts/{post}', 'PostsController@destroy')->name('post.destroy');//删除已回复帖子，必须有权限

   Route::get('/posts/{post}/', 'PostsController@show')->name('post.show');//单独的回帖的主页 //7.9.19
}

{//以下是admin
   Route::get('/admin', 'AdminsController@index')->name('admin.index');//管理员管理界面
   Route::post('/admin/threadmanagement/{thread}','AdminsController@threadmanagement')->name('admin.threadmanagement');//管理员管理主题贴
   Route::post('/admin/postmanagement/{post}','AdminsController@postmanagement')->name('admin.postmanagement');//管理员管理回帖
   Route::post('/admin/usermanagement/{user}','AdminsController@usermanagement')->name('admin.usermanagement');//管理员管理用户
   Route::post('/admin/postcommentmanagement/{postcomment}','AdminsController@postcommentmanagement')->name('admin.postcommentmanagement');//管理员管理点评
   Route::post('/admin/statusmanagement/{status}','AdminsController@statusmanagement')->name('admin.statusmanagement');//管理员管理动态

   Route::get('/admin/threadform/{thread}','AdminsController@threadform')->name('admin.threadform');//进入管理主题贴页面
   Route::get('/admin/statusform/{status}','AdminsController@statusform')->name('admin.statusform');//进入管理动态页面

   Route::get('/admin/sendpublicnoticeform', 'AdminsController@sendpublicnoticeform')->name('admin.sendpublicnoticeform')->middleware('admin');//发送提醒通知表格
   Route::post('/admin/sendpublicnotice', 'AdminsController@sendpublicnotice')->name('admin.sendpublicnotice')->middleware('admin');//发送提醒通知
   Route::get('/admin/createtag', 'AdminsController@create_tag_form')->name('admin.createtag')->middleware('admin');//显示新建tag表格
   Route::post('/admin/createtag', 'AdminsController@store_tag')->name('admin.store_tag')->middleware('admin');//发送提醒通知
   Route::get('/admin/longcomments', 'AdminsController@longcommentsreview')->name('admin.review_longcomments');//审核是否允许成为长评
   Route::get('/admin/searchusersform', 'AdminsController@searchusersform')->name('admin.searchusersform');//审核是否存在某用户
   Route::get('/admin/searchusers', 'AdminsController@searchusers')->name('admin.searchusers');//审核是否存在某用户
   Route::get('/admin/invitation_token', 'InvitationTokensController@index')->name('invitation_tokens.index');//管理员查看邀请码列表
   Route::get('/admin/invitation_token/create', 'InvitationTokensController@create')->name('invitation_token.create');//管理员新建邀请码
   Route::post('/admin/invitation_token/store', 'InvitationTokensController@store')->name('invitation_token.store');//管理员储存邀请码

}

{//收藏模块
   Route::get('/threads/{thread}/collection', 'CollectionsController@store')->name('collection.store')->middleware('filter_thread');//收藏某个主题帖
   Route::get('/collections/books', 'CollectionsController@books')->name('collections.books');//显示收藏夹内容（首先是书）
   Route::get('/collections/threads', 'CollectionsController@threads')->name('collections.threads');//显示收藏夹内容（其他讨论）

   Route::post('/collections/cancel', 'CollectionsController@cancel')->name('collection.cancel');//取消收藏某个主题帖
   Route::post('/collections/store', 'CollectionsController@storeitem')->name('collection.storeitem');//收藏某个主题帖

   Route::post('/collections/togglekeepupdate', 'CollectionsController@togglekeepupdate')->name('collection.togglekeepupdate');//是否订阅更新提醒
   Route::post('/collections/clearupdates', 'CollectionsController@clearupdates')->name('collection.clearupdates');//清零更新提醒
}

{//消息提醒模块
   Route::get('/messages/unread','MessagesController@unread')->name('messages.unread');
   Route::get('/messages/index','MessagesController@index')->name('messages.index');
   Route::get('/messages/messagebox','MessagesController@messagebox')->name('messages.messagebox');
   Route::get('/messages/messages','MessagesController@messages')->name('messages.messages');
   Route::get('/messages/messages_sent','MessagesController@messages_sent')->name('messages.messages_sent');
   Route::get('/messages/posts','MessagesController@posts')->name('messages.posts');
   Route::get('/messages/upvotes','MessagesController@upvotes')->name('messages.upvotes');
   Route::get('/messages/public_notices','MessagesController@public_notices')->name('messages.public_notices');
   Route::get('/messages/replies','MessagesController@replies')->name('messages.replies');
   Route::get('/messages/clear','MessagesController@clear')->name('messages.clear');
   Route::get('/messages/create/{user}','MessagesController@create')->name('messages.create');
   Route::get('/messages/conversation/{user}/{is_group_messaging}','MessagesController@conversation')->name('messages.conversation');
   Route::post('/messages/store/{user}','MessagesController@store')->name('messages.store');
}
//动态微博模块
{
   Route::resource('statuses', 'StatusesController', ['only' => [
       'index', 'store', 'destroy'
   ]]); //
   Route::get('/statuses/collections', 'StatusesController@collections')->name('statuses.collections');//显示关注对象的所有动态

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
    Route::get('quiz/taketest','QuizController@taketest')->name('quiz.taketest');//测试
    Route::post('quiz/submittest','QuizController@submittest')->name('quiz.submittest');//测试
}
