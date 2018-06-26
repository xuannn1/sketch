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
    // public function store(Thread $thread)//最简单的收藏某个thread
    // {
    //     $data =[];
    //     $collection = $thread->collection(Auth::id(),0);
    //     if($collection){
    //         $data['info']="您已收藏本文，无需重复收藏~";
    //     }else{
    //         $collecttion = Collection::create([
    //             'user_id' => Auth::id(),
    //             'item_id' => $thread->id,
    //         ]);
    //         $thread->increment('collection');
    //         $user->update(['lastresponded_at' => Carbon::now()]);
    //         $data['success']="您已成功收藏本文";
    //         $data['collection']=$thread->collection;
    //     }
    //     return $data;
    // }
    public function storeitem(Request $request) //itemid,itemtype,listid
    {
        $data =[];
        $collection_list = CollectionList::find(request('collection_list_id'));
        if((!$collection_list)||($collection_list->user_id==Auth::id())){
            if((request('item_type')==1)||(request('item_type')==2)){
                $thread = Thread::find(request('item_id'));
                $collection = $thread->collection(Auth::id(),request('collection_list_id'));
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
                        $collection_list->update(['lastresponded_at'=>Carbon::now() ]);
                    }
                    $thread->increment('collection');
                    Auth::user()->update(['lastresponded_at' => Carbon::now() ]);
                    $data['success']="您已成功收藏本文";
                    $data['collection']=$thread->collection;
                }
            }elseif(request('item_type')==4){
                $collection_list_to_add = CollectionList::find(request('item_id'));
                $master_collection_list = Auth::user()->collected_list;
                if($master_collection_list->id>0){
                    $collection = Collection::where('user_id',Auth::id())->where('collection_list_id',$master_collection_list->id)->where('item_id',$collection_list_to_add->id)->first();
                    if($collection){
                        $data['info']="您已收藏本文，无需重复收藏~";
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
                $collection_list_to_add->increment('collected');
                $data['success']="您已收藏本文!";
                $data['collection']=$collection_list_to_add->collected;
            }
            return $data;
        }

    }

    public function cancel(Request $request)
    {
        if((request('item_type')==1)||(request('item_type')==2)){
            $thread = Thread::find(request('item_id'));
            $collection = $thread->collection(Auth::id(),request('collection_list_id'));
            if($collection){
                $collection->collection_list->decrement('item_number');
                $collection->delete();
                $thread->decrement('collection');
            }else{
                return redirect()->route('error', ['error_code' => '409']);
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
        $own_collection_lists = Auth::user()->own_collection_lists->load('creator');
        $collected_lists = Auth::user()->collected_list->collected_items->load('creator');
        $updates = [Auth::user()->collection_books_updated,Auth::user()->collection_threads_updated,Auth::user()->collection_statuses_updated, Auth::user()->collection_lists_updated];
        $collections = true;
        Auth::user()->collection_lists_updated = 0;
        Auth::user()->save();
        return view('collections.collections_lists', compact('own_collection_lists','collected_lists','user','updates','collections'))->with('show_as_collections',1)->with('active',3);
    }

    public function collection_list_store(Request $request)
    {
        if ($own_collection_lists = Auth::user()->own_collection_lists->count() < Auth::user()->user_level-2){
            $this->validate($request, [
                'collection_type' => 'required|numeric|min:1|max:3',
                'title' => 'required|string|max:20',
                'brief' => 'required|string|max:50',
                'body' => 'nullable|string|max:5000',
            ]);
            $data = $request->only('title','brief','body');
            $data['user_id'] = auth()->id();
            $data['type'] = request('collection_type');
            $data['lastupdated_at'] = Carbon::now();
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
            ->select('books.*','threads.*','users.name','labels.labelname', 'chapters.title as last_chapter_title','chapters.responded as last_chapter_responded', 'collections.updated as updated','collections.keep_updated as keep_updated', 'chapters.post_id as last_chapter_post_id','tongrens.tongren_yuanzhu','tongrens.tongren_cp','tongrens.tongren_yuanzhu_tag_id','tongrens.tongren_cp_tag_id','tongren_yuanzhu_tags.tagname as tongren_yuanzhu_tagname','tongren_cp_tags.tagname as tongren_cp_tagname')
            ->orderBy('books.lastaddedchapter_at','desc')
            ->paginate(config('constants.index_per_page'));
            break;
            case "2"://讨论帖收藏单
            return $this->join_thread_tables()
            ->join('collections','collections.item_id','=','threads.id')
            ->where([['collections.user_id','=',$user_id],['collections.collection_list_id','=',$collection_list_id], ['threads.deleted_at', '=', null],['threads.book_id','=',0]])
            ->select('threads.*', 'users.name', 'labels.labelname', 'channels.channelname' , 'posts.body as last_post_body', 'chapters.title as last_chapter_title', 'chapters.responded as last_chapter_responded', 'collections.updated as updated', 'collections.keep_updated as keep_updated', 'chapters.post_id as last_chapter_post_id', 'tongrens.tongren_yuanzhu', 'tongrens.tongren_cp', 'tongrens.tongren_yuanzhu_tag_id', 'tongrens.tongren_cp_tag_id', 'tongren_yuanzhu_tags.tagname as tongren_yuanzhu_tagname', 'tongren_cp_tags.tagname as tongren_cp_tagname', 'collections.updated as updated', 'collections.keep_updated as keep_updated')
            ->orderBy('threads.lastresponded_at','desc')
            ->paginate(config('constants.items_per_page'));
            break;
            default:
            echo "应该奖励什么呢？一个bug呀……";
        endswitch;

    }

    public function collection_list_show(CollectionList $collection_list)
    {
        $collection_list->load('creator');
        $collected_items = $this->find_collected_items($collection_list->id, $collection_list->type, $collection_list->user_id);
        return view('collections.collections_list_show', compact('collected_items','collection_list'))->with('show_as_collections',2)->with('show_channel',true);
    }

    public function all_collection_index()
    {
        $collection_lists = CollectionList::with('creator')->where('type','<>',4)->where('private',false)->orderBy('lastupdated_at','desc')->simplePaginate(config('constants.index_per_page'));
        return view('collections.collection_list_index', compact('collection_lists'));
    }
}
