<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Label;
use App\Thread;
use App\Book;
use App\Post;
use App\Chapter;
use App\Tag;
use Carbon\Carbon;
use App\Tongren;
use Auth;

class BooksController extends Controller
{
   public function __construct()
   {
     $this->middleware('auth')->only('create', 'store', 'update','edit');
   }

   public function create()
   {
     $book= new Book;
     $book->id = 0;
     $book->book_status = '0';
     $book->book_length = '0';
     $book->sexual_orientation = '0';
     $book->book_status = '0';
     $book->indentation = true;
     $thread =new Thread;
     $thread->bianyuan = 3;
     $thread->public = 1;
     $thread->noreply = false;
     $thread->body=null;
     $thread->brief=null;
     $thread->title=null;
     $thread->tags = [];
     $thread->id = 0;
     $thread->download_as_thread = true;
     $thread->download_as_book = true;
     $mainpost = new Post;
     $mainpost->markdown = false;
     $mainpost->indentation = true;
     $tongren = new Tongren;
     $tongren->tongren_yuanzhu = '';
     $tongren->tongren_cp = '';
     return view('books/create',compact('book', 'thread','mainpost','tongren'));
   }

   public function labels_validation($originalornot, $label){
      $expected_channel_id = 2-(int)$originalornot;
     return (Label::find($label)->channel->id==$expected_channel_id);

   }
   public function tags_validation($bianyuan, $tags){
      if (count($tags)>3){
         return (false);
      }
      if (!$bianyuan){
         foreach ($tags as $tag)
         {
            if (Tag::find($tag)->tag_group == 5){
               return (false);
            }
         }
      }
      return (true);
  }
   public function store(Request $request)
   {
      $user = Auth::user();
      $this->validate($request, [
          'title' => 'required|string|max:20',
          'brief' => 'required|string|max:25',
          'wenan' => 'required',
          'originalornot' => 'required',
          'label' => 'required',
          'book_status' => 'required|numeric|max:3',
          'book_length' => 'required|numeric|max:3',
          'sexual_orientation' => 'required|numeric|max:7',
          'bianyuan' =>'required',
          'majia' => 'string|max:10',
       ]);
      if((! $this->labels_validation(request('originalornot'), request('label')))||((! $this->tags_validation(request('bianyuan'), request('tags'))))){
         return redirect()->route('error', ['error_code' => '409']);
      }//检验标签与原创性是否对应，检验tag与边缘题材选项是否符合
      if (!request('originalornot')){//选择了同人作品，那么检验是否填写了同人相关内容
         $this->validate($request, [
             'original_work' => 'required|string',
             'tongren_cp' => 'required|string',
         ]);
      }
      $anonymous = request('anonymous')? true: false;
      $majia = request('anonymous')? request('majia'):null;
      $bianyuan = request('bianyuan')? true: false;
      $original = request('originalornot')? true: false;
      $markdown = request('markdown')? true: false;
      $indentation = request('indentation')? true: false;
      $download_as_thread = request('download_as_thread')? true: false;
      $download_as_book = request('download_as_book')? true: false;
      $thread = Thread::create([
         'title' => request('title'),
         'brief' => request('brief'),
         'body' => request('wenan'),
         'bianyuan' => $bianyuan,
         'channel_id' => 2-$original,
         'user_id' => $user->id,
         'lastresponded_at' => Carbon::now(),
         'label_id' => request('label'),
         'anonymous' => $anonymous,
         'majia' => $majia,
         'download_as_thread' => $download_as_thread,
         'download_as_book' => $download_as_book,
      ]);
      $post = Post::create([
         'user_id' => $user->id,
         'body' => null,
         'thread_id' => $thread->id,
         'markdown' => $markdown,
      ]);
      $book = Book::create([
         'thread_id' => $thread->id,
         'original' => $original,
         'book_status' => request('book_status'),
         'book_length' => request('book_length'),
         'sexual_orientation' => request('sexual_orientation'),
         'lastaddedchapter_at' => Carbon::now(),
         'indentation' => $indentation,
      ]);
      if (!$original){//选择了同人作品，那么检验是否填写了同人相关内容
         $tongren = Tongren::create([
            'book_id' => $book->id,
            'tongren_yuanzhu' => request('original_work'),
            'tongren_cp' => request('tongren_cp'),
         ]);
      }
      $user->update(['majia'=>$majia]);
      $thread->addTags(request('tags'));
      $thread->update([
         'post_id' => $post->id,
         'book_id' => $book->id,
      ]);
      $user->jifen+=10;
      $user->shengfan+=10;
      $user->xianyu+=5;
      $user->sangdian+=1;
      $user->save();
      return redirect()->route('book.show', $book->id)->with("success", "您已成功发布文章");
   }
   public function edit(Book $book)
   {
      $thread = $book->thread;
      $mainpost = $thread->mainpost;
      $tongren = $book->tongren;
     return view('books.edit', compact('book', 'thread','mainpost','tongren'));
   }
   public function update(Request $request, Book $book)
   {
      $thread = $book->thread;
      if ((Auth::id() == $book->thread->user_id)&&(!$thread->locked)){
         $this->validate($request, [
             'title' => 'required|string|max:15',
             'brief' => 'required|string|max:25',
             'wenan' => 'required',
             'originalornot' => 'required',
             'label' => 'required',
             'book_status' => 'required|numeric|max:3',
             'book_length' => 'required|numeric|max:3',
             'sexual_orientation' => 'required|numeric|max:7',
             'bianyuan' =>'required',
             'majia' => 'string|max:10',
         ]);
         if((! $this->labels_validation(request('originalornot'), request('label')))||((! $this->tags_validation(request('bianyuan'), request('tags'))))){
            return redirect()->route('error', ['error_code' => '409']);
         }//检验标签与原创性是否对应，检验tag与边缘题材选项是否符合

         if (!request('originalornot')){//选择了同人作品，那么检验是否填写了同人相关内容
            $this->validate($request, [
                'original_work' => 'required|string',
                'tongren_cp' => 'required|string',
            ]);
         }
         $channel_id = 2-(int)request('originalornot');
         $anonymous = request('anonymous')? true:false;
         $public = request('public')? true:false;
         $noreply = request('noreply')? true:false;
         $markdown = request('markdown')? true: false;
         $indentation = request('indentation')? true: false;
         $download_as_thread = request('download_as_thread')? true: false;
         $download_as_book = request('download_as_book')? true: false;
         $thread->update([
            'title' => request('title'),
            'brief' => request('brief'),
            'body' => request('wenan'),
            'bianyuan' => request('bianyuan'),
            'channel_id' => $channel_id,
            'label_id' => request('label'),
            'anonymous' => $anonymous,
            'public' => $public,
            'noreply' => $noreply,
            'edited_at' => Carbon::now(),
            'download_as_thread' => $download_as_thread,
            'download_as_book' => $download_as_book,
         ]);
         $thread->deleteTags();
         $thread->addTags(request('tags'));
         $book->update([
            'book_status' => request('book_status'),
            'book_length' => request('book_length'),
            'sexual_orientation' => request('sexual_orientation'),
            'indentation' => $indentation,
         ]);
         if(!$book->original){
            $tongren = $book->tongren();
            $tongren->update([
               'tongren_yuanzhu' => request('original_work'),
               'tongren_cp' => request('tongren_cp'),
            ]);
         }
         $post = $thread->mainpost;
         $post->update([
           'markdown'=>$markdown,
         ]);
         return redirect()->route('book.show', $book->id)->with("success", "您已成功修改文章");
      }else{
         return redirect()->route('error', ['error_code' => '405']);
      }
   }

