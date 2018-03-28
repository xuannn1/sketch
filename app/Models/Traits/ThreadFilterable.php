<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Auth;

trait ThreadFilterable
{
    public function scopeFilterBianyuan($query, $bianyuan)//0:notbianyuan, 1:both bianyuan and not bianyuan
    {
        if (!$bianyuan){
            return $query->where('bianyuan', '=', 0);
        }else{
            return $query;
        }
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

    public function scopeIsPublic($query)//1:only public; 0:all
    {
        return $query->where('public',1);
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

    public function scopeIsBook($query)
    {
        return $query->where('book_id','>',0);
    }

    public function scopeNotBook($query)
    {
        return $query->where('book_id',0);
    }

}
