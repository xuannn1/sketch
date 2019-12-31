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
        if((!$this->is_anonymous)||((auth('api')->check())&&(auth('api')->id()===$this->user_id))){
            $author = new UserBriefResource($this->whenLoaded('author'));
        }else{
            $author = [];
        }
        $component = [];
        if($this->type==='chapter'){
            $component = new ChapterResource($this->whenLoaded('chapter'));
        }
        if($this->type==='review'){
            $component = new ReviewResource($this->whenLoaded('review'));
        }
        if($this->type==='answer'){
            $component = new PostResource($this->whenLoaded('parent'));
        }
        return [
            'type' => 'post',
            'id' => (int)$this->id,
            'attributes' => [
                'post_type' => (string) $this->type,
                'thread_id' => (int)$this->thread_id,
                'title' => (string)$this->title,
                'brief' => (string)$this->brief,
                'body' => (string)$body,
                'is_anonymous' => (bool)$this->is_anonymous,
                'majia' => (string)$this->majia,
                'created_at' => (string)$this->created_at,
                'edited_at' => (string)$this->edited_at,
                'reply_id' => (int)$this->reply_id,
                'reply_brief' => (string)$this->reply_brief,
                'reply_position' => (string)$this->reply_position,
                'is_folded' => (bool)$this->is_folded,
                'is_bianyuan' => (bool)$this->is_bianyuan,
                'use_markdown' => (bool)$this->markdown,
                'use_indentation' => (bool)$this->use_indentation,
                'upvote_count' => (int)$this->upvote_count,
                'reply_count' => (int)$this->reply_count,
                'view_count' => (int)$this->views,
                'char_count' => (int)$this->char_count,
                'responded_at' => (string)$this->responded_at,
            ],
            'author' => $author,
            $this->type => $component,
            'tags' => TagInfoResource::collection($this->whenLoaded('tags')),
        ];
    }
}
