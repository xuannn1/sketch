<?php

namespace App\Http\Resources\ThreadResources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AuthorIdentifierResource;

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
            'id' => $this->id,
            'attributes' => [
                'title' => $this->title,
                'brief' => $this->brief,
                'last_post_id' => $this->last_post_id,
                'last_post_preview' => $this->last_post_preview,
                'is_anonymous' => $this->is_anonymous,
                'majia' => $this->majia ?? '匿名咸鱼',
                'created_at' => (string)$this->created_at,
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
