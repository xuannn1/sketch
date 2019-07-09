<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Helpers\ThreadObjects;
use App\Models\Post;

use Auth;


class threadsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show', 'showpost']);
    }

    public function index(Request $request)
    {
        $group = 10;
        $logged = Auth::check()? true:false;
        if(Auth::check()){$group = Auth::user()->group;}
        $threadqueryid = 'threadQuery'
        .url('/')
        .$group
        .($logged?'Lgd':'nLg')
        .($request->label? 'L'.$request->label:'')
        .($request->channel? 'Ch'.$request->channel:'')
        .(is_numeric($request->page)? 'P'.$request->page:'P1');
        $threads = Cache::remember($threadqueryid, 2, function () use($group, $request, $logged) {
            $query = $this->join_no_book_thread_tables()
            ->where([['threads.book_id','=',0],['threads.deleted_at', '=', null],['channels.channel_state','<',$group],['threads.public','=',1]]);
            if($request->label){$query = $query->where('threads.label_id','=',$request->label);}
            if($request->channel){$query = $query->where('threads.channel_id','=',$request->channel);}
            if(!$logged){$query = $query->where('threads.bianyuan','=',0);}
            $threads = $this->return_no_book_thread_fields($query)
            ->orderBy('threads.lastresponded_at', 'desc')
            ->paginate(config('constants.index_per_page'))
            ->appends($request->only('page','label','channel'));
            return $threads;
        });

        $simplethreads = Cache::remember('jinghua-threads', 2, function (){
            return Thread::where('jinghua','>',Carbon::now())
            ->inRandomOrder()
            ->take(3)
            ->get();
        });
        return view('threads.index', compact('threads','simplethreads'))->with('show_as_collections', false)->with('show_channel',true)->with('active',1);
    }

    public function show($id, Request $request)
    {
        $page = is_numeric($request->page)? $request->page:1;
        if($page==1){
            $thread = ThreadObjects::threadProfile($id);
        }else{
            $thread = ThreadObjects::thread($id);
        }
        $posts = ThreadObjects::threadPostsOldest($id, $page);
        return view('threads.show', compact('thread', 'posts'));
    }

    public function filterpost($id, Request $request)
    {
        $thread = ThreadObjects::thread($id);
        $page = is_numeric($request->page)? $request->page:1;
        $posts = \App\Models\Post::where('thread_id',$id)
        ->with('author.title','tags')
        ->withType($request->withType)//可以筛选显示比如只看post，只看comment，只看。。。
        ->withComponent($request->withComponent)//可以选择是只看component，还是不看component
        ->userOnly($request->userOnly)//可以只看某用户（这样选的时候，默认必须同时属于非匿名）
        ->withReplyTo($request->withReplyTo)//可以只看用于回复某个回帖的
        ->ordered($request->ordered)//排序方式
        ->paginate(config('constants.posts_per_page'));

        $channel = $thread->channel();
        if($channel->type==='book'){
            $posts->load('chapter');
        }
        if($channel->type==='review'){
            $posts->load('review.reviewee');
            $posts->review->reviewee->load('tags','author');
        }

        return view('threads.show', compact('thread', 'posts'));
    }

    public function createThreadForm($channel)
    {
        $channel = Helper::allChannels()->keyBy('id')->get($channel);
        $labels = $channel->labels();
        if ($channel->id<=2){
            return view('books.create');
        }
        return view('threads.create', compact('labels', 'channel'));
    }

    public function store(StoreThread $form, $channel)
    {
        $channel = Channel::find($channel);
        $thread = $form->generateThread($channel->id);
        $thread->user->reward("regular_thread");
        if($thread->label_id == 50){
            $thread->registerhomework();
        }
        return redirect()->route('thread.show', $thread->id)->with("success", "您已成功发布主题");
    }

    public function edit(Thread $thread)
    {
        if ((Auth::user()->admin)||($thread->user_id == Auth::id()&&(!$thread->locked)&&($thread->channel->channel_state!=2))){
            return view('threads.edit', compact('thread'));
        }else{
            return redirect()->back()->with("danger","本版面无法编辑内容");
        }

    }

    public function update(StoreThread $form, Thread $thread)
    {
        if ((Auth::id() == $thread->user_id)&&((!$thread->locked)||(Auth::user()->admin))){
            $form->updateThread($thread);
            return redirect()->route('thread.show', $thread->id)->with("success", "您已成功修改主题");
        }else{
            return redirect()->route('error', ['error_code' => '403']);
        }
    }
    public function showpost(Post $post)
    {
        $previousposts = Post::where('thread_id',$post->thread_id)
        ->where('created_at', '<', $post->created_at)
        ->count();
        $page = intdiv($previousposts, config('preference.posts_per_page'))+1;
        $url = 'threads/'.$post->thread_id.'?page='.$page.'#post'.$post->id;
        return redirect($url);
    }
}
