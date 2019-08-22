<?php

namespace App\Models\Traits;

use Cache;
use Auth;
use Carbon;

trait RecordViewTrait
{
	public function recordCount($countable='', $type=''){ // $type='post', 'thread', recordCount($countable='view','collection', $type='thread','post')

		//old recordViewCount
		if(!Cache::has($countable.$type.'CacheInterval.'.$this->id)){//假如距离上次cache这个post阅读次数的时间已经超过了默认时间，应该把它记下来了
			if(!Cache::has($countable.$type.'Count.'.$this->id)){ // 检查 viewPostCount.$tid ?是否有点击数的数据？
				// 不存在的话，新建viewPostCount.$tid，并存入新点击为0，时限1d
				 Cache::put($countable.$type.'Count.'.$this->id,1,1440);
				 $this->increment($countable.'_count', 1);//立刻在数据库计数这个第一次的点击
			}else{// 存在？的话， 取值，删除，修改post->view，把这部分点击算进去。
				$value = (int)Cache::pull($countable.$type.'Count.'.$this->id);
				if($value>1){ 
					$this->increment($countable.'_count', $value);
				}
			}
			Cache::put($countable.$type.'CacheInterval.'.$this->id,1,20);
		}else{
			if(!Cache::has($countable.$type.'Count.'.$this->id)){
				// 不存在的话，新建YYYXXXCount.$tid，并存入1，时限1d
				 Cache::put($countable.$type.'Count.'.$this->id,1,1440);
			}else{
				// 存在记数的话， 递增cache
				 Cache::increment($countable.$type.'Count.'.$this->id);
			}
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
			Cache::put('ViewHistory.Uid'.Auth::id().'.Tid.'.$this->id,1,1440);
		}
	}
}
