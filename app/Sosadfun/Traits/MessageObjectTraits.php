<?php
namespace App\Sosadfun\Traits;

use DB;
use Cache;
use ConstantObjects;

trait MessageObjectTraits{

    public function findAllPulicNotices(){
        return Cache::remember('publicNotices', 15, function () {
            return \App\Models\PublicNotice::with('author')
            ->orderBy('created_at','desc')
            ->get();
        });
    }
    public function refreshPulicNotices(){
        return Cache::forget('publicNotices');
    }
    public function findPulicNotices($id){
        // 寻找id大于给定id的public notice
        return $this->findAllPulicNotices()->where('id','>',$id);
    }
    public function findLastPulicNotices($id){
        return Cache::remember('LastPublicNotice', 15, function () {
            return $this->findAllPulicNotices()->sortByDesc('id')->take(1);
        });
    }

    public function count_messagebox_reminders($user, $info){
        return $info->message_reminders
        +$info->administration_reminders
        +ConstantObjects::system_variable()->latest_public_notice_id
        - $user->public_notice_id;
    }

    public function count_activity_reminders($user,$info){
        return $info->reply_reminders+$info->upvote_reminders+$info->reward_reminders;
    }
}
