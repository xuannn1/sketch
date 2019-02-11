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
            $author = new AuthorIdentifierResource($this->whenLoaded('author'));
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
                'replies' => (int)$this->replies,
                'views' => (int)$this->views,
                'is_folded' => (bool)$this->is_folded,
                'is_bianyuan' => (bool)$this->is_bianyuan,
                'last_responded_at' => (string)$this->last_responded_at,
            ],
            'author' => $author,
            'component' => $component,
            'tags' => TagInfoResource::collection($this->whenLoaded('tags')),
        ];
    }
}
