<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Sosadfun\Traits\BookTraits;
use App\Sosadfun\Traits\ThreadTraits;
use Auth;
use App\Models\Collection;
use App\Models\CollectionList;
use App\Models\Thread;
use Carbon\Carbon;
use App\Models\Follower;

class CollectionsController extends Controller
{

    use BookTraits;
    use ThreadTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function storeitem(Request $request) //itemid,itemtype,listid
    {
        $data =[];
        $collection_list = CollectionList::find(request('collection_list_id'));
        if((!$collection_list)||($collection_list->user_id==Auth::id())){
            if((request('item_type')==1)||(request('item_type')==2)){
                $thread = Thread::find(request('item_id'));
                $collection = Collection::where('user_id',Auth::id())->where('collection_list_id',request('collection_list_id'))->where('item_id',request('item_id'))->first();
                if($collection){
                    $data['info']="您已收藏本文，无需重复收藏~";
                }else{
                    $collecttion = Collection::create([
                        'user_id' => Auth::id(),
                        'item_id' => $thread->id,
                        'collection_list_id' => request('collection_list_id'),
                    ]);
                    if($collection_list){
                        $collection_list->increment('item_number');
                        $collection_list->update([
                            'lastupdated_at'=>Carbon::now(),
                            'last_item_id'=>request('item_id'),
                        ]);
                        DB::table('collections')//告诉所有收藏本收藏单，并保持更新提示的读者，这个单子发生了更新
                        ->join('users','users.id','=','collections.user_id')
                        ->join('collection_lists','collection_lists.id','=','collections.collection_list_id')
                        ->where([['collections.item_id','=',$collection_list->id],['collection_lists.type','=',4],['collections.keep_updated','=',true],['collections.user_id','<>',auth()->id()]])
                        ->update(['collections.updated'=>1,'users.collection_lists_updated'=>DB::raw('users.collection_lists_updated + 1')]);
                    }
                    $thread->increment('collection');
                    Auth::user()->update(['lastresponded_at' => Carbon::now() ]);
                    $data['success']="您已成功收藏本文";
                    $data['collection']=$thread->collection;
                }
            }elseif(request('item_type')==4){
                $collection_list_to_add = CollectionList::find(request('item_id'));
                $master_collection_list = Auth::user()->collected_list();
                if($master_collection_list){
                    $collection = Collection::where('user_id',Auth::id())->where('collection_list_id',$master_collection_list->id)->where('item_id',$collection_list_to_add->id)->first();
                    if($collection){
                        $data['info']="您已收藏本收藏单，无需重复收藏~";
                        return $data;
                    }
                }else{
                    $master_collection_list = CollectionList::create([
                        'type' => 4,
                        'user_id' => Auth::id(),
                        'lastupdated_at' => Carbon::now(),
                    ]);
                }
                $collecttion = Collection::create([
                    'user_id' => Auth::id(),
                    'item_id' => $collection_list_to_add->id,
                    'collection_list_id' => $master_collection_list->id,
                ]);
                $master_collection_list->increment('item_number');
                $collection_list_to_add->increment('collected');
                $data['success']="您已收藏本收藏单!";
                $data['collection']=$collection_list_to_add->collected;
            }
            return $data;
        }

    }

    public function store_comment(Collection $collection, Request $request)
    {
        if(Auth::id()==$collection->user_id){
            $this->validate($request, [
                'body' => 'required|string|max:2000',
            ]);
            $data = $request->only('body');
            $collection->update($data);
            if($collection->collection_list->id){
                $collection->collection_list->update(['lastupdated_at'=>Carbon::now()]);
            }
            return back()->with("success", "您已成功添加评价");
        }else{
            return redirect()->back()->with("danger","抱歉，数据冲突");
        }
    }

    public function cancel(Request $request)
    {
        if((request('item_type')==1)||(request('item_type')==2)){
            $thread = Thread::find(request('item_id'));
            $collection = Collection::where('user_id',Auth::id())->where('collection_list_id',request('collection_list_id'))->where('item_id',request('item_id'))->first();
            if($collection){
                if($collection->collection_list->item_number>0){
                    $collection->collection_list->decrement('item_number');
                }
                $collection->delete();
                $thread->decrement('collection');
                return 'worked';
            }else{
                return 'notwork';
            }
        }elseif(request('item_type')==4){
            $collection_list_to_remove = CollectionList::find(request('item_id'));
            $collection = Collection::where('user_id',Auth::id())->where('item_id', request('item_id'))->where('collection_list_id',request('collection_list_id'))->first();
            if($collection){
                if($collection->collection_list->item_number>0){
                    $collection->collection_list->decrement('item_number');
                }
                $collection->delete();
                $collection_list_to_remove->decrement('collected');
                return 'worked';
            }else{
                return 'notwork';
            }
        }
    }
    public function togglekeepupdate(Request $request)
    {
        $user = Auth::user();
        $thread = Thread::find(request('thread_id'));
        $collection = $thread->collection($user);
        if($collection){
            $collection->keep_updated = !$collection->keep_updated;
            $collection->save();
            return $collection;
        }else{
            return "notwork";
        }
    }

