<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// common
use Carbon\Carbon;
use App\Helpers\ConstantObjects;
use App\Helpers\StringProcess;

// model
use App\Models\Thread;
use App\Models\Post;
use App\Models\Chapter;

// form request
use App\Http\Requests\StorePost;
use DB;

class StoreChapter extends FormRequest
{
    /**
    * Determine if the user is authorized to make this request.
    *
    * @return bool
    */
    public function authorize()
    {
        $thread = request()->route('thread');

        return (($thread->is_public)&&(!$thread->no_reply))||(auth('api')->id()===$thread->user_id)||(auth('api')->user()->canManageChannel($thread->channel_id));
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // for post record
            'body' => 'required|string|max:20000',
            'majia' => 'string|max:10'
        ];
    }

    public function generateChapter()
    {
        // check previous first
        // connect with previous and next
        $previous_chapter_id = $this->previous_chapter_id;
        
        if ($previous_chapter_id){
            $previous_chapter = $this->getPrevious($previous_chapter_id);
        }else{
            $previous_chapter = NULL;
        }

        // create post first
        $postid = $this->generatePost()->id;
        $chapter['post_id'] = $postid;
        $chapter['characters'] = mb_strlen($this->body);
        
        //  add additional information 
        if ($previous_chapter){
            $chapter['order_by'] = $previous_chapter->order_by + 1;
            $chapter['previous_chapter_id'] = $previous_chapter_id;
            $chapter['volumn_id'] = $previous_chapter->volumn_id; //默认跟前面的同一volumn      
        }
        
        // save 把所有东西放进transaction里
        $chapter_obj = DB::transaction(function () use($chapter,$previous_chapter,$postid) {
                if ($previous_chapter){ 
                    $previous_update_data['next_chapter_id'] = $postid;
                    $previous_chapter->update($previous_update_data);
                    $this->updatePost($previous_chapter->post_id);
                }
                $chapter_obj = Chapter::create($chapter);
                return $chapter_obj;
            });
        return $chapter_obj;
    }

    private function generatePost()
    {
        // basicly copy from storepost
        $thread = request()->route('thread');
        $channel = ConstantObjects::allChannels()->keyBy('id')->get($thread->channel_id);
        $post['body'] = $this->body;
        $post['thread_id'] = $thread->id;
        // chapter有brief用brief，没有用previes
        $post['preview'] = StringProcess::trimtext($post['body'], config('constants.preview_len'));//这里存放节选的正文
        $post['creation_ip'] = request()->getClientIp();
        
        $post['is_anonymous']=0; // 去掉马甲
        $post['type'] = 'chapter'; // add type
    
        $post['use_markdown']=$this->use_markdown ? true:false;
        $post['use_indentation']=$this->use_indentation ? true:false;
        $post['is_bianyuan']=$this->is_bianyuan ? true:false;
        $post['last_responded_at']=Carbon::now();
        $post['user_id'] = auth('api')->id();
        if (!$this->isDuplicatePost($post)){
            $post = DB::transaction(function () use($post) {
                $post = Post::create($post);
                return $post;
            });
        }else{ abort(409); }
        return $post;
    }

    private function isDuplicatePost($post)
    {
        $last_post = Post::where('user_id', auth('api')->id())
        ->orderBy('created_at', 'desc')
        ->first();
        return (!empty($last_post)) && (strcmp($last_post->body, $post['body']) === 0);
    }


    private function getPrevious($previous_chapter_id)
    {
        // check whether previous chapter exist 
        $chapter = Chapter::where('post_id','=',$previous_chapter_id)->first();
        $check_previous = ($chapter && ($chapter->next_chapter_id == 0));
        if (!$check_previous == 1){ abort(595); } 
        return $chapter;
    }

    private function updatePost($postid)
    {
        // get corresponding post and update
        $post = Post::where('id','=', $postid)->first();
        if (!$post) {abort(404); }
        $data['last_edited_at']=Carbon::now();
        $post->update($data);
        return;
    }
}
