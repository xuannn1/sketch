<?php
namespace App\Sosadfun\Traits;

use Cache;
use Carbon\Carbon;
use Auth;
use App\Models\ViewHistory;

trait RecordViewHistoryTraits{
    public function recordViewHistory($ip='', $uid=0, $tid=0, $pid=0)
    {
        if(Auth::check()) {
            if (!Cache::has('ip'.$ip.'uid'.$uid.'tid'.$tid.'pid'.$pid)){
                Cache::put('ip'.$ip.'uid'.$uid.'tid'.$tid.'pid'.$pid,1,Carbon::now()->addMinutes(30));
                $record = ViewHistory::create([
                    'ip_address' => $ip,
                    'user_id' => $uid,
                    'thread_id' => $tid,
                    'post_id' => $pid,
                ]);
            }
        }
    }
}
