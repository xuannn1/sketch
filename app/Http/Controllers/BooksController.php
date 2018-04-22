<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreBook;
use App\Sosadfun\Traits\BookTraits;

use App\Models\Label;
use App\Models\Thread;
use App\Models\Book;
use App\Models\Post;
use App\Models\Chapter;
use App\Models\Tag;
use App\Models\Channel;
use Carbon\Carbon;
use App\Models\Tongren;
use Auth;

class BooksController extends Controller
{
    use BookTraits;

    public function __construct()
    {
        $this->middleware('auth')->only('create', 'store', 'update','edit');
    }

    public function all_book_tags(){
        $tags=[];
        $tags['labels_yuanchuang']= Cache::remember('-labels_yuanchuang-', 10, function () {
            $labels_yuanchuang = Label::where('channel_id',1)->get();
            return $labels_yuanchuang;
        });
        $tags['labels_tongren']=Cache::remember('-labels_tongren-', 10, function () {
            $labels_tongren = Label::where('channel_id',2)->get();
            return $labels_tongren;
        });
        $tags['tags_feibianyuan']=Cache::remember('-tags-feibianyuan-', 10, function () {
            $tags_feibianyuan = Tag::where('tag_group',0)->get();
            return $tags_feibianyuan;
        });
        $tags['tags_bianyuan']=Cache::remember('-tags-bianyuan-', 10, function () {
            $tags_bianyuan = Tag::where('tag_group',5)->get();
            return $tags_bianyuan;
        });
        $tags['tags_tongren']=Cache::remember('-tags-tongren-', 10, function () {
            $tags_tongren = Tag::where('tag_group',25)->get();
            return $tags_tongren;
        });
        $tags['tags_tongren_yuanzhu']=Cache::remember('-tags-tongren-yuanzhu-', 10, function () {
            $tags_tongren_yuanzhu = Tag::where('tag_group',10)->get();
            return $tags_tongren_yuanzhu;
        });
        $tags['tags_tongren_cp']=Cache::remember('-tags-tongren-cp-', 10, function () {
            $tags_tongren_cp = Tag::where('tag_group',20)->get();
            return $tags_tongren_cp;
        });
        return $tags;
    }

    public function create()
    {
        $all_book_tags = $this->all_book_tags();
        return view('books.create',compact('all_book_tags'));
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
            $tags = $thread->tags->pluck('id')->toArray();
            $all_book_tags = $this->all_book_tags();
            return view('books.edit',compact('book', 'thread','tags', $all_book_tags));
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
        $book->load('chapters.mainpost','tongren');
        $thread=$book->thread->load(['creator', 'tags','channel','label', 'mainpost.comments.owner']);
        $thread->increment('viewed');
        $posts = Post::allPosts($thread->id,$thread->post_id)->noMaintext()->userOnly(request('useronly'))->withOrder('oldest')
        ->with('owner','reply_to_post.owner','comments.owner')->paginate(config('constants.items_per_page'));
        return view('books.show', compact('book','thread', 'posts'))->with('defaultchapter',0)->with('chapter_replied',true);
    }



    public function index(Request $request)
    {
        $all_book_tags = $this->all_book_tags();
        $bookqueryid = '-bookquery'.'-'.(Auth::check()?'logged':'not_logged')
        .'-'.($request->label? 'l'.$request->label:'no_l')
        .'-'.($request->channel? 'ch'.$request->channel:'no_ch')
        .'-'.($request->book_length? 'bl'.$request->book_length:'no_bl')
        .'-'.($request->book_status? 'bs'.$request->book_status:'no_bs')
        .'-'.($request->sexual_orientation? 'so'.$request->sexual_orientation:'no_so');
        //dd($bookqueryid);
        $books = Cache::remember($bookqueryid, 10, function () use($request) {
            $query = $this->join_book_tables();
            if(!Auth::check()){$query = $query->where('bianyuan','=',0);}
            if($request->label){$query = $query->where('threads.label_id','=',$request->label);}
            if($request->channel){$query = $query->where('threads.channel_id','=',$request->channel);}
            if($request->book_length){$query = $query->where('books.book_length','=',$request->book_length);}
            if($request->book_status){$query = $query->where('books.book_status','=',$request->book_status);}
            if($request->sexual_orientation){$query = $query->where('books.sexual_orientation','=',$request->sexual_orientation);}
            $query->where([['threads.deleted_at', '=', null],['threads.public','=',1]]);
            $books = $this->return_book_fields($query)
            ->orderby('books.lastaddedchapter_at', 'desc')
            ->paginate(config('constants.index_per_page'));
            return $books;
        });
        return view('books.index', compact('books','all_book_tags'))->with('show_as_collections', false);
    }

    public function selector($bookquery_original)
    {
        $all_book_tags = $this->all_book_tags();
        $bookquery=explode('-',$bookquery_original);
        $bookinfo=[];
        foreach($bookquery as $info){
            array_push($bookinfo,array_map('intval',explode('_',$info)));
        }
        $books = Cache::remember('-bookselector-'.$bookquery_original, 10, function () use($bookinfo) {
            $query = $this->join_book_tables();
            $query->where([['threads.deleted_at', '=', null],['threads.public','=',1]]);
            $query->whereIn('books.book_length',$bookinfo[1])
            ->whereIn('books.book_status',$bookinfo[2])
            ->whereIn('books.sexual_orientation',$bookinfo[3]);
            if(!Auth::check()){$query = $query->where('bianyuan','=',0);}
            if(count($bookinfo[0])==1){
                $query->where('threads.channel_id','=', $bookinfo[0][0]);
            }
            if ((count($bookinfo)>=5)&&(count($bookinfo[4])==1)){
                if($bookinfo[4][0]<3){
                    $query->where('threads.bianyuan', '=', (bool)$bookinfo[4][0]);
                }else{
                    $query->where('threads.bianyuan', '=',$bookinfo[4][0]);
                }
            }
            $books = $this->return_book_fields($query)
            ->distinct()
            ->orderby('books.lastaddedchapter_at', 'desc')
            ->paginate(config('constants.index_per_page'));
            return $books;
        });

        return view('books.index', compact('books','all_book_tags'))->with('show_as_collections', false);
    }

    public function booktag(Tag $booktag){
        $all_book_tags = $this->all_book_tags();
        $query = $this->join_book_tables();
        if($booktag->tag_group==10){
            $query->where('tongren_yuanzhu_tags.id','=',$booktag->id);
        }elseif($booktag->tag_group==20){
            $query->where('tongren_cp_tags.id','=',$booktag->id);
        }else{
            $query->join('tagging_threads','threads.id','=','tagging_threads.thread_id');
            $query->where('tagging_threads.tag_id','=',$booktag->id);//for regular tag
        }
        $query->where([['threads.deleted_at', '=', null],['threads.public','=',1]]);
        if(!Auth::check()){$query = $query->where('bianyuan','=',0);}
        $books = $this->return_book_fields($query)
        ->orderby('books.lastaddedchapter_at', 'desc')
        ->paginate(config('constants.index_per_page'));
        return view('books.index', compact('books','all_book_tags'))->with('show_as_collections', false);
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
        $bookquery.='-';
        if(request('bianyuan')){
            foreach(request('bianyuan') as $i=>$query){
                if($i>0){
                    $bookquery.='_';
                }
                $bookquery.=$query;
            }
        }else{
            $bookquery.=3;
        }
        return redirect()->route('books.selector',$bookquery);
    }
}
