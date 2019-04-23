<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreReward extends FormRequest
{
    protected $rewards = array('shengfan','xianyu','sangdian','jifen');
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'rewardable_type' => 'required|string|max:20',
            'rewardable_id' => 'required|numeric',
            'attribute' => 'required|string|max:10',
            'value' => 'required|numeric|between:1,10',
        ];
    }

    public function validateDateAndAttribite($rewarded_model,$reward_data){
        
        return $rewarded_model->rewards()
        ->where('user_id',$reward_data['user_id'])
        ->where('created_at',$reward_data['created_at'])
        ->where('attribute',$reward_data['attribute'])
        ->get()->isEmpty();

    }

    public function validateBalance($reward_data,$user){
        return $user->info()->value($reward_data['attribute']) >= $reward_data['value'];

    }

    public function generateReward($rewarded_model){
        if(!in_array($this->attribute, $this->rewards)){abort(422);}

        $reward_data = $this->only('attribute','value');
        $user = auth('api')->user();
        $reward_data['user_id'] = $user->id;
        $reward_data['created_at'] = Carbon::now()->toDateString();

        if(!$this->validateDateAndAttribite($rewarded_model,$reward_data)){
            abort(409); //今天已经打赏过该奖励
        }

        if(!$this->validateBalance($reward_data,$user)){
            abort(409); //奖励余额是否充足
        }
        //TODO：打赏和被打赏用户之间转账

        $reward = $rewarded_model->rewards()->create($reward_data);

        return $reward;
    }
}
