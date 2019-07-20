<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Cache;
use ConstantObjects;
use App\Models\Post;
use App\Models\Thread;
use CacheUser;
use App\Http\Requests\StoreThread;

use Auth;
use App\Sosadfun\Traits\ThreadObjectTraits;

class threadsController extends Controller
{
    use ThreadObjectTraits;

    public function __construct()
    {
        $this->middleware('auth')->only('create','store','edit','update');
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
            ->appends($request->only('inChannel','withType','withBianyuan','withTag','excludeTag','ordered','page'));
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
        $simplethreads = $this->jinghua_threads();

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

    public function channel_index($channel, Request $request)
    {
        $channel = collect(config('channel'))->keyby('id')->get($channel);
        $primary_tags = ConstantObjects::extra_primary_tags_in_channel($channel->id);

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
            ->appends($request->only('withBianyuan', 'ordered', 'withTag','page'));
        });

        $simplethreads = $this->find_top_threads_in_channel($channel->id);

        return view('threads.thread_channel', compact('channel', 'threads', 'simplethreads', 'primary_tags'));
    }



    public function create(Request $request)
    {
        $user = CacheUser::Auser();
        $channel = collect(config('channel'))->keyby('id')->get($request->channel_id);

        if(empty($channel)||((!$channel->is_public)&&(!$user->canSeeChannel($channel->id)))){abort(403,'权限不足');}

        $tags = ConstantObjects::primary_tags_in_channel($channel->id);

        if($channel->type==='list'){
            $list_count = Thread::where('user_id', $user->id)->withType('list')->count();
            if($list_count > $user->level-4){
                return redirect()->back()->with('warning','您的收藏单数量已达上线，不能再建立');
            }
        }
        if($channel->type==='box'){
            $box_count = Thread::where('user_id', auth('api')->id())->withType('box')->count();
            if($box_count >=1){
                return redirect()->back()->with('warning','每个人只能建立一个问题箱，您已经建立了问题箱');
            }
        }

        if($channel->type==='book'){
            if($user->level<1||$user->quiz_level<1){
                return redirect()->back()->with('warning','您的用户等级/答题等级不足，目前不能建立书籍');
            }
            return view('books.create');
        }

        if($user->level<5||$user->quiz_level<1){
            return redirect()->back()->with('warning','您的用户等级/答题等级不足，目前不能建立讨论帖');
        }

        return view('threads.create', compact('channel','tags'));
    }

    public function store(StoreThread $form)
    {
        $channel = collect(config('channel'))->keyby('id')->get($form->channel_id);
        $user = CacheUser::Auser();

        if(empty($channel)||((!$channel->is_public)&&(!$user->canSeeChannel($channel->id)))){abort(403,'权限不足');}

        if($channel->type==='list'){
            $list_count = Thread::where('user_id', $user->id)->withType('list')->count();
            if($list_count > $user->level-4){abort(403);}
        }

        if($channel->type==='box'){
            $box_count = Thread::where('user_id', auth('api')->id())->withType('box')->count();
            if($box_count >=1){abort(403);}
        }

        if($channel->type==='book'){
            abort(403);
        }

        if($user->level<5){
            abort(403);
        }


        $thread = $form->generateThread($channel);

        $thread->tags()->syncWithoutDetaching($thread->tags_validate(array($form->tag)));

        $thread->user->reward("regular_thread");
        // if($channel->type==='homework'){
        //     $thread->register_homework();
        // }
        return redirect()->route('thread.show', $thread->id)->with("success", "您已成功发布主题");
    }

    public function edit(Thread $thread)
    {
        $user = CacheUser::Auser();
        $channel = $thread->channel();

        if(empty($channel)||((!$channel->is_public)&&(!$user->canSeeChannel($channel->id)))){abort(403,'权限不足');}

        if ((Auth::user()->isAdmin())||($thread->user_id == Auth::id()&&(!$thread->is_locked)&&($thread->channel()->allow_edit))){

            $selected_tags = $thread->tags;

            $tags = ConstantObjects::primary_tags_in_channel($channel->id);

            return view('threads.edit', compact('channel', 'thread','user','tags','selected_tags'));
        }else{
            return redirect()->back()->with("danger","本版面无法编辑内容");
        }
    }

    public function update(StoreThread $form, Thread $thread)
    {
        if ((Auth::id() == $thread->user_id)&&((!$thread->is_locked)||(Auth::user()->isAdmin()))){

            $form->updateThread($thread);

            $thread->keep_only_admin_tags();
            $thread->tags()->syncWithoutDetaching($thread->tags_validate(array($form->tag)));

            $this->clearAllThread($thread->id);
            return redirect()->route('thread.show', $thread->id)->with("success", "您已成功修改主题");
        }else{
            abort(403);
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
        $thread = $this->findThread($id);
        $posts = $this->threadChapterIndex($id);
        return view('chapters.chapter_index', compact('thread', 'posts'));
    }

    public function review_index($id)
    {
        $thread = $this->findThread($id);
        $posts = $this->threadReviewIndex($id);
        return view('reviews.review_index', compact('thread', 'posts'));
    }

    public function show_profile($id, Request $request)
    {
        $thread = $this->threadProfile($id);
        $posts = $this->threadProfilePosts($id);
        $user = Auth::check()? CacheUser::Auser():'';
        $info = Auth::check()? CacheUser::Ainfo():'';
        $thread->recordViewCount();
        $thread->recordViewHistory();
        return view('threads.show_profile', compact('thread', 'posts','user','info'));
    }

    public function show($id, Request $request)
    {


        $show_config = $this->decide_thread_show_config($request);

        if($show_config['show_profile']){
            $thread = $this->threadProfile($id);
        }else{
            $thread = $this->findThread($id);
        }
        $thread->recordViewCount();
        $thread->recordViewHistory();

        $posts = \App\Models\Post::where('thread_id',$id)
        ->with('author.title','tags','last_reply')
        ->withType($request->withType)//可以筛选显示比如只看post，只看comment，只看。。。
        ->withComponent($request->withComponent)//可以选择是只看component，还是不看component只看post，还是全都看
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
        $user = Auth::check()? CacheUser::Auser():'';
        $info = Auth::check()? CacheUser::Ainfo():'';

        $selector = config('selectors')[$channel->type.'_filter'];

        return view('threads.show', compact('show_config', 'thread', 'posts', 'user', 'info', 'selector'));
    }
}
