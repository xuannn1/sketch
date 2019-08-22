<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StoreBook;
use App\Models\Thread;
use ConstantObjects;
use CacheUser;
use Auth;
use Carbon;
use StringProcess;
use App\Sosadfun\Traits\ThreadObjectTraits;
use App\Sosadfun\Traits\ThreadQueryTraits;

class BooksController extends Controller
{
    use ThreadObjectTraits;
    use ThreadQueryTraits;

    public function __construct()
    {
        $this->middleware('auth')->except('show','index','selector','interpret_selector');
    }

    public function create(Request $request)
    {
        $tag_range = ConstantObjects::organizeBookCreationTags();
        $user = CacheUser::Auser();
        if(!$user){abort(404);}
        if(Cache::has('created-thread-' . $user->id)){
            return redirect('/')->with('danger', '你在10分钟内已成功建立过新主题，请查询个人主题记录，勿重复建立主题。');
        }
        if($user->no_posting){
            return back()->with('danger','你被禁言中，暂时无法创建书籍');
        }
        if($user->level<1||$user->quiz_level<1){
            return redirect()->back()->with('warning','你的用户等级/答题等级不足，目前不能建立书籍');
        }
        return view('books.create', compact('tag_range'));
    }

    public function store(StoreBook $form)
    {
        $user = CacheUser::Auser();
        $channel = collect(config('channel'))->keyby('id')->get($form->channel_id);
        if(!$channel||$channel->type<>'book'||$user->level<1||$user->quiz_level<1){
            abort(403);
        }

        if(Cache::has('created-thread-' . $user->id)){
            return redirect('/')->with('danger', '你在10分钟内已成功建立过新主题，请查询个人主题记录，勿重复建立主题。');
        }

        $thread = $form->generateBook($channel);
        $thread->tongren_data_sync($form->all());

        $tags = array_merge($thread->tags->pluck('id')->toArray(),$form->all_tags());

        $thread->tags()->syncWithoutDetaching($thread->tags_validate($tags));

        $thread->user->reward("regular_book");

        Cache::put('created-thread-' . $user->id, true, 10);

        return redirect()->route('thread.show', $thread->id)->with("success", "你已成功发布文章");
    }

    public function edit($id)
    {
        $thread = Thread::on('mysql::write')->find($id);
        if(!$thread){
            return redirect()->back()->with('danger','找不到文章');
        }
        $channel = $thread->channel();
        if(!$channel||$channel->type!='book'){
            return redirect()->back()->with('danger','不是文章，无法编辑');
        }
        if($thread->user_id!=Auth::id()){
            return redirect()->back()->with('danger','不能修改不是自己的文章');
        }
        if($thread->is_locked&&!Auth::user()->isAdmin()){
            return redirect()->back()->with('danger','不能修改已经锁定的文章');
        }
        return view('books.edit', compact('thread'));
    }

    public function edit_profile($id)
    {
        $thread = Thread::on('mysql::write')->find($id);
        $user = CacheUser::Auser();
        if(!$thread||$thread->user_id!=$user->id||($thread->is_locked&&!$user->isAdmin())){abort(403);}
        return view('books.edit_profile', compact('thread'));
    }

    public function update_profile($id, StoreBook $form)
    {
        $thread = Thread::on('mysql::write')->find($id);
        $user = CacheUser::Auser();
        if(!$thread||$thread->user_id!=$user->id||($thread->is_locked&&!$user->isAdmin())){abort(403);}
        $thread = $form->updateBookProfile($thread);
        $valid_tags = $thread->tags_validate($thread->tags->pluck('id'));
        $thread->keep_only_admin_tags();
        $thread->tags()->syncWithoutDetaching($valid_tags);
        $this->clearThread($id);
        return redirect()->route('thread.show_profile', $id)->with('success','已经成功更新书籍文案设置信息');
    }

    public function edit_tag($id)
    {
        $thread = Thread::on('mysql::write')->find($id);
        $user = CacheUser::Auser();
        if(!$thread||$thread->user_id!=$user->id||($thread->is_locked&&!$user->isAdmin())){abort(403);}
        $selected_tags = $thread->tags;
        $tag_range = ConstantObjects::organizeBasicBookTags();

        return view('books.edit_tag', compact('selected_tags','thread','tag_range'));
    }

