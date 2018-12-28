<?php

namespace App\Http\Resources\ThreadResources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AuthorIdentifierResource;

class ThreadBriefResource extends JsonResource
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
                'is_anonymous' => $this->is_anonymous,
                'majia' => $this->majia,
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
