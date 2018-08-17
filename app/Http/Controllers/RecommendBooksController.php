<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RecommendBook;
use App\Models\Thread;
use App\Models\Post;
use App\Sosadfun\Traits\BookTraits;
use Illuminate\Support\Facades\DB;

class RecommendBooksController extends Controller
{
    use BookTraits;

    public function create()
    {
        return view('recommend_books/create');
    }

    public function store(Request $request)
    {
        $this->validate(request(), [
            'thread_id' => 'required|numeric',
            'recommendation' => 'required|string',
        ]);

        $recommend_book = RecommendBook::create([
            'thread_id' => $request->thread_id,
            'recommendation' => $request->recommendation,
            'long' => $request->long? 1 : 0,
            'clicks' => 0
        ]);

        if(!$recommend_book->long) {
              $thread = Thread::find($request->thread_id);
              $recommend_book->update([
                  'deleted_at' => $thread->deleted_at,
                  'created_at' => $thread->created_at,
                  'updated_at' => $thread->updated_at,
                  'bianyuan' => $thread->bianyuan,
                  'title' => $thread->title,
              ]);
        } else {
            $post = Post::find($request->thread_id);
            $recommend_book->update([
                'deleted_at' => $post->deleted_at,
                'created_at' => $post->created_at,
                'updated_at' => $post->updated_at,
                'bianyuan' => $post->thread->bianyuan,
                'title' => $post->thread->title,
            ]);
        }

        return redirect()->route('recommend_books.index')->with("success", "您已成功添加推荐书籍");
    }

    public function index()
    {
      $recommend_books = $this->join_book_tables()
          ->join('recommend_books', 'threads.id', '=', 'recommend_books.thread_id')
          ->where('long', '=', '0')
          ->select('threads.book_id', 'threads.user_id', 'threads.bianyuan', 'threads.locked', 'threads.public', 'threads.noreply', 'threads.last_post_id', 'threads.channel_id', 'threads.label_id', 'threads.anonymous', 'threads.responded',
          'recommend_books.title', 'recommend_books.id', 'recommend_books.recommendation', 'recommend_books.clicks',
          'chapters.title as last_chapter_title', 'chapters.post_id as last_chapter_post_id',
          'labels.labelname', 'users.name',
          'books.lastaddedchapter_at', 'books.last_chapter_id', 'books.book_length', 'books.book_status', 'books.sexual_orientation', 'books.total_char',
          'tongrens.tongren_cp_tag_id', 'tongrens.tongren_yuanzhu_tag_id',
          'tongren_yuanzhu_tags.tagname as tongren_yuanzhu_tagname', 'tongren_cp_tags.tagname as tongren_cp_tagname')
          ->orderBy('recommend_books.id','desc')
          ->paginate(config('constants.items_per_page'));

      return view('recommend_books.index', compact('recommend_books'))->with('active', 1);
    }

    public function edit(RecommendBook $recommend_book)
    {
        return view('recommend_books.edit', compact('recommend_book'));
    }

    public function update(Request $request, RecommendBook $recommend_book)
    {
        $this->validate(request(), [
            'recommendation' => 'required|string'
        ]);

        $recommend_book->update([
            'recommendation' => $request->recommendation
        ]);

        return redirect()->route('recommend_books.index')->with('success', '您已成功修改推荐语');
    }

    public function longcomments()
    {
        $recommend_longcomments = DB::table('recommend_books')
            ->join('posts', 'posts.id', '=', 'recommend_books.thread_id')
            ->join('threads', 'threads.id', '=', 'posts.thread_id')
            ->join('users','users.id','=','posts.user_id')
            ->join('long_comments','posts.id','=','long_comments.post_id')
            ->where('long', '=', '1')
            ->select('posts.*', 'recommend_books.title as thread_title', 'recommend_books.id as recommend_id', 'recommend_books.recommendation', 'users.name', 'long_comments.reviewed','long_comments.approved')
            ->orderBy('recommend_books.id','desc')
            ->paginate(config('constants.items_per_page'));
        return view('recommend_books.longcomments', compact('recommend_longcomments'))->with('active', 2);
    }
}
