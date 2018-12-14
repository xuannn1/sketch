<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreBook;
use App\Sosadfun\Traits\BookTraits;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\Book;
use App\Models\Tag;
use App\Helpers\Helper;
use Auth;

class BooksController extends Controller
{
    use BookTraits;

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
    public function edit(Book $book)
    {
        if ((Auth::id() == $book->thread->user_id)&&(!$book->thread->locked)){
            $thread = $book->thread->load('mainpost');
            $book->load('tongren');
            $tags = $thread->tags;
            return view('books.edit',compact('book', 'thread','tags'));
        }else{
            return redirect()->route('error', ['error_code' => '405']);
        }
    }
    public function update(StoreBook $form, Book $book)
    {
        $thread = $book->thread;
        if ((Auth::id() == $book->thread->user_id)&&(!$thread->locked)){
            $form->updateBook($thread);
            return redirect()->route('book.show', $book->id)->with("success", "您已成功修改文章");
        }else{
            return redirect()->route('error', ['error_code' => '405']);
        }
    }

    public function show(Book $book, Request $request)
    {
        $thread = $book->thread;
        if($thread->id>0){
            $book->load('chapters.mainpost_info','tongren');
            $channel = Helper::allChannels()->keyBy('id')->get($thread->channel_id);
            $label = Helper::allLabels()->keyBy('id')->get($thread->label_id);
            $thread->load(['creator', 'tags', 'mainpost.comments.owner']);
            if(!Auth::check()||(Auth::id()!=$thread->user_id)){
                $thread->increment('viewed');
            }
            $posts = Post::allPosts($thread->id,$thread->post_id)->noMaintext()->userOnly(request('useronly'))->latest()
            ->with('owner','reply_to_post.owner','comments.owner')->paginate(config('constants.items_per_page'));

            $xianyus = [];
            $shengfans = [];
            if((!request()->page)||(request()->page == 1)){
                //dd('front page');
                $xianyus = Cache::remember('-t'.$thread->id.'-xianyus', 10, function () use($thread) {
                    $xianyus = $thread->xianyus;
                    $xianyus->load('creator');
                    return $xianyus;
                });

                $shengfans = Cache::remember('-t'.$thread->id.'-shengfans', 10, function () use($thread) {
                    $shengfans = $thread->mainpost->shengfans;
                    $shengfans->load('creator');
                    return $shengfans;
                });
            }
            return view('books.show', compact('book','thread', 'posts', 'xianyus', 'shengfans','channel','label'))->with('defaultchapter',0)->with('chapter_replied',true)->with('show_as_book',true);
        }else{
            return redirect()->route('error', ['error_code' => '404']);
        }

    }



    public function index(Request $request)
    {
        $logged = Auth::check()? true:false;
        $page = is_numeric($request->page)? $request->page:1;
        $bookqueryid = 'booksQuery'.($logged? '-Loggedd':'-notLogged')//logged or not
        .($request->label? '-Label'.$request->label:'')
        .($request->channel? '-Channel'.$request->channel:'')
        .($request->book_length? '-Booklength'.$request->book_length:'')
        .($request->book_status? '-Bookstatus'.$request->book_status:'')
        .($request->sexual_orientation? '-SexualOrientation'.$request->sexual_orientation:'')
        .($request->rating? '-Rating'.$request->rating:'-noRating')
        .($request->orderby? '-Orderby'.$request->orderby:'-defaultOrderBy')
        .'-P'.$page;
        $books = Cache::remember($bookqueryid, 5, function () use($request, $page, $logged) {
            $query = $this->join_book_tables();
            if(!$logged){$query = $query->where('bianyuan','=',0);}
            if($request->label){$query = $query->where('threads.label_id','=',$request->label);}
            if($request->channel){$query = $query->where('threads.channel_id','=',$request->channel);}
            if($request->book_length){$query = $query->where('books.book_length','=',$request->book_length);}
            if($request->book_status){$query = $query->where('books.book_status','=',$request->book_status);}
            if($request->sexual_orientation){$query = $query->where('books.sexual_orientation','=',$request->sexual_orientation);}
            if($request->rating){$query = $query->where('threads.bianyuan','=',$request->rating-1);}
            $query->where([['threads.deleted_at', '=', null],['threads.public','=',1]]);
            $query = $this->return_book_fields($query);
            $books = $this->bookOrderBy($query, $request->orderby)
            ->paginate(config('constants.index_per_page'))
            ->appends($request->query());
            return $books;
        });
        return view('books.index', compact('books'))->with('show_as_collections', false);
    }

