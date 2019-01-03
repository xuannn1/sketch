<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Models\Recommendation;
use Illuminate\Validation\Rule;
use DB;

class UpdateRecommendation extends FormRequest
{
    /**
    * Determine if the user is authorized to make this request.
    *
    * @return bool
    */
    public function authorize()
    {
        return auth('api')->user()->hasAccess(['can_review_recommendation','can_manage_anything'])||auth('api')->user()->recommendations->contains($this->route('recommendation')->id);
    }

    /**
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
    public function rules()
    {
        return [
            'brief' => 'bail|required|string|max:190',
            'body' => 'string',
            'users.*' => 'numeric',
        ];
    }

    public function updateRecommendation($recommendation)
    {
        $data = request()->only('brief', 'body');
        if(auth('api')->user()->hasAccess(['can_review_recommendation','can_manage_anything'])){
            $data['is_public']=$this->is_public ? true:false;
            $data['is_past']=$this->is_past ? true:false;
            $recommendation->users()->sync(json_decode(request()->users));
        }
        $recommendation->update($data);
        return $recommendation;
    }
}
