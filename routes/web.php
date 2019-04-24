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
   Route::get('register/confirm/{token}', 'Auth\RegisterController@confirmEmail')->name('confirm_email');//确认邮箱正确
    Route::get('/linkedaccounts','LinkedAccountsController@index')->name('linkedaccounts.index');
   Route::get('/linkedaccounts/create','LinkedAccountsController@create')->name('linkedaccounts.create');
   Route::post('/linkedaccounts/store','LinkedAccountsController@store')->name('linkedaccounts.store');
   Route::get('/linkedaccounts/switch/{id}','LinkedAccountsController@switch')->name('linkedaccounts.switch');
   Route::delete('/linkedaccounts/destroy/{id}','LinkedAccountsController@destroy')->name('linkedaccounts.destroy');
}

{//以下是静态页面模块
    Route::group(['middleware' => 'fw-block-blacklisted'], function ()
    {
    Route::get('/', 'PagesController@home')->name('home');
    });
    Route::get('about', 'PagesController@about')->name('about');
    Route::get('help', 'PagesController@help')->name('help');
    Route::get('contacts', 'PagesController@contacts')->name('contacts');
    Route::get('/search','PagesController@search')->name('search');
    Route::get('error/{error_code}', 'PagesController@error')->name('error');
    Route::get('/administrationrecords', 'PagesController@administrationrecords')->name('administrationrecords');
    Route::get('/qiandao', 'UsersController@qiandao')->name('qiandao');//签到
    Route::get('/recommend_records', 'PagesController@recommend_records')->name('recommend_records');//普通用户查看推荐书籍历史
}

{//提头部分
   Route::get('/quote/create', 'QuotesController@create')->name('quote.create');//贡献题头
   Route::post('/quote/create', 'QuotesController@store')->name('quote.store');//贡献题头
   Route::get('/quotes/review', 'AdminsController@quotesreview')->name('quotes.review');//审核题头
   Route::get('/quotes/{quote}/toggle_review/{quote_method}','AdminsController@toggle_review_quote')->name('quote.toggle_review');//通过题头
   Route::get('/quotes/{quote}/xianyu','QuotesController@xianyu')->name('quote.vote');//给题头投喂咸鱼
}

{//以下是用户信息展示模块
   Route::get('/users/{id}', 'UsersController@show')->name('user.show');//展示某用户的个人页面
   Route::get('users/{id}/threads','UsersController@showthreads')->name('user.showthreads');//展示某用户的全部主题贴
   Route::get('users/{id}/books','UsersController@showbooks')->name('user.showbooks');//展示某用户的全部文章
   Route::get('users/{id}/statuses','UsersController@showstatuses')->name('user.showstatuses');//展示某用户的全部动态
   Route::get('users/{id}/comments','UsersController@showcomments')->name('user.showcomments');//展示某用户的全部长评
   Route::get('/users/{id}/upvotes', 'UsersController@showupvotes')->name('user.showupvotes');
   Route::get('/users/{id}/xianyus', 'UsersController@showxianyus')->name('user.showxianyus');
   Route::get('/users/{id}/records', 'UsersController@showrecords')->name('user.showrecords')->middleware('admin');//展示某用户的全部管理记录
   //Route::get('/users/{id}/shengfans', 'UsersController@showshengfans')->name('user.showshengfans');
   Route::get('/users/{id}/followings', 'UsersController@followings')->name('users.followings');
   Route::get('/users/{id}/followers', 'UsersController@followers')->name('users.followers');
   Route::post('/users/followers/{id}', 'FollowersController@store')->name('followers.store');
   Route::delete('/users/followers/{id}', 'FollowersController@destroy')->name('followers.destroy');
   Route::post('/followers/togglekeepupdate', 'FollowersController@togglekeepupdate')->name('followers.togglekeepupdate');//是否订阅动态更新提醒
   Route::get('/users', 'UsersController@index')->name('users.index');//展示所有用户，按最后签到时间排序
   Route::get('/user/edit', 'UsersController@edit')->name('users.edit');//更改用户的个人信息
   Route::post('/user/update', 'UsersController@update')->name('users.update');//更新用户的个人信息
}

