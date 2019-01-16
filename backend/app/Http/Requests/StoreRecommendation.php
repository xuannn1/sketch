<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Models\Thread;
use App\Models\Recommendation;
use Illuminate\Validation\Rule;
use DB;

class StoreRecommendation extends FormRequest
{
    /**
    * Determine if the user is authorized to make this request.
    *
    * @return bool
    */
    public function authorize()
    {
        return auth('api')->user()->canRecommend();
    }

    /**
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
    public function rules()
    {
        return [
            'thread' => [
                'required',
                'numeric',
                Rule::unique('recommendations')->where(function ($query) {
                    return $query->where('type', request()->type);
                }),
            ],
            'brief' => 'bail|required|string|max:190',
            'body' => 'string',
            'type' =>  [
                'required',
                Rule::in(['long', 'short', 'topic']),
            ],
            'users.*' => 'numeric',
        ];
    }

    public function generateRecommendation()
    {
        $thread = Thread::find(request()->thread);
        if (($thread)&&($thread->is_public)){
            $recommendation = Recommendation::create(request()->only('thread', 'brief', 'body', 'type'));
            $recommendation->authors()->sync(json_decode(request()->authors()??auth('api')->id()));
            return $recommendation;
        }else{
            abort(404);
        }
    }
}
