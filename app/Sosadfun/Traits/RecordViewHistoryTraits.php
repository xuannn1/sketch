<?php
namespace App\Sosadfun\Traits;

use Cache;
use Carbon;
use Auth;

trait RecordViewHistoryTraits{
    public function recordViewHistory($ip='', $uid=0, $tid=0)
    {
        if(Auth::check()) {
            if (!Cache::has('ip'.$ip.'uid'.$uid.'tid'.$tid)){
                Cache::put('ip'.$ip.'uid'.$uid.'tid'.$tid,1,Carbon::now()->addMinutes(30));
                $record = \App\Models\ViewHistory::create([
                    'ip_address' => $ip,
                    'user_id' => $uid,
                    'thread_id' => $tid,
                    'post_id' => 0,
                ]);
            }
        }
    }
}
