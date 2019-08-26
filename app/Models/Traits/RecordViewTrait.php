<?php

namespace App\Models\Traits;

use Cache;
use Auth;
use Carbon;

trait RecordViewTrait
{
	public function recordCount($countable='', $type=''){ // $type='post', 'thread', recordCount($countable='view','collection', $type='thread','post')

		if(!Cache::has($countable.$type.'Count.'.$this->id)){
			Cache::put($countable.$type.'Count.'.$this->id,1,10080);
		}else{
			Cache::increment($countable.$type.'Count.'.$this->id);
		}

		if(!Cache::has($countable.$type.'CacheInterval.'.$this->id)){//假如距离上次cache这个post阅读次数的时间已经超过了默认时间，应该把它记下来了
			$value = (int)Cache::get($countable.$type.'Count.'.$this->id);
			if($value>1){
				$this->increment($countable.'_count', $value);
				Cache::put($countable.$type.'Count.'.$this->id,0,10080);
			}
			Cache::put($countable.$type.'CacheInterval.'.$this->id,1,30);
		}
	}

	public function recordViewHistory(){// 只限于thread使用
		if(!Auth::check()){
			return;
		}
		if(!Cache::has('ViewHistory.Uid'.Auth::id().'.Tid.'.$this->id)){
			\App\Models\TodayUsersView::firstOrCreate([
				'user_id' => Auth::id(),
				'thread_id' => $this->id,
			]);
			Cache::put('ViewHistory.Uid'.Auth::id().'.Tid.'.$this->id,1,10080);//调整为7d记录一次，减少记录数量
		}
	}
}
