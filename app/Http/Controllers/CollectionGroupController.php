<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collection;
use App\Models\CollectionGroup;
use Carbon;
use DB;
use Auth;
use CacheUser;
use App\Sosadfun\Traits\CollectionObjectTraits;


class CollectionGroupController extends Controller
{
    use CollectionObjectTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $user = CacheUser::Auser();

        $groups = $this->findCollectionGroups($user->id);

        if($groups->count()<$user->level){
            return view('collections.group_create');
        }
        return redirect()->back()->with('warning','你的等级不足，无法建立更多收藏页');
    }

    public function store(Request $request)
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();

        $groups = $this->findCollectionGroups($user->id);

        if($groups->count()<$user->level){
            $collection_group = CollectionGroup::create([
                'name' => request('name'),
                'user_id' => $user->id,
                'order_by' => request('order_by')
            ]);
            $this->refreshCollectionGroups($user->id);
            if(request()->set_as_default_group){
                $info->update([
                    'default_collection_group_id' => $collection_group->id,
                ]);
            }
            return redirect()->route('collection.index');
        }
        return redirect()->back()->with('warning','你的等级不足，无法建立更多收藏页');
    }

    public function edit(CollectionGroup $collection_group)
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();

        if($collection_group->user_id!=$user->id){ //必须本人才能修改自己的收藏页
            abort(403);
        }

        return view('collections.group_edit', compact('collection_group','info'));
    }

    public function update(CollectionGroup $collection_group, Request $request)
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();

        if($collection_group->user_id!=$user->id){ //必须本人才能修改自己的收藏页
            abort(403);
        }


        $collection_group->update([
            'name' => request()->name,
            'order_by' => request()->order_by,
        ]);
        $this->refreshCollectionGroups($user->id);

        if(request()->set_as_default_group&&$info->default_collection_group_id!=$collection_group->id){
            $info->update([
                'default_collection_group_id' => $collection_group->id,
            ]);
        }

        if(!request()->set_as_default_group&&$info->default_collection_group_id===$collection_group->id){
            $info->update([
                'default_collection_group_id' => 0,
            ]);
        }

        return redirect()->route('collection.index');
    }

    public function destroy(CollectionGroup $collection_group)
    {
        $collections = DB::table('collections')
        ->join('threads', 'threads.id', '=', 'collections.thread_id')
        ->where('collections.user_id', Auth::id())
        ->where('collections.group',$collection_group->id)
        ->update(['collections.group'=>0]);

        $info = Auth::user()->info;

        if($info->default_collection_group_id===$collection_group->id){
            $info->update(['default_collection_group_id'=>0]);
        }

        $collection_group->delete();

        $this->refreshCollectionGroups(Auth::id());

        return redirect()->route('collection.index')->with('success','你已成功删除收藏夹');
    }


}
