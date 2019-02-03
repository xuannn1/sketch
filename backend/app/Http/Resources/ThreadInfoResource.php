<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ThreadInfoResource extends JsonResource
{
    /**
    * Transform the resource into an array.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function toArray($request)
    {
        if (!$this->is_anonymous){
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
                'is_anonymous' => (bool)$this->is_anonymous,
                'majia' => (string)$this->majia ?? '匿名咸鱼',
                'created_at' => (string)$this->created_at,
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
            'author' => $author,
            'channel'        => new ChannelBriefResource($this->channel()),
            'tags' => TagInfoResource::collection($this->tags),
            'last_component' => new PostBriefResource($this->last_component),
            'last_post' => new PostBriefResource($this->last_post),
        ];
    }

}