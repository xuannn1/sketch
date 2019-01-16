<?php

namespace App\Http\Resources\ThreadResources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AuthorIdentifierResource;

class ThreadProfileResource extends JsonResource
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
        if ((!$this->is_anonymous)||((auth('api')->check())&&(auth('api')->id()===$this->user_id))){
            $author = new AuthorIdentifierResource($this->author);
        }else{
            $author = [];
        }
        return [
            'type' => 'thread',
            'id' => (int)$this->id,
            'attributes' => [
                'title' => (string)$this->title,
                'brief' => (string)$this->brief,
                'body' => (string)$body,
                'is_anonymous' => (bool)$this->is_anonymous,
                'majia' => (string)$this->majia ?? '匿名咸鱼',
                'created_at' => (string)$this->created_at,
                'last_editor_id' => (int)$this->last_editor_id,
                'last_edited_at' => (string)$this->last_edited_at,
                'use_markdown' => (bool)$this->use_markdown,
                'use_indentation' => (bool)$this->use_indentation,
                'xianyus' => (int)$this->xianyus,
                'shengfans' => (int)$this->shengfans,
                'views' => (int)$this->views,
                'replies' => (int)$this->replies,
                'collections' => (int)$this->collections,
                'downloads' => (int)$this->downloads,
                'jifen' => (int)$this->jifen,
                'weighted_jifen' => (int)$this->weighted_jifen,
                'is_locked' => (bool)$this->is_locked,
                'is_public' => (bool)$this->is_public,
                'is_bianyuan' => (bool)$this->is_bianyuan,
                'no_reply' => (bool)$this->no_reply,
                'last_responded_at' => (string)$this->last_responded_at,
            ],
        ];
    }
}