    public function clearupdates()
    {
        $user = Auth::user();
        Collection::where('user_id','=',$user->id)->update(['updated'=> false]);
        Follower::where('follower_id','=',$user->id)->update(['updated'=> false]);
        return 'worked';
    }

    public function books()
    {
        $books = $this->find_collected_items(0, 1, Auth::id());
        $book_info = config('constants.book_info');
        $updates = [Auth::user()->collection_books_updated,Auth::user()->collection_threads_updated,Auth::user()->collection_statuses_updated,Auth::user()->collection_lists_updated];
        Auth::user()->collection_books_updated = 0;
        Auth::user()->save();
        $own_collection_book_lists = Auth::user()->own_collection_book_lists;
        return view('collections.collections_books', compact('books', 'book_info','updates','own_collection_book_lists'))->with('show_as_collections',1)->with('active',0);
    }
    public function threads()
    {
        $threads = $this->find_collected_items(0, 2, Auth::id());
        $updates = [Auth::user()->collection_books_updated,Auth::user()->collection_threads_updated,Auth::user()->collection_statuses_updated, Auth::user()->collection_lists_updated];
        Auth::user()->collection_threads_updated = 0;
        Auth::user()->save();
        $own_collection_thread_lists = Auth::user()->own_collection_thread_lists;
        return view('collections.collections_threads', compact('threads','updates','own_collection_thread_lists'))->with('show_as_collections',1)->with('active',1)->with('show_channel',1);
    }

    public function statuses()
    {
        $user = Auth::user();
        $statuses = DB::table('followers')
        ->join('users','followers.user_id','=','users.id')
        ->join('statuses','users.id','=','statuses.user_id')
        ->where([['followers.follower_id','=',$user->id], ['users.deleted_at', '=', null]])
        ->select('statuses.*','users.name','followers.keep_updated as keep_updated','followers.updated as updated')
        ->orderBy('statuses.created_at','desc')
        ->paginate(config('constants.index_per_page'));
        $updates = [Auth::user()->collection_books_updated,Auth::user()->collection_threads_updated,Auth::user()->collection_statuses_updated, Auth::user()->collection_lists_updated];
        $collections = true;
        Auth::user()->collection_statuses_updated = 0;
        Auth::user()->save();
        return view('collections.collections_statuses', compact('statuses','user','updates','collections'))->with('show_as_collections',1)->with('active',2);
    }

    public function collection_lists()
    {
        $user = Auth::user();
        $own_collection_lists = $this->find_collected_items(0, 5, Auth::id());
        $collected_list = CollectionList::where('type','=',4)->where('user_id', '=', Auth::id())->first();
        $collected_lists=[];
        if($collected_list){
            $collected_lists = $this->find_collected_items($collected_list->id, 4, Auth::id());
        }
        $updates = [Auth::user()->collection_books_updated,Auth::user()->collection_threads_updated,Auth::user()->collection_statuses_updated, Auth::user()->collection_lists_updated];
        $collections = true;
        Auth::user()->collection_lists_updated = 0;
        Auth::user()->save();
        return view('collections.collections_lists', compact('own_collection_lists','collected_list','collected_lists','user','updates','collections'))->with('active',3);
    }

