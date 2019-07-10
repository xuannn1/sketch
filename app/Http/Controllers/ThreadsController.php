<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Helpers\ThreadObjects;
use App\Helpers\ConstantObjects;
use App\Models\Post;
use App\Models\Thread;

use Auth;


class threadsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show', 'showpost', 'channel_index']);
    }

    public function index(Request $request)
    {
        $queryid = 'threadQ'
        .url('/')
        .'-inChannel'.$request->inChannel
        .'-withType'.$request->withType
        .'-withBianyuan'.$request->withBianyuan
        .'-withTag'.$request->withTag
        .'-excludeTag'.$request->excludeTag
        .'-ordered'.$request->ordered
        .(is_numeric($request->page)? 'P'.$request->page:'P1');
        $threads = Cache::remember($queryid, 5, function () use($request) {
            return $threads = Thread::with('author', 'tags', 'last_component', 'last_post')
            ->inChannel($request->inChannel)
            ->isPublic()
            ->inPublicChannel()
            ->withType($request->withType)
            ->withBianyuan($request->withBianyuan)
            ->withTag($request->withTag)
            ->excludeTag($request->excludeTag)
            ->ordered($request->ordered)
            ->paginate(config('preference.threads_per_page'))
            ->appends($request->only('inChannel','withType','withBianyuan','withTag','excludeTag','ordered'));
        });

        return view('threads.filter', compact('threads'));
    }

    public function thread_index(Request $request)
    {
        $page = is_numeric($request->page)? $request->page:'1';
        $threads = Cache::remember('thread_index_P'.$page, 5, function () use($page) {
            return $threads = Thread::with('author', 'tags', 'last_component', 'last_post')
            ->isPublic()
            ->inPublicChannel()
            ->withType('thread')
            ->ordered()
            ->paginate(config('preference.threads_per_page'))
            ->appends(['page'=>$page]);
        });
        $simplethreads = ThreadObjects::jinghua_threads();

        return view('threads.thread_index', compact('threads','simplethreads'))->with('threads_tab','index');
    }

    public function thread_jinghua(Request $request)
    {
        $page = is_numeric($request->page)? $request->page:'1';
        $jinghua_tag = ConstantObjects::find_tag_by_name('精华');
        $threads = Cache::remember('thread_jinghua_P'.$page, 5, function () use($page, $jinghua_tag) {
            return $threads = Thread::with('author', 'tags', 'last_component', 'last_post')
            ->isPublic()//复杂的筛选
            ->inPublicChannel()
            ->withTag($jinghua_tag->id)
            ->ordered()
            ->paginate(config('preference.threads_per_page'))
            ->appends(['page'=>$page]);
        });

        return view('threads.thread_jinghua', compact('threads'))->with('threads_tab','jinghua');
    }

    public function thread_list(Request $request)
    {

    }

    public function thread_box(Request $request)
    {

    }

    public function channel_index($channel, Request $request)
    {
        $channel = collect(config('channel'))->keyby('id')->get($channel);
        $primary_tags = ThreadObjects::find_primary_tags_in_channel($channel->id);

        $queryid = 'channel-index'
        .url('/')
        .'-ch'.$channel->id
        .'-withBianyuan'.$request->withBianyuan
        .'-withTag'.$request->withTag
        .'-ordered'.$request->ordered
        .(is_numeric($request->page)? 'P'.$request->page:'P1');

        $threads = Cache::remember($queryid, 5, function () use($request, $channel) {
            return $threads = Thread::with('author', 'tags', 'last_component', 'last_post')
            ->isPublic()
            ->inChannel($channel->id)
            ->withBianyuan($request->withBianyuan)
            ->withTag($request->withTag)
            ->ordered($request->ordered)
            ->paginate(config('preference.threads_per_page'))
            ->appends($request->only('withBianyuan', 'ordered', 'withTag'));
        });

        $simplethreads = ThreadObjects::find_top_threads_in_channel($channel->id);

        return view('threads.thread_channel', compact('channel', 'threads', 'simplethreads', 'primary_tags'));
    }

    public function createThreadForm($channel)
    {
        $channel = collect(config('channel'))->keyby('id')->get($this->channel_id);
        // $labels = $channel->labels();
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

    public function chapter_index($id)
    {
        $thread = ThreadObjects::thread($id);
        $posts = ThreadObjects::threadChapterIndex($id);


        return view('chapters.chapter_index', compact('thread', 'posts'));

    }

    public function component_index($id)
    {

    }

    public function show_profile($id, Request $request)
    {
        $thread = ThreadObjects::threadProfile($id);
        $posts = ThreadObjects::threadProfilePosts($id);
        return view('threads.show_profile', compact('thread', 'posts'));
    }

    public function show($id, Request $request)
    {
        $show_profile = true;
        $page = (int)(is_numeric($request->page)? $request->page:'1');

        if($page>1||$request->withType||$request->withComponent||$request->userOnly||$request->withReplyTo||$request->ordered){
            $show_profile = false;
        }

        if($show_profile){
            $thread = ThreadObjects::threadProfile($id);
        }else{
            $thread = ThreadObjects::thread($id);
        }

        $posts = \App\Models\Post::where('thread_id',$id)
        ->with('author.title','tags','last_reply')
        ->withType($request->withType)//可以筛选显示比如只看post，只看comment，只看。。。
        ->withComponent($request->withComponent)//可以选择是只看component，还是不看component
        ->userOnly($request->userOnly)//可以只看某用户（这样选的时候，默认必须同时属于非匿名）
        ->withReplyTo($request->withReplyTo)//可以只看用于回复某个回帖的
        ->ordered($request->ordered)//排序方式
        ->paginate(config('preference.posts_per_page'))
        ->appends($request->only('withType', 'withComponent', 'userOnly', 'withReplyTo', 'ordered', 'page'));

        $channel = $thread->channel();
        if($channel->type==='book'){
            $posts->load('chapter');
        }
        if($channel->type==='list'){
            $posts->load('review.reviewee');
        }

        return view('threads.show', compact('show_profile', 'thread', 'posts'));
    }
}
