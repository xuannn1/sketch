<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use DB;
use StringProcess;
use App\Models\Thread;
use App\Models\Post;
use App\Models\PostInfo;
use Carbon;
use App\Sosadfun\Traits\GeneratePostDataTraits;

class StoreChapter extends FormRequest
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
            'title' => 'required|string|max:30',
            'brief' => 'required|string|max:40',
            'body' => 'required|string|min:15|max:20000',
            'annotation' => 'max:2000',
            'warning' => 'max:200',
        ];
    }

    public function generateChapter($thread){
        $post_data = $this->generatePostData($thread);
        $post_data['type'] = 'chapter';
        $post_data['brief'] = $this->brief;
        $post_data['is_anonymous']=$thread->is_anonymous;
        $post_data['majia']=$thread->majia;
        if($this->is_bianyuan){
            $post_data['is_bianyuan']=true;
        }

        // chapter data
        $info_data['annotation']=StringProcess::trimSpaces($this->annotation);
        $info_data['warning']=StringProcess::trimSpaces($this->warning);

        $info_data['abstract']=StringProcess::trimtext($post_data['body'],150);

        $max_order_by = $thread->max_component_order();
        $info_data['order_by'] = $max_order_by ? $max_order_by+1 : 1;
        $post = DB::transaction(function()use($post_data, $info_data){
            $post = Post::create($post_data);
            $info_data['post_id']=$post->id;
            $info = PostInfo::create($info_data);
            return $post;
        });
        return $post;
    }

    public function updateChapter($post, $thread)
    {
        $old_post = $post;
        $info = $post->info;
        $post_data = $this->generateUpdatePostData($post, $thread);
        $post_data['brief'] = $this->brief;
        $post_data['is_anonymous']=$thread->is_anonymous;
        $post_data['is_bianyuan']= ($thread->is_bianyuan||$this->is_bianyuan)? true:false;
        $info_data = $this->only('warning','annotation');
        $info_data['annotation']=StringProcess::trimSpaces($info_data['annotation']);
        $info_data['warning']=StringProcess::trimSpaces($info_data['warning']);

        $info_data['abstract']=StringProcess::trimtext($post_data['body'],150);

        $post->update($post_data);
        $info->update($info_data);

        $this->check_length($old_post,$post);

        return $post;

    }
}