    public function collection_list_store(Request $request)
    {
        if ($own_collection_lists = Auth::user()->own_collection_lists->count() < Auth::user()->user_level-2){
            $this->validate($request, [
                'collection_type' => 'required|numeric|min:1|max:3',
                'title' => 'required|string|max:20',
                'brief' => 'required|string|max:50',
                'body' => 'nullable|string|max:5000',
                'majia' => 'string|max:10',
            ]);
            $data = $request->only('title','brief','body');
            $data['user_id'] = auth()->id();
            $data['type'] = request('collection_type');
            $data['lastupdated_at'] = Carbon::now();
            if (request('anonymous')){
                $data['anonymous']=1;
                $data['majia']=request('majia');
                auth()->user()->update(['majia'=>request('majia')]);
            }else{
                $data['anonymous']=0;
            }
            if (!$this->isDuplicateList($data)){
                $collection_list = CollectionList::create($data);
            }
            return redirect()->route('collections.collection_lists')->with('success','成功建立收藏单！');
        }else{
            return back()->with('warning','您的等级不足，不能建立更多收藏单！');
        }
    }
    public function isDuplicateList($data)
    {
        $last_collection_list = CollectionList::where('user_id', auth()->id())
        ->orderBy('id', 'desc')
        ->first();
        return count($last_collection_list) && strcmp($last_collection_list->title.$last_collection_list->brief.$last_collection_list->body, $data['title'].$data['brief'].$data['body']) === 0;
    }

    public function collection_list_create()
    {
        if ($own_collection_lists = Auth::user()->own_collection_lists->count() < Auth::user()->user_level-2){
            return view('collections.create_collection_list');
        }else{
            return back()->with('warning','您的等级不足，不能建立更多收藏单！');
        }
    }

    public function collection_list_edit(CollectionList $collection_list)
    {
        if (Auth::id()==$collection_list->user_id){
            return view('collections.collection_list_edit', compact('collection_list'));
        }
    }

    public function collection_list_update(CollectionList $collection_list, Request $request)
    {
        if (Auth::id()==$collection_list->user_id){
            $this->validate($request, [
                'title' => 'required|string|max:20',
                'brief' => 'required|string|max:50',
                'body' => 'nullable|string|max:5000',
            ]);
            $data = $request->only('title','brief','body');
            $data['lastupdated_at'] = Carbon::now();
            $data['anonymous']=request('anonymous') ? true:false;
            $collection_list->update($data);
            return redirect()->route('collections.collection_list_show',$collection_list->id)->with('success','您已经成功修改收藏单描述！');
        }
    }

