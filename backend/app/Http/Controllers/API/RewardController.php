<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Reward;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReward;
use App\Http\Resources\RewardResource;
use App\Sosadfun\Traits\FindModelTrait;

class RewardController extends Controller
{
    //
    use FindModelTrait;
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    private function findRewardableModel($request)
    {
        if (!array_key_exists('rewardable_type', $request)
        || !array_key_exists('rewardable_id', $request)){
            return false;
        }
        return $this->findModel(
            $request['rewardable_type'],
            $request['rewardable_id'],
            array('Post','Thread')
        );
    }

    public function index(Request $request)
    {
        
        $rewarded_model=$this->findRewardableModel($request->all());
        if(empty($rewarded_model)){abort(404);}

        $rewards=$rewarded_model->rewards;
        return response()->success([
            'rewards' => RewardResource::collection($rewards),
        ]);
    }

    public function store(StoreReward $form)
    {
        //TODO：打赏和被打赏用户之间转账
        $rewarded_model=$this->findRewardableModel($form->all());
        if(empty($rewarded_model)){abort(404);} //检查被投票的对象是否存在

        $reward = $form->generateReward($rewarded_model);
        return response()->success(new RewardResource($reward));
    }


    public function destroy(Reward $reward)
    {
        //TODO：打赏和被打赏用户之间转账
        if(!$reward){abort(404);}
        if($reward->user_id!=auth('api')->id()){abort(403);}
        $reward->delete();
        
        return response()->success('deleted');
    }
}
