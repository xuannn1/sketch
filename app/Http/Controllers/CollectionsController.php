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

class CollectionsController extends Controller
{
    use CollectionObjectTraits;
    use FindThreadTrait;

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
        $orderby = 0;
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
        ->paginate(config('preference.threads_per_page'));

        $collections->load('thread.author','thread.tags','thread.last_post','thread.last_component');

        return view('collections.index',compact('user','info','collections','groups','collections','default_collection_updates'))->with(['show_collection_tab'=>$request->group??'default']);
    }


    public function store($thread)
    {
        if(!$this->chechCollectedOrNot(Auth::id(), (int)request('thread'))){
            $collection = Collection::create([
                'thread_id' => request('thread'),
                'user_id' => Auth::id(),
                'group' => request('group')??0,
            ]);
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
            $updated = (int)request()->keep_updated;
            if($updated===0||$updated===1){
                $collection->update([
                    'keep_updated' => $updated,
                ]);

            }
        }

        if(request()->group){
            $group = (int)request()->group;
            if($group>0){
                $collection_group = CollectionGroup::find(request()->group);
                if(!$collection_group||$collection_group->user_id!=Auth::id()){
                    return 'not your collection group! cannot update';
                }
            }
            $collection->update([
                'group' => $group,
            ]);
        }

        return [
            'success' => 'collection updated',
            'collection' => $collection,
        ];
    }

    public function destroy(Collection $collection, Request $request)
    {
        if($collection->user_id===Auth::id()){
            $thread_id = $collection->thread_id;
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
