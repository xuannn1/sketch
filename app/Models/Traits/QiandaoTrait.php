<?php

namespace App\Models\Traits;

use Carbon;

trait QiandaoTrait
{
	public function qiandao(){
		$info = $this->info;

		// 计算连续签到天数
		if ($this->qiandao_at > Carbon::now()->subdays(2)) {
			$info->qiandao_continued+=1;
			if($info->qiandao_continued>$info->qiandao_max){$info->qiandao_max = $info->qiandao_continued;}
		}else{
			$info->qiandao_continued=1;
		}

		// 更新签到天数
		$this->qiandao_at = Carbon::now();
		$message = "您已成功签到！连续签到".$info->continued_qiandao."天！";

		//根据连续签到时间发放奖励
		$reward_base = 1;
		if(($info->continued_qiandao>=5)&&($info->continued_qiandao%5==0)){
			$reward_base = intval($info->continued_qiandao/10)+2;
			if($reward_base > 10){$reward_base = 10;}
			$message .="您获得了特殊奖励！";
		}
		$info->rewardData(5*$reward_base, 1*$reward_base, 0);
		// 更新每日私信数量
		$info->message_limit = $this->level-4;
		$info->save();
		$this->save();

		if($this->checklevelup()){
			$message .="您的个人等级已提高!";
		}

		return $message;

	}
}
