<?php

namespace App\Models\Traits;

use Cache;
use Auth;
use Carbon;

trait RecordViewThreadTrait
{
	public function recordViewCount(){
		if(!Cache::has('viewThreadCacheInterval.'.$this->id)){//假如距离上次cache这个thread阅读次数的时间已经超过了默认时间，应该把它记下来了
			if(!Cache::has('viewThreadCount.'.$this->id)){ // 检查 viewThreadCount.$tid ?是否有点击数的数据？
				// 不存在的话，新建viewThreadCount.$tid，并存入新点击为1，时限1d
				 Cache::put('viewThreadCount.'.$this->id,1,Carbon::now()->addDay(1));
			}else{// 存在？的话， 取值，删除，修改thread->view，把这部分点击算进去。
				$value = (int)Cache::forget('viewThreadCount.'.$this->id);
				if($value>0){
					$this->increment('view_count', $value);
				}
			}
			Cache::put('viewThreadCacheInterval.'.$this->id,1,Carbon::now()->addHour(1));
		}else{
			if(!Cache::has('viewThreadCount.'.$this->id)){
				// 不存在的话，新建viewThreadCount.$tid，并存入1，时限1d
				 Cache::put('viewThreadCount.'.$this->id,1,Carbon::now()->addDay(1));
			}else{
				// 存在记数的话， 递增cache
				 Cache::increment('viewThreadCount.'.$this->id);
			}
		}
	}
	public function recordViewHistory(){
		if(!Auth::check()){
			return;
		}
		if(!Cache::has('ViewHistory.Uid'.Auth::id().'.Tid.'.$this->id)){
			\App\Models\HistoricalUsersView::create([
				'user_id' => Auth::id(),
				'thread_id' => $this->id,
			]);
			Cache::put('ViewHistory.Uid'.Auth::id().'.Tid.'.$this->id,1,Carbon::now()->addDay(1));
		}
	}
}
