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
                'brief' => (string)$this->brief,
                'created_at' => (string)$this->created_at,
                'edited_at' => (string)$this->edited_at,
                'upvote_count' => (int)$this->upvote_count,
                'reply_count' => (int)$this->reply_count,
                'view_count' => (int)$this->views,
                'char_count' => (int)$this->char_count,
                'is_folded' => (bool)$this->is_folded,
                'is_bianyuan' => (bool)$this->is_bianyuan,
                'responded_at' => (string)$this->responded_at,
            ],
            'component' => $component,
            'tags' => TagInfoResource::collection($this->whenLoaded('tags')),
        ];
    }
}
