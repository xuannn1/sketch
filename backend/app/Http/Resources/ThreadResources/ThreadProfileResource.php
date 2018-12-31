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
            'id' => $this->id,
            'attributes' => [
                'title' => $this->title,
                'brief' => $this->brief,
                'body' => $body,
                'is_anonymous' => $this->is_anonymous,
                'majia' => $this->majia ?? '匿名咸鱼',
                'created_at' => (string)$this->created_at,
                'last_editor_id' => $this->last_editor_id,
                'last_edited_at' => (string)$this->last_edited_at,
                'use_markdown' => $this->use_markdown,
                'use_indentation' => $this->use_indentation,
                'xianyus' => $this->xianyus,
                'shengfans' => $this->shengfans,
                'views' => $this->views,
                'replies' => $this->replies,
                'collections' => $this->collections,
                'downloads' => $this->downloads,
                'jifen' => $this->jifen,
                'weighted_jifen' => $this->weighted_jifen,
                'is_locked' => $this->is_locked,
                'is_public' => $this->is_public,
                'is_bianyuan' => $this->is_bianyuan,
                'no_reply' => $this->no_reply,
                'last_responded_at' => (string)$this->last_responded_at,
            ],
            'author' => $author,
            'channel'        => [
                'type'             => 'channel',
                'id'                => $this->channel_id,
                'attributes'        => $this->simpleChannel(),
            ],
            'tags' => TagResource::collection($this->tags)
        ];
    }
}
