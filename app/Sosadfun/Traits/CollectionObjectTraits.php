<?php
namespace App\Sosadfun\Traits;

use Cache;

trait CollectionObjectTraits{

    public function findCollectionGroups($id)
    {
        return Cache::remember('collectionGroups.'.$id, 15, function () use($id){
            return \App\Models\CollectionGroup::where('user_id',$id)->get();
        });
    }

    public function chechCollectedOrNot($user_id, $thread_id)
    {
        return Collection::where('user_id',$user_id)->where('thread_id',$thread_id)
        ->first()? true:false;
    }
}
