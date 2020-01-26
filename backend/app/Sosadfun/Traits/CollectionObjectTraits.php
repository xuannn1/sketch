<?php
namespace App\Sosadfun\Traits;

use Cache;

trait CollectionObjectTraits{

    public function findCollectionGroups($id)
    {
        return Cache::remember('collectionGroups.'.$id, 10, function () use($id){
            return \App\Models\CollectionGroup::where('user_id',$id)->get();
        });
    }

    public function clearCollectionGroups($id)
    {
        Cache::forget('collectionGroups.'.$id);
    }

    public function findCollectionIndex($id, $group_id, $order_by, $page)
    {
        $collections = \App\Models\Collection::join('threads', 'threads.id', '=', 'collections.thread_id')
        ->where('collections.user_id', $id)
        ->where('collections.group_id', $group_id)
        ->threadOrdered($order_by)
        ->select('collections.*')
        ->paginate(config('preference.threads_per_page'))
        ->appends(['group'=>$group_id, 'page'=>$page, 'order_by'=> $order_by]);
        $collections->load('briefThread.author','briefThread.tags','briefThread.last_post','briefThread.last_component');
        return $collections;
    }

    // public function clearCollectionIndex($id, $group)
    // {
    //     Cache::forget(url('/').'collectionIndexU.'.$id.'Group.'.($group?$group->id:0).'P.1');
    // }
}
