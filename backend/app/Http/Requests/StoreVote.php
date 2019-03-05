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

    public function getVotedModel($vote_data){
        //获得被投票的对象       
        $voted_model=$vote_data['votable_type']::where('id',$vote_data['votable_id'])->first();
        return $voted_model;
    }


    public function validateAttitude($attitude,$voted_model,$user_id){
        //检查投票类型是否符合要求
        

        if(in_array($attitude, array('upvote','funnyvote'))){
            $votes=$voted_model->votes()
                ->where('user_id',$user_id)
                ->where('attitude', 'like', 'downvote')
                ->orWhere('attitude', 'like', 'foldvote')
                ->get();
            return $votes->isEmpty();

        }elseif(in_array($attitude, array('downvote','foldvote'))){
            $votes=$voted_model->votes()
                ->where('user_id',$user_id)
                ->where('attitude', 'like', 'upvote')
                ->orWhere('attitude', 'like', 'funnyvote')
                ->get();

            return $votes->isEmpty();
        }else{
            return false;
        }
    }

    

    public function generateVote(){

        $vote_data = $this->only('votable_type','votable_id','attitude');
        $user_id=auth('api')->id();    

        $voted_model=$this->getVotedModel($vote_data);

        if(empty($voted_model)){abort(410);} //检查被投票的对象是否存在
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
