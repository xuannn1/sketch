<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        if((!$this->is_bianyuan)||(auth('api')->check())){
            $body = $this->body;
        }else{
            $body = '';
        }
        if((!$this->is_anonymous)&&(($this->type==='post')||($this->type==='comment'))||((auth('api')->check())&&(auth('api')->id()===$this->user_id))){
            //如果标记了是匿名且是评论（不是评论的，只显示类型，不需要显示作者信息，根据类型可以判断作者身份，比如chapter必然是作者发布的），或者虽然不属于上述，但是是当前用户本人发布的，那么显示用户信息
            $author = new AuthorIdentifierResource($this->author);
        }else{
            $author = [];
        }
        $component = [];
        if($this->type==='chapter'){
            $component = new ChapterResource($this->chapter);
        }
        if($this->type==='review'){
            $component = new ReviewResource($this->review);
        }
        return [
            'type' => 'post',
            'id' => (int)$this->id,
            'attributes' => [
                'post_type' => (string) $this->type,
                'thread_id' => (int)$this->thread_id,
                'title' => (string)$this->title,
                'preview' => (string)$this->preview,
                'body' => (string)$body,
                'is_anonymous' => (bool)$this->is_anonymous,
                'majia' => (string)$this->majia,
                'created_at' => (string)$this->created_at,
                'last_edited_at' => (string)$this->last_edited_at,
                'reply_to_post_id' => (int)$this->reply_to_post_id,
                'reply_to_post_preview' => (string)$this->reply_to_post_preview,
                'use_markdown' => (bool)$this->markdown,
                'use_indentation' => (bool)$this->use_indentation,
                'up_votes' => (int)$this->up_votes,
                'xianyus' => (int)$this->xianyus,
                'shengfans' => (int)$this->shengfans,
                'replies' => (int)$this->replies,
                'is_folded' => (bool)$this->is_folded,
                'is_bianyuan' => (bool)$this->is_bianyuan,
                'last_responded_at' => (string)$this->last_responded_at,
            ],
            'author' => $author,
            'component' => $component,
        ];
    }
}
