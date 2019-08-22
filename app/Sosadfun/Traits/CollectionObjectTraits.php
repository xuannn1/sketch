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

    public function checkCollectedOrNot($user_id, $thread_id)
    {
        $collection = \App\Models\Collection::on('mysql::write')->where('user_id',$user_id)->where('thread_id',$thread_id)
        ->first();
        return $collection? true:false;
    }

    public function findCollectionIndex($id, $group, $page)
    {
        return Cache::remember('collectionIndexU.'.$id.'Group.'.($group?$group->id:0).'P.'.$page, 15, function () use($id, $group, $page){
            $orderby = 2;
            $group_id = 0;
            if($group){
                $orderby = $group->order_by;
                $group->update_count();
                $group_id = $group->id;
            }
            $collections = \App\Models\Collection::join('threads', 'threads.id', '=', 'collections.thread_id')
            ->where('collections.user_id', $id)
            ->where('collections.group', $group_id)
            ->threadOrdered($orderby)
            ->select('collections.*')
            ->paginate(config('preference.threads_per_page'))
            ->appends(['group'=>$group_id, 'page'=>$page]);
            $collections->load('thread.author','thread.tags','thread.last_post','thread.last_component');
            return $collections;
        });
    }

    public function clearCollectionIndex($id, $group)
    {
        Cache::forget('collectionIndexU.'.$id.'Group.'.($group?$group->id:0).'P.1');
    }
}
