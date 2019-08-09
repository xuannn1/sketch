<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reward;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReward;
use App\Sosadfun\Traits\FindModelTrait;
use CacheUser;
use Cache;

class RewardController extends Controller
{
    //
    use FindModelTrait;
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function findRewardableModel($request)
    {
        if (!array_key_exists('rewardable_type', $request)
        || !array_key_exists('rewardable_id', $request)){
            return 'array_key_not_exist';
        }
        return $this->findModel(
            $request['rewardable_type'],
            $request['rewardable_id'],
            array('post','thread','quote','status')
        );
    }

    public function index(Request $request)
    {
        $type = $request->rewardable_type;
        $model=$this->findRewardableModel($request->all());
        if(!$model){abort(404);}

        $page = is_numeric($request->page)? 'P'.$request->page:'P1';
        $rewards = Cache::remember('rewardindex.'.$request->rewardable_type.$request->rewardable_id.$page, 15, function () use($request){
            $rewards = \App\Models\Reward::with('author')
            ->withType($request->rewardable_type)
            ->withId($request->rewardable_id)
            ->orderBy('created_at','desc')
            ->paginate(config('preference.rewards_per_page'))
            ->appends($request->only(['rewardable_type','rewardable_id']));
            return $rewards;
        });

        return view('rewards.index', compact('type', 'model', 'rewards'));
    }

    public function received(Request $request)
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();
        $info->clear_column('reward_reminders');
        $rewards = Reward::with('rewardable','author')
        ->where('receiver_id',$user->id)
        ->orderBy('created_at','desc')
        ->paginate(config('preference.rewards_per_page'));
        return view('rewards.index_received', compact('user', 'info', 'rewards'))->with('show_reward_tab','received');

    }

    public function sent(Request $request)
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();
        $rewards = Reward::with('rewardable','author')
        ->where('user_id',$user->id)
        ->orderBy('created_at','desc')
        ->paginate(config('preference.rewards_per_page'));
        return view('rewards.index_sent', compact('user', 'info', 'rewards'))->with('show_reward_tab','sent');
    }

    public function store(StoreReward $form)
    {
        $rewarded_model=$this->findRewardableModel($form->all());
        if(empty($rewarded_model)){abort(404);} //检查被投票的对象是否存在

        $reward = $form->generateReward($rewarded_model);
        return redirect()->back()->with('success', '已经成功打赏，请耐心等待缓存后更新打赏榜单');
    }


    public function destroy(Reward $reward)
    {
        if(!$reward){
            return [
                'danger' => '内容已删除或不存在'
            ];
        }
        $reward_id = $reward->id;
        if($reward->user_id!=auth()->id()){
            return [
                'danger' => "不能删除他人的打赏"
            ];
        }
        $reward->delete();

        return [
            'success' => "成功删除已有打赏",
            'reward_id' => $reward_id,
        ];
    }
}