{//以下展示论坛贴按标签（label）与板块（channel）分布的视图
   Route::get('/channels', 'ChannelsController@index')->name('channel.show');//展示某个板块的所有帖子
   Route::get('/channels/{channel}', 'ChannelsController@show')->name('channel.show')->middleware('filter_channel');//展示某个板块的所有帖子
   Route::get('/channels/{channel}/threads/create', 'ThreadsController@createThreadForm')->name('thread.create')->middleware('filter_channel');//发布新主题页面
   Route::post('/channels/{channel}/threads/create','ThreadsController@store')->name('thread.store')->middleware('filter_channel');//在特定板块发表主题
}


{//以下是论坛主题模块
   Route::get('/threads', 'ThreadsController@index')->name('threads.index');//看全部主题
   Route::get('/threads/{thread}', 'ThreadsController@show')->name('thread.show')->middleware('filter_thread');//看某个主题,注意必须有权限
   Route::get('/threads/{thread}/edit', 'ThreadsController@edit')->name('thread.edit');
   Route::post('/threads/{thread}/update', 'ThreadsController@update')->name('thread.update');
   Route::post('/threads/{thread}/posts', 'PostsController@store')->name('post.store');//在某个主题发表回帖
   Route::get('/threads/{thread}/xianyu', 'XianyusController@vote')->name('xianyu.vote');//为主题投放咸鱼
}

{//作业模块
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
   Route::get('/books/{book}', 'BooksController@show')->name('book.show')->middleware('filter_book');//查看某本书的目录

   Route::get('/books/{book}/createchapter', 'ChaptersController@createChapterForm')->name('book.createchapter');//更新章节的表格
   Route::post('/books/{book}/storechapter', 'ChaptersController@store')->name('book.storechapter');//储存新章节
   Route::get('/chapters/{chapter}', 'ChaptersController@show')->name('book.showchapter')->middleware('filter_chapter');//展示章节
   Route::get('/chapters/{chapter}/edit', 'ChaptersController@edit')->name('book.editchapter');//编辑章节
   Route::post('chapters/{chapter}/update', 'ChaptersController@update')->name('book.updatechapter');//编辑章节
   Route::get('/books', 'BooksController@index')->name('books.index');//看全部书
   Route::get('/book-tag/{booktag}','BooksController@booktag')->name('books.booktag');//图书过滤-tag
   Route::get('/bookselector/{bookquery}','BooksController@selector')->name('books.selector');//图书过滤
   Route::post('/book-filter','BooksController@filter')->name('books.filter');//输入过滤信息表格
   Route::get('/tags', 'BooksController@tags')->name('book.tags');//全站标签列表
   Route::get('/selector', 'BooksController@bookselector')->name('book.selector');//全站详细搜索
}

{//以下是回帖模块
   Route::get('/thread-posts/{post}', 'ThreadsController@showpost')->name('thread.showpost')->middleware('filter_post');//展示某个主题贴下的特定回帖
   Route::get('/posts/{post}/edit', 'PostsController@edit')->name('post.edit');//更改已回复主题，必须有权限
   Route::post('/posts/{post}/update', 'PostsController@update')->name('post.update');//更改帖子，必须有权限
   Route::delete('/posts/{post}', 'PostsController@destroy')->name('post.destroy');//删除已回复帖子，必须有权限
   Route::post('/posts/{post}/comments', 'PostCommentsController@store')->name('postcomment.store');//对某个回帖发点评
   Route::delete('/postcomments/{postcomment}', 'PostCommentsController@destroy')->name('postcomment.destroy');//对某个回帖发点评
   Route::get('/posts/{post}/', 'PostsController@show')->name('post.show')->middleware('filter_post');//查看某个回帖
   Route::get('/posts/{post}/shengfan', 'ShengfansController@vote_post')->name('shengfan.vote_post');//为回帖投剩饭；
   Route::get('/posts/{post}/shengfan-index', 'ShengfansController@index')->name('shengfan.index');//显示本条信息下所有剩饭投喂情况；

   Route::get('/posts/{post}/upvote','VotePostsController@upvote')->name('voteposts.upvote');//为回帖投票赞
   Route::get('/posts/{post}/downvote','VotePostsController@downvote')->name('voteposts.downvote');//为回帖投票踩
   Route::get('/posts/{post}/funny','VotePostsController@funny')->name('voteposts.funny');//为回帖投票踩
   Route::get('/posts/{post}/fold','VotePostsController@fold')->name('voteposts.fold');//为回帖投票踩
   Route::get('/longcomments', 'LongCommentsController@index')->name('longcomments.index');
   Route::get('/posts/{post}/toggle_review/{longcomment_method}','AdminsController@toggle_review_longcomment')->name('longcomment.toggle_review');//通过长评
}

