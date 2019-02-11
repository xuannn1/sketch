<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
     //在book/list/other thread formed index页面，用于返回components目录(因此不需要返回作者是谁)
    public function toArray($request)
    {
        $component = [];
        if($this->type==='chapter'){
            $component = new ChapterInfoResource($this->whenLoaded('chapter'));
        }
        if($this->type==='review'){
            $component = new ReviewResource($this->whenLoaded('review'));
        }
        if($this->type==='answer'){
            $component = new PostInfoResource($this->whenLoaded('parent_brief'));
        }
        return [
            'type' => 'post',
            'id' => (int)$this->id,
            'attributes' => [
                'post_type' => (string) $this->type,
                'thread_id' => (int)$this->thread_id,
                'title' => (string)$this->title,
                'preview' => (string)$this->preview,
                'created_at' => (string)$this->created_at,
                'last_edited_at' => (string)$this->last_edited_at,
                'up_votes' => (int)$this->up_votes,
                'replies' => (int)$this->replies,
                'views' => (int)$this->views,
                'is_folded' => (bool)$this->is_folded,
                'is_bianyuan' => (bool)$this->is_bianyuan,
                'last_responded_at' => (string)$this->last_responded_at,
            ],
            'component' => $component,
            'tags' => TagInfoResource::collection($this->whenLoaded('tags')),
        ];
    }
}
