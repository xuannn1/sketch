<?php

namespace App\Models\Traits;

use Carbon;

trait QiandaoTrait
{
	public function qiandao(){
		$info = $this->info;

		// 计算连续签到天数
		if ($this->qiandao_at > Carbon::now()->subDays(2)) {
			$info->qiandao_continued+=1;
			if($info->qiandao_continued>$info->qiandao_max){$info->qiandao_max = $info->qiandao_continued;}
		}else{
			$info->qiandao_last = $info->qiandao_continued;
			$info->qiandao_continued=1;
		}
		$info->qiandao_all+=1;
		\App\Models\Checkin::create(['user_id' => $this->id]);

		// 更新签到天数
		$this->qiandao_at = Carbon::now();
		$message = "你已成功签到！连续签到".$info->qiandao_continued."天！";

		//根据连续签到时间发放奖励
		$reward_base = 1;
		if(($info->qiandao_continued>=5)&&($info->qiandao_continued%10==0)){
			$reward_base = intval($info->qiandao_continued/10)+2;
			if($reward_base > 5){$reward_base = 5;}
			$message .="你获得了特殊奖励！";
		}
		$info->rewardData(5*$reward_base, 1*$reward_base, 0);
		// 更新每日私信数量
		$info->message_limit = $this->level-4;
		$info->save();
		$this->save();

		if($this->checklevelup()){
			$message .="你的个人等级已提高!";
		}

		//补签卡专区
		if($info->qiandao_continued==1&&$info->qiandao_last>1&&$info->qiandao_reward_limit>0){
			$message .= "监测到你已断签且具有补签额度，此前签到".$info->qiandao_last."天，".'<a href="'. route('donation.mydonations'). '">前去补签</a> 。';
		}
		// TODO:在这里加上补签的链接

		return $message;
	}
}