   public function show(Book $book, Request $request)
   {
      $chapters = DB::table('chapters')
      ->join('posts','chapters.post_id','=','posts.id')
      ->where('chapters.book_id','=',$book->id)
      ->where('posts.deleted_at','=',null)
      ->select('chapters.*','posts.title as brief')
      ->orderBy('chapters.chapter_order')
      ->get();
      $thread=$book->thread->load(['creator', 'tags', 'label', 'mainpost.comments.owner']);
      $thread->increment('viewed');
      $posts = Post::where([
         ['thread_id', '=', $thread->id],
         ['maintext', '=', false],
         ['id', '<>', $thread->post_id]
         ])->with(['owner', 'comments.owner', 'reply_to_post.owner','chapter.mainpost'])
         ->latest()
         ->paginate(Config::get('constants.index_per_part'));
      $ip = $request->getClientIp();
      $only = false;
      $chapter_replied = true;
      $book_info = Config::get('constants.book_info');
      return view('books.show', compact('book', 'thread','chapters', 'posts', 'ip', 'only', 'book_info','chapter_replied'));
   }
   public function index()
   {
      if (Auth::check()){
         $books = DB::table('threads')
            ->join('books', 'threads.book_id', '=', 'books.id')
            ->join('users', 'threads.user_id', '=', 'users.id')
            ->join('labels', 'threads.label_id', '=', 'labels.id')
            ->leftjoin('chapters','books.last_chapter_id','=', 'chapters.id')
            ->where([['threads.deleted_at', '=', null],['threads.public','=',1]])
            ->select('books.*', 'threads.*', 'users.name','labels.labelname','chapters.title as last_chapter_title')
            ->orderby('books.lastaddedchapter_at', 'desc')
            ->paginate(Config::get('constants.index_per_page'));
      }else{
         $books = DB::table('threads')
            ->join('books', 'threads.book_id', '=', 'books.id')
            ->join('users', 'threads.user_id', '=', 'users.id')
            ->join('labels', 'threads.label_id', '=', 'labels.id')
            ->leftjoin('chapters','books.last_chapter_id','=', 'chapters.id')
            ->where([['threads.deleted_at', '=', null],['threads.bianyuan', '=', false],['threads.public','=',1]])
            ->select('books.*', 'threads.*', 'users.name','labels.labelname', 'chapters.title as last_chapter_title')
            ->orderby('books.lastaddedchapter_at', 'desc')
            ->paginate(Config::get('constants.index_per_page'));
      }
      $collections = false;
      $book_info = Config::get('constants.book_info');
      return view('books.index', compact('books','book_info','collections'));
   }