{//以下是admin
   Route::get('/admin', 'AdminsController@index')->name('admin.index');//管理员管理界面
   Route::post('/admin/threadmanagement/{thread}','AdminsController@threadmanagement')->name('admin.threadmanagement');//管理员管理主题贴
   Route::post('/admin/postmanagement/{post}','AdminsController@postmanagement')->name('admin.postmanagement');//管理员管理回帖
   Route::post('/admin/usermanagement/{user}','AdminsController@usermanagement')->name('admin.usermanagement');//管理员管理用户
   Route::post('/admin/postcommentmanagement/{postcomment}','AdminsController@postcommentmanagement')->name('admin.postcommentmanagement');//管理员管理点评
   Route::post('/admin/statusmanagement/{status}','AdminsController@statusmanagement')->name('admin.statusmanagement');//管理员管理动态
   Route::get('/admin/advancedthreadform/{thread}','AdminsController@advancedthreadform')->name('admin.advancedthreadform');//高级管理主题贴页面
   Route::get('/admin/sendpublicnoticeform', 'AdminsController@sendpublicnoticeform')->name('admin.sendpublicnoticeform')->middleware('admin');//发送提醒通知表格
   Route::post('/admin/sendpublicnotice', 'AdminsController@sendpublicnotice')->name('admin.sendpublicnotice')->middleware('admin');//发送提醒通知
   Route::get('/admin/createtag', 'AdminsController@create_tag_form')->name('admin.createtag')->middleware('admin');//显示新建tag表格
   Route::post('/admin/createtag', 'AdminsController@store_tag')->name('admin.store_tag')->middleware('admin');//发送提醒通知
   Route::get('/admin/longcomments', 'AdminsController@longcommentsreview')->name('admin.review_longcomments');//审核是否允许成为长评
   Route::get('/admin/searchusersform', 'AdminsController@searchusersform')->name('admin.searchusersform');//审核是否存在某用户
   Route::get('/admin/searchusers', 'AdminsController@searchusers')->name('admin.searchusers');//审核是否存在某用户

}

{//收藏模块
   Route::get('/threads/{thread}/collection', 'CollectionsController@store')->name('collection.store')->middleware('filter_thread');//收藏某个主题帖
   Route::get('/collections/books', 'CollectionsController@books')->name('collections.books');//显示收藏夹内容（首先是书）
   Route::get('/collections/threads', 'CollectionsController@threads')->name('collections.threads');//显示收藏夹内容（其他讨论）
   Route::get('/collections/statuses', 'CollectionsController@statuses')->name('collections.statuses');//显示收藏夹内容（用户动态）
   Route::get('/collections/collection_lists', 'CollectionsController@collection_lists')->name('collections.collection_lists');//显示收藏夹内容（收藏列表中的更新）
   Route::post('/collections/cancel', 'CollectionsController@cancel')->name('collection.cancel');//取消收藏某个主题帖
   Route::post('/collections/store', 'CollectionsController@storeitem')->name('collection.storeitem');//收藏某个主题帖
   Route::post('/collections/{collection}/store_comment', 'CollectionsController@store_comment')->name('collection.store_comment');//增加对某个收藏的评论
   Route::post('/collections/togglekeepupdate', 'CollectionsController@togglekeepupdate')->name('collection.togglekeepupdate');//是否订阅更新提醒
   Route::post('/collections/clearupdates', 'CollectionsController@clearupdates')->name('collection.clearupdates');//清零更新提醒

   Route::get('/collections/collection_lists/create', 'CollectionsController@collection_list_create')->name('collections.collection_list_create');//新增收藏夹
   Route::post('/collections/collection_lists', 'CollectionsController@collection_list_store')->name('collections.collection_list_store');//储存新收藏夹
   Route::get('/collections/collection_lists/{collection_list}', 'CollectionsController@collection_list_show')->name('collections.collection_list_show');//展示收藏单内容
   Route::get('/collections/collection_lists/{collection_list}/edit', 'CollectionsController@collection_list_edit')->name('collections.collection_list_edit');//修改收藏单内容
   Route::patch('/collections/collection_lists/{collection_list}', 'CollectionsController@collection_list_update')->name('collections.collection_list_update');//展示收藏单内容
   Route::get('/collection_lists', 'CollectionsController@all_collection_index')->name('collection_lists.index');
}

