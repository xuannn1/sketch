<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Auth;

trait ThreadFilterable
{
    public function scopeIsNotBianyuan($query)
    {
        return $query->where('bianyuan', '=', false);
    }
    public function scopeIsBianyuan($query)
    {
        return $query->where('bianyuan', '=', true);
    }

    public function scopeInChannel($query,$channel)
    {
        if ($channel){
            return $query->where('channel_id', '=', $channel);
        }else{
            return $query;
        }
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
