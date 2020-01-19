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
use App\Sosadfun\Traits\FindThreadTrait;

class StoreReview extends FormRequest
{
    use GeneratePostDataTraits;
    use FindThreadTrait;

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
            'title' => 'nullable|string|max:30',
            'brief' => 'nullable|string|max:50',
            'body' => 'required|string|min:15|max:20000',
            'rating' => 'numeric|min:0|max:10',
            'reviewee_id' => 'numeric|min:0',
        ];
    }

    public function generateReview($thread){
        $post_data = $this->generatePostData($thread);
        $post_data['type'] = 'review';
        $post_data['brief'] = $this->brief;
        $post_data['is_anonymous']=$thread->is_anonymous;
        $post_data['majia']=$thread->majia;

        $info_data = $this->only('rating','reviewee_id');
        $info_data = $this->validateReviewee($info_data, $thread);
        $info_data['recommend'] = $this->recommend? true:false;

        $info_data['abstract']=StringProcess::trimtext($post_data['body'],150);

        $post_data = $this->validateBianyuan($post_data, $info_data, $thread);

        $post = DB::transaction(function()use($post_data, $info_data){
            $post = Post::create($post_data);
            $info_data['post_id']=$post->id;
            $info = PostInfo::create($info_data);
            return $post;
        });
        return $post;
    }

    public function updateReview($post, $thread)
    {
        $info = $post->info;

        $post_data = $this->generateUpdatePostData($post);
        $post_data['brief'] = $this->brief;
        $post_data['is_anonymous']=$thread->is_anonymous;

        $info_data = $this->only('rating','reviewee_id');
        $info_data = $this->validateReviewee($info_data, $thread);
        $info_data['recommend'] = $this->recommend? true:false;

        $info_data['abstract']=StringProcess::trimtext($post_data['body'],150);

        $post_data = $this->validateBianyuan($post_data, $info_data, $thread);

        $post->update($post_data);
        $info->update($info_data);

        return $post;

    }

    public function validateReviewee($info_data, $thread){
        // 如果填写的书籍id就是本帖的id，归零
        if($info_data['reviewee_id']==$thread->id){
            $info_data['reviewee_id']=0;
        }
        if($info_data['reviewee_id']>0){
            $info_data['reviewee_type']='thread';// TODO right now, reviewee has to be a thread
        }
        return $info_data;
    }

    public function validateBianyuan($post_data, $info_data, $thread){
        // 如果整个书评楼都是边限，这个评也属于边限
        if($thread->is_bianyuan){
            $post_data['is_bianyuan']=true;
        }
        // 如果被推荐对象是站内文章，且是边缘文，需要增加边缘标记
        if($info_data['reviewee_id']>0){
            $reviewee = $this->findThread($info_data['reviewee_id']);
            if($reviewee&&$reviewee->is_bianyuan){
                $post_data['is_bianyuan']=true;
            }
        }
        return $post_data;
    }
}