   public function selector($bookquery)
   {
      $bookquery=explode('-',$bookquery);
      $bookinfo=[];
      foreach($bookquery as $info){
         array_push($bookinfo,array_map('intval',explode('_',$info)));
      }
      if (Auth::check()){
         $books = DB::table('threads')
            ->join('books', 'threads.book_id', '=', 'books.id')
            ->join('users', 'threads.user_id', '=', 'users.id')
            ->join('labels', 'threads.label_id', '=', 'labels.id')
            ->leftjoin('chapters','books.last_chapter_id','=', 'chapters.id')
            ->where([['threads.deleted_at', '=', null],['threads.public','=',1]])
            ->whereIn('books.original',$bookinfo[0])
            ->whereIn('books.book_length',$bookinfo[1])
            ->whereIn('books.book_status',$bookinfo[2])
            ->whereIn('books.sexual_orientation',$bookinfo[3])
            ->select('books.*', 'threads.*', 'users.name','labels.labelname', 'chapters.title as last_chapter_title')
            ->distinct()
            ->orderby('books.lastaddedchapter_at', 'desc')
            ->paginate(Config::get('constants.index_per_page'));
      }else{
         $books = DB::table('threads')
            ->join('books', 'threads.book_id', '=', 'books.id')
            ->join('users', 'threads.user_id', '=', 'users.id')
            ->join('labels', 'threads.label_id', '=', 'labels.id')
            ->leftjoin('chapters','books.last_chapter_id','=', 'chapters.id')
            ->where([['threads.deleted_at', '=', null],['threads.bianyuan', '=', false],['threads.public','=',1]])
            ->whereIn('books.original',$bookinfo[0])
            ->whereIn('books.book_length',$bookinfo[1])
            ->whereIn('books.book_status',$bookinfo[2])
            ->whereIn('books.sexual_orientation',$bookinfo[3])
            ->select('books.*', 'threads.*', 'users.name','labels.labelname', 'chapters.title as last_chapter_title')
            ->distinct()
            ->orderby('books.lastaddedchapter_at', 'desc')
            ->paginate(Config::get('constants.index_per_page'));
      }
      $collections = false;
      $book_info = Config::get('constants.book_info');
      return view('books.index', compact('books','book_info','collections'));
   }
   public function bookoriginal($original){
      if (Auth::check()){
         $books = DB::table('threads')
            ->join('books', 'threads.book_id', '=', 'books.id')
            ->join('users', 'threads.user_id', '=', 'users.id')
            ->join('labels', 'threads.label_id', '=', 'labels.id')
            ->leftjoin('chapters','books.last_chapter_id','=', 'chapters.id')
            ->where([['threads.deleted_at', '=', null],['threads.public','=',1],['books.original','=',$original]])
            ->select('books.*', 'threads.*', 'users.name','labels.labelname', 'chapters.title as last_chapter_title')
            ->orderby('books.lastaddedchapter_at', 'desc')
            ->paginate(Config::get('constants.index_per_page'));
      }else{
         $books = DB::table('threads')
            ->join('books', 'threads.book_id', '=', 'books.id')
            ->join('users', 'threads.user_id', '=', 'users.id')
            ->join('labels', 'threads.label_id', '=', 'labels.id')
            ->leftjoin('chapters','books.last_chapter_id','=', 'chapters.id')
            ->where([['threads.deleted_at', '=', null],['threads.bianyuan', '=', false],['threads.public','=',1],['books.original','=',$original]])
            ->select('books.*', 'threads.*', 'users.name','labels.labelname', 'chapters.title as last_chapter_title')
            ->orderby('books.lastaddedchapter_at', 'desc')
            ->paginate(Config::get('constants.index_per_page'));
      }
      $book_info = Config::get('constants.book_info');
      $collections = false;
      return view('books.index', compact('books','book_info','collections'));
   }
   public function bookstatus($bookstatus){
      if (Auth::check()){
         $books = DB::table('threads')
            ->join('books', 'threads.book_id', '=', 'books.id')
            ->join('users', 'threads.user_id', '=', 'users.id')
            ->join('labels', 'threads.label_id', '=', 'labels.id')
            ->leftjoin('chapters','books.last_chapter_id','=', 'chapters.id')
            ->where([['threads.deleted_at', '=', null],['threads.public','=',1],['books.book_status','=',$bookstatus]])
            ->select('books.*', 'threads.*', 'users.name','labels.labelname', 'chapters.title as last_chapter_title')
            ->orderby('books.lastaddedchapter_at', 'desc')
            ->paginate(Config::get('constants.index_per_page'));
      }else{
         $books = DB::table('threads')
            ->join('books', 'threads.book_id', '=', 'books.id')
            ->join('users', 'threads.user_id', '=', 'users.id')
            ->join('labels', 'threads.label_id', '=', 'labels.id')
            ->leftjoin('chapters','books.last_chapter_id','=', 'chapters.id')
            ->where([['threads.deleted_at', '=', null],['threads.bianyuan', '=', false],['threads.public','=',1],['books.book_status','=',$bookstatus]])
            ->select('books.*', 'threads.*', 'users.name','labels.labelname', 'chapters.title as last_chapter_title')
            ->orderby('books.lastaddedchapter_at', 'desc')
            ->paginate(Config::get('constants.index_per_page'));
      }
      $book_info = Config::get('constants.book_info');
      $collections = false;
      return view('books.index', compact('books','book_info','collections'));
   }
   public function booklength($booklength){
      if (Auth::check()){
         $books = DB::table('threads')
            ->join('books', 'threads.book_id', '=', 'books.id')
            ->join('users', 'threads.user_id', '=', 'users.id')
            ->join('labels', 'threads.label_id', '=', 'labels.id')
            ->leftjoin('chapters','books.last_chapter_id','=', 'chapters.id')
            ->where([['threads.deleted_at', '=', null],['threads.public','=',1],['books.book_length','=',$booklength]])
            ->select('books.*', 'threads.*', 'users.name','labels.labelname', 'chapters.title as last_chapter_title')
            ->orderby('books.lastaddedchapter_at', 'desc')
            ->paginate(Config::get('constants.index_per_page'));
      }else{
         $books = DB::table('threads')
            ->join('books', 'threads.book_id', '=', 'books.id')
            ->join('users', 'threads.user_id', '=', 'users.id')
            ->join('labels', 'threads.label_id', '=', 'labels.id')
            ->leftjoin('chapters','books.last_chapter_id','=', 'chapters.id')
            ->where([['threads.deleted_at', '=', null],['threads.bianyuan', '=', false],['threads.public','=',1],['books.book_length','=',$booklength]])
            ->select('books.*', 'threads.*', 'users.name','labels.labelname', 'chapters.title as last_chapter_title')
            ->orderby('books.lastaddedchapter_at', 'desc')
            ->paginate(Config::get('constants.index_per_page'));
      }
      $book_info = Config::get('constants.book_info');
      $collections = false;
      return view('books.index', compact('books','book_info','collections'));
   }
   public function booksexualorientation($booksexualorientation){
      if (Auth::check()){
         $books = DB::table('threads')
            ->join('books', 'threads.book_id', '=', 'books.id')
            ->join('users', 'threads.user_id', '=', 'users.id')
            ->join('labels', 'threads.label_id', '=', 'labels.id')
            ->leftjoin('chapters','books.last_chapter_id','=', 'chapters.id')
            ->where([['threads.deleted_at', '=', null],['threads.public','=',1],['books.sexual_orientation','=',$booksexualorientation]])
            ->select('books.*', 'threads.*', 'users.name','labels.labelname', 'chapters.title as last_chapter_title')
            ->orderby('books.lastaddedchapter_at', 'desc')
            ->paginate(Config::get('constants.index_per_page'));
      }else{
         $books = DB::table('threads')
            ->join('books', 'threads.book_id', '=', 'books.id')
            ->join('users', 'threads.user_id', '=', 'users.id')
            ->join('labels', 'threads.label_id', '=', 'labels.id')
            ->leftjoin('chapters','books.last_chapter_id','=', 'chapters.id')
            ->where([['threads.deleted_at', '=', null],['threads.bianyuan', '=', false],['threads.public','=',1],['books.sexual_orientation','=',$booksexualorientation]])
            ->select('books.*', 'threads.*', 'users.name','labels.labelname', 'chapters.title as last_chapter_title')
            ->orderby('books.lastaddedchapter_at', 'desc')
            ->paginate(Config::get('constants.index_per_page'));
      }
      $book_info = Config::get('constants.book_info');
      $collections = false;
      return view('books.index', compact('books','book_info','collections'));
   }
   public function booklabel($booklabel){
      if (Auth::check()){
         $books = DB::table('threads')
            ->join('books', 'threads.book_id', '=', 'books.id')
            ->join('users', 'threads.user_id', '=', 'users.id')
            ->join('labels', 'threads.label_id', '=', 'labels.id')
            ->leftjoin('chapters','books.last_chapter_id','=', 'chapters.id')
            ->where([['threads.deleted_at', '=', null],['threads.public','=',1],['threads.label_id','=',$booklabel]])
            ->select('books.*', 'threads.*', 'users.name','labels.labelname', 'chapters.title as last_chapter_title')
            ->orderby('books.lastaddedchapter_at', 'desc')
            ->paginate(Config::get('constants.index_per_page'));
      }else{
         $books = DB::table('threads')
            ->join('books', 'threads.book_id', '=', 'books.id')
            ->join('users', 'threads.user_id', '=', 'users.id')
            ->join('labels', 'threads.label_id', '=', 'labels.id')
            ->leftjoin('chapters','books.last_chapter_id','=', 'chapters.id')
            ->where([['threads.deleted_at', '=', null],['threads.bianyuan', '=', false],['threads.public','=',1],['threads.label_id','=',$booklabel]])
            ->select('books.*', 'threads.*', 'users.name','labels.labelname', 'chapters.title as last_chapter_title')
            ->orderby('books.lastaddedchapter_at', 'desc')
            ->paginate(Config::get('constants.index_per_page'));
      }
      $book_info = Config::get('constants.book_info');
      $collections = false;
      return view('books.index', compact('books','book_info','collections'));
   }
   public function booktag($booktag){
      if (Auth::check()){
         $books = DB::table('threads')
            ->join('books', 'threads.book_id', '=', 'books.id')
            ->join('users', 'threads.user_id', '=', 'users.id')
            ->join('labels', 'threads.label_id', '=', 'labels.id')
            ->join('tagging_threads','threads.id','=','tagging_threads.thread_id')
            ->leftjoin('chapters','books.last_chapter_id','=', 'chapters.id')
            ->where([['threads.deleted_at', '=', null],['threads.public','=',1],['tagging_threads.tag_id','=',$booktag]])
            ->select('books.*', 'threads.*', 'users.name','labels.labelname', 'chapters.title as last_chapter_title')
            ->orderby('books.lastaddedchapter_at', 'desc')
            ->paginate(Config::get('constants.index_per_page'));
      }else{
         $books = DB::table('threads')
            ->join('books', 'threads.book_id', '=', 'books.id')
            ->join('users', 'threads.user_id', '=', 'users.id')
            ->join('labels', 'threads.label_id', '=', 'labels.id')
            ->join('tagging_threads','threads.id','=','tagging_threads.thread_id')
            ->leftjoin('chapters','books.last_chapter_id','=', 'chapters.id')
            ->where([['threads.deleted_at', '=', null],['threads.bianyuan', '=', false],['threads.public','=',1],['tagging_threads.tag_id','=',$booktag]])
            ->select('books.*', 'threads.*', 'users.name','labels.labelname', 'chapters.title as last_chapter_title')
            ->orderby('books.lastaddedchapter_at', 'desc')
            ->paginate(Config::get('constants.index_per_page'));
      }
      $book_info = Config::get('constants.book_info');
      $collections = false;
      return view('books.index', compact('books','book_info','collections'));
   }
   public function filter(Request $request){
      $bookquery='';
      if(request('original')){
         foreach(request('original') as $i=>$query){
            if($i>0){
               $bookquery.='_';
            }
            $bookquery.=$query;
         }
      }else{
         $bookquery.=3;
      }
      $bookquery.='-';
      if(request('length')){
         foreach(request('length') as $i=>$query){
            if($i>0){
               $bookquery.='_';
            }
            $bookquery.=$query;
         }
      }else{
         $bookquery.=0;
      }
      $bookquery.='-';
      if(request('status')){
         foreach(request('status') as $i=>$query){
            if($i>0){
               $bookquery.='_';
            }
            $bookquery.=$query;
         }
      }else{
         $bookquery.=0;
      }
      $bookquery.='-';
      if(request('sexual_orientation')){
         foreach(request('sexual_orientation') as $i=>$query){
            if($i>0){
               $bookquery.='_';
            }
            $bookquery.=$query;
         }
      }else{
         $bookquery.=0;
      }
      return redirect()->route('books.selector',$bookquery);
   }
   public function txt_download(Thread $thread)
   {
      $book = $thread->book;
      $user = Auth::user();
      if (($user->id!=$thread->user_id)||(!$user->admin)) {//假如并非本人主题，登陆用户也不是管理员，首先看主人是否允许开放下载
        if (!$thread->download_as_book){
          return redirect()->back()->with("danger","作者并未开放下载");
        }else{
          if($user->user_level>0){
            if (($user->shengfan > 10)&&($user->xianyu > 2)){
              $user->decrement('shengfan',10);
              $user->decrement('xianyu',2);
            }else{
              return redirect()->back()->with("danger","您的剩饭与咸鱼不够，不能下载");
            }
          }else{
            return redirect()->back()->with("danger","您的用户等级不够，不能下载");
          }
        }
      }
      $thread->increment('downloaded');
      $author = $thread->creator;
      $author->increment('shengfan',5);
      $author->increment('jifen',5);
      $author->increment('xianyu',1);
      $chapters = $book->chapters;
      $chapters->load(['mainpost']);
      $thread->load(['creator', 'tags', 'label']);
      $book_info = Config::get('constants.book_info');
      $txt = 'Downloaded from http://sosad.fun by Username:'.Auth::user()->name.' UserID:'.Auth::user()->id.' at UTC+8 '.Carbon::now(8)."\n";
      $txt .= "标题：".$thread->title."\n";
      $txt .= "简介：".$thread->brief."\n";
      $txt .= "作者：";
      if($thread->anonymous){$txt.=$thread->majia;}else{$txt.=$thread->creator->name;}
      $txt .= " at ".Carbon::parse($thread->created_at)->setTimezone(8);
      if($thread->created_at < $thread->edited_at){
        $txt.= "/".Carbon::parse($thread->edited_at)->setTimezone(8);
      }
      $txt .= "\n";
      $txt .= "图书信息：".$book_info['originality_info'][$book->original].'-'.$book_info['book_lenth_info'][$book->book_length].'-'.$book_info['book_status_info'][$book->book_status].'-'.$book_info['sexual_orientation_info'][$book->sexual_orientation];
      if($thread->bianyuan){$txt .= "|边缘";}
      $txt .= '|'.$thread->label->labelname;
      foreach ($thread->tags as $tag){
        $txt .= '-'.$tag->tagname;
      }
      $txt .="\n文案：\n".$thread->body."\n\n";

      foreach($chapters as $i=>$chapter){
        $txt .= ($i+1).'.'.$chapter->title."\n";//章节名
        $txt .= Carbon::parse($chapter->created_at)->setTimezone(8);
        if($chapter->created_at < $chapter->edited_at){
          $txt.= "/".Carbon::parse($chapter->edited_at)->setTimezone(8);
        }
        $txt .= "\n";
        if($chapter->mainpost->title){$txt .= $chapter->mainpost->title."\n";}
        if($chapter->mainpost->body){$txt .= $chapter->mainpost->body."\n";}
        if($chapter->annotation){$txt .= "备注：".$chapter->annotation."\n";}
        $txt .="\n";
      }
      $response = new StreamedResponse();
      $response->setCallBack(function () use($txt) {
          echo $txt;
      });
      $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'book'.$book->id.'.txt');
      $response->headers->set('Content-Disposition', $disposition);
      return $response;
   }
}
