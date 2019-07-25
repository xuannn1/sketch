<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collection;
use App\Models\CollectionGroup;
use App\Models\Thread;
use Carbon;
use DB;
use Auth;
use CacheUser;
use App\Sosadfun\Traits\CollectionObjectTraits;
use App\Sosadfun\Traits\FindThreadTrait;
use App\Sosadfun\Traits\ListObjectTraits;


class CollectionController extends Controller
{
    use CollectionObjectTraits;
    use FindThreadTrait;
    use ListObjectTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();
        $default_collection_updates = $info->default_collection_updates;

        $groups = $this->findCollectionGroups($user->id);
        $lists = $this->findLists($user->id);
        $orderby = 2;
        if($request->group){
            $group = $groups->keyby('id')->get($request->group);
            $orderby = $group->order_by;
            $group->update_count();
        }
        $user->clear_column('unread_updates');
        $info->clear_column('default_collection_updates');

        $collections = Collection::join('threads', 'threads.id', '=', 'collections.thread_id')
        ->where('collections.user_id', $user->id)
        ->where('collections.group',(int)$request->group??0)
        ->threadOrdered($orderby)
        ->select('collections.*')
        ->paginate(config('preference.threads_per_page'))
        ->appends($request->only('group'));

        $collections->load('thread.author','thread.tags','thread.last_post','thread.last_component');
        $lists = $user->lists;

        return view('collections.index',compact('user','info','collections','groups','collections','default_collection_updates','order_by','lists'))->with(['show_collection_tab'=>$request->group??'default']);
    }


    public function store($id)
    {
        if(!$this->checkCollectedOrNot(Auth::id(), (int)request('thread'))){
            $group = request('group')??CacheUser::Ainfo()->default_collection_group_id;
            $thread = $this->findThread($id);
            $collection = Collection::create([
                'thread_id' => $id,
                'user_id' => Auth::id(),
                'group' => $group??0,
            ]);
            $thread->increment('collection_count');

            return [
                'success' => '您已成功收藏本文!',
                'collection'=> $collection,
            ];
        }
        return [
            'info' => '您已收藏过本文,请勿重复收藏!',
        ];

    }

    public function update(Collection $collection, Request $request)
    {
        if(!$collection||$collection->user_id!=Auth::id()){
            return 'notwork';
        }
        if(request()->keep_updated){
            if(request()->keep_updated==='nomoreupdate'){
                $collection->update([
                    'keep_updated' => 0,
                ]);
            }
            if(request()->keep_updated=='keepupdate'){
                $collection->update([
                    'keep_updated' => 1,
                ]);
            }
        }

        if(request()->group==='cancel'){
            $collection->update([
                'group' => 0,
            ]);
        }elseif(request()->group){
            $group = (int)request()->group;
            if($group>0){
                $collection->update([
                    'group' => $group,
                ]);
            }
        }

        return [
            'success' => 'collection updated',
            'collection' => $collection,
        ];
    }

    public function destroy(Collection $collection)
    {
        if($collection->user_id===Auth::id()){

            $thread_id = $collection->thread_id;

            $thread = $this->findThread($thread_id);
            if($thread){
                $thread->decrement('collection_count');
            }

            $collection->delete();
            return [
                'success'=> '已经成功删除收藏',
                'thread_id' => $thread_id,
            ];
        }
    }


    public function clearupdates()
    {
        $user = Auth::user();
        $info = CacheUser::Ainfo();
        Collection::where('user_id','=',$user->id)->update(['updated'=> 0]);
        CollectionGroup::where('user_id','=',$user->id)->update(['update_count'=> 0]);
        $this->refreshCollectionGroups($user->id);
        $user->clear_column('unread_updates');
        $info->clear_column('default_collection_updates');
        return redirect()->back()->with('success','已将所有更新标记为已读');
    }


}