    public function selector($bookquery_original, Request $request)
    {
        $bookquery=explode('-',$bookquery_original);
        $bookinfo=[];
        foreach($bookquery as $info){
            array_push($bookinfo,array_map('intval',explode('_',$info)));
        }
        $logged = Auth::check()? true:false;
        $page = is_numeric($request->page) ? $request->page:1;
        $books = Cache::remember('booksQuery'.($logged? '-Logged':'-notLogged').'-selector:'.$bookquery_original.'-P'.$page, 10, function () use($bookinfo, $page, $logged) {
            if((!empty($bookinfo[5]))&&($bookinfo[5][0]>0)){//用户是否提交了标签(tag)筛选要求？
                $query = $this->join_complex_book_tables();//包含标签筛选
            }else{
                $query = $this->join_book_tables();//不包含标签筛选
            }
            $query->where([['threads.deleted_at', '=', null],['threads.public','=',1]]);
            if(!$logged){$query = $query->where('bianyuan','=',0);}//未登陆用户不能进一步看限制文
            if(!empty($bookinfo[0])&&count($bookinfo[0])==1){//原创性筛选
                $query->where('threads.channel_id','=', $bookinfo[0][0]);
            }
            if(count($bookinfo[1])<count($book_info['book_lenth_info'])){//书籍长度筛选
                $query->whereIn('books.book_length',$bookinfo[1]);
            }
            if(count($bookinfo[2])<count($book_info['book_status_info'])){//书籍进度筛选
                $query->whereIn('books.book_status',$bookinfo[2]);
            }
            if(count($bookinfo[3])<count($book_info['sexual_orientation_info'])){//书籍性向筛选
                $query->whereIn('books.sexual_orientation',$bookinfo[3]);
            }
            if(count($bookinfo[4])<count($book_info['rating_info'])){//书籍限制性筛选
                $query->where('threads.bianyuan','=',$bookinfo[4][0]-1);
            }
            if((!empty($bookinfo[5]))&&($bookinfo[5][0]>0)){//标签筛选
                $query->whereIn('tagging_threads.tag_id',$bookinfo[5]);
            }
            if((!empty($bookinfo[6]))&&($bookinfo[6]>0)){//排序方式筛选
                $query = $this->bookOrderBy($query, $bookinfo[6]);
            }
            $books = $this->return_book_fields($query)
            ->distinct()
            ->paginate(config('constants.index_per_page'))
            ->appends($request->query());
            return $books;
        });
        return view('books.index', compact('books'))->with('show_as_collections', false);
    }

    public function booktag(Tag $booktag, Request $request){
        $logged = Auth::check()? true:false;
        $books = Cache::remember('-tag-'.($logged? 'Lgd':'nLg').$booktag->id.($request->orderby? '-Orderby'.$request->orderby:'-defaultOrderBy').(is_numeric($request->page)? 'P'.$request->page:'P1'), 2, function () use($request, $booktag, $logged) {
            $query = $this->join_book_tables()
            ->join('tagging_threads','threads.id','=','tagging_threads.thread_id')
            ->where('tagging_threads.tag_id','=',$booktag->id)
            ->where([['threads.deleted_at', '=', null],['threads.public','=',1]]);
            if(!$logged){$query = $query->where('bianyuan','=',0);}
            $query = $this->return_book_fields($query);
            $books = $this->bookOrderBy($query, $request->orderby)
            ->paginate(config('constants.index_per_page'))
            ->appends($request->query());
            return $books;
        });
        return view('books.index', compact('books'))->with('show_as_collections', false);
    }

    public function filter(Request $request){
        $bookquery='';
        //[0]原创性
        if(request('original')){
            foreach(request('original') as $i=>$query){
                if($i>0){
                    $bookquery.='_';
                }
                $bookquery.=$query;
            }
        }else{
            $bookquery.=0;
        }
        //[1]篇幅
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
        //[2]进度
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
        //[3]性向
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
        //[4]边缘限制性
        $bookquery.='-';
        if(request('rating')){
            foreach(request('rating') as $i=>$query){
                if($i>0){
                    $bookquery.='_';
                }
                $bookquery.=$query;
            }
        }else{
            $bookquery.=0;
        }
        //[5]标签
        $bookquery.='-';
        if(request('tag')){
            foreach(request('tag') as $i=>$query){
                if($i>0){
                    $bookquery.='_';
                }
                $bookquery.=$query;
            }
        }else{
            $bookquery.=0;
        }
        //[6]排序方式
        $bookquery.='-';
        if(request('orderby')){
            $bookquery.=request('orderby');
        }else{
            $bookquery.=1;
        }
        return redirect()->route('books.selector',$bookquery);
    }
}