    public function find_collected_items($collection_list_id, $collection_list_type, $user_id)
    {
        switch ($collection_list_type):
            case "1"://书本收藏单
            return $this->join_book_tables()
            ->join('collections','collections.item_id','=','threads.id')
            ->where([['collections.user_id','=',$user_id],['collections.collection_list_id','=',$collection_list_id], ['threads.deleted_at', '=', null],['threads.book_id','>',0]])
            ->select('books.*','threads.*','users.name','labels.labelname', 'chapters.title as last_chapter_title','chapters.responded as last_chapter_responded', 'collections.updated as updated','collections.keep_updated as keep_updated','collections.body as collection_body', 'collections.id as collection_id', 'chapters.post_id as last_chapter_post_id','tongrens.tongren_yuanzhu','tongrens.tongren_cp','tongrens.tongren_yuanzhu_tag_id','tongrens.tongren_cp_tag_id','tongren_yuanzhu_tags.tagname as tongren_yuanzhu_tagname','tongren_cp_tags.tagname as tongren_cp_tagname')
            ->orderBy('collections.id','desc')
            ->paginate(config('constants.index_per_page'));
            break;
            case "2"://讨论帖收藏单
            return $this->join_thread_tables()
            ->join('collections','collections.item_id','=','threads.id')
            ->where([['collections.user_id','=',$user_id],['collections.collection_list_id','=',$collection_list_id], ['threads.deleted_at', '=', null],['threads.book_id','=',0]])
            ->select('threads.id', 'threads.id as thread_id', 'threads.user_id','threads.book_id', 'threads.title', 'threads.brief', 'threads.locked', 'threads.public', 'threads.bianyuan', 'threads.anonymous', 'threads.majia', 'threads.noreply', 'threads.viewed', 'threads.responded', 'threads.lastresponded_at', 'threads.channel_id', 'threads.label_id', 'threads.deleted_at', 'threads.created_at',  'threads.edited_at', 'threads.homework_id', 'threads.post_id', 'threads.last_post_id', 'threads.show_homework_profile', 'threads.downloaded',
            'users.name', 'labels.labelname', 'channels.channelname', 'posts.body as last_post_body', 'collections.updated as updated', 'collections.id as collection_id',  'collections.keep_updated as keep_updated', 'collections.body as collection_body')
            ->orderBy('collections.id','desc')
            ->paginate(config('constants.items_per_page'));
            break;
            case "4"://某人的收藏单的收藏总体
            return DB::table('collections')
                ->join('collection_lists','collections.item_id','=','collection_lists.id')
                ->join('users','users.id','=','collection_lists.user_id')
                ->leftjoin('threads',function($join)
                {
                    $join->whereIn('collection_lists.type',[1,2]);
                    $join->on('collection_lists.last_item_id','=','threads.id');
                })
                ->where([['collections.user_id', '=', $user_id],['collections.collection_list_id','=',$collection_list_id]])
                ->select(
                    'collections.id', 'collections.id as collection_id','collections.updated as updated',   'collections.keep_updated as keep_updated', 'collections.body as collection_body',
                    'collection_lists.id as collection_list_id', 'collection_lists.private', 'collection_lists.title', 'collection_lists.brief', 'collection_lists.body', 'collection_lists.user_id', 'users.name', 'collection_lists.anonymous', 'collection_lists.majia',
                    'collection_lists.type', 'collection_lists.item_number','collection_lists.last_item_id', 'threads.title as last_thread_title',
                    'collection_lists.viewed', 'collection_lists.collected', 'collection_lists.xianyu', 'collection_lists.shengfan', 'collection_lists.created_at', 'collection_lists.lastupdated_at'
                )
                ->orderBy('collection_lists.lastupdated_at','desc')
                ->paginate(config('constants.index_per_page'));
            break;
            case "5"://某人建立的所有收藏单
            return DB::table('collection_lists')
                ->join('users','users.id','=','collection_lists.user_id')
                ->leftjoin('threads',function($join)
                {
                    $join->whereIn('collection_lists.type',[1,2]);
                    $join->on('collection_lists.last_item_id','=','threads.id');
                })
                ->where([['collection_lists.user_id', '=', $user_id],['collection_lists.type', '<>', 4]])
                ->select('collection_lists.id', 'collection_lists.id as collection_list_id', 'collection_lists.private', 'collection_lists.title', 'collection_lists.brief', 'collection_lists.body', 'collection_lists.user_id', 'users.name', 'collection_lists.anonymous', 'collection_lists.majia',
                    'collection_lists.type', 'collection_lists.item_number', 'collection_lists.last_item_id', 'threads.title as last_thread_title',
                    'collection_lists.viewed', 'collection_lists.collected', 'collection_lists.xianyu', 'collection_lists.shengfan', 'collection_lists.created_at', 'collection_lists.lastupdated_at'
                )
                ->orderBy('collection_lists.lastupdated_at','desc')
                ->paginate(config('constants.index_per_page'));
            break;
            case "6"://所有收藏单
            return DB::table('collection_lists')
                ->join('users','users.id','=','collection_lists.user_id')
                ->leftjoin('threads',function($join)
                {
                    $join->whereIn('collection_lists.type',[1,2]);
                    $join->on('collection_lists.last_item_id','=','threads.id');
                })
                ->where('collection_lists.type', '<>', 4)
                ->select('collection_lists.id', 'collection_lists.id as collection_list_id', 'collection_lists.private', 'collection_lists.title', 'collection_lists.brief', 'collection_lists.body', 'collection_lists.user_id', 'users.name', 'collection_lists.anonymous', 'collection_lists.majia',
                    'collection_lists.type', 'collection_lists.item_number', 'collection_lists.last_item_id', 'threads.title as last_thread_title',
                    'collection_lists.viewed', 'collection_lists.collected', 'collection_lists.xianyu', 'collection_lists.shengfan', 'collection_lists.created_at', 'collection_lists.lastupdated_at'
                )
                ->orderBy('collection_lists.lastupdated_at','desc')
                ->paginate(config('constants.index_per_page'));
            break;
            default:
            echo "应该奖励什么呢？一个bug呀……";
            //这里的case4，5，6可以合并，回头搞一下
        endswitch;
    }

    public function collection_list_show(CollectionList $collection_list)
    {
        if(!Auth::check()||(Auth::id()!=$collection_list->user_id)){
            $collection_list->increment('viewed');
        }
        $collection_list->load('creator');
        $collected_items = $this->find_collected_items($collection_list->id, $collection_list->type, $collection_list->user_id);
        return view('collections.collections_list_show', compact('collected_items','collection_list'))->with('show_as_collections',2)->with('show_channel',true);
    }

    public function all_collection_index()
    {
        $collection_lists = $this->find_collected_items(0,6,0);
        return view('collections.collection_list_index', compact('collection_lists'))->with('show_as_collections',0)->with('active',2);
    }
}
