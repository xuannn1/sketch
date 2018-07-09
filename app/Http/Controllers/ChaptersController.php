<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreChapter;
use Illuminate\Support\Facades\DB;

use App\Models\Label;
use App\Models\Thread;
use App\Models\Book;
use App\Models\Post;
use App\Models\Chapter;
use App\Models\Tag;
use Carbon\Carbon;
use App\Models\Tongren;
use App\Models\Status;
use App\Helpers\Helper;
use Auth;

class ChaptersController extends Controller
{
    public function createChapterForm(Book $book)
    {
        if (Auth::id()==$book->thread->creator->id){
            $book->load(['thread','thread.channel','thread.creator','thread.label']);
            return view('chapters.create_chapter', compact('book'));
        }else{
            return redirect()->route('error', ['error_code' => '403']);
        }
    }

    public function store(StoreChapter $form, Book $book)
    {
        if ((Auth::id()==$book->thread->creator->id)&&(!$book->thread->locked)) {
            $chapter = $form->generateChapter($book);
            return redirect()->route('book.show', $book)->with("success", "您已成功增添章节");
        }
        abort(403,'数据冲突');
    }

    public function isDuplicatePost($data)
    {
        $last_post = Post::where('user_id', auth()->id())
        ->orderBy('id', 'desc')
        ->first();
        return count($last_post) && strcmp($last_post->body, $data) === 0;
    }

    public function show(Chapter $chapter)
    {
        $book = $chapter->book;
        $thread = $book->thread;
        if($thread->id){
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

            $thread->load('label','creator');
            $posts = Post::where([
                ['chapter_id','=', $chapter->id],
                ['maintext', '=', false],
                ])
            ->with(['owner', 'comments.owner', 'reply_to_post.owner'])
            ->latest()
            ->paginate(config('constants.items_per_page'));
            if(!Auth::check()||(Auth::id()!=$thread->user_id)){
                $chapter->increment('viewed');
            }
            return view('chapters.chaptershow', compact('chapter', 'posts', 'thread', 'book', 'previous', 'next'))->with('chapter_replied',false);
        }else{
            return redirect()->route('error', ['error_code' => '404']);
        }
    }
    public function edit(Chapter $chapter)
    {
        $book=$chapter->book;
        $thread = $book->thread;
        if($thread->id){
            $mainpost = $chapter->mainpost;
            if($thread->locked){
                return redirect()->back()->with("danger","本文已被锁定，不能修改");
            }else{
                return view('chapters.chapteredit', compact('chapter', 'thread', 'book', 'mainpost'));
            }
        }else{
            return redirect()->route('error', ['error_code' => '404']);
        }
    }
    public function update(StoreChapter $form, Chapter $chapter)
    {
        $form->updateChapter($chapter);
        return redirect()->route('book.show', $chapter->book_id)->with("success", "您已成功修改文章");
    }
}
