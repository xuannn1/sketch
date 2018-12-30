<?php

namespace App\Http\Resources\ThreadResources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AuthorIdentifierResource;

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
        if((!$this->is_anonymous)||((auth('api')->check())&&(auth('api')->id()===$this->user_id))){
            $author = new AuthorIdentifierResource($this->author);
        }else{
            $author = [];
        }
        return [
            'type' => 'post',
            'id' => $this->id,
            'attributes' => [
                'body' => $body,
                'is_anonymous' => $this->is_anonymous,
                'majia' => $this->majia ?? '匿名咸鱼',
                'created_at' => $this->created_at ? $this->created_at->toDateTimeString():null,
                'last_edited_at' => $this->last_edited_at ? $this->last_edited_at->toDateTimeString():null,
                'reply_to_post_id' => $this->reply_to_post_id,
                'is_maintext' => $this->is_maintext,
                'use_markdown' => $this->markdown,
                'use_indentation' => $this->use_indentation,
                'is_comment' => $this->is_comment,
                'up_votes' => $this->up_votes,
                'xianyus' => $this->xianyus,
                'shengfans' => $this->shengfans,
                'replies' => $this->replies,
                'is_folded' => $this->is_folded,
                'is_bianyuan' => $this->is_bianyuan,
                'last_responded_at' => $this->last_responded_at?  $this->last_responded_at->toDateTimeString():null,
            ],
            'author' => $author,
        ];
    }
}
