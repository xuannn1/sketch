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

        $group = $request->group? $groups->keyby('id')->get($request->group):null;

        $user->clear_column('unread_updates');
        $info->clear_column('default_collection_updates');

        $page = is_numeric($request->page)? (int)$request->page:1;

        $collections = $this->findCollectionIndex($user->id, $group, $page);

        return view('collections.index',compact('user','info','collections','groups','collections','default_collection_updates','order_by','lists'))->with(['show_collection_tab'=>$group?$group->id:'default']);
    }


    public function store($id)
    {
        if(!$this->checkCollectedOrNot(Auth::id(), (int)request('thread'))){
            $groups = $this->findCollectionGroups(Auth::id());
            $group_id = request('group')??CacheUser::Ainfo()->default_collection_group_id;
            $group = $group_id>0? $groups->keyby('id')->get($group_id):null;
            $thread = $this->findThread($id);
            $collection = Collection::create([
                'thread_id' => $id,
                'user_id' => Auth::id(),
                'group' => $group? $group->id:0,
            ]);
            $thread->recordCount('collection', 'thread');
            $this->refreshCollectionIndex(Auth::id(), $group);

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
        $groups = $this->findCollectionGroups(Auth::id());
        $oldgroup = $collection->group>0? $groups->keyby('id')->get($collection->group):null;
        $newgroup = null;
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
            $newgroup_id = (int)request()->group;
            $newgroup = $newgroup_id>0? $groups->keyby('id')->get($newgroup_id):null;
            if($newgroup){
                $collection->update([
                    'group' => $newgroup->id,
                ]);
            }
        }
        $this->refreshCollectionIndex(Auth::id(), $oldgroup);
        $this->refreshCollectionIndex(Auth::id(), $newgroup);
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

            $group_id = $collection->group;
            $groups = $this->findCollectionGroups(Auth::id());
            $group = $group_id>0? $groups->keyby('id')->get($group_id):null;

            $this->refreshCollectionIndex(Auth::id(), $group);

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
