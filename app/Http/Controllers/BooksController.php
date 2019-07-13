<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreBook;
use App\Models\Thread;
use ConstantObjects;

use Auth;

class BooksController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->only('create', 'store', 'update','edit');
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(StoreBook $form)
    {
        $thread = $form->generateBook();
        $thread->user->reward("regular_book");
        return redirect()->route('book.show', $thread->book_id)->with("success", "您已成功发布文章");
    }

    public function show($id, Request $request)
    {   $book = DB::table('books')->where('id','=',$id)->first();
        redirect()->route('thread.show_profile', $book->thread_id);
    }



    public function index(Request $request)
    {
        $tags = ConstantObjects::organizeBookTags();

        $queryid = 'bookQ'
        .url('/')
        .'-inChannel'.$request->inChannel
        .'-withBianyuan'.$request->withBianyuan
        .'-withTag'.$request->withTag
        .'-excludeTag'.$request->excludeTag
        .'-ordered'.$request->ordered
        .(is_numeric($request->page)? 'P'.$request->page:'P1');
        $threads = Cache::remember($queryid, 5, function () use($request) {
            return Thread::with('author', 'tags', 'last_component', 'last_post')
            ->inChannel($request->inChannel)
            ->isPublic()
            ->withType('book')
            ->withBianyuan($request->withBianyuan)
            ->withTag($request->withTag)
            ->excludeTag($request->excludeTag)
            ->ordered($request->ordered)
            ->paginate(config('preference.threads_per_page'))
            ->appends($request->only('inChannel','withBianyuan','withTag','excludeTag','ordered'));
        });

        return view('books.index', compact('threads','tags'));
    }

    public function tags()
    {
        return view('books.tags');
    }
    
}
