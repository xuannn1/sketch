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

    public function __construct()
    {
        $this->middleware('admin');
    }

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
        $record = RecommendBook::where('thread_id', $request->thread_id)->first();
        if($record){
            return redirect()->route('recommend_books.index')->with("info", "该书籍已推荐过");
        }else{
            $recommendation=$request->only('thread_id','recommendation');
            if(!$request->long) {
                  $thread = Thread::find($request->thread_id);
                  if($thread){
                      $recommendation['title']=$thread->title;
                      $recommendation['bianyuan']=$thread->bianyuan;
                  }
            }
            $recommendation['long']=$request->long? 1 : 0;
            $recommend_book = RecommendBook::create($recommendation);
            $thread->update(['recommended'=>1]);
            return redirect()->route('recommend_books.index')->with("success", "您已成功添加推荐书籍");
        }
    }

    public function index()
    {
      $recommend_books = DB::table('threads')
          ->join('users', 'threads.user_id', '=', 'users.id')
          ->join('recommend_books', 'threads.id', '=', 'recommend_books.thread_id')
          ->where('long', '=', '0')
          ->select('threads.user_id', 'threads.bianyuan', 'threads.locked', 'threads.public', 'threads.noreply', 'threads.anonymous', 'threads.majia', 'threads.title', 'recommend_books.id', 'recommend_books.thread_id', 'recommend_books.recommendation', 'recommend_books.clicks', 'recommend_books.valid', 'recommend_books.past', 'users.name', 'recommend_books.created_at', 'recommend_books.updated_at')
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
