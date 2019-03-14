<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Models\Vote;



class StoreVote extends FormRequest
{
    protected $attitudes = array('upvote','downvote','funnyvote','foldvote');
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
            'attitude' => 'required|string|max:10',
        ];
    }

    public function validateAttitude($voted_model,$attitude,$user_id){
        $votes = $voted_model->votes()->where('user_id',$user_id)->get();
        $check_attitude=in_array($attitude, array('upvote','downvote')) ? array('upvote','downvote'):array($attitude);
        return $votes->whereIn('attitude', $check_attitude)->isEmpty();
    }

    public function generateVote($voted_model){

        if(!in_array($this->attitude, $this->attitudes)){abort(422);}

        $vote_data = $this->only('attitude');
        $vote_data['user_id'] = auth('api')->id();

        if(!$this->validateAttitude($voted_model, $vote_data['attitude'], $vote_data['user_id'])){
            abort(409); //和已有投票冲突（可能是重复投票，也可能是已经赞还要踩）
        }

        $vote = $voted_model->votes()->create($vote_data);

        return $vote;

    }

}
