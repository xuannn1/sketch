<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Vote;



class StoreVote extends FormRequest
{
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
            'votable_type' => 'required|string|max:20',
            'votable_id' => 'required|numeric',
            'attitude' => 'required|string|max:8',
        ];
    }

    

    public function validateAttitude($attitude,$voted_model,$user_id){
        //检查投票类型是否符合要求
        //可以同时赞和搞笑,踩和折叠
        if(in_array($attitude, array('upvote','funnyvote'))){

            $votes=$voted_model->votes()->where('user_id',$user_id)->get();

            return $votes->whereIn('attitude',['downvote','foldvote'])->isEmpty();

        }elseif(in_array($attitude, array('downvote','foldvote'))){

            $votes=$voted_model->votes()->where('user_id',$user_id)->get();

            return $votes->whereIn('attitude',['upvote','funnyvote'])->isEmpty();

        }else{
            return false;
        }
    }

    

    public function generateVote($voted_model){

        $vote_data = $this->only('attitude');
        $user_id=auth('api')->id();    

        if(!$this->validateAttitude($vote_data['attitude'],$voted_model,$user_id)){
            abort(403); //检查投票类型是否符合规范
        }
        
        $vote=$voted_model->votes()->create([
            'user_id'=>$user_id,
            'attitude'=>$vote_data['attitude']
        ]);

        return $vote;

    }

    


    

}
