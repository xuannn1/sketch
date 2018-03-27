<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Auth;

trait ThreadFilterable
{
    public function scopeBianyuan($query,$bianyuan=0)//0:notbianyuan, 1:both bianyuan and not bianyuan
    {
        return $query->where('bianyuan', '<=', $bianyuan);
    }

    public function scopeInChannel($query,$channel)
    {
        if ($channel){
            return $query->where('channel_id', '=', $channel);
        }else{
            return $query;
        }
    }

    public function scopePublic($query,$public=1)//1:only public; 0:
    {
        return $query->where('public', '>=', $public);
    }

    public function scopeInLabel($query,$label)
    {
        if ($label){
            return $query->where('label_id', '=', $label);
        }else{
            return $query;
        }
    }

    public function scopeCanSee($query)
    {
        $usergroup=config('constants.default_user_group');
        if(Auth::check()){
            $usergroup = Auth::user()->group;
        }
        return $query->whereHas('channel', function($q) use ($usergroup){
            $q->where('channel_state','<=',$usergroup);
        });
    }


}
