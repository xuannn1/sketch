<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Auth;

trait PostFilterable
{

    public function scopeAllPosts($query,$thread,$mainpost)
    {
        return $query->where('id','<>',$mainpost)->where('thread_id','=',$thread);
    }
    public function scopeUserOnly($query,$useronly)
    {
        if($useronly){
            return $query->where('user_id','=',$useronly)->where('anonymous','=',0);
        }else{
            return $query;
        }

    }
}
