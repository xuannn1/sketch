<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon;

class UserInfo extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'user_id';
    const UPDATED_AT = null;
    protected $dates = ['created_at','no_posting_until','no_logging_until','login_at','active_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rewardData($exp=0, $jifen=0, $shengfan=0, $xianyu=0, $sangdian=0)
    {
        $this->exp+=$exp;
        $this->jifen+=$jifen;
        $this->shengfan+=$shengfan;
        $this->xianyu+=$xianyu;
        $this->sangdian+=$sangdian;
    }

    public function reward($kind, $base = 0){
        switch ($kind):
            case "regular_status"://普通状态奖励
            $this->rewardData(1,1,1,0,0);
            break;
            case "regular_post"://普通回帖奖励
            $this->rewardData(2,3,0,1,0);
            break;
            case "long_post":// 长评
            $this->rewardData(5,5,5,3,1);
            break;
            case "first_post"://抢到新章节首杀
            $this->rewardData(4,4,0,2,0);
            break;
            case "regular_thread"://普通主题奖励
            $this->rewardData(5,5,0,3,0);
            break;
            case "regular_book"://普通书本奖励
            $this->rewardData(20,10,0,5,2);
            break;
            case "short_chapter"://短小章节奖励
            $this->rewardData(3,3,0,1,0);
            break;
            case "standard_chapter"://标准章节奖励
            $this->rewardData(5,5,0,1,1);
            break;
            case "upvoted_by_many":
            $this->rewardData(5,5,0,1,1);
            break;
            case "book_downloaded_as_thread":
            $this->rewardData(5,5,0,1,0);
            break;
            case "book_downloaded_as_book":
            $this->rewardData(10,10,0,2,0);
            break;
            case "homework_excellent":
            $this->rewardData(100,100,100,50,$base*3);
            break;
            case "homework_regular":
            $this->rewardData(50,50,50,20,$base*2);
            break;
            // case "online_reward"://保持登陆奖励
            // $info->reward(1,0,0,0,0);
            // break;
            case "first_quiz":// 首次答题奖励
            $this->rewardData(10,10,0,2,0);
            break;
            case "more_quiz":// 重复答题奖励
            $this->rewardData(5,0,5,0,0);
            break;
            default:
            echo "应该奖励什么呢？一个bug呀……";
        endswitch;
        $this->save();
    }

    public function activate(){
        $user = $this->user;
        $user->activated = true;
        $this->activation_token = null;
        $user->save();
        $this->save();
    }

    public function active_now($ip=null){
        $this->active_at = Carbon::now();
        $this->rewardData(1,0,0,0,0);
        $this->save();
    }


}
