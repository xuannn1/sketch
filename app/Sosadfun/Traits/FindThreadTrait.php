<?php
namespace App\Sosadfun\Traits;

use Cache;

trait FindThreadTrait{

    public function findThread($id)
    {
        return Cache::remember('thread.'.$id, 15, function () use($id){
            return \App\Models\Thread::find($id);
        });
    }
}