    public function update_tag($id, Request $request)
    {
        $thread = Thread::on('mysql::write')->find($id);
        $user = CacheUser::Auser();
        if(!$thread||$thread->user_id!=$user->id||($thread->is_locked&&!$user->isAdmin())){abort(403);}
        $input_tags = array($request->sexual_orientation_tag, $request->book_length_tag, $request->book_status_tag);
        if($request->tags){
            $input_tags = array_merge($input_tags, $request->tags);// 所有本次选择的tag（非管理，非同人原作信息）
        }
        $thread->drop_none_tongren_tags();//去掉所有本次能选的tag的范畴内的tag
        $thread->tags()->syncWithoutDetaching($thread->tags_validate($input_tags));//并入新tag

        $this->clearThread($id);

        return redirect()->route('thread.show_profile', $id)->with('success','已经成功更新书籍标签信息');
    }

    public function edit_tongren($id)
    {
        $thread = Thread::on('mysql::write')->find($id);
        $user = CacheUser::Auser();
        if(!$thread||$thread->user_id!=$user->id||($thread->is_locked&&!$user->isAdmin())||$thread->channel_id<>2){abort(403);}

        $tongren = \App\Models\Tongren::on('mysql::write')->find($id);

        $selected_tags = $thread->tags()->whereIn('tag_type',['同人原著','同人CP'])->get();

        $tag_range = ConstantObjects::organizeBookCreationTags();

        return view('books.edit_tongren', compact('selected_tags','thread','tag_range','tongren'));

    }

    public function update_tongren($id, Request $request)
    {
        $thread = Thread::on('mysql::write')->find($id);
        $user = CacheUser::Auser();
        if(!$thread||$thread->user_id!=$user->id||($thread->is_locked&&!$user->isAdmin())||$thread->channel_id<>2){abort(403);}

        $thread->tongren_data_sync($request->all());
        $this->clearThread($id);
        return redirect()->route('thread.show_profile', $id)->with('success','已经成功更新书籍同人信息');
    }

    public function edit_chapter_index($id)
    {
        $thread = Thread::on('mysql::write')->find($id);
        $user = CacheUser::Auser();
        $posts = $this->threadChapterIndex($thread->id);
        if(!$thread||$thread->user_id!=$user->id||($thread->is_locked&&!$user->isAdmin())){abort(403);}

        return view('books.edit_chapter_index', compact('thread','posts'));
    }

    public function update_chapter_index($id, Request $request)
    {
        $thread = Thread::on('mysql::write')->find($id);
        $user = CacheUser::Auser();
        if(!$thread||$thread->user_id!=$user->id||($thread->is_locked&&!$user->isAdmin())){abort(403);}

        $posts = \App\Models\Post::with('chapter')
        ->where('thread_id',$id)
        ->withType('chapter')
        ->get();

        foreach($request->order_by as $key=>$order_by){
            if(is_numeric($order_by)){
                $post = $posts->firstWhere('id', $key);
                $post->chapter->update(['order_by' => $order_by]);
            }
        }
        $thread->reorder_chapters();

        $first = $request->first_component_id;
        if($first&&is_numeric($first)){
            $post = $posts->firstWhere('id', $first);
            if($post&&$post->user_id===$user->id&&$post->type==='chapter'){
                $thread->update(['first_component_id'=>$first]);
            }
        }
        $last = $request->last_component_id;
        if($last&&is_numeric($last)){
            $post = $posts->firstWhere('id', $last);
            if($post&&$post->user_id===$user->id&&$post->type==='chapter'){
                $thread->update(['last_component_id'=>$last]);
            }
        }

        $this->clearThread($id);
        return redirect()->route('thread.chapter_index', $thread->id);
    }

    public function show($id)
    {   $book = DB::table('books')->where('id','=',$id)->first();
        if($book){
            return redirect()->route('thread.show_profile', $book->thread_id);
        }else{
            abort(404);
        }

    }

    public function index(Request $request)
    {
        $tags = ConstantObjects::organizeBasicBookTags();

        $request_data = $this->sanitize_book_request_data($request);

        $query_id = $this->process_thread_query_id($request_data);

        $results = $this->find_books_with_query($query_id, $request_data);

        return view('books.index', compact('tags','results'));
    }

    public function selector(Request $request)
    {
        $tag_range = ConstantObjects::organizeBasicBookTags();

        return view('books.selector', compact('tag_range'));
    }

    public function interpret_selector(Request $request)
    {
        $request_data = $this->convert_book_request_data($request);

        return redirect()->route('books.index', $request_data);
    }


}