{//消息提醒模块
   Route::get('/messages/unread','MessagesController@unread')->name('messages.unread');
   Route::get('/messages/index','MessagesController@index')->name('messages.index');
   Route::get('/messages/messagebox','MessagesController@messagebox')->name('messages.messagebox');
   Route::get('/messages/messages','MessagesController@messages')->name('messages.messages');
   Route::get('/messages/messages_sent','MessagesController@messages_sent')->name('messages.messages_sent');
   Route::get('/messages/posts','MessagesController@posts')->name('messages.posts');
   Route::get('/messages/postcomments','MessagesController@postcomments')->name('messages.postcomments');
   Route::get('/messages/upvotes','MessagesController@upvotes')->name('messages.upvotes');
   Route::get('/messages/public_notices','MessagesController@public_notices')->name('messages.public_notices');
   Route::get('/messages/replies','MessagesController@replies')->name('messages.replies');
   Route::get('/messages/clear','MessagesController@clear')->name('messages.clear');
   Route::get('/messages/create/{user}','MessagesController@create')->name('messages.create');
   Route::get('/messages/conversation/{user}/{is_group_messaging}','MessagesController@conversation')->name('messages.conversation');
   Route::post('/messages/store/{user}','MessagesController@store')->name('messages.store');
   Route::get('/messages/receivemessagesfromstrangers','MessagesController@receivemessagesfromstrangers')->name('messages.receivemessagesfromstrangers');
   Route::get('/messages/cancelreceivemessagesfromstrangers','MessagesController@cancelreceivemessagesfromstrangers')->name('messages.cancelreceivemessagesfromstrangers');
   Route::get('/messages/receiveupvotereminders','MessagesController@receiveupvotereminders')->name('messages.receiveupvotereminders');
   Route::get('/messages/cancelreceiveupvotereminders','MessagesController@cancelreceiveupvotereminders')->name('messages.cancelreceiveupvotereminders');

}
//动态微博模块
{
   Route::resource('statuses', 'StatusesController', ['only' => [
       'index', 'store', 'destroy'
   ]]);
   Route::get('/statuses/collections', 'StatusesController@collections')->name('statuses.collections');//显示关注对象的所有动态
   Route::post('/cache/save', 'CachesController@save')->name('cache.save');
   Route::get('/cache/retrieve', 'CachesController@retrieve')->name('cache.retrieve');
   Route::get('/cache/initcache','CachesController@initcache')->name('cache.initcache');
}

//动态下载模块
{
    Route::get('/downloads/index/{thread}', 'DownloadsController@index')->name('download.index')->middleware('filter_thread');//下载某个主题,注意必须有权限
   Route::get('/downloads/thread_txt/{thread}','DownloadsController@thread_txt')->name('download.thread_txt')->middleware('filter_thread');
   Route::get('/downloads/book_noreview_text/{thread}','DownloadsController@book_noreview_text')->name('download.book_noreview_text')->middleware('filter_thread');

}
    Route::resource('polls', 'PollsController');

//留言箱功能
{
    Route::get('/users/{user}/questions/create','QuestionsController@create')->name('questions.create');//加载新建问题页面
    Route::post('/users/{user}/questions','QuestionsController@store')->name('questions.store');//储存问题路径
    Route::get('/users/{user}/questions', 'QuestionsController@index')->name('questions.index');//问题列表
    Route::post('/users/{user}/questions/{question}/answer','QuestionsController@answer')->name('questions.answer');//储存回答路径
}

//推荐书籍功能
{
    Route::get('/recommend_books/create', 'RecommendBooksController@create')->name('recommend_books.create');//添加推荐书籍
    Route::post('/recommend_books/store', 'RecommendBooksController@store')->name('recommend_books.store');//储存推荐书籍
    Route::get('/recommend_books/index', 'RecommendBooksController@index')->name('recommend_books.index');//查看推荐书籍
    Route::get('/recommend_books/recommend_longcomments', 'RecommendBooksController@longcomments')->name('recommend_books.longcomments');//查看推荐长评
    Route::get('/recommend_books/{recommend_book}', 'RecommendBooksController@show')->name('recommend_books.show');//推荐书籍信息
    Route::get('/recommend_books/{recommend_book}/edit', 'RecommendBooksController@edit')->name('recommend_books.edit');//修改书籍信息
    Route::post('/recommend_books/{recommend_book}/update', 'RecommendBooksController@update')->name('recommend_books.update');//保存修改
}
