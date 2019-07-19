<?php
namespace App\Sosadfun\Traits;

use Cache;

trait StatusObjectTraits{

    public function statusProfile($id)
    {
        return Cache::remember('status.'.$id, 15, function () use($id) {
            $status = \App\Models\Status::find($id);
            if(!$status){
                return;
            }
            $status->load('author.title');

            $status->setAttribute('recent_rewards', $status->latest_rewards());
            $status->setAttribute('recent_upvotes', $status->latest_upvotes());

            return $status;
        });
    }

}
