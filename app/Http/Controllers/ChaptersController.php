<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\Label;
use App\Models\Thread;
use App\Models\Book;
use App\Models\Post;
use App\Chapter;
use App\Models\Tag;
use Carbon\Carbon;
use App\Tongren;
use App\Status;
use Auth;

class ChaptersController extends Controller
{
   public function createChapterForm(Book $book)
   {
      if (Auth::id()==$book->thread->creator->id){
         $book->load(['thread','thread.channel','thread.creator','thread.label']);
         return view('books.create_chapter', compact('book'));
      }else{
         return redirect()->route('error', ['error_code' => '403']);
      }
   }

   public function store(Book $book, Request $request)
   {
      $thread = $book->thread;
      if ((Auth::id()==$thread->creator->id)&&(!$thread->locked)){
          $this->validate($request, [
             'title' => 'required|string|max:35',
             'brief' => 'max:35',
             'body' => 'required|string|min:15',
             'annotation' => 'max:20000',
          ]);
         $max_volumn = $book->recent_volumn();
         $volumn_id = $max_volumn ? $max_volumn->id: 0;
         $max_chapter = $book->max_chapter_order();
         $chapter_order = $max_chapter ? : 0;
         $markdown = request('markdown')? true: false;
         $indentation = request('indentation')? true: false;
         $post = Post::create([
            'body' => request('body'),
            'title' => request('brief'),
            'user_id' => $thread->creator->id,
            'thread_id' => $thread->id,
            'maintext' => true,
            'anonymous' => $thread->anonymous,
            'majia' => $thread->majia,
            'markdown' => $markdown,
            'indentation' => $indentation,
         ]);
         $string = preg_replace('/[[:punct:]\s\n\t\r]/','',$post->body);
         $characters = iconv_strlen($string, 'utf-8');
         $chapter = Chapter::create([
            'chapter_order' => $chapter_order +1,
            'title' => request('title'),
            'annotation' => request('annotation'),
            'post_id' => $post->id,
            'book_id' => $book->id,
            'volumn_id' => $volumn_id,
            'characters' => $characters,
            'edited_at' => Carbon::now(),
         ]);
        $total_char = DB::table('chapters')
        ->join('posts','posts.id','=','chapters.post_id')
        ->where([
          ['chapters.book_id','=',$book->id],
          ['posts.deleted_at','=',NULL],
        ])
        ->sum('chapters.characters');
         $post->update(['chapter_id'=>$chapter->id]);
         if ($characters>Config::get('constants.update_min')){
            $book->update(['lastaddedchapter_at' => Carbon::now()]);
         }
         $thread->update([
            'lastresponded_at' => Carbon::now(),
            'last_post_id' => $post->id,
         ]);
         $book->update([
            'last_chapter_id' => $chapter->id,
            'total_char' => $total_char
         ]);
         DB::table('collections')//告诉所有收藏本文章、愿意接受更新的读者, 这里发生了更新
         ->join('users','users.id','=','collections.user_id')
         ->where([['collections.thread_id','=',$thread->id],['collections.keep_updated','=',true],['collections.user_id','<>',Auth::id()]])
         ->update(['collections.updated'=>1,'users.collection_books_updated'=>DB::raw('users.collection_books_updated + 1')]);

         if((request('sendstatus'))&&(!$thread->anonymous)){
            Status::create([
               'user_id' => Auth::id(),
               'content' => '更新了[《'.$thread->title.'》'
               .$chapter->title.'：'.request('brief').']('
               .route('book.showchapter', $chapter->id)
               .')',
            ]);
         }
         return redirect()->route('book.show', $book)->with("success", "您已成功增添章节");
      }else{
         return redirect()->route('error', ['error_code' => '405']);
      }
   }
   public function show(Chapter $chapter)
   {
      $book=$chapter->book;
      $chapter->load(['mainpost.owner','mainpost.comments.owner']);
      $previous = DB::table('chapters')
        ->join('posts','posts.id','=','chapters.post_id')
        ->where([
          ['chapters.chapter_order','<',$chapter->chapter_order],
          ['chapters.book_id','=',$book->id],
          ['posts.deleted_at','=',NULL],
          ])
        ->orderBy('chapters.chapter_order','desc')
        ->select('chapters.*')
        ->first();

      $next = DB::table('chapters')
            ->join('posts','posts.id','=','chapters.post_id')
            ->where([
              ['chapters.chapter_order','>',$chapter->chapter_order],
              ['chapters.book_id','=',$book->id],
              ['posts.deleted_at','=',NULL],
              ])
            ->orderBy('chapters.chapter_order','asc')
            ->select('chapters.*')
            ->first();

      $thread = $book->thread->load(['label','creator']);
      $posts = Post::where([
         ['chapter_id','=', $chapter->id],
         ['maintext', '=', false],
         ])->with(['owner', 'comments.owner', 'reply_to_post.owner'])
         ->latest()
         ->paginate(Config::get('constants.items_per_page'));
      $chapter->increment('viewed');
      $only = false;
      $chapter_replied = false;
      $book_info = Config::get('constants.book_info');
      return view('books.chaptershow', compact('chapter', 'posts', 'thread', 'book', 'previous', 'next', 'only', 'book_info','chapter_replied'));
   }
   public function edit(Chapter $chapter)
   {
      $book=$chapter->book;
      $thread = $book->thread;
      $mainpost = $chapter->mainpost;
      if($thread->locked){
         return redirect()->back()->with("danger","本文已被锁定，不能修改");
      }else{
         return view('books.chapteredit', compact('chapter', 'thread', 'book', 'mainpost'));
      }

   }
   public function update(Chapter $chapter, Request $request)
   {
      $book=$chapter->book;
      $thread = $book->thread;
      if ((Auth::id()==$thread->creator->id)&&(!$thread->locked)){
         $this->validate($request, [
            'title' => 'required|string|max:35',
            'brief' => 'max:35',
            'body' => 'required|string|min:15',
            'annotation' => 'max:20000',
         ]);
         $markdown = request('markdown')? true: false;
         $indentation = request('indentation')? true:false;
         $post = $chapter->mainpost;
         $string = preg_replace('/[[:punct:]\s\n\t\r]/','',request('body'));
         $characters = iconv_strlen($string, 'utf-8');
         $chapter->update([
            'title' => request('title'),
            'annotation' => request('annotation'),
            'characters' => $characters,
            'edited_at' => Carbon::now(),
         ]);
         $post->update([
            'body' => request('body'),
            'title' => request('brief'),
            'markdown' => $markdown,
            'indentation' => $indentation,
            'edited_at' => Carbon::now(),
         ]);
         $total_char = DB::table('chapters')
         ->join('posts','posts.id','=','chapters.post_id')
         ->where([
           ['chapters.book_id','=',$book->id],
           ['posts.deleted_at','=',NULL],
         ])
         ->sum('chapters.characters');
         $book->update(['total_char' => $total_char]);
         return redirect()->route('book.showchapter', $chapter)->with("success", "您已成功修改章节");
      }else{
         return redirect()->route('error', ['error_code' => '403']);
      }
   }


}
