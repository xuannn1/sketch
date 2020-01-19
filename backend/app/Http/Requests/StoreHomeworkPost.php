<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon;
use DB;
use StringProcess;
use Auth;
use App\Models\Post;
use App\Sosadfun\Traits\GeneratePostDataTraits;


class StoreHomeworkPost extends FormRequest
{
    use GeneratePostDataTraits;
    /**
    * Determine if the user is authorized to make this request.
    *
    * @return bool
    */
    public function authorize()
    {
        return true;
    }

    /**
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
    public function rules()
    {
        return [
            'body' => 'required|string|min:200|max:20000',
            'title' => 'string|nullable|max:30',
        ];
    }

    public function generateHomeworkWork($thread)
    {
        $post_data = $this->generatePostData($thread);
        $post_data['type'] = 'work';
        $post_data['is_anonymous']=$thread->is_anonymous;
        $post_data['majia']=$thread->majia;
        if($this->is_bianyuan){
            $post_data['is_bianyuan']=true;
        }
        $post = Post::create($post_data);
        return $post;
    }

    public function generateHomeworkCritique($thread)
    {
        $post_data = $this->generatePostData($thread);
        $post_data['type'] = 'critique';
        if($this->is_bianyuan){
            $post_data['is_bianyuan']=true;
        }
        $post = Post::create($post_data);
        return $post;
    }
}
