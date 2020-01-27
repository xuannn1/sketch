<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cache;

class QiandaoController extends Controller
{

    public function qiandao()
    {
        //TODO
        // $user = Auth::user();
        // $info = $user->info;
        // if($user->qiandao_at > Carbon::today()->subHours(2)->toDateTimeString()){
        //     return back()->with("info", "你已领取奖励，请勿重复签到");
        // }
        // $message = $user->qiandao();
        // return back()->with("success", $message);
    }

    public function complement_qiandao()
    {
        // 补签
        // $user = Auth::user();
        // $info = $user->info;
        // if($info->qiandao_reward_limit <=0){
        //     return back()->with("warning", "你的补签额度不足");
        // }
        // if($info->qiandao_continued >$info->qiandao_last){
        //     return back()->with("info", "你的连续签到天数超过了上次断签天数，无需补签");
        // }
        //
        // $info->complement_qiandao();
        // return back()->with("success", '成功补签');
    }
}
